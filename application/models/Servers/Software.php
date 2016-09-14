<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Servers_Software extends Common
{
    protected $_name = 'servers_software';
    protected $_rowClass = 'Servers_Software_Row';

    public function getCountByProjectId($id) {
        return $this->getAdapter()->fetchOne(
            "SELECT COUNT(spo.id) FROM servers_software spo
             JOIN servers s  ON spo.server_id = s.id
             JOIN projects p ON s.project_id = p.id
             WHERE p.id = $id"
        );
    }

    public function getListPaginator($serverId, $search, $page) {
        $select = $this->select()->where("server_id = $serverId")->order("name ASC");
        if (strlen($search)) {
            $select->where("name LIKE ? OR comment LIKE ?", "%$search%", "%$search%");
        }
        $paginator = Zend_Paginator::factory(
            $select
        )->setItemCountPerPage(8)
            ->setCurrentPageNumber($page);

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        $view = Zend_View_Helper_PaginationControl::setDefaultViewPartial(
            'paginator.phtml'
        );
        $paginator->setView($view);
        return $paginator;
    }

    public function exists($serverId, $name) {
        return (bool)$this->fetchRow("server_id = {$serverId} AND name = {$this->getAdapter()->quote($name)}");
    }

    public function getPairsList($serverId, $order = "id") {
        return $this->getAdapter()->fetchPairs(
            "SELECT id, name FROM {$this->_name} WHERE server_id = $serverId ORDER BY $order DESC"
        );
    }

    public function getByServerId($serverId, $checked = null) {
        return $this->fetchAll(
            "server_id = $serverId" . ($checked !== NULL ? " AND checked = $checked" : "")
        );
    }

    private function _getSoftwareExistsListByPortAndProto($serverId) {
        return $this->getAdapter()->fetchPairs(
            "SELECT CONCAT(proto, port), id FROM {$this->_name} WHERE server_id = $serverId"
        );
    }

    public function nmapImport($fileName, $serverId, $all, $ignoreBlank) {
        $Servers = new Servers();
        $Domains = new Domains();
        $origServer = $Servers->get($serverId);
        $config = Zend_Registry::get('config');
        $existsList = $this->_getSoftwareExistsListByPortAndProto($serverId);

        $xml = simplexml_load_file($config->paths->tmp . "/$fileName");
        $hostsNodes = $xml->xpath("host");
        foreach ($hostsNodes as $hostNode) {
            if ($addrNode = $hostNode->xpath('address[@addrtype="ipv4"]')) {
                $ip = (string)$addrNode[0]['addr'];

                if ($ignoreBlank and !count($hostNode->xpath("ports/port"))) {
                    continue;
                }

                if ($all || $ip == $origServer->ip) {
                    if ($all) {
                        if ($Servers->existsByIp($origServer->project_id, $ip)) {
                            $serverId = $Servers->getByIp($origServer->project_id, $ip)->id;
                        } else {
                            $serverId = $Servers->add(
                                [
                                    "project_id" => $origServer->project_id,
                                    "ip" => $ip,
                                    "name" => $ip
                                ]
                            )->id;
                        }
                    }

                    if ($hostNames = $hostNode->xpath("hostnames/hostname")) {
                        foreach ($hostNames as $hostName) {
                            $hostName = (string)$hostName['name'];
                            if (!$Domains->exists($serverId, $hostName)) {
                                $Domains->add([
                                    'server_id' => $serverId,
                                    'name' => $hostName,
                                ]);
                            }
                        }
                        $tmpServer = $Servers->get($serverId);
                        if ($tmpServer->name == $tmpServer->ip && !$Servers->exists($origServer->project_id, $hostName)) {
                            $tmpServer->name = $hostName;
                            $tmpServer->save();
                        }
                    }
                    // ---
                    $portsNodes = $hostNode->xpath("ports/port");
                    foreach ($portsNodes as $portNode) {
                        if ($portNode->state['state'] == "open") {
                            $node = $portNode;

                            if (!isset($existsList[(string)$node['protocol'] . (string)$node['portid']])) {
                                $toAdd = [
                                    'server_id' => $serverId,
                                    'port' => (string)$node['portid'],
                                    'name' => (string)$node->service['name'] . (isset($node->service['product']) ? " {$node->service['product']}" : ''),
                                    'proto' => (string)$node['protocol'],
                                    'banner' => (string)$node->service['extrainfo'],
                                    'version' => (string)$node->service['version'],
                                    'version_unknown' => !(bool)strlen($node->service['version']),
                                ];
                                $this->createRow($toAdd)->save();
                            } else {
                                $toUpdate = $this->get($existsList[(string)$node['protocol'] . (string)$node['portid']]);
                                if (!strlen($toUpdate->version) && strlen((string)$node->service['version'])) {
                                    $toUpdate->version = (string)$node->service['version'];
                                    $toUpdate->version_unknown = false;
                                }
                                if (!strlen($toUpdate->banner) && strlen((string)$node->service['extrainfo'])) {
                                    $toUpdate->banner = (string)$node->service['extrainfo'];
                                }
                                $toUpdate->save();
                            }
                        }
                    }
                }
            }
        }
    }

    public function getCountByServerId($serverId) {
        return $this->getAdapter()->fetchOne("SELECT COUNT(id) FROM {$this->_name} WHERE server_id = $serverId");
    }
}