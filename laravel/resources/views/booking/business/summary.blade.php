@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')
    @include('includes.return')

    <div class="dashboard">

        <hr>
        @foreach($types as $type)
            <span class="
                @if($type['type'] == $typeSelected)
                    list-page-selected
                @else
                    list-page
                @endif
            ">
                <a href="/booking/summary/{{ $type['type'] }}">{{ $type['title'] }}</a>
            </span>
        @endforeach
        <br><br>

        @if(count($bookings))
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>Activity</th>
                    <th>Staff</th>
                    <th>Customer Name</th>
                    <th>Customer Contact</th>
                    <th>Customer Email</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($bookings AS $booking)
                    <tr>
                        <td>{{ $booking['booking_id'] }}</td>
                        <td>{{ $booking['date'] }}</td>
                        <td>{{ $booking['start_time'] }}</td>
                        <td>{{ $booking['activity_name'] }}</td>
                        <td>{{ $booking['employee_name'] }}</td>
                        <td>{{ $booking['customer_name'] }}</td>
                        <td>{{ $booking['mobile_phone'] }}</td>
                        <td>{{ $booking['email_address'] }}</td>
                        <td><a href="/booking/cancel/{{ $booking['booking_id'] }}">cancel</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div>No booking.</div>
        @endif
    </div>

@endsection
