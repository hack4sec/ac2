/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */

function serversListFilter(page) {
    $.post("/servers/ajax-list/page/" + page, $( "#filterForm" ).serialize(), function (htmlData) {
        $('#content').html(htmlData)
    } );
}
function openServerAddForm(projectId) {
    createDialogBox(_t('L_SERVER_ADDING'), '/servers/add/project_id/' + projectId, 650, 430)
}

function sendServerAddForm() {
    $.post(
        "/servers/add/",
        $( "#serverAddForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()
                serversListFilter(1)
                loadProjectMenu(projectId)
            }
        }
    );
}

function openServerEditForm(projectId, id) {
    createDialogBox(_t('L_SERVER_EDITING'), '/servers/edit/project_id/' + projectId + '/id/' + id, 650, 430)
}


function sendServerEditForm() {
    var server_id = $("#serverForm_id").val()
    $.post(
        "/servers/edit/",
        $( "#serverEditForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                if ($('#serverRow' + server_id).length) {
                    get('/servers/one-in-list/id/' + server_id, function (htmlData) {
                        $('#serverRow' + server_id).html(htmlData)
                    })
                } else {
                    serverLoadView(server_id)
                }
            }
        }
    );
}

function serverDelete(id, title) {
    if (confirm(_t('L_REALY_WANT_DELETE_SERVER') + ' "' + title + '"?')) {
        get("/servers/delete/id/" + id, function(data) {
            if (data.length) {
                alert(data)
            } else {
                //$("#serverRow" + id).remove()
                serversListFilter(1)
                loadProjectMenu(projectId)
            }
        })
    }
}

function goServersListLink() {
    var filterData = []
    if ($('#filterSearch').val().length) {
        filterData[filterData.length] = $('#filterSearch').val()
    }
    var hash = '#server-openlist-FILTER-' + filterData.join("/")
    $.cookie('menu_servers', hash)
    $("#menu-servers").attr('href', hash)
    goNavigation(hash)
}


function serverLoadView(serverId) {
    get('/servers/view/id/' + serverId, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}

function loadServersList(projectId) {
    get('/servers/index/project_id/' + projectId, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}

function openServersListImportForm(projectId) {
    createDialogBox(_t('L_LIST_IMPORT'), '/servers/list-import/project_id/' + projectId, 650, 150)
}

function serversListExport(projectId) {
    window.location.href = '/servers/list-export/project_id/' + projectId
}