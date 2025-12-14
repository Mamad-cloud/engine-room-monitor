<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeripheralState extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'peripheral_states';

    protected $fillable = [
        'peripheral_id',   // _id of peripherals collection
        'device_id',
        'state',           // mixed: boolean, numeric, string — stored as-is
        'ts',              // timestamp string or numeric epoch
        'engine_room_id',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public $timestamps = false; // we store ts explicitly, but you can enable timestamps if you prefer
}
