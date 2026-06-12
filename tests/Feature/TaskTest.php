<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;  // テストごとにDBをリセットしてくれる

    // タスク一覧ページが正常に表示できる
    public function test_tasks_index_can_be_displayed(): void
    {
        $response = $this->get('/tasks');
        $response->assertStatus(200);
    }

    // タスクが追加できる
    public function test_tasks_can_be_created(): void
    {
        $response = $this->post('/tasks', [
            'title'=> 'テストタスク',
        ]);
        
        $response->assertRedirect('/tasks');  // 追加後のリダイレクト確認
        $this->assertDatabaseHas('tasks', [  // データが保存されているか確認
            'title' => 'テストタスク',
            'is_done' => false,
        ]);
    }

    // タイトルが空のとき保存できない
    public function test_task_title_is_required(): void
    {
        $response = $this->post('/tasks', [
            'title' => '',
        ]);

        $response->assertSessionHasErrors('title');  // タイトルにバリデーションエラーが入ったか確認
        $this->assertDatabaseCount('tasks', 0);  // DBに何も保存されていないことを確認
    }

    // タスクが更新できる
    public function test_task_can_be_updated(): void
    {
        $task = Task::factory()->create([
            'title' => '変更前のタスク',
            'due_date' => null,
            'memo' => null,
        ]);

        $response = $this->put('/tasks/' . $task->id, [
            'title' => '変更後のタスク',
            'due_date' => '2026-06-30',
            'memo' => '更新テスト用メモ',
        ]);

        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => '変更後のタスク',
            'due_date' => '2026-06-30',
            'memo' => '更新テスト用メモ',
        ]);
    }

    // タスクがAjaxで更新できる
    public function test_task_can_be_updated_with_json_response(): void
    {
        $task = Task::factory()->create([
            'title'=> 'Ajax更新前',
        ]);

        $response = $this->putJson('/tasks/' . $task->id, [  // putJson：$request->expectsJson() が true になりやすい
            'title' => 'Ajax更新後',
            'due_date' => '2026-07-01',
            'memo' => 'Ajax更新テスト',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'タスクを更新しました。',
            'task' => [
                'id' => $task->id,
                'title' => 'Ajax更新後',
                'due_date' => '2026-07-01',
                'memo' => 'Ajax更新テスト',
            ],
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Ajax更新後',
            'due_date' => '2026-07-01',
            'memo' => 'Ajax更新テスト',
        ]);
    }

    // タスクがAjaxで削除できる
    public function test_task_can_be_deleted_with_json_response(): void
    {
        $task = Task::factory()->create([
            'title' => 'Ajax削除されるタスク',
        ]);

        $response = $this->deleteJson('/tasks/' . $task->id);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'タスクを削除しました',
            'task_id' => $task->id,
        ]);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    // タスクが削除できる
    public function test_task_can_be_deleted(): void
    {
        $task = Task::factory()->create([
            'title' => '削除されるタスク',
        ]);

        $response = $this->delete('/tasks/' . $task->id);

        $response->assertRedirect('/tasks');
        $this->assertDatabaseMissing('tasks', [  // 指定したIDのレコードが存在しないことを確認
            'id'=> $task->id,
        ]);
    }

    // 完了/未完了を切り替えられる
    public function test_task_done_status_can_be_toggled(): void
    {
        $task = Task::factory()->create([
            'is_done' => false,
        ]);

        $response = $this->patch('/tasks/' . $task->id . '/toggle');

        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'is_done' => true,
        ]);
    }

    // Ajaxで完了/未完了を切り替えられる
    public function test_task_done_status_can_be_toggled_with_json_response(): void
    {
        $task = Task::factory()->create([
            'is_done' => false,
        ]);

        $response = $this->patchJson('/tasks/' . $task->id . '/toggle');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'タスクの状態を更新しました',
            'task' => [
                'id' => $task->id,
                'is_done' => true,
            ],
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'is_done' => true,
        ]);
    }

    // タスクをすべて完了できる
    public function test_all_tasks_can_be_marked_as_done(): void
    {
        Task::factory()->count(3)->create([
            'is_done' => false
        ]);

        $response = $this->patch('/tasks/mark-all-done');

        $response->assertRedirect('/tasks');
        $this->assertDatabaseMissing('tasks', [
            'is_done'=> false
        ]);
    }

    // タスクをすべて未完了に戻せる
    public function test_all_tasks_can_be_marked_as_undone(): void
    {
        Task::factory()->count(3)->create([
            'is_done' => true
        ]);

        $response = $this->patch('/tasks/mark-all-undone');
        
        $response->assertRedirect('/tasks');
        $this->assertDatabaseMissing('tasks', [
            'is_done' => true
        ]);
    }

    // 完了済みのタスクをすべて削除できる
    public function test_completed_tasls_can_be_deleted(): void
    {
        Task::factory()->count(2)->create([
            'is_done' => true,
        ]);

        Task::factory()->create([    // 消えてはいけないタスクが残ることも確認
            'title'=> '残る未完了タスク',
            'is_done' => false,
        ]);

        $response = $this->delete('/tasks/completed');

        $response->assertRedirect('/tasks');
        $this->assertDatabaseMissing('tasks', [
            'is_done' => true
        ]);

        $this->assertDatabaseHas('tasks', [
            'title'=> '残る未完了タスク',
            'is_done' => false,
        ]);
    }

    // 未完了フィルター表示ができる
    public function test_active_tasks_can_be_filtered(): void
    {
        Task::factory()->create([
            'title' => 'テスト用タスク（未完了）',
            'is_done' => false,
        ]);

        Task::factory()->create([
            'title' => 'テスト用タスク（完了）',
            'is_done' => true,
        ]);

        $response = $this->get('/tasks?filter=active');

        $response->assertStatus(200);
        $response->assertSee('テスト用タスク（未完了）');  // assertSee：レスポンスHTMLの中にその文字列があるか確認
        $response->assertDontSee('テスト用タスク（完了）');
    }

    // 完了フィルター表示ができる
    public function test_completed_tasks_can_be_filtered(): void
    {
        Task::factory()->create([
            'title' => 'テスト用タスク（未完了）',
            'is_done' => false,
        ]);

        Task::factory()->create([
            'title' => 'テスト用タスク（完了）',
            'is_done' => true,
        ]);

        $response = $this->get('/tasks?filter=completed');

        dump(Task::first());
        $response->assertStatus(200);
        $response->assertSee('テスト用タスク（完了）');
        $response->assertDontSee('テスト用タスク（未完了）');
    }
}
