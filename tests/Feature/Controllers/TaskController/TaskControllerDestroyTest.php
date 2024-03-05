<?php

use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerDestroyTest extends TestCase {
    public function test_can_destroy_created_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user, 'creator')->create();
        
        Sanctum::actingAs($user);
        
        $route = route('tasks.destroy', $task);
        $response = $this->deleteJson($route);

        $response->assertNoContent();

        $this->assertDatabaseMissing('tasks', $task->toArray());
    }
}