<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'=> 'required|max:255',
			'due_date' => 'nullable|date',
			'memo' => 'nullable|max:1000',
            'task_group_id' => 'nullable|exists:task_groups,id', // 存在するtask_group.idだけ許可する
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'title.required'=> 'タスク名は必須です。',
    //         'title.max'=> 'タスク名は255文字以内で入力してください',
    //         'due_date.date'=> '期限は日付として正しい形式で入力してください',
    //         'memo.max'=> 'メモは1000文字以内で入力してください',
    //     ];
    // }
}
