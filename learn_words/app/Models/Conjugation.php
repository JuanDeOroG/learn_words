<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conjugation extends Model
{
    public $timestamps = false; // No hay created_at ni updated_at

    protected $fillable = ['name'];

    
}
