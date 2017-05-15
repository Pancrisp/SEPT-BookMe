@extends('layouts.template')

@section('title')
    Update services - BookMe
@endsection

@section('nav')
    @include('nav.dashboard')
    @include('nav.logout')
@endsection

@section('content')

    <div class="box">
        <div>
            <a href="{{ URL::previous() }}">Update another one</a>
        </div>

        <h1>Update Business Services</h1>

        <form action="/business/activity/update/submit" method="post">
            {{ csrf_field() }}
            <input name="activity_id" value="{{ $activity['activity_id'] }}" hidden>

            <label>Service Name</label>
            <input type="text" name="activity_name" placeholder="Service Name" value="{{ $activity['activity_name'] }}" required>
            <div class="error">{{ $errors->first('activity_name') }}</div>

            <label>Number of Slots</label>
            <input type="number" min="1" name="num_of_slots" placeholder="Number of Slots" value="{{ $activity['num_of_slots'] }}" required>
            <div class="error">{{ $errors->first('num_of_slots') }}</div>

            <label>
                Slot Period (in minutes) <span class="error">Warning: changing this will update periods for all services</span>
            </label>
            <input type="number" min="0" name="slot_period" placeholder="Slot Period" value="{{ $business['slot_period'] }}" required>
            <div class="error">{{ $errors->first('slot_period') }}</div>

            <button type="submit">Update</button>
        </form>

    </div>

@endsection
