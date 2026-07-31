<?php

namespace App\Models;

use App\Traits\LocationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory, LocationTrait;

    protected $table = 'hospitals';

    protected $fillable = [
        'user_id',
        'facility_name',
        'facility_type',
        'license_number',
        'manager_name',
        'license_file',
        'address',
        'latitude',
        'longitude',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'latitude'    => 'float',
        'longitude'   => 'float',
    ];

    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->facility_name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class);
    }

    public function bloodInventories()
    {
        return $this->hasMany(BloodInventory::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
