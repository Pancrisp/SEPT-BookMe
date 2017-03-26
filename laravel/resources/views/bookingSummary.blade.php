@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    @include('includes.header')

        <h1>Booking Summary</h1>

    @if(count($bookings))
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Customer ID</th>
            </tr>
            </thead>
            <tbody>
            @foreach($bookings AS $booking)
            <tr>
                <td>{{ $booking['booking_id'] }}</td>
                <td>{{ $booking['date'] }}</td>
                <td>{{ $booking['start_time'] }}</td>
                <td>{{ $booking['end_time'] }}</td>
                <td>{{ $booking['customer_id'] }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div>Currently no booking.</div>
    @endif

@endsection