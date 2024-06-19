<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleOwnerHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vehicle_owners_history';

    protected $fillable = [
        'vehicle_id',
        'user_id',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
