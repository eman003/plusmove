<?php

namespace App\Listeners;

use App\Events\MissingAddressEvent;
use App\Notifications\MissingAddressNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class MissingAddressListener
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
    public function handle(MissingAddressEvent $event): void
    {
        Notification::route('email', $event->package?->customer?->email)
            ->notify(new MissingAddressNotification($event->package));
    }
}
