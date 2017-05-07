@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')
    @include('includes.return')

    <div class="dashboard">
        <h2>New Bookings Today</h2>
        @if(count($newBookings))
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
                </tr>
                </thead>
                <tbody>
                @foreach($newBookings AS $booking)
                    <tr>
                        <td>{{ $booking['booking_id'] }}</td>
                        <td>{{ $booking['date'] }}</td>
                        <td>{{ $booking['start_time'] }}</td>
                        <td>{{ $booking['activity_name'] }}</td>
                        <td>{{ $booking['employee_name'] }}</td>
                        <td>{{ $booking['customer_name'] }}</td>
                        <td>{{ $booking['mobile_phone'] }}</td>
                        <td>{{ $booking['email_address'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div>No new booking today.</div>
        @endif

        <h2>Booking Summary</h2>
        @if(count($allBookings))
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
                </tr>
                </thead>
                <tbody>
                @foreach($allBookings AS $booking)
                <tr>
                    <td>{{ $booking['booking_id'] }}</td>
                    <td>{{ $booking['date'] }}</td>
                    <td>{{ $booking['start_time'] }}</td>
                    <td>{{ $booking['activity_name'] }}</td>
                    <td>{{ $booking['employee_name'] }}</td>
                    <td>{{ $booking['customer_name'] }}</td>
                    <td>{{ $booking['mobile_phone'] }}</td>
                    <td>{{ $booking['email_address'] }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div>Currently no booking.</div>
        @endif
    </div>

@endsection
