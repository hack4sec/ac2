/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */

function openProjectAddForm() {
    createDialogBox(_t('L_PROJECT_ADDING'), '/projects/add/', 650, 340)
}

function openProjectEditForm(id) {
    createDialogBox(_t('L_PROJECT_EDITING'), '/projects/edit/id/' + id, 650, 340)
}

function projectDelete(id, title) {
    if (confirm(_t('L_REALY_WANT_DELETE_PRJ') + ' "' + title + '"?')) {
        get("/projects/delete/id/" + id, function(data) {
            if (data.length) {
                alert(data)
            } else {
                $('#projectInView' + id).remove()
            }
        })
    }
}

function sendProjectAddForm() {
    $.post(
        "/projects/add/",
        $( "#projectAddForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()
                window.location.reload()
            }
        }
    );
}

function sendProjectEditForm() {
    var project_id = $("#projectForm_id").val()
    $.post(
        "/projects/edit/",
        $( "#projectEditForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                get('/projects/one-in-list/id/' + project_id, function (htmlData) {
                    $('#projectInView' + project_id).html(htmlData)
                })
            }
        }
    );
}

function loadProjectMenu(projectId) {
    get('/projects/menu/project_id/' + projectId, function (htmlData) {
        $("#menu").html(htmlData)
    })
}