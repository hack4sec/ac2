/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */

function loadNotesOfCurrentProject() {
    get('/notes/get-list/project_id/' + projectId, function (htmlData) {
        $("#notesBlock").html(htmlData)
    })
    loadNotesCountOfCurrentProject()
}


function loadNotesCountOfCurrentProject() {
    get('/notes/count/project_id/' + projectId, function (htmlData) {
        $("#notesCount").html(htmlData)
    })
}

function deleteNote(id) {
    if (confirm(_t('L_REALY_WANT_DELETE_NOTE'))) {
        get('/notes/delete/id/' + id, function (htmlData) {
            $("#note" + id).remove()
            loadNotesCountOfCurrentProject()
        })
    }
}

function editNote(id) {
    $("#spanNote" + id).hide()
    $("#textareaNote" + id).show()
}

function editNoteDone(id) {
    $.post("/notes/save/id/" + id, {content: $("#textareaNote" + id).val()}, function () {
        get('/notes/get-one/id/' + id, function (htmlData) {
            $("#note" + id).html(htmlData)
        })
    } );
}

function addNote() {
    $.post(
        "/notes/add/",
        {
            content: $("#addNoteTextarea").val(),
            project_id: projectId
        },
        function () {
            $("#addNoteTextarea").val('')
            loadNotesOfCurrentProject()
        }
    );

}