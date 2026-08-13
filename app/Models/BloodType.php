<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BloodType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_rare',
    ];

    protected $casts = [
        'is_rare' => 'boolean',
    ];

    public function donors()
    {
        return $this->hasMany(Donor::class);
    }

    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class);
    }

    public function bloodInventories()
    {
        return $this->hasMany(BloodInventory::class);
    }
}
