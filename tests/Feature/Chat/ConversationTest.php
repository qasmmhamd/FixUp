<?php
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConversationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_message(): void
    {
        $user = User::factory()->create();

        $conversation = Conversation::factory()->create([
            'customer_id' => $user->id
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/chat/send-template', [
            'conversation_id' => $conversation->id,
            'template_id' => 1
        ]);

        $response->assertStatus(201);
    }
}