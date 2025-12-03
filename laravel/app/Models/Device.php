<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'devices';
    protected $fillable = ['device_id','name','token','meta', 'engine_room_id'];
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->device_id)) {
                $model->device_id = (string) Str::uuid();
            }
            if (empty($model->token)) {
                $model->token = bin2hex(random_bytes(12));
            }
        });
    }


    public function engine_room() 
    {
        return $this->belongsTo(EngineRoom::class, 'engine_room_id', 'id');
    } 
}
