import $ from 'jquery';

window.$ = $;
window.jQuery = $;

// AjaxリクエストのヘッダーにCSRFトークンを設置（HTMLのmetaタグから読む）
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
});

import './task-modal';
