/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */

function tasksTemplatesFilterTypeChange(type) {
    blankContent()

    $("#addTaskTemplateLink").data('type', type)
    tasksTemplatesListFilter(1)
}

function tasksTemplatesListFilter(page) {
    var type = $('#filterType').val()
    if (type != '0') {
        $.post(
            '/tasks-templates/ajax-list/type/' + type +  '/project_id/' + projectId + '/page/' + page,
            $( "#filterForm" ).serialize(), function (htmlData) {
                $('#content').html(htmlData)
            }
        )
    }
}

function openTaskTemplateAddForm(type) {
    if (type == '0') {
        return false;
    }
    createDialogBox(_t('L_TASK_ADDING'), '/tasks-templates/add/project_id/' + projectId + '/type/' + type, 650, 420)
}


function sendTaskTemplateAddForm() {
    var type = $("#taskForm_type").val()
    var object_id = $("#taskForm_object_id").val()
    $.post(
        "/tasks-templates/add/",
        $( "#taskTemplateAddForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                tasksTemplatesListFilter(1)
                loadProjectMenu(projectId)
            }
        }
    );
}

function openTaskTemplateEditForm(id) {
    createDialogBox(_t('L_TASK_EDITING'), '/tasks-templates/edit/id/' + id, 650, 420)
}

function sendTaskTemplateEditForm() {
    var task_id = $("#taskTemplatesForm_id").val()
    $.post(
        "/tasks-templates/edit/",
        $( "#taskTemplateEditForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                if ($("#taskTemplateRow" + task_id).length) {
                    get('/tasks-templates/one-in-list/id/' + task_id, function (htmlData) {
                        $("#taskTemplateRow" + task_id).html(htmlData)
                    })
                } else {
                    taskTemplateLoadView(task_id)
                }
            }
        }
    );
}

function taskTemplateDelete(id, title) {
    if (confirm(_t('L_REALY_WANT_DELETE_TASK') + ' "' + title + '"?')) {
        get("/tasks-templates/delete/id/" + id, function(data) {
            if (data.length) {
                alert(data)
            } else {
                //$("#taskTemplateRow" + id).remove()
                tasksTemplatesListFilter(1)
                loadProjectMenu(projectId)
            }
        })
    }
}


function setDataToTaskTemplateAddLink() {
    $('#addTaskTemplateLink').data('type', $('#filterType').val())
}


function goTasksTemplateListLink() {
    var filterData = []
    filterData[filterData.length] = $('#filterType').val()
    var hash = '#tasktemplates-openlist-FILTER-' + filterData.join("/")
    $.cookie('menu_taskstemplates', hash)
    $("#menu-taskstemplates").attr('href', hash)
    goNavigation(hash)
}


function taskTemplateLoadView(id) {
    get('/tasks-templates/view/id/' + id, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}


function openTasksTemplatesList(projectId) {
    get('/tasks-templates/index/project_id/' + projectId, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}