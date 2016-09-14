/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */

function userGroupDelete(id, name) {
    if (confirm(_t('L_REALY_WANT_DELETE_GROUP') + ' "' + name + '"?')) {
        get("/users/delete-group/id/" + id, function(data) {
            if (data.length) {
                alert(data)
            } else {
                $('#filterGroup').val(0)
                $('#filterGroup option[value="' + id + '"]').remove()
                blankContent()
            }
        })
    }
}

function sendUserGroupEditForm() {
    var group_id = $("#userGroupForm_id").val()
    var group_name = $("#userGroupForm_name").val()
    $.post(
        "/users/edit-group/",
        $( "#userGroupEditForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                $('#filterGroup>option[value=' + group_id + ']').html(group_name)
            }
        }
    );
}

function openUserGroupAddForm(type, object_id) {
    if (!object_id.toString().length || object_id.toString() == '0') {
        alert(_t('L_SELECT_OBJECT_FIRST'))
        return;
    }
    createDialogBox(_t('L_GROUP_ADDING'), '/users/add-group/type/' + type + '/object_id/' + object_id, 650, 170)
}

function sendUserGroupAddForm() {
    var type = $("#userGroupForm_type").val()
    var object_id = $("#userGroupForm_object_id").val()
    $.post(
        "/users/add-group/",
        $( "#userGroupAddForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                usersFilterObjectChange($('#filterObject').val())
            }
        }
    );
}

function openUserGroupEditForm(id) {
    createDialogBox(_t('L_GROUP_EDITING'), '/users/edit-group/id/' + id, 650, 170)
}