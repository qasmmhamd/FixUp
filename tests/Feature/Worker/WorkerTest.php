<?php
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Career;

class WorkerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_become_worker(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $career = Career::factory()->create();

        $response = $this->postJson('/api/register-worker', [
            'career_id' => $career->id,
            'about' => 'skilled worker',
            'years_experience' => 3
        ]);

        $response->assertStatus(201);
    }
}