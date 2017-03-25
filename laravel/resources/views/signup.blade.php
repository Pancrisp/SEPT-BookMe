@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    <div class='box'>
        <h1>Restaurant Booking App</h1>
        <form action="/register" method="post">
            {{ csrf_field() }}
            <input type="text" name="fullname" placeholder="Your Name" value="{!! old('fullname') !!}">
            <input type="text" name="username" placeholder="Username" value="{!! old('username') !!}">
            <input type="password" name="password" placeholder="Password">
            <input type="password" name="password_confirmation" placeholder="Confirm Password">
            <input type="text" name="email" placeholder="Email" value="{!! old('email') !!}">
            <input type="text" name="phone" placeholder="Contact No" value="{!! old('phone') !!}">
            <input type="text" name="address" placeholder="Address" value="{!! old('address') !!}">
            <button type="submit">Sign Up</button>
        </form>
        <div class="login">
            <p>Already have an account? <a href="/">Sign in here</a></p>
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
    </div>

@endsection
