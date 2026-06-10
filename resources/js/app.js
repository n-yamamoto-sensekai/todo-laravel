import $ from 'jquery'

window.$ = $;
window.jQuery = $;

function openTaskModal() {
    $('#task-modal').removeClass('hidden');
}

function closeTaskModal() {
    $('#task-modal').addClass('hidden');
}

function setTaskModalValues(title, dueDate, memo) {
    $('#modal-task-title').val(title);
    $('#modal-task-due-date').val(dueDate);
    $('#modal-task-memo').val(memo);
}

function setTaskFormActions(id) {
    $('#modal-task-form').attr('action', '/tasks/' + id);
    $('#modal-task-delete-form').attr('action', '/tasks/' + id);
}

function submitTaskDeleteForm() {
    if (confirm('このタスクを削除しますか？')) {
        $('#modal-task-delete-form').submit();
    }
}

$(function () {
    
    //  モーダル表示とタスク情報の取得
    $('.js-open-task-model').on('click', function () {
        
        const id = $(this).data('id')
        const title = $(this).data('title')
        const dueDate = $(this).data('due-date')
        const memo = $(this).data('memo')

        setTaskModalValues(title, dueDate, memo);  // モーダルの中身にタスクの値をセット
        setTaskFormActions(id);   // フォームの送り先をセット
        
        openTaskModal();
    });

    // モーダル内削除
    $('#js-delete-task-form-modal').on('click', function () {
        submitTaskDeleteForm();
    });

    // モーダルを閉じる
    $('#js-close-task-modal').on('click', function () {
        closeTaskModal();
    });

    $('#js-cancel-task-modal').on('click', function () {
        closeTaskModal();
    });

    $('#task-modal-backdrop').on('click', function (event) {
        if (event.target === this) {     // モーダルの黒い背景をクリックした時
            closeTaskModal();
        }
    });
});
