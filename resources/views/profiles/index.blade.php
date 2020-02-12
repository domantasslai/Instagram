@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-3 p-5">
            <img src="{{ $user->profile->profileImage() }}" alt="" class="rounded-circle" style="height: 150px;">
        </div>
        <div class="col-9 pt-5">
            <div class="d-flex justify-content-between align-items-baseline">
                <div class="d-flex align-items-center pb-3">
                    <h1>{{ $user->username }}</h1>

                    @if (Auth::check())
                      @if (auth()->user()->id !== $profile)
                        <follow-button user-id="{{ $user->id }}" follows="{{ $follows }}"></follow-button>
                      @endif
                    @endif


                </div>

                @can ('update', $user->profile)
                <a href="{{ route('post.create') }}" class="btn btn-primary">Add New Post</a>
                @endcan
            </div>
            @can ('update', $user->profile)
            <a href="{{ route('profile.edit', ['user' => $user->id]) }}">Edit profile</a>
            @endcan
            {{-- /profile/{{ $user->id }}/edit --}}
            <div class="d-flex">
                <div class="pr-5"><strong>{{ $postsCount }}</strong> posts</div>
                <div class="pr-5"><strong>{{ $followersCount }}</strong> followers</div>
                <div class="pr-5"><strong>{{ $followingCount }}</strong> following</div>
            </div>
            <div class="pt-4 font-weight-bold">
                {{ $user->profile->title ?? ""}}
            </div>
            <div class="">
                {{ $user->profile->description ?? "" }}
            </div>
            <div class="">
                <a href="{{ $user->profile->url ?? "" }}">{{ $user->profile->url ?? "" }}</a>
            </div>
        </div>
    </div>

    <div class="row pt-5">
        @forelse ($user->posts as $post)
        <div class="col-4 pb-4">
            <a href="/p/{{ $post->id }}">
                <img src="/storage/{{ $post->image }}" alt="{{ $post->image }}" class="w-100">
            </a>
        </div>
        @empty

        @endforelse

    </div>
</div>
@endsection
