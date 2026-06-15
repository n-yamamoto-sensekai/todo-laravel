@props(['taskGroups'])

<div id="task-modal" class="hidden fixed inset-0 z-50 bg-black/50">
    <div id="task-modal-backdrop" class="flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-lg rounded bg-white p-6 shadow-lg">
            <div class="mb-4 flex items-center justify-between">
                
                <h2 class="text-xl font-bold">タスク詳細</h2>

                <button
                    type="button"
                    id="js-close-task-modal"
                    class="text-gray-500 hover:text-gray-700"
                >
                    ×
                </button>
            </div>

            {{-- 更新フォーム --}}
            <form id="modal-task-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        タスク名
                    </label>
                    <input
                        type="text"
                        id="modal-task-title"
                        name="title"
                        class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <p id="modal-task-title-error" class="mt-2 text-sm text-red-600"></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        期限
                    </label>
                    <input
                        type="date"
                        id="modal-task-due-date"
                        name="due_date"
                        class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        メモ
                    </label>
                    <textarea
                        id="modal-task-memo"
                        name="memo"
                        rows="4"
                        class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        グループ
                    </label>
                    <select
                        id="modal-task-group-id"
                        name="task_group_id"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">グループなし</option>

                        @foreach ($taskGroups as $taskGroup)
                            <option value="{{ $taskGroup->id }}">
                                {{ $taskGroup->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        id="js-cancel-task-modal"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
                    >
                        閉じる
                    </button>

                    <button
                        type="button"
                        id="js-delete-task-form-modal"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                    >
                        削除
                    </button>

                    <x-primary-button>
                        更新
                    </x-primary-button>
                </div>
            </form>
            <form
                id="modal-task-delete-form"
                method="POST"
                class="hidden"
            >
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>