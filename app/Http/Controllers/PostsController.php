<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * index() funkcija ataskinga už autentifikuoto vartotojo sekamų profilių postų suradimą.
     * Jeigu autentifikuotas vartojas neseka kitų profilių, tada pagrindiniame lange yra atvaizduojami visi sukurti postai.
     * Jeigu autentifikuotas vartojas seka tam tikrus profilius, tada pagrindiniame lange yra atvaizduojami tik sekamu profilių postai.
    */
    public function index(){

      if (auth()->user()) {
        $users = auth()->user()->following()->pluck('profiles.user_id');
        $count = auth()->user()->following()->pluck('profiles.user_id')->count();

        if ($count > 0) {
          $posts = Post::whereIn('user_id', $users)->with('user')->orderBy('created_at', 'DESC')->get();
        }else {
          $posts = Post::all();
        }
        return view('posts.index', compact('posts'));

      }else {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
      }

    }

    /**
     * Create() funkcija grąžiną posto sukurimo formą.
    */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store() funkcija skirta už naujo posto sukurimą [posto nuotrauka, aprašas].
    */
    public function store()
    {
      $data = $this->validateData();
      $imagePath = request('image')->store('uploads', 'public');

      $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);
      $image->save();

      auth()->user()->posts()->create([
        'caption' => $data['caption'],
        'image' => $imagePath,
      ]);
      return redirect('/profile/'. auth()->user()->id);
    }

    /**
     * Show() funkcija skirta už vieno posto atvaizdavimą.
    */
    public function show(Post $post)
    {
      $profile = $post->user_id;
      $follows = (auth()->user()) ? auth()->user()->following->contains($post->user->id) : false;
      return view('posts.show', compact('post', 'profile', 'follows'));
    }

    /**
     * ValidateData() funkcija skirta už profilio, įvedamos informacijos validavimą.
    */
    public function validateData(){
      return request()->validate([
        'caption' => 'required',
        'image' => ['required', 'image'],
      ]);
    }
}
