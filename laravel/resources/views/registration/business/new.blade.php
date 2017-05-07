@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')

    <div class='box'>
        <h1>Sign up as a Business</h1>

        <form action="/register/submit/business" method="post">
            {{ csrf_field() }}
            <div class="error">{{ $errors->first('business_name') }}</div>
            <input type="text" name="business_name" placeholder="Business Name" value="{!! old('business_name') !!}">
            <div class="error">{{ $errors->first('owner_name') }}</div>
            <input type="text" name="owner_name" placeholder="Owner's Name" value="{!! old('owner_name') !!}">
            <div class="error">{{ $errors->first('username') }}</div>
            <input type="text" name="username" placeholder="Username" value="{!! old('username') !!}">
            <div class="error">{{ $errors->first('password') }}</div>
            <input type="password" name="password" placeholder="Password">
            <input type="password" name="password_confirmation" placeholder="Confirm Password">
            <div class="error">{{ $errors->first('email') }}</div>
            <input type="text" name="email" placeholder="Email" value="{!! old('email') !!}">
            <div class="error">{{ $errors->first('phone') }}</div>
            <input type="text" name="phone" placeholder="Contact No" value="{!! old('phone') !!}">
            <div class="error">{{ $errors->first('address') }}</div>
            <input type="text" name="address" placeholder="Address" value="{!! old('address') !!}">
            <button type="submit" name="signup">Sign Up</button>
        </form>

        <div class="login">
            <p>Already have an account? <a href="/login">Sign in here</a></p>
        </div>
    </div>

@endsection
