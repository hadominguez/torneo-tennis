<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'gender', 'winner_id'];

    public function matchs()
    {
        return $this->hasMany(Matches::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'matches');
    }

    public function winner()
    {
        return $this->belongsTo(Player::class, 'winner_id');
    }
}
