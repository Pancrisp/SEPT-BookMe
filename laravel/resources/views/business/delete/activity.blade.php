@extends('layouts.template')

@section('title')
    Delete business service - BookMe
@endsection

@section('nav')
    @include('nav.dashboard')
    @include('nav.logout')
@endsection

@section('content')

    <div class="dashboard">
        <div>
            <a href="{{ URL::previous() }}">Delete another one</a>
        </div>

        <h1>Confirm to delete a business service</h1>
        <table>
            <thead>
            <tr>
                <th>Service Name</th>
                <th>Number of Slots</th>
                <th>Service Period</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $activity['activity_name'] }}</td>
                <td>{{ $activity['num_of_slots'] }}</td>
                <td>{{ $activity['num_of_slots'] * $business['slot_period'] }} minutes</td>
            </tr>
            </tbody>
        </table>

        <form action="/business/activity/delete/submit" method="post">
            {{ csrf_field() }}
            <input name="activity" value="{{ $activity['activity_id'] }}" hidden>
            <button type="submit">Confirm to Delete</button>
        </form>

        @endsection

        @section('pageSpecificJs')
            <script src="{{ asset('js/form.js') }}"></script>
@endsection
