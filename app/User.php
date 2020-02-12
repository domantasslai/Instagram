<?php

namespace App;

use App\Profile;
use App\Post;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Boot funkcija atsakinga, kai naujas Vartotojas užsiregistruoja sistemoje,
     * automatiškai yra sukuriamas Vartotojo profilio 'title' skiltis su Vartotojo username
    */
    protected static function boot()
    {
      parent::boot();

      static::created(function ($user){
        $user->profile()->create([
          'title' => $user->username,
        ]);
      });
    }

    /**
     * OneToOne sąryšis tarp User ir Profile modelių.
     * Vartotojas gali turėti tik vieną profilį
    */
    public function profile()
    {
      return $this->hasOne(Profile::class);
    }

    /**
     * OneToMany sąryšis tarp User ir Post modelių.
     * Vartotojas gali turėti daug postų.
    */
    public function posts()
    {
      return $this->hasMany(Post::class)->orderBy('created_at', 'DESC');
    }

    /**
     * ManyToMany sąryšis tarp User ir Profile modelių,
     * Vartotojas gali sekti daug profilių
    */
    public function following()
    {
      return $this->belongsToMany(Profile::class);
    }

    /**
     * path() funcija asakinga už Route kelią iki Vartotojo profilio
    */
    public function path(){
      return route('profile.show', $this->id);
    }
}
