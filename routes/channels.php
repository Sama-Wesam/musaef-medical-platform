<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// مصادقة قناة المستشفى الخاصة لاستقبال قبول أو رفض المتبرعين
Broadcast::channel('hospital.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id && strtolower((string) $user->role) === 'hospital';
});

// مصادقة قناة لوحة تحكم الإدارة
Broadcast::channel('admin.dashboard', function ($user) {
    return strtolower((string) $user->role) === 'admin';
});
