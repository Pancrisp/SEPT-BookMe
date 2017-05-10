@extends('layouts.template')

@section('title')
    Profile Settings - BookMe
@endsection

@section('content')
    @include('includes.return')

    <div class='box'>
        <h1>Hi {{ $user['owner_name'] }}</h1>
        <h2>Please modify the fields that require updating</h2>

        <div class="success">{{ $errors->first('result') }}</div>

        <form action="/profile/update/submit" method="post">
            {{ csrf_field() }}

            <label>Business Name</label>
            <input type="text" name="business_name" value="{{ $user['business_name'] }}" required>
            <div class="error">{{ $errors->first('business_name') }}</div>

            <label>Mobile Phone</label>
            <input type="text" name="phone" value="{{ $user['mobile_phone'] }}" required>
            <div class="error">{{ $errors->first('phone') }}</div>

            <label>Email Address</label>
            <input type="text" name="email" value="{{ $user['email_address'] }}" required>
            <div class="error">{{ $errors->first('email') }}</div>

            <label>Address</label>
            <input type="text" name="address" value="{{ $user['address'] }}" required>
            <div class="error">{{ $errors->first('address') }}</div>

            <button type="submit" name="update">Update</button>
        </form>
    </div>

@endsection
