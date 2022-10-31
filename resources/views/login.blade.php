@extends('template')

@section('content')
    <h1>Auth</h1>
    <form action="{{ route('login-send') }}" method="post">
        @csrf
        <div class="form-group">
            <label>EMail</label>
            <input type="text" class="form-control" placeholder="EMail" name="email" value="{{ old('email') }}">
            @error('email') {{ $message }} @enderror
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" placeholder="Password" name="password">
            @error('password') {{ $message }} @enderror
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary btn" value="Login">
        </div>
    </form>
@endsection
