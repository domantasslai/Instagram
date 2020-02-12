<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = [];

    /**
     * profileImage funkcija atsakinga už vartotojo profilio nuotrauką.
     * Jeigu vartotojas neturi profilio nuotraukos, tada yra nustatytą default nuotrauka
    */
    public function profileImage()
    {
        $imagePath = ($this->image) ? $this->image : 'profile/account.png';
        return '/storage/'. $imagePath;
    }


    /**
     * OneToOne sąryšis tarp Profile ir User modelių.
     * Profilis gali turėti tik vieną vartotoją.
    */
    public function user(){
      return $this->belongsTo(User::class);
    }

    /**
     * ManyToMany sąryšis tarp Profile ir User modelių,
     * Profilį gali sekti daug vartotojų.
    */
    public function followers()
    {
      return $this->belongsToMany(User::class);
    }
}
