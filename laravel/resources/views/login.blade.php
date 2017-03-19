@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    <div class="form-wrapper">
        <h1>App Name</h1>
        <h3>Sign in to access your dashboard</h3>
        <form action="authenticate" method="post">
            {{ csrf_field() }}
            <input type="text" name="username" placeholder="Email/Username" value="{!! old('username') !!}" required>
            <input type="password" name="password" placeholder="Password" required>
            <div class="form-group">
                <button type="submit" name="login">Login</button>
            </div>
        </form>
        <div class="registration">
            <p>Don't have an account? <a href="/signup">Sign up here</a></p>
        </div>
    </div>
    <div class="col-lg-4">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection
