@extends('template.master')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    <form action="" method="post">
        <label><input type="text" name="fullname" placeholder="Your Name"></label>
        <label><input type="text" name="username" placeholder="Username"></label>
        <label><input type="password" name="password" placeholder="Password"></label>
        <label><input type="text" name="address" placeholder="Address"></label>
        <label><input type="text" name="zipcode" placeholder="ZIP"></label>
        <label><input type="text" name="contact" placeholder="Contact No"></label>
        <a href="/dashboard"><button type="button" name="signup">Sign Up</button></a>
    </form>
    <div class="login">
        <p>Already have an account? <a href="/">Sign in here</a></p>
    </div>
@endsection
