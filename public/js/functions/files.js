/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */

function filesFilterTypeChange(type) {
    blankContent()

    $("#addFileLink").data('type', type)
    $('#parentDiv').show()
    $('#objectDiv').show()

    if (type == 'web-app' || type == 'server-software' || type == 'domain') {
        getJSON('/files/parents-list-json/type/' + type + '/project_id/' + projectId, function (data) {
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
            filesListFilter(1)
        }
    } else if(type == 'project') {
        $('#parentDiv').hide()
        $('#objectDiv').hide()
        filesFilterObjectChange(projectId)
        filesListFilter(1)

    } else if(type == 'server') {
        $('#parentDiv').hide()
        filesFilterParentChange(projectId)
    } else {
        filesListFilter(1)
    }
}

function filesFilterParentChange(parent) {
    blankContent()

    var type = $('#filterType').val()

    getJSON('/files/objects-list-json/parent_id/' + parent + '/type/' + type + '/project_id/' + projectId, function (data) {
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
        filesListFilter(1)
    }}

function filesFilterObjectChange(object) {
    $("#addFileLink").data('object', object)
}


function filesListFilter(page) {
    var type = $('#filterType').val()
    var obj = $('#filterObject').val()
    var parent = $('#filterParent').val()

    $.post(
        '/files/ajax-list/type/' + type + '/object_id/' + obj + '/parent/' + parent +  '/project_id/' + projectId + '/page/' + page,
        $( "#filterForm" ).serialize(), function (htmlData) {
            $('#content').html(htmlData)
        }
    )
    /*if ($('#filterType').val() == 'project') {
        var type = $('#filterType').val()
        var obj  = projectId

        $.post('/files/ajax-list/type/' + type + '/object_id/' + obj + '/page/' + page, $( "#filterForm" ).serialize(), function (htmlData) {
            $('#content').html(htmlData)
        })
    } else {
        if (!parseInt($('#filterObject').val())) {
            alert(_t('L_SELECT_OBJECT_FIRST'));
            return false;
        }
        var type = $('#filterType').val()
        var obj = $('#filterObject').val()
        $.post('/files/ajax-list/type/' + type + '/object_id/' + obj + '/page/' + page, $( "#filterForm" ).serialize(), function (htmlData) {
            $('#content').html(htmlData)
        })
    }*/

}

function openFileAddForm(type, objectId) {
    if (!objectId.toString().length || objectId.toString() == '0') {
        alert(_t('L_SELECT_OBJECT_FIRST'))
        return;
    }
    createDialogBox(_t('L_FILE_ADDING'), '/files/add/object_id/' + objectId + '/type/' + type, 650, 340)
}

function openFileEditForm(id) {
    createDialogBox(_t('L_FILE_EDITING'), '/files/edit/id/' + id, 650, 320)
}

function sendFileEditForm() {
    var file_id = $("#fileForm_id").val()
    $.post(
        "/files/edit/",
        $( "#fileEditForm" ).serialize()
    ).done(
        function (data) {
            if (data.length) {
                $('#dialogbox').html(data)
            } else {
                $('#dialogbox').dialog('close')
                $('#dialogbox').remove()

                if ($("#fileRow" + file_id).length) {
                    get('/files/one-in-list/id/' + file_id, function (htmlData) {
                        $("#fileRow" + file_id).html(htmlData)
                    })
                } else {
                    fileLoadView(file_id)
                }
            }
        }
    );
}

function fileDelete(id, title) {
    if (confirm(_t('L_REALY_WANT_DELETE_FILE') + ' "' + title + '"?')) {
        get("/files/delete/id/" + id, function(data) {
            if (data.length) {
                alert(data)
            } else {
                //$("#fileRow" + id).remove()
                filesListFilter(1);
                loadProjectMenu(projectId)
            }
        })
    }
}

function sendFileAddForm() {
    var file_data = $('#fileForm_file').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
    form_data.append('comment', $('#fileForm_comment').val());
    form_data.append('type', $('#fileForm_type').val());
    form_data.append('object_id', $('#fileForm_object_id').val());

    $.ajax({
        url: '/files/add/',
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
                filesListFilter(1)
            }
        }
    });
}

function fileDownload(id) {
    window.location.href = '/files/download/id/' + id
}

function goFilesListLink() {
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
    var hash = '#file-openlist-FILTER-' + filterData.join("/")
    $.cookie('menu_files', hash)
    $("#menu-files").attr('href', hash)
    goNavigation(hash)
}


function fileLoadView(id) {
    get('/files/view/id/' + id, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}

function openFilesList(projectId) {
    get('/files/index/project_id/' + projectId, function (htmlData) {
        $("#work-space").html(htmlData)
    })
}
