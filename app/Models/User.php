<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
    ];

    protected $dates = ['deleted_at'];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
    public function vehicleOwnerHistories(): HasMany
    {
        return $this->hasMany(VehicleOwnerHistory::class, 'user_id');
    }
}
