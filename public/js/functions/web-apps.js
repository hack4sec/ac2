/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */

function webAppsFilterServerChange(server_id) {
    blankContent()
    getJSON('/domains/get-list-json/server_id/' + server_id, function (data) {
        $('#filterDomain').html('')

        var option = document.createElement('option')
        option.value = 0
        option.innerHTML = _t('L_ALL')
        $('#filterDomain').append(option)

        for (i in data) {
            var option = document.createElement('option')
            option.value = i
            option.innerHTML = data[i]
            $('#filterDomain').append(option)
        }
    })
    if (!app_navigation) {
        webAppsListFilter(1)
    }

}


function setDomainIdToWebAppAddLink(domain_id) {
    $('#addWebAppLink').data('domain', domain_id)
}

function webAppsListFilter(page) {
    $.post("/web-apps/ajax-list/project_id/" + projectId + "/page/" + page, $( "#filterForm" ).serialize(), function (htmlData) {
        $('#content').html(htmlData)
    } );
}

function webAppDelete(id, name) {
    if (confirm(_t('L_REALY_WANT_DELETE_APP') + ' "' + name + '"?')) {
        get("/web-apps/delete/id/" + id, function(data) {
            if (data.length) {
                alert(data)
            } else {
                //$("#webAppRow" + id).remove()
                webAppsListFilter(1)
                loadProjectMenu(projectId)
            }
        })
    }
}

function sendWebAppEditForm() {
    var app_id = $('#webAppForm_id').val()

    $.post(
        "/web-apps/edit/",
        $( "#webAppEditForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                if ($("#webAppRow" + app_id).length) {
                    get('/web-apps/one-in-list/id/' + app_id, function (htmlData) {
                        $("#webAppRow" + app_id).html(htmlData)
                    })
                } else {
                    webAppLoadView(app_id)
                }
            }
        }
    );
}


function openWebAppEditForm(id) {
    createDialogBox(_t('L_WEBAPP_EDITING'), '/web-apps/edit/id/' + id, 650, 560)
}

function openWebAppAddForm(domainId) {
    if (!domainId.toString().length || domainId.toString() == '0') {
        alert(_t('L_SELECT_DOMAIN_FIRST'))
        return;
    }
    createDialogBox(_t('L_WEBAPP_ADDING'), '/web-apps/add/domain_id/' + domainId, 650, 560)
    tmp_domain = domainId
}

function sendWebAppAddForm() {
    $.post(
        "/web-apps/add/",
        $( "#webAppAddForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                webAppsListFilter(1)
                loadProjectMenu(projectId)
            }
        }
    );
}

function goWebAppsListLink() {
    var filterData = []
    filterData[filterData.length] = $('#filterServer').val()
    filterData[filterData.length] = $('#filterDomain').val()
    if ($('#filterSearch').val().length) {
        filterData[filterData.length] = $('#filterSearch').val()
    }

    var hash = '#webapp-openlist-FILTER-' + filterData.join("/")
    $("#menu-webapps").attr('href', hash)
    $.cookie('menu_webapps', hash)
    goNavigation(hash)
}




function webAppLoadView(id) {
    get('/web-apps/view/id/' + id, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}

function openWebAppsList(projectId) {
    get('/web-apps/index/project_id/' + projectId, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}
