<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'type', // emergency, info, reward, system
        'related_id',
        'related_type',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // المستخدم صاحب الإشعار
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة مرنة (Polymorphic) لربط الإشعار بطلب دم أو مكافأة
    public function related()
    {
        return $this->morphTo();
    }
}