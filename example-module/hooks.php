<?php

use Illuminate\Support\Facades\Log;
use Williamug\Modular\HookManager;

return function (HookManager $hooks) {
    $hooks->listen('student.created', function ($student) {
        Log::info("New student registered: {$student->name}");
    });

    $hooks->listen('fee.payment.completed', function ($payment) {
        Log::info("Fee payment processed: {$payment->id}");
    });
};
