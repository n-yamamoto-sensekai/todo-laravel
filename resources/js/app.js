import $ from 'jquery'

window.$ = $;
window.jQuery = $;

$(function () {
    
    //  モーダル表示とタスク情報の取得
    $('.js-open-task-model').on('click', function () {
        
        const id = $(this).data('id')
        const title = $(this).data('title')
        const dueDate = $(this).data('due-date')
        const memo = $(this).data('memo')

        $('#modal-task-title').val(title);
        $('#modal-task-due-date').val(dueDate);
        $('#modal-task-memo').val(memo);

        // フォームの送り先
        $('#modal-task-form').attr('action', '/tasks/' + id);
        $('#modal-task-delete-form').attr('action', '/tasks/' + id);
        
        $('#task-modal').removeClass('hidden');
    });

    // モーダル内削除
    $('#js-delete-task-form-modal').on('click', function () {
        if (confirm('このタスクを削除しますか？')) {
            $('#modal-task-delete-form').submit();
        }
    });

    // モーダルを閉じる
    $('#js-close-task-modal').on('click', function () {
        $('#task-modal').addClass('hidden');
    });

    $('#js-cancel-task-modal').on('click', function () {
        $('#task-modal').addClass('hidden');
    });

    $('#task-modal-backdrop').on('click', function (event) {
        if (event.target === this) {     // モーダルの黒い背景をクリックした時
            $('#task-modal').addClass('hidden');
        }
    });
});
