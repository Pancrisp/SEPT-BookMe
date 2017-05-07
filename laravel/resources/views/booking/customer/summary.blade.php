@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')
    @include('includes.return')

    <div class="dashboard">
        <h2>Booking Summary</h2>
        @if(count($allBookings))
            <table>
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>Business</th>
                    <th>Activity</th>
                    <th>Staff</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($allBookings AS $booking)
                    <tr>
                        <td>{{ $booking['date'] }}</td>
                        <td>{{ $booking['start_time'] }}</td>
                        <td>{{ $booking['business_name'] }}</td>
                        <td>{{ $booking['activity_name'] }}</td>
                        <td>{{ $booking['employee_name'] }}</td>
                        <td>{{ $booking['address'] }}</td>
                        <td>{{ $booking['mobile_phone'] }}</td>
                        <td>{{ $booking['email_address'] }}</td>
                        <td><a href="/booking/cancel/{{ $booking['booking_id'] }}">cancel</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div>Currently no booking.</div>
        @endif
    </div>

@endsection
