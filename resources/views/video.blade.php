@extends('template')

@section('content')
    <h1>{{ $video->title }}</h1>
    <div class="row">
        <div class="col-6">
            <img src="{{ $video->cover_url }}" alt="" style="display: block;width: 100%;"><br>
        </div>
        <div class="col-6">
            <video src="{{ $video->video_url }}" style="display: block;width: 100%;" controls></video>
        </div>
    </div>
    <p>{{ $video->description }}</p>
    <p>Status: <b>{{ $video->status }}</b></p>
    <p>User: <b>{{ $video->user->username }}, {{ $video->user->email }}, ID: {{ $video->user->id }}</b></p>
@endsection
