<?php

namespace App\Listeners;

use App\Events\PackageDeliveryStatusEvent;
use App\Models\V1\Package;
use App\Notifications\DeliveryStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendPackageDeliveryStatusNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PackageDeliveryStatusEvent $event): void
    {
        $email = $event->package?->customer?->email;

        Notification::route('mail', $email)->notify(new DeliveryStatusNotification($event->package));
    }
}
