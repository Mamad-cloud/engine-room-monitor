<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class EngineRoom extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
   
    protected $fillable = ['subscribtion_id'];


    public function subscription() 
    {
        return $this->belongsTo(Subscription::class, 'subscribtion_id', 'id');
    } 

}
