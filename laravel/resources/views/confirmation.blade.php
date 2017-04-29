@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')
    @include('includes.return')

    <div class="dashboard">
        <h1>Thank you for making a booking with {{ $business['business_name'] }}</h1>
        <h2>Your booking details:</h2>
        <table>
            <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Service</th>
                <th>Serve By</th>
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
    </div>

@endsection
