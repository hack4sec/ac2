/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */

function vulnsFilterTypeChange(type) {
    blankContent()

    $("#addVulnLink").data('type', type)

    getJSON('/vulns/parents-list-json/type/' + type + '/project_id/' + projectId, function (data) {
        $('#filterParent').html('')
        var option = document.createElement('option')
        option.value = 0
        option.innerHTML = '-------'
        $('#filterParent').append(option)

        for (i in data) {
            var option = document.createElement('option')
            option.value = i
            option.innerHTML = data[i]
            $('#filterParent').append(option)
        }

        $('#filterObject').html('')
        var option = document.createElement('option')
        option.value = 0
        option.innerHTML = '-------'
        $('#filterObject').append(option)
    })
    if (!app_navigation) {
        vulnsListFilter(1)
    }
}

function vulnsFilterObjectChange(object) {
    $("#addVulnLink").data('object', object)
}

function vulnsFilterParentChange(parent) {
    blankContent()

    var type = $('#filterType').val()

    getJSON('/vulns/objects-list-json/parent_id/' + parent + '/type/' + type + '/project_id/' + projectId, function (data) {
        $('#filterObject').html('')
        var option = document.createElement('option')
        option.value = 0
        option.innerHTML = '-------'
        $('#filterObject').append(option)

        for (i in data) {
            var option = document.createElement('option')
            option.value = i
            option.innerHTML = data[i]
            $('#filterObject').append(option)
        }
    })
    if (!app_navigation) {
        vulnsListFilter(1)
    }
}

function vulnsListFilter(page) {
    var type = $('#filterType').val()
    var obj = $('#filterObject').val()
    $.post('/vulns/ajax-list/project_id/' + projectId + '/type/' + type + '/object_id/' + obj + '/page/' + page, $( "#filterForm" ).serialize(), function (htmlData) {
        $('#content').html(htmlData)
    })
}


function vulnDelete(id, name) {
    if (confirm(_t('L_REALY_WANT_DELETE_VULN') + ' "' + name + '"?')) {
        get("/vulns/delete/id/" + id, function(data) {
            if (data.length) {
                alert(data)
            } else {
                //$("#vulnRow" + id).remove()
                vulnsListFilter(1);
                loadProjectMenu(projectId)
            }
        })
    }
}

function openVulnEditForm(id) {
    createDialogBox(_t('L_VULN_EDITING'), '/vulns/edit/id/' + id, 650, 500)
}

function sendVulnEditForm() {
    var vuln_id = $("#vulnForm_id").val()
    $.post(
        "/vulns/edit/",
        $( "#vulnEditForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                if ($('#vulnRow' + vuln_id).length) {
                    get('/vulns/one-in-list/id/' + vuln_id, function (htmlData) {
                        $("#vulnRow" + vuln_id).html(htmlData)
                    })
                } else {
                    vulnLoadView(vuln_id)
                }
            }
        }
    );
}

function setDataToVulnAddLink() {
    $('#addVulnLink').data('type', $('#filterType').val())
    $('#addVulnLink').data('object', $('#filterObject').val())
}


function openVulnAddForm(type, objectId) {
    if (!objectId.toString().length || objectId.toString() == '0') {
        alert(_t('L_SELECT_OBJECT_FIRST'))
        return;
    }
    createDialogBox(_t('L_VULN_ADDING'), '/vulns/add/object_id/' + objectId + '/type/' + type, 650, 500)
}

function sendVulnAddForm() {
    var type = $("#vulnForm_type").val()
    var object_id = $("#vulnForm_object_id").val()

    $.post(
        "/vulns/add/",
        $( "#vulnAddForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                vulnsListFilter(1)
                loadProjectMenu(projectId)
            }
        }
    );
}


function vulnLoadView(id) {
    get('/vulns/view/id/' + id, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}



function goVulnsListLink() {
    var filterData = []
    filterData[filterData.length] = $('#filterType').val()
    filterData[filterData.length] = $('#filterParent').val()
    filterData[filterData.length] = $('#filterObject').val()
    if ($('#filterSearch').val().length) {
        filterData[filterData.length] = $('#filterSearch').val()
    }

    var hash = '#vuln-openlist-FILTER-' + filterData.join("/")
    $.cookie('menu_vulns', hash)
    $("#menu-vulns").attr('href', hash)
    goNavigation(hash)
}


function openVulnsList(projectId) {
    get('/vulns/index/project_id/' + projectId, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}
