<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peripheral extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'peripherals';

    protected $fillable = [
        'device_id',       // device.device_id (string)
        'mode_id',         // reference to peripheral_modes._id
        'name',            // e.g. "relay_1"
        'persian_label',   // localized label
        'index',           // integer slot index (0-based) — important to map states from device
        'meta',            // optional extra JSON
        'active',          // boolean
        'engine_room_id'   // optional copy to speed queries if device belongs to engine room
    ];

    protected $casts = [
        'meta' => 'array',
        'active' => 'boolean',
    ];

    public $timestamps = true;

    public function mode()
    {
        return $this->belongsTo(PeripheralMode::class, 'mode_id', '_id');
    }
}
