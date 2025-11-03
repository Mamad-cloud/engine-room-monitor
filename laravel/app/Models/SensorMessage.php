<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class SensorMessage extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'sensor_messages';
    protected $fillable = ['device_id','ts','seq','sensors','raw'];
    protected $casts = [
        'sensors' => 'array',
        'raw' => 'array'
    ];
    public $timestamps = false;
}
