<?php

namespace App\Jobs;

use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendNotificationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public $user,
        public string $title,
        public string $body,
        public string $type,
        public array $data = []
    ) {}

    public function handle(
        NotificationService $notificationService
    ): void {

        $notificationService->send(
            $this->user,
            $this->title,
            $this->body,
            $this->type,
            $this->data
        );
    }
}