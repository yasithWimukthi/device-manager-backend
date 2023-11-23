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
}
