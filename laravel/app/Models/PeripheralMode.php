<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class PeripheralMode extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'peripheral_modes';

    protected $fillable = [
        'mode',        // human name (e.g. "relay", "temp sensor")
        'slug',        // machine slug
        'legacy_id',   // optional integer id devices may send like 1,2...
        'unit',        // optional (e.g. "boolean", "°C")
        'description',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (empty($m->slug) && !empty($m->mode)) {
                $m->slug = Str::slug($m->mode);
            }
        });
    }
}
