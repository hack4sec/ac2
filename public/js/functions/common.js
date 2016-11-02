/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */

app_navigation = false

function connectMenuLinks() {
    var menusArr = {
        'menu_domains': '#menu-domains',
        'menu_files': '#menu-files',
        'menu_spo': '#menu-spo',
        'menu_servers': '#menu-servers',
        'menu_domains': '#menu-domains',
        'menu_tasks': '#menu-tasks',
        'menu_users': '#menu-users',
        'menu_vulns': '#menu-vulns',
        'menu_webapps': '#menu-webapps',
    }
    for (cookieName in menusArr) {
        if ($.cookie(cookieName) != undefined) {
            $(menusArr[cookieName]).attr('href', $.cookie(cookieName))
        }
    }
}

function hashNavigation() {
    app_navigation = true

    if (window.location.hash.length == 0) {
        loadServersList(projectId)
    }
    var filterData = []
    var filterRegEx = /(.*?)FILTER(.*)/
    if (filterRegEx.test(window.location.hash)) {
        var tmp = filterRegEx.exec(window.location.hash)
        var hash = tmp[1].substr(1, tmp[1].length-2)
        filterData = tmp[2].substr(1).split('/')
    } else {
        var hash = window.location.hash.substr(1)
    }
    /*if (window.location.hash.substr(1).indexOf('-') == -1) {
        return false;
    }*/
    var hash = hash.split('-')
    var controller = hash[0]
    var action = hash[1]
    var id = hash[2]
    
    if (action == 'view') {
        if (controller == 'webapp') {
            webAppLoadView(id)
        }

        if (controller == 'spo') {
            spoLoadView(id)
        }

        if (controller == 'server') {
            serverLoadView(id)
        }

        if (controller == 'domain') {
            domainLoadView(id)
        }

        if (controller == 'vuln') {
            vulnLoadView(id)
        }

        if (controller == 'file') {
            fileLoadView(id)
        }

        if (controller == 'task') {
            taskLoadView(id)
        }

        if (controller == 'user') {
            userLoadView(id)
        }        
    } else if (action == 'openlist') {
        if (controller == 'webapp') {
            openWebAppsList(projectId)


            $('#filterServer').val(filterData[0]).triggerHandler('change')
            $('#filterDomain').val(filterData[1]).triggerHandler('change')
            if (filterData[2] != undefined) {
                $('#filterSearch').val(filterData[2])
            }
            webAppsListFilter(1);

        }

        if (controller == 'spo') {
            openSpoList(projectId)


            $('#filterServer').val(filterData[0]).trigger('change')
            if (filterData[1] != undefined) {
                $('#filterSearch').val(filterData[1])
            }
            spoListFilter(1);

        }

        if (controller == 'server') {
            loadServersList(projectId)

            $('#filterSearch').val(filterData[0])
            serversListFilter(1)

        }

        if (controller == 'domain') {
            openDomainsList(projectId)


            $('#filterServer').val(filterData[0]).trigger('change')
            if (filterData[1] != undefined) {
                $('#filterSearch').val(filterData[1])
            }
            domainsListFilter(1);
        }

        if (controller == 'vuln') {
            openVulnsList(projectId)

            $('#filterType').val(filterData[0]).triggerHandler('change')
            $('#filterParent').val(filterData[1]).triggerHandler('change')
            $('#filterObject').val(filterData[2]).triggerHandler('change')
            if (filterData[3] != undefined) {
                $('#filterSearch').val(filterData[3])
            }
            vulnsListFilter(1);
        }

        if (controller == 'file') {
            openFilesList(projectId)


            var type = filterData[0]
            if (type == 'web-app' || type == 'server-software' || type == 'domain') {
                $('#filterType').val(filterData[0]).triggerHandler('change')
                $('#filterParent').val(filterData[1]).triggerHandler('change')
                $('#filterObject').val(filterData[2]).triggerHandler('change')
            } else if(type == 'project') {
                $('#filterType').val(filterData[0]).triggerHandler('change')
            } else if(type == 'server') {
                $('#filterType').val(filterData[0]).triggerHandler('change')
                $('#filterObject').val(filterData[2]).triggerHandler('change')
            }

            if (filterData[3] != undefined) {
                $('#filterSearch').val(filterData[3])
            }

            filesListFilter(1);

        }

        if (controller == 'task') {
            openTasksList(projectId)


            var type = filterData[0]
            if (type == 'web-app' || type == 'server-software' || type == 'domain') {
                $('#filterType').val(filterData[0]).triggerHandler('change')
                $('#filterParent').val(filterData[1]).triggerHandler('change')
                $('#filterObject').val(filterData[2]).triggerHandler('change')
            } else if(type == 'project') {
                $('#filterType').val(filterData[0]).triggerHandler('change')
            } else if(type == 'server') {
                $('#filterType').val(filterData[0]).triggerHandler('change')
                $('#filterObject').val(filterData[2]).triggerHandler('change')
            }

            if (filterData[3] != undefined) {
                $('#filterSearch').val(filterData[3])
            }

            tasksListFilter(1);

        }

        if (controller == 'user') {
            openUsersList(projectId)


            var type = filterData[0]
            if (type == 'web-app' || type == 'server-software') {
                $('#filterType').val(filterData[0]).triggerHandler('change')
                $('#filterParent').val(filterData[1]).triggerHandler('change')
                $('#filterObject').val(filterData[2]).triggerHandler('change')
            } else if(type == 'server') {
                $('#filterType').val(filterData[0]).triggerHandler('change')
                $('#filterObject').val(filterData[2]).triggerHandler('change')
            }

            $('#filterGroup').val(filterData[3]).triggerHandler('change')
            if (filterData[4] != undefined) {
                    $('#filterSearch').val(filterData[4])
            }

            usersListFilter(1);

        }
    }

    app_navigation = false
    console.log(controller, action, id)
}

function strrchr (haystack, needle) {
    //  discuss at: http://locutus.io/php/strrchr/
    // original by: Brett Zamir (http://brett-zamir.me)
    //    input by: Jason Wong (http://carrot.org/)
    // bugfixed by: Brett Zamir (http://brett-zamir.me)
    //   example 1: strrchr("Line 1\nLine 2\nLine 3", 10).substr(1)
    //   returns 1: 'Line 3'

    var pos = 0

    if (typeof needle !== 'string') {
        needle = String.fromCharCode(parseInt(needle, 10))
    }
    needle = needle.charAt(0)
    pos = haystack.lastIndexOf(needle)
    if (pos === -1) {
        return false
    }

    return haystack.substr(pos)
}

function createDialogBox(title, url, width, height) {
    if($('#dialogbox')) {
        $('#dialogbox').remove()
    }
    $('<div id="dialogbox">' + _t('L_LOADING') + '</div>').dialog({
        height: height,
        width: width,
        modal: true,
        title: title
    }).load(url)
}


function openNmapImportForm(serverId) {
    createDialogBox(_t('L_NMAP_IMPORT_XML'), '/server-software/nmap-import/server_id/' + serverId, 600, 200);
}

function get(url, successFunc) {
    $.ajax(
        url,
        {
            async: false,
            success: successFunc
        }
    )
}

function getJSON(url, successFunc) {
    $.ajax(
        url,
        {
            dataType: "json",
            async: false,
            success: successFunc
        }
    )
}

function boolToInt(boolVar) {
    return boolVar ? 1 : 0;
}

function blankContent() {
    $("#content").html('');
}

function goNavigation(uri) {
    if (window.location.hash.length && window.location.hash == uri) {
        hashNavigation()
    } else {
        window.location.href = uri
    }
}

function errfunc(a, b, c) {
    $("#errorlog").html(
        $("#errorlog").html() + "<br/>" + a + " " + b + " " + c
    )
}


function _t(tKey) {
    return translates[tKey];
}