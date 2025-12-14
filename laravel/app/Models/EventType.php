<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventType extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'event_types';

    protected $fillable = [
        'name',
        'code',        // optional short code
        'description',
    ];
}
