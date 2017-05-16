@extends('layouts.template')

@section('title')
    Booking Summary - BookMe
@endsection

@section('nav')

@endsection

@section('content')

    <div class="dashboard" id="booking-summary">
        <nav id="nav-summary">
            @include('nav.dashboard')
            @include('nav.logout')
        </nav>
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
                    <!-- disabling cancel on history and today page -->
                    @if($typeSelected != 'history' && $typeSelected != 'today')
                        <th></th>
                    @endif
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
                        <!-- disabling cancel on history page -->
                        @if($typeSelected != 'history' && $typeSelected != 'today')
                            <td><a href="/booking/cancel/{{ $booking['booking_id'] }}">Cancel</a></td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div>No booking.</div>
        @endif
    </div>

@endsection
