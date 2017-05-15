@extends('layouts.template')

@section('title')
    Add new services - BookMe
@endsection

@section('nav')
    @include('nav.dashboard')
    @include('nav.logout')
@endsection

@section('content')

    <div class="box">
        <h1>Add New Business Services</h1>

        <form action="/business/activity/submit/add" method="post">
            {{ csrf_field() }}

            <label>Each Slot Period (in minutes)</label>
            <input id="readonly" type="text" name="slot_period" value="{{ $slotPeriod }}" readonly required>
            <div class="error">{{ $errors->first('slot_period') }}</div>

            <label>Number of Slots</label>
            <input type="number" min="1" name="num_of_slots" placeholder="Number of Slots" value="{!! old('num_of_slots') !!}" required>
            <div class="error">{{ $errors->first('num_of_slots') }}</div>

            <label>Service Name</label>
            <input type="text" name="activity_name" placeholder="Service Name" value="{!! old('activity_name') !!}" required>
            <div class="error">{{ $errors->first('activity_name') }}</div>

            <button type="submit">Add</button>
        </form>

    </div>

@endsection
