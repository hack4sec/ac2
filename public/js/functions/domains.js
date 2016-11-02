/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */

function setServerIdToLinks(server_id) {
    $('#addDomainLink').data('server', server_id)
    $('#exportListButton').data('server', server_id)
    $('#importListButton').data('server', server_id)
}

function domainsListFilter(page) {
    $.post("/domains/ajax-list/project_id/" + projectId + "/page/" + page, $( "#filterForm" ).serialize(), function (htmlData) {
        $('#content').html(htmlData)
    } );
}

function openDomainAddForm(serverId) {
    if (!serverId.toString().length || serverId.toString() == '0') {
        alert(_t('L_SELECT_SERVER_FIRST'))
        return;
    }
    createDialogBox(_t('L_DOMAIN_ADD'), '/domains/add/server_id/' + serverId, 650, 380)
}

function sendDomainAddForm() {
    $.post(
        "/domains/add/",
        $( "#domainAddForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                domainsListFilter(1)
                loadProjectMenu(projectId)
            }
        }
    );
}

function openDomainEditForm(domainId) {
    createDialogBox(_t('L_DOMAIN_EDIT'), '/domains/edit/id/' + domainId, 650, 380)
}

function sendDomainEditForm() {
    var domain_id = $( "#domainForm_id" ).val()
    $.post(
        "/domains/edit/",
        $( "#domainEditForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                if ($('#domainRow' + domain_id).length) {
                    get('/domains/one-in-list/id/' + domain_id, function (htmlData) {
                        $('#domainRow' + domain_id).html(htmlData)
                    })
                } else {
                    domainLoadView(domain_id)
                }

            }
        }
    );
}

function domainDelete(id, title) {
    if (confirm(_t('L_REALY_WANT_DELETE_DOMAIN') + ' "' + title + '"?')) {
        get("/domains/delete/id/" + id, function(data) {
            if (data.length) {
                alert(data)
            } else {
                //$('#domainRow' + id).remove()
                domainsListFilter(1);
                loadProjectMenu(projectId)
            }
        })
    }
}

function goDomainsListLink() {
    var filterData = []
    filterData[filterData.length] = $('#filterServer').val()
    if ($('#filterSearch').val().length) {
        filterData[filterData.length] = $('#filterSearch').val()
    }
    var hash = '#domain-openlist-FILTER-' + filterData.join("/")
    $.cookie('menu_domains', hash)
    $("#menu-domains").attr('href', hash)
    goNavigation(hash)
}


function openDomainsList(projectId) {
    get('/domains/index/project_id/' + projectId, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}


function domainLoadView(domainId) {
    get('/domains/view/id/' + domainId, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}


function openDomainsListImportForm(serverId) {
    if (!serverId.toString().length || serverId.toString() == '0') {
        alert(_t('L_SELECT_SERVER_FIRST'))
        return;
    }
    createDialogBox(_t('L_LIST_IMPORT'), '/domains/list-import/server_id/' + serverId, 650, 150)
}

function domainsListExport(serverId) {
    if (!serverId.toString().length || serverId.toString() == '0') {
        alert(_t('L_SELECT_SERVER_FIRST'))
        return;
    }
    window.location.href = '/domains/list-export/server_id/' + serverId
}