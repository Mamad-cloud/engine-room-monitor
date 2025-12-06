<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'subscriptions';

    // allow these fields
    protected $fillable = [
        'user_id',
        'uuid',
        'title',
        'status',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->status)) {
                $model->status = 'active';
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function engineRooms()
    {
        return $this->hasMany(EngineRoom::class, 'subscription_id', 'id');
    }
}
