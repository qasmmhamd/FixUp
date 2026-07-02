<?php
namespace Database\Factories;

use App\Models\Conversation;
use App\Models\User;
use App\Models\Worker;
use App\Models\MessageTopic;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition(): array
    {
        $customer = User::factory()->create();
        $workerUser = User::factory()->create();

        $worker = Worker::factory()->create([
            'user_id' => $workerUser->id,
        ]);

        return [
        'customer_id' => \App\Models\User::factory(),
        'worker_id' => \App\Models\User::factory(),
        'topic_id' => MessageTopic::factory(),
        'status' => 'open',
    ];
    }
}