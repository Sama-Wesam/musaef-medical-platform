<?php

use App\Providers\AppServiceProvider;
use App\Providers\BroadcastServiceProvider;

return [
    AppServiceProvider::class,
    BroadcastServiceProvider::class, // تم تفعيله لتشغيل البث اللحظي (Real-time Broadcast)
];
