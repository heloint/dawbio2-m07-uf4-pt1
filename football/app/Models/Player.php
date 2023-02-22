<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    public $timestamps = false;

    // Relationship OneToMany between Player and Team
    public function notes() {
        return $this->belongsTo(Note::class);        
    }
}
