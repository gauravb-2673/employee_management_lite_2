<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

// Run this task once every minute.
Schedule::call(function () {
    Log::info('Employee Management Lite scheduler is running.', [
        'executed_at' => now()->toDateTimeString(),
    ]);
})->everyMinute()->name('employee-scheduler-test');
