/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */

function usersFilterTypeChange(type) {
    blankContent()

    $("#addUserLink").data('type', type)
    $("#addGroupLink").data('type', type)

    $("#editGroupLink").hide()
    $("#delGroupLink").hide()

    $('#parentDiv').show()
    $('#objectDiv').show()

    if (type == 'web-app' || type == 'server-software' || type == 'domain') {
        getJSON('/users/parents-list-json/type/' + type + '/project_id/' + projectId, function (data) {
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

            $('#filterGroup').html('')
            var option = document.createElement('option')
            option.value = 0
            option.innerHTML = '-------'
            $('#filterGroup').append(option)
        })
    } else if(type == 'project') {
        $('#parentDiv').hide()
        $('#objectDiv').hide()
        usersFilterObjectChange(projectId)
    } else if(type == 'server') {
        $('#parentDiv').hide()
        usersFilterParentChange(projectId)
    }
}

function usersFilterParentChange(parent) {
    blankContent()

    var type = $('#filterType').val()
    $("#editGroupLink").hide()
    $("#delGroupLink").hide()

    getJSON('/users/objects-list-json/parent_id/' + parent + '/type/' + type + '/project_id/' + projectId, function (data) {
        $('#filterObject').html('')
        var option = document.createElement('option')
        option.value = 0
        option.innerHTML = '-------'
        $('#filterObject').append(option)

        $('#filterGroup').html('')
        var option = document.createElement('option')
        option.value = 0
        option.innerHTML = '-------'
        $('#filterGroup').append(option)

        for (i in data) {
            var option = document.createElement('option')
            option.value = i
            option.innerHTML = data[i]
            $('#filterObject').append(option)
        }
    })
}

function usersFilterGroupChange(group_id) {
    $("#addUserLink").data('group', group_id)
    $("#exportUserLink").data('group', group_id)
    $("#importUserLink").data('group', group_id)

    $("#editGroupLink").data('id', group_id)
    $("#delGroupLink").data('id', group_id).data('name', $('#filterGroup option[value="' + group_id + '"]').html())

    $("#editGroupLink").show()
    $("#delGroupLink").show()
}

function usersFilterObjectChange(object_id) {
    blankContent()

    $("#addUserLink").data('object', object_id)
    $("#addGroupLink").data('object', object_id)
    $("#editGroupLink").hide()
    $("#delGroupLink").hide()

    var type = $('#filterType').val()

    getJSON('/users/groups-list-json/object_id/' + object_id + '/type/' + type, function (data) {
        $('#filterGroup').html('')
        var option = document.createElement('option')
        option.value = 0
        option.innerHTML = '-------'
        $('#filterGroup').append(option)

        for (i in data) {
            var option = document.createElement('option')
            option.value = i
            option.innerHTML = data[i]
            $('#filterGroup').append(option)
        }
    })
}

function usersListFilter(page) {
    if (!parseInt($('#filterObject').val())) {
        alert(_t('L_SELECT_OBJECT_FIRST'));
        return false;
    }
    var type = $('#filterType').val()
    var group = $('#filterGroup').val()
    $.post('/users/ajax-list/group_id/' + group + '/page/' + page, $( "#filterForm" ).serialize(), function (htmlData) {
        $('#content').html(htmlData)
    })
}

function userDelete(id, name) {
    if (confirm(_t('L_REALY_WANT_DELETE_USER') + ' "' + name + '"?')) {
        get("/users/delete/id/" + id, function(data) {
            if (data.length) {
                alert(data)
            } else {
                //$("#userRow" + id).remove()
                usersListFilter(1)
                loadProjectMenu(projectId)
            }
        })
    }
}

function openUserEditForm(id) {
    createDialogBox(_t('L_USER_EDITING'), '/users/edit/id/' + id, 650, 420)
}

function sendUserEditForm() {
    var user_id = $("#userForm_id").val()
    $.post(
        "/users/edit/",
        $( "#userEditForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                get('/users/one-in-list/id/' + user_id, function (htmlData) {
                    $("#userRow" + user_id).html(htmlData)
                })
            }
        }
    );
}

function openUserAddForm(type, objectId, groupId) {
    if (!groupId.toString().length || groupId.toString() == '0') {
        alert(_t('L_SELECT_GROUP_FIRST'))
        return;
    }
    createDialogBox(
        _t('L_USER_ADDING'),
        '/users/add/type/' + type + '/object_id/' + objectId + '/group_id/' + groupId,
        650,
        420
    )
}

function openUserAddFormWoGroup(type, object_id) {
    createDialogBox(_t('L_USER_ADDING'), '/users/add/object_id/' + object_id + '/type/' + type, 650, 320)
}

function sendUserAddForm() {
    var group_id = $("#userForm_group_id").val()
    $.post(
        "/users/add/",
        $( "#userAddForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                usersListFilter(1)

                loadProjectMenu(projectId)
            }
        }
    );
}

function openExportForm(groupId) {
    if (!groupId.toString().length || groupId.toString() == '0') {
        alert(_t('L_SELECT_GROUP_FIRST'))
        return;
    }
    createDialogBox(_t('L_EXPORT_USERS_LIST'), '/users/export/group_id/' + groupId, 650, 420)
}

function openImportForm(groupId) {
    if (!groupId.toString().length || groupId.toString() == '0') {
        alert(_t('L_SELECT_GROUP_FIRST'))
        return;
    }
    createDialogBox(_t('L_IMPORT_USERS_LIST'), '/users/import/group_id/' + groupId, 650, 420)
}

function openPairsForm() {
    createDialogBox(_t('L_PAIRS_LOAD'), '/users/pairs-load/', 650, 420)
}

function openUserView(id) {
    createDialogBox(_t('L_USERS_VIEW'), '/users/view/id/' + id, 650, 450)
}



function hashAndAlgCheck() {
    $("#importForm_alg").prop("checked", $("#importForm_hash").prop("checked"))
}

function sendImportForm() {
    // usersImportForm
    //
    var file_data = $('#importForm_file').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);

    $("#usersImportForm input[type='checkbox']").each(
        function (el){
            form_data.append(
                $(this).prop("name"),
                boolToInt($(this).prop("checked"))
            );
        }
    )
    form_data.append('group_id', $('#importForm_group_id').val());
    form_data.append('delimiter', $('#importForm_delimiter').val());
    /*form_data.append('login', $('#importForm_login').val());
     form_data.append('email', $('#importForm_email').val());
     if ($('#importForm_home_dir').val() != undefined) {
     form_data.append('home_dir', $('#importForm_home_dir').val());
     }
     if ($('#importForm_shell').val() != undefined) {
     form_data.append('shell', $('#importForm_shell').val());
     }
     form_data.append('vip', $('#importForm_vip').val());
     form_data.append('hash', $('#importForm_hash').val());
     form_data.append('password', $('#importForm_password').val());
     form_data.append('salt', $('#importForm_salt').val());*/


    //form_data.append('wpasswords', $('#importForm_wpasswords').val());
    //form_data.append('wopasswords', $('#importForm_wpasswords').val());

    $.ajax({
        url: '/users/import/',
        dataType: 'text',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()
                usersListFilter(1)
            }
        }
    });
}

function userLoadView(id) {
    get('/users/view/id/' + id, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}

function goUsersListLink() {
    /*if ($('#filterObject').val() == "0") {
     return alert(_t('L_SELECT_OBJECT_FIRST'))
     }*/
    var filterData = []
    filterData[filterData.length] = $('#filterType').val()
    filterData[filterData.length] = $('#filterParent').val()
    filterData[filterData.length] = $('#filterObject').val()
    filterData[filterData.length] = $('#filterGroup').val()
    if ($('#filterSearch').val().length) {
        filterData[filterData.length] = $('#filterSearch').val()
    }

    var hash = '#user-openlist-FILTER-' + filterData.join("/")
    $.cookie('menu_users', hash)
    $("#menu-users").attr('href', hash)
    goNavigation(hash)
}


function openUsersList(projectId) {
    get('/users/index/project_id/' + projectId, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}

function hashlistFileChange(path) {
    while (path.indexOf('\\') != -1) {
        path = path.replace('\\', '/')
    }
    document.getElementById("fileName").value=strrchr(path, '/')
}