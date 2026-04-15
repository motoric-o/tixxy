<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * Queue & Waitlist Processing
 * Expires stale holds and promotes waitlisted users every minute.
 */
Schedule::command('queue:process-tickets')->everyMinute();
