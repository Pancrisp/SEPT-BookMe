@extends('layouts.template')

@section('title')
    Profile Settings - Booking App
@endsection

@section('content')
    @include('includes.return')

    <div class='box'>
        <h1>Hello, {{ $user['owner_name'] }} from {{ $user['business_name'] }}</h1>

        <table>
            <thead>
                <tr>
                    <th>Mobile Phone</th>
                    <th>Email Address</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $user['mobile_phone'] }}</td>
                    <td>{{ $user['email_address'] }}</td>
                    <td>{{ $user['address'] }}</td>
                </tr>
            </tbody>
        </table>

        <div>
            <p>Details changed? <a href="/profile/update">Update here</a></p>
        </div>
    </div>

@endsection
