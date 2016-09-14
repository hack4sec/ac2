/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */

function setServerIdToSpoAddLink(server_id) {
    $('#addSpoLink').data('server', server_id)
}

function spoListFilter(page) {
    if (!parseInt($('#filterServer').val())) {
        alert(_t('L_SELECT_SERVER_FIRST'));
        return false;
    }
    $.post("/server-software/ajax-list/page/" + page, $( "#filterForm" ).serialize(), function (htmlData) {
        $('#content').html(htmlData)
    } );
}

function softwareDelete(id, title) {
    if (confirm(_t('L_REALY_WANT_DELETE_SPO') + ' "' + title + '"?')) {
        get("/server-software/delete/id/" + id, function(data) {
            if (data.length) {
                alert(data)
            } else {
                //$('#spoRow' + id).remove()
                spoListFilter(1);
                loadProjectMenu(projectId)
            }
        })
    }
}

function openSoftwareEditForm(id) {
    createDialogBox(_t('L_SPO_EDITING'), '/server-software/edit/id/' + id, 750, 520)
}

function sendSoftwareEditForm() {
    var spo_id = $('#serverSoftwareForm_id').val()

    $.post(
        "/server-software/edit/",
        $( "#serverSoftwareEditForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                if ($("#spoRow" + spo_id).length) {
                    get('/server-software/one-in-list/id/' + spo_id, function (htmlData) {
                        $("#spoRow" + spo_id).html(htmlData)
                    })
                } else {
                    spoLoadView(spo_id)
                }
            }
        }
    );
}

function openSoftwareAddForm(serverId) {
    if (!serverId.toString().length || serverId.toString() == '0') {
        alert(_t('L_SELECT_SERVER_FIRST'))
        return;
    }
    createDialogBox(_t('L_SPO_ADDING'), '/server-software/add/server_id/' + serverId, 750, 570)
}

function sendSoftwareAddForm() {
    $.post(
        "/server-software/add/",
        $( "#serverSoftwareAddForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html().html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                spoListFilter(1)
                loadProjectMenu(projectId)
            }
        }
    );
}



function goSpoListLink() {
    if ($('#filterServer').val() == "0") {
        return alert(_t('L_SELECT_SERVER_FIRST'))
    }
    var filterData = []
    filterData[filterData.length] = $('#filterServer').val()
    if ($('#filterSearch').val().length) {
        filterData[filterData.length] = $('#filterSearch').val()
    }

    var hash = '#spo-openlist-FILTER-' + filterData.join("/")
    $.cookie('menu_spo', hash)
    $("#menu-spo").attr('href', hash)
    goNavigation(hash)
}


function spoLoadView(id) {
    get('/server-software/view/id/' + id, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}

function openSpoList(projectId) {
    get('/server-software/index/project_id/' + projectId, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}