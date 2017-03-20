@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')

    <form action="/register" method="post">
        {{ csrf_field() }}
        <label><input type="text" name="fullname" placeholder="Your Name" value="{!! old('fullname') !!}"></label>
        <label><input type="text" name="username" placeholder="Username" value="{!! old('username') !!}"></label>
        <label><input type="password" name="password" placeholder="Password"></label>
        <label><input type="password" name="password_confirmation" placeholder="Confirm Password"></label>
        <label><input type="text" name="email" placeholder="Email" value="{!! old('email') !!}"></label>
        <label><input type="text" name="phone" placeholder="Contact No" value="{!! old('phone') !!}"></label>
        <label><input type="text" name="address" placeholder="Address" value="{!! old('address') !!}"></label>
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

@endsection
