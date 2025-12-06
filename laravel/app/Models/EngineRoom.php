<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

class EngineRoom extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'engine_rooms';

    // allow these fields
    protected $fillable = [
        'subscription_id', 
        'uuid',
        'name',
        'location',
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
        });
    }

    /**
     * Return subscription relation.
     * We support both 'subscription_id' and historical 'subscribtion_id' field names.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'id');
    }
}
