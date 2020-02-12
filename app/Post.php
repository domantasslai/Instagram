<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    /**
     * OneToMany sąryšis tarp Post ir User modelių.
     * Postas gali turėti tik vieną vartotoją.
    */
    public function user()
    {
      return $this->belongsTo(User::class);
    }
}
