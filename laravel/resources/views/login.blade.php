@extends('template.master')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    <div class="form-wrapper">
        <h1>App Name</h1>
        <h3>Sign in to access your dashboard</h3>
        <form action="" method="post">
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="pwd" placeholder="Password" required>
            <div class="form-group">
                <a href="/dashboard"><button type="button" name="login">Login</button></a>
            </div>
        </form>
        <div class="registration">
            <p>Don't have an account? <a href="/signup">Sign up here</a></p>
        </div>
    </div>
@endsection
