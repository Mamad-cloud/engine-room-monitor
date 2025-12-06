<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class SensorMessage extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'sensor_messages';
    protected $fillable = ['device_id', 'ts', 'seq', 'sensors', 'raw', 'engine_room_id'];
    protected $casts = [
        'sensors' => 'array',
        'raw' => 'array',
        'ts' => 'string',
        'engine_room_id' => 'string',
    ];
    public $timestamps = false;
}
