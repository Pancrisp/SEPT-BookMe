@extends('layouts.template')

@section('title')
    Register services - BookMe
@endsection

@section('nav')
    @include('nav.logout')
@endsection

@section('content')

    <div class="box">
        <h1>Setting Up Business Services for {{ $business['business_name'] }}</h1>
        <div class="success">{{ $errors->first('result') }}</div>

        <form method="post">
            {{ csrf_field() }}

            <!-- readonly if already set -->
            <label>Each Slot Period (in minutes)</label>
            @if($slotPeriod == 0)
                <input type="number" min="0" name="slot_period" placeholder="Slot Period" value="{!! old('slot_period') !!}" required>
            @else
                <input id="readonly" type="text" name="slot_period" value="{{ $slotPeriod }}" readonly required>
            @endif
            <div class="error">{{ $errors->first('slot_period') }}</div>

            <label>Number of Slots</label>
            <input type="number" min="1" name="num_of_slots" placeholder="Number of Slots" value="{!! old('num_of_slots') !!}" required>
            <div class="error">{{ $errors->first('num_of_slots') }}</div>

            <label>Service Name</label>
            <input type="text" name="activity_name" placeholder="Service Name" value="{!! old('activity_name') !!}" required>
            <div class="error">{{ $errors->first('activity_name') }}</div>

            <button type="submit" name="add-another-one" formaction="/business/activity/submit/next">Add another Service</button>
            <button id="end-submit" type="submit" name="done" formaction="/business/activity/submit/done">Done</button>
        </form>

    </div>

@endsection
