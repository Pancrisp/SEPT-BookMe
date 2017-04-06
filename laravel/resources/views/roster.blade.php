@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    @include('includes.header')

    <div class="dashboard">
        <h2>Weekly Roster</h2>
        <table>
            <tr>
                <th class="head"></th>
                <th class="head">Mon</th>
                <th class="head">Tue</th>
                <th class="head">Wed</th>
                <th class="head">Thu</th>
                <th class="head">Fri</th>
                <th class="head">Sat</th>
                <th class="head">Sun</th>
            </tr>
            <tr>
                <td>Day</td>
                <!-- feed worker availability data here -->
            </tr>
            <tr>
                <td>Night</td>
                <!-- feed worker availability data here ->
            </tr>
        </table>
    </div>

@endsection
