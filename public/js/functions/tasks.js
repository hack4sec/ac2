/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */

function tasksFilterObjectChange(object) {
    $("#addTaskLink").data('object', object)
}

function tasksFilterTypeChange(type) {
    blankContent()

    $("#addTaskLink").data('type', type)
    $('#parentDiv').show()
    $('#objectDiv').show()

    if (type == 'web-app' || type == 'server-software' || type == 'domain') {
        getJSON('/tasks/parents-list-json/type/' + type + '/project_id/' + projectId, function (data) {
            $('#filterParent').html('')
            var option = document.createElement('option')
            option.value = 0
            option.innerHTML = _t('L_ALL')
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
            option.innerHTML = _t('L_ALL')
            $('#filterObject').append(option)
        })
        if (!app_navigation) {
            tasksListFilter(1)
        }
    } else if(type == 'project') {
        $('#parentDiv').hide()
        $('#objectDiv').hide()
        tasksFilterObjectChange(projectId)
        tasksListFilter(1)
    } else if(type == 'server') {
        $('#parentDiv').hide()
        tasksFilterParentChange(projectId)
    } else {
        tasksListFilter(1)
    }
}


function tasksFilterParentChange(parent) {
    var type = $('#filterType').val()

    getJSON('/tasks/objects-list-json/parent_id/' + parent + '/type/' + type + '/project_id/' + projectId, function (data) {
        $('#filterObject').html('')
        var option = document.createElement('option')
        option.value = 0
        option.innerHTML = _t('L_ALL')
        $('#filterObject').append(option)

        for (i in data) {
            var option = document.createElement('option')
            option.value = i
            option.innerHTML = data[i]
            $('#filterObject').append(option)
        }
    })
    if (!app_navigation) {
        tasksListFilter(1)
    }
}

function tasksListFilter(page) {
    var type = $('#filterType').val()
    var obj = $('#filterObject').val()
    var parent = $('#filterParent').val()

    $.post(
        '/tasks/ajax-list/type/' + type + '/object_id/' + obj + '/parent/' + parent +  '/project_id/' + projectId + '/page/' + page,
        $( "#filterForm" ).serialize(), function (htmlData) {
            $('#content').html(htmlData)
        }
    )
    /*if ($('#filterType').val() == '0') {

    } else if ($('#filterType').val() == 'project') {
        var type = $('#filterType').val()
        var obj  = projectId

        $.post('/tasks/ajax-list/type/' + type + '/object_id/' + obj + '/page/' + page, $( "#filterForm" ).serialize(), function (htmlData) {
            $('#content').html(htmlData)
        })
    } else {
        if (!parseInt($('#filterObject').val())) {
            alert(_t('L_SELECT_OBJECT_FIRST'));
            return false;
        }
        var type = $('#filterType').val()
        var obj = $('#filterObject').val()
        $.post('/tasks/ajax-list/type/' + type + '/object_id/' + obj + '/page/' + page, $( "#filterForm" ).serialize(), function (htmlData) {
            $('#content').html(htmlData)
        })
    }*/

}

function tasksFilterProjectChange(project_id) {
    $('#filterType').html('')

    var option = document.createElement('option')
    option.value = 0
    option.innerHTML = _t('L_ALL')
    $('#filterType').append(option)

    var data = {'web-app': _t('L_WEB_APPS'), 'server-software': _t('L_SPO')}
    for (i in data) {
        var option = document.createElement('option')
        option.value = i
        option.innerHTML = data[i]
        $('#filterType').append(option)
    }
}

function openTaskAddForm(type, objectId) {
    if (!objectId.toString().length || objectId.toString() == '0') {
        alert(_t('L_SELECT_OBJECT_FIRST'))
        return;
    }
    createDialogBox(_t('L_TASK_ADDING'), '/tasks/add/object_id/' + objectId + '/type/' + type, 650, 420)
}


function sendTaskAddForm() {
    var type = $("#taskForm_type").val()
    var object_id = $("#taskForm_object_id").val()
    $.post(
        "/tasks/add/",
        $( "#taskAddForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                tasksListFilter(1)
                loadProjectMenu(projectId)
            }
        }
    );
}

function openTaskEditForm(id) {
    createDialogBox(_t('L_TASK_EDITING'), '/tasks/edit/id/' + id, 650, 420)
}

function sendTaskEditForm() {
    var task_id = $("#taskForm_id").val()
    $.post(
        "/tasks/edit/",
        $( "#taskEditForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                if ($("#taskRow" + task_id).length) {
                    get('/tasks/one-in-list/id/' + task_id, function (htmlData) {
                        $("#taskRow" + task_id).html(htmlData)
                    })
                } else {
                    taskLoadView(task_id)
                }
            }
        }
    );
}

function taskDelete(id, title) {
    if (confirm(_t('L_REALY_WANT_DELETE_TASK') + ' "' + title + '"?')) {
        get("/tasks/delete/id/" + id, function(data) {
            if (data.length) {
                alert(data)
            } else {
                //$("#taskRow" + id).remove()
                tasksListFilter(1)
                loadProjectMenu(projectId)
            }
        })
    }
}


function setDataToTaskAddLink() {
    $('#addTaskLink').data('type', $('#filterType').val())
    $('#addTaskLink').data('object', $('#filterObject').val())
}


function goTasksListLink() {
    /*if ($('#filterObject').val() == "0") {
     return alert(_t('L_SELECT_OBJECT_FIRST'))
     }*/
    var filterData = []
    filterData[filterData.length] = $('#filterType').val()
    filterData[filterData.length] = $('#filterParent').val()
    filterData[filterData.length] = $('#filterObject').val()
    if ($('#filterSearch').val().length) {
        filterData[filterData.length] = $('#filterSearch').val()
    }
    var hash = '#task-openlist-FILTER-' + filterData.join("/")
    $.cookie('menu_tasks', hash)
    $("#menu-tasks").attr('href', hash)
    goNavigation(hash)
}


function taskLoadView(id) {
    get('/tasks/view/id/' + id, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}


function openTasksList(projectId) {
    get('/tasks/index/project_id/' + projectId, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}