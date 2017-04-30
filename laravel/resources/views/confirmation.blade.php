@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')
    @include('includes.return')

    <div class="dashboard">
        @if($auth['user_type'] == 'customer')
            <div id="receipt">
                <div class="img">
                    <img src="img/receipt.png" alt="receipt icon">
                </div>
                <h2>Your booking has been received!</h2>
                <h2>Thank you for ordering with {{ $business['business_name'] }}</h2>
                <h3>Booking details</h3>
                <p class="receipt-info">Date</p>
                {{ $booking['date'] }}
                <p class="receipt-info">Time</p>
                {{ $booking['start_time'] }}
                <p class="receipt-info">Service</p>
                {{ $booking['activity_name'] }}
                <p class="receipt-info">Service by</p>
                {{ $booking['employee_name'] }}
            </div>
        @else
            <h1>Booking for {{ $customer['customer_name'] }} ({{ $customer['username'] }}) has been made successfully</h1>
            <h2>Customer's booking details:</h2>

            <table>
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Service</th>
                    <th>Service by</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $booking['date'] }}</td>
                        <td>{{ $booking['start_time'] }}</td>
                        <td>{{ $booking['activity_name'] }}</td>
                        <td>{{ $booking['employee_name'] }}</td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>

@endsection
