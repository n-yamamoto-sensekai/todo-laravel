import $ from 'jquery';

function openTaskModal() {
    $('#task-modal').removeClass('hidden');
}

function closeTaskModal() {
    $('#task-modal').addClass('hidden');
}

function clearTaskModalErrors() {
    $('#modal-task-title-error').text('');
}

function setTaskModalValues(title, dueDate, memo, taskGroupId) {
    $('#modal-task-title').val(title);
    $('#modal-task-due-date').val(dueDate);
    $('#modal-task-memo').val(memo);
    $('#modal-task-group-id').val(taskGroupId || '');
}

function setTaskFormActions(id) {
    $('#modal-task-form').attr('action', '/tasks/' + id);
    $('#modal-task-delete-form').attr('action', '/tasks/' + id);
}

function submitTaskDeleteForm() {
    if (!confirm('このタスクを削除しますか？')) {
        return;
    }    
    const $deleteForm = $('#modal-task-delete-form');

    $.ajax({
        url: $deleteForm.attr('action'),
        method: 'DELETE',
        dataType: 'json',
        success: function (response) {
            console.log(response);
            removeTaskItem(response.task_id);   // タスク一覧からタスクを削除
            closeTaskModal();
        },
        error: function (xhr) {
            console.log('status:', xhr.status);
            console.log('responseJSON:', xhr.responseJSON);
            console.log('resoponseTEXT:', xhr.responseTEXT);
        }
    });
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

// 期限の表示分け
function formatDueDateText(dueDate) {
    if (!dueDate) {
        return '';
    }

    const today = new Date();   // 現在時刻を含んだ今日の日時
    const targetDate = new Date(dueDate);

    today.setHours(0, 0, 0, 0);  // 00:00:00.000 にそろえる
    targetDate.setHours(0, 0, 0, 0);

    const diffDays = Math.round((targetDate - today) / (1000 * 60 * 60 * 24));  // ミリ秒の差を日数に。Math.round()：少数を一番近い整数に丸める

    if (diffDays === 0) {
        return '今日';
    }
    if (diffDays === -1) {
        return '昨日';
    }
    if (diffDays === 1) {
        return '明日';
    }
    return dueDate;
}

// 期限の色分け
function updateDueDateClass($taskDueDate, dueDate, isDone) {
    $taskDueDate.removeClass('text-gray-500 text-red-600');

    const today = new Date();
    const targetDate = new Date(dueDate);

    today.setHours(0, 0, 0, 0);
    targetDate.setHours(0, 0, 0, 0);

    const isOverdue = dueDate && targetDate < today;

    if (isOverdue && !isDone) {
        $taskDueDate.addClass('text-red-600');
    } else {
        $taskDueDate.addClass('text-gray-500');
    }
}

// Ajax更新時 一覧表示のタスク更新
function updateTaskItem(task) {
    const $taskTitle = $('#task-title-' + task.id);
    const $taskDueDate = $('#task-due-date-' + task.id);
    const $taskMemo = $('#task-memo-' + task.id);
    const $taskGroupName = $('#task-group-name-' + task.id);

    $taskTitle.text(task.title);

    if (task.due_date) {
        $taskDueDate.text('期限：' + formatDueDateText(task.due_date));
    } else {
        $taskDueDate.text('');
    }

    updateDueDateClass($taskDueDate, task.due_date, task.is_done);

    if (task.memo) {
        $taskMemo.text('メモ：' + truncateText(task.memo, 15));
    } else {
        $taskMemo.text('');
    }

    if (task.task_group_name) {
        $taskGroupName.text('グループ：' + task.task_group_name);
    } else {
        $taskGroupName.text('');
    }

    // jQuery内のキャッシュを更新
    $taskTitle.data('title', task.title);
    $taskTitle.data('due-date', task.due_date);
    $taskTitle.data('memo', task.memo);
    $taskTitle.data('task-group-id', task.task_group_id);

    // HTML上の見た目を更新
    $taskTitle.attr('data-title', task.title);
    $taskTitle.attr('data-due-date', task.due_date);
    $taskTitle.attr('data-memo', task.memo);
    $taskTitle.attr('task-group-id', task.task_group_id);
}

function updateTaskDoneStatus(task) {
    const $taskTitle = $('#task-title-' + task.id);
    const $toggleButton = $('#task-toggle-button-' + task.id);
    const $statusLabel = $('#task-status-label-' + task.id);
    const $taskDueDate = $('#task-due-date-' + task.id);

    if (task.is_done) {
        $taskTitle.addClass('line-through text-gray-400');
        $toggleButton.text('未完了に戻す');
        $statusLabel
            .text('完了')
            .removeClass('text-gray-500')
            .addClass('text-green-600');

        $taskTitle.data('is-done', 1);
        $taskTitle.attr('data-is-done', 1);

    } else {
        $taskTitle.removeClass('line-through text-gray-400');
        $toggleButton.text('完了');
        $statusLabel
            .text('未完了')
            .removeClass('text-green-600')
            .addClass('text-gray-500');

        $taskTitle.data('is-done', 0);
        $taskTitle.attr('data-is-done', 0);
    }
    
    updateDueDateClass($taskDueDate, task.due_date, task.is_done);
}

function removeTaskItem(taskId) {
    $('#task-item-' + taskId).remove();
}

$(function () {
    
    //  モーダル表示とタスク情報の取得
    $('.js-open-task-model').on('click', function () {
        
        const id = $(this).data('id');
        const title = $(this).data('title');
        const dueDate = $(this).data('due-date');
        const memo = $(this).data('memo');
        const taskGroupId = $(this).data('task-group-id');

        clearTaskModalErrors();  // モーダル内のエラーを消去
        setTaskModalValues(title, dueDate, memo, taskGroupId);  // モーダルの中身にタスクの値をセット
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

                clearTaskModalErrors();
                updateTaskItem(response.task);  // タスク一覧の表示を更新
                closeTaskModal(); // モーダルを閉じる
            },
            error: function (xhr) {
                const errors = xhr.responseJSON.errors;

                if (errors.title) {
                    $('#modal-task-title-error').text(errors.title[0]);
                }
            }
        });
    });

    // 完了状態のAjax更新
    $('.js-toggle-task-form').on('submit', function (event) {

        event.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: 'PATCH',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                console.log(response);
                updateTaskDoneStatus(response.task);
            },
            error: function (xhr) {
                console.log('status:', xhr.status);
                console.log('responseJSON:', xhr.responseJSON);
                console.log('resoponseTEXT:', xhr.responseTEXT);
            }
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