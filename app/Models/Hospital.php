<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $fillable = [
        'user_id',
        'license_number',
        'address',
        'latitude',
        'longitude',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    // علاقة المستشفى بحساب المستخدم الأساسي
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // طلبات الدم التي قام بها المستشفى
    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class);
    }

    // مخزون الدم الخاص بالمستشفى
    public function bloodInventories()
    {
        return $this->hasMany(BloodInventory::class);
    }

    // التبرعات التي تمت داخل هذا المستشفى
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}