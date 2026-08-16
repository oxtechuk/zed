<?php

namespace App\Jobs;

use App\Services\Tracking\ConversionTrackingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendConversionTrackingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 15;

    public function __construct(
        public string $type,
        public array $userData,
        public array $customData = [],
        public ?string $eventId = null,
    ) {}

    public function handle(ConversionTrackingService $tracker): void
    {
        if ($this->type === 'calculator') {
            $tracker->trackCalculatorLead($this->userData, $this->customData, $this->eventId);
        } else {
            $tracker->trackLead($this->userData, $this->customData, $this->eventId);
        }
    }
}
