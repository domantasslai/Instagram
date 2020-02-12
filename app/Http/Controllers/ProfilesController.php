<?php

namespace App\Http\Controllers;

use App\User;
use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{

  public function __construct(){
    $this->middleware('auth')->except('index');
  }


  /**
   * index() funkcija atsakinga už vartotojo profilio informacija.
   * $follows skirta, sekti ar autentifikuotas vartotojas seka tam tikrą profilį, jeigu true,
   * mygtuko 'Follow' reikšmė bus 'Unfollow', jeigu reikšmė false, mygtuko 'Follow' reikšmė bus 'Follow'.
   * $profile skirta sekti ar profilis priklauso autentifikuotam vartotojui.
   * $postsCount skirta sekti postu kiekį.
   * $followersCount skirta sekti siekėjų kiekį.
   * $followingCount skirta sekti sekamų profilių kiekį
  */
  public function index(User $user)
  {
      $profile = $user->profile->user_id;
      $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;

      $postsCount = $this->postsCount($user);
      $followersCount = $this->followersCount($user);
      $followingCount = $this->followingCount($user);

      return view('profiles.index', compact('user', 'follows', 'postsCount', 'followersCount', 'followingCount', 'profile'));
  }

  /**
   * Edit() funkcija skirta už profilio edit lango atvaizdavimą
  */
  public function edit(User $user)
  {
    $this->authorize('update', $user->profile);
    return view('profiles.edit', compact('user'));
  }

  /**
   * Update() funkcija skirta už profilio informacijos atnaujinimą [profilio nuotrauka, pavadinimas,
   * aprašymas, url].
  */
  public function update(User $user)
  {
    $this->authorize('update', $user->profile);
    $user->load('profile');
    $data = $this->validateData();

    if (request('image')) {
      $imagePath = request('image')->store('profile', 'public');

      $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
      $image->orientate();
      $image->save();
      $imageArray = ['image' => $imagePath];
    }

    auth()->user()->profile->update(array_merge(
      $data,
      $imageArray ?? []
    ));

    return redirect(route('profile.show', ['user' => $user->id]));
  }

  /**
   * ValidateData() funkcija skirta už profilio, įvedamos informacijos validavimą.
  */
  public function validateData()
  {
    return request()->validate([
      'title' => 'sometimes',
      'description' => 'sometimes',
      'url' => 'sometimes|url',
      'image' => 'sometimes',
    ]);
  }

  /**
   * PostsCount() funcija skirta už Cache funckiuonalumą.
   * Kas 30 sekundžių yra apskaičiuojamas postų kiekis.
  */
  public function postsCount($user)
  {
    return Cache::remember(
                    'count.posts'. $user->id, now()->addSeconds(30),
                        function() use ($user){
                          return $user->posts()->count();
                      });
  }

  /**
   * FollowersCount() funcija skirta už Cache funckiuonalumą.
   * Kas 30 sekundžių yra apskaičiuojamas sekėjų kiekis.
  */
  public function followersCount($user)
  {
    return Cache::remember(
                    'followers.posts'. $user->id, now()->addSeconds(30),
                        function() use ($user){
                          return $user->profile->followers->count();
                  });
  }

  /**
   * FollowingCount() funcija skirta už Cache funckiuonalumą.
   * Kas 30 sekundžių yra apskaičiuojamas sekamų profilių kiekis.
  */
  public function followingCount($user)
  {
    return Cache::remember(
                    'following.posts'. $user->id, now()->addSeconds(30),
                        function() use ($user){
                          return $user->following->count();
                  });
  }
}
