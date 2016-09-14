<?php
/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */
class Utils
{
    static function getSubnetsByIps($ips) {
        $subnets = [];
        foreach ($ips as $ip) {
            $subnetPart = substr($ip, 0, strrpos($ip, "."));
            $subnetFull = "$subnetPart.0 - $subnetPart.255";
            if (!isset($subnets[$subnetFull])) {
                $subnets[$subnetFull] = [];
            }
            $subnets[$subnetFull][] = $ip;
        }
        return $subnets;
    }

    static function getDomainsFromSubdomains($domains) {
        $result = [];
        foreach ($domains as $domain) {
            if (substr_count($domain, ".") <= 1) {
                if (!isset($result[$domain])) {
                    $result[$domain] = [];
                }
                $result[$domain][] = $domain;
            } else {
                $parts = explode(".", $domain);
                $domain = $parts[count($parts)-2] . "." . $parts[count($parts)-1];
                unset($parts[count($parts)-1]);
                unset($parts[count($parts)-1]);
                $subdomain = implode(".", $parts);

                if (!isset($result[$domain])) {
                    $result[$domain] = [];
                }

                $result[$domain][] = "$subdomain.$domain";
            }

        }
        return $result;
    }
}