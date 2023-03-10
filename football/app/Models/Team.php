<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    public $timestamps = false;

    // Relationship OneToMany between Team and Player
    public function players() {
        return $this->hasMany(Player::class);
    }
}
