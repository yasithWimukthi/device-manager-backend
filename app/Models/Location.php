<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['serial_number', 'name', 'ip_address'];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function organization()
    {
        return $this->belongsToMany(Organization::class, 'organization_location', 'location_id', 'organization_id');
    }
}
