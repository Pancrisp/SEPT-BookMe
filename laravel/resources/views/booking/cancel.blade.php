@extends('layouts.template')

@section('title')
    Cancel a booking - BookMe
@endsection

@section('nav')
    @include('nav.dashboard')
    @include('nav.logout')
@endsection

@section('content')

    <div class="dashboard">
        <h1>Cancel confirmation</h1>

        @if(\Illuminate\Support\Facades\Auth::user()['user_type'] == 'customer')
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
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $booking['date'] }}</td>
                        <td>{{ $booking['start_time'] }}</td>
                        <td>{{ $booking['business_name'] }}</td>
                        <td>{{ $booking['activity_name'] }}</td>
                        <td>{{ $booking['employee_name'] }}</td>
                        <td>{{ $booking['address'] }}</td>
                        <td>{{ $booking['mobile_phone'] }}</td>
                        <td>{{ $booking['email_address'] }}</td>
                    </tr>
                </tbody>
            </table>
        @else
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
                </tbody>
            </table>
        @endif

        <form action="/booking/cancel/submit" method="post">
            {{ csrf_field() }}
            <input id="booking" name="booking" value="{{ $booking['booking_id'] }}" hidden>
            <div class="form-controls">
                <a class="btn-back" href="/booking">Back</a>
                <button class="btn-delete" type="submit">Cancel</button>
            </div>
        </form>
    </div>

@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/form.js') }}"></script>
@endsection
