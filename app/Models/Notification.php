<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'type', // emergency, info, reward, system, fraud_alert, warning
        'related_id',
        'related_type',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * تحديث حالة الإشعار إلى "مقروء" وتسجيل وقت القراءة
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Scope لجلب الإشعارات غير المقروءة فقط
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * علاقة متعددة الأشكال (Polymorphic)
     */
    public function related()
    {
        return $this->morphTo(__FUNCTION__, 'related_type', 'related_id');
    }
}
