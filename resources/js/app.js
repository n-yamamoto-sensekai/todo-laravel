import $ from 'jquery'

window.$ = $;
window.jQuery = $;

// AjaxリクエストのヘッダーにCSRFトークンを設置（HTMLのmetaタグから読む）
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});

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

// 最大文字数で省略
function truncateText(text, maxLength) {
    if (!text) {
        return '';
    }
    if (text.length <= maxLength) {
        return text;
    }
    return text.slice(0, maxLength) + '...';
}

function updateTaskItem(task) {
    const $taskTitle = $('#task-title-' + task.id);
    const $taskDueDate = $('#task-due-date-' + task.id);
    const $taskMemo = $('#task-memo-' + task.id);

    $taskTitle.text(task.title);

    if (task.due_date) {
        $taskDueDate.text('期限：' + task.due_date);
    } else {
        $taskDueDate.text('');
    }

    if (task.memo) {
        $taskMemo.text('メモ：' + truncateText(task.memo, 15));
    } else {
        $taskMemo.text('');
    }

    // jQuery内のキャッシュを更新
    $taskTitle.data('title', task.title);
    $taskTitle.data('due-date', task.due_date);
    $taskTitle.data('memo', task.memo);

    // HTML上の見た目を更新
    $taskTitle.attr('data-title', task.title);
    $taskTitle.attr('data-due-date', task.due_date);
    $taskTitle.attr('data-memo', task.memo);
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

    // Ajax更新
    $('#modal-task-form').on('submit', function (event) {

        // フォームの通常送信を止める
        event.preventDefault();

        // 代わりにAjaxで送る
        $.ajax({
            url: $(this).attr('action'),
            method: 'PUT',
            data: $(this).serialize(),  // フォーム内のname属性がある入力欄の値をまとめて送信データにする
            dataType: 'json',        // サーバーから返ってくるレスポンスをJSONとして扱う
            success: function (response) {   // Ajax通信が成功した時に返ってきたレスポンスをもって実行
                console.log(response);
                updateTaskItem(response.task);  // タスク一覧の表示を更新
                closeTaskModal(); // モーダルを閉じる
            },
        });
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
