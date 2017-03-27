@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')

    <div class="box">
        <h1>Restaurant Booking App</h1>
        <h3>Sign in to access your dashboard</h3>
        <form action="dashboard" method="post">
            {{ csrf_field() }}
            <div class="error">{{ $errors->first('username') }}</div>
            <input type="text" name="username" placeholder="Email/Username" value="{!! old('username') !!}" required>
            <input type="password" name="password" placeholder="Password" required>
            <div class="error">{{ $errors->first('password') }}</div>
            <div class="user-type">
                <input id="left" type="radio" name="usertype" value="customer" required>Customer
                <input type="radio" name="usertype" value="business">Business Owner
            </div>
            <button type="submit" name="login">Login</button>
        </form>
        <div class="registration">
            <p>Don't have an account? <a href="/signup">Sign up here</a></p>
        </div>
    </div>

@endsection
