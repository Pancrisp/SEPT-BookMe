@extends('layouts.template')

@section('title')
    Update services - BookMe
@endsection

@section('nav')
    @include('nav.dashboard')
    @include('nav.logout')
@endsection

@section('content')

    <div class="dashboard">
        <h2>Business Services</h2>

        <table>
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Number of Slots</th>
                    <th>Service Period</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($activities AS $activity)
                <tr>
                    <td>{{ $activity['activity_name'] }}</td>
                    <td>{{ $activity['num_of_slots'] }}</td>
                    <td>{{ $activity['num_of_slots'] * $business['slot_period'] }} minutes</td>
                    <td>
                        <a class="icons" href="/business/activity/update/{{ $activity['activity_id'] }}"><img id="edit" src="/img/edit.png" alt="edit"></a>
                        <a class="icons" href="/business/activity/delete/{{ $activity['activity_id'] }}"><img id="delete" src="/img/delete.png" alt="delete"></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <a href="/business/activity/add"><button>Add New Service</button></a>

    </div>

@endsection
