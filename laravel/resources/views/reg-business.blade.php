@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')

    <div class='box'>
        <h1>Booking App</h1>

        <form action="/register/submit" method="post">
            {{ csrf_field() }}
            <input type="text" name="username" placeholder="Username" value="{!! old('username') !!}">
            <input type="password" name="password" placeholder="Password">
            <input type="password" name="password_confirmation" placeholder="Confirm Password">
            <input type="text" name="businessname" placeholder="Business Name" value="{!! old('fullname') !!}">
            <input type="text" name="" value="">
            <input type="tel" name="" value="">

            services provided
            business hours

            <button type="submit" name="signup">Sign Up</button>
        </form>
    </div>

@endsection
