@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row d-flex">
      <div class="col-9">
        <img src="/storage/{{$post->image}}" alt="" class="w-100">
      </div>
      <div class="col-3">
        <div class="">
          <div class="d-flex align-items-center">
            <div class="pr-3">
              <img src="{{ $post->user->profile->profileImage() }}" class="rounded-circle w-100" style="max-width: 40px; min-width: 30px" alt="">
            </div>
            <div class="">
              <div class="font-weight-bold d-flex justify-content-between">
                <a href="{{ route('profile.show', ['user' => $post->user->id]) }}">
                  <span class="text-dark">
                    {{ $post->user->username }}
                  </span>
                </a>

                @if (Auth::check())
                  @if (auth()->user()->id !== $profile)
                    <follow-button-show user-id="{{ $post->user->id }}" follows="{{ $follows }}"></follow-button-show>
                  @endif
                @endif
              </div>
            </div>
          </div>

          <hr>

          <p><span class="font-weight-bold">
            <a href="{{ route('profile.show', ['user' => $post->user->id]) }}">
              <span class="text-dark">
                {{ $post->user->username }}
              </span>
            </a>
          </span> {{ $post->caption }}</p>

        </div>
      </div>
    </div>
  </div>
@endsection
