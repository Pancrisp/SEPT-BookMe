@extends('layouts.template')

@section('title')
    Register business hours - BookMe
@endsection

@section('nav')
    @include('nav.logout')
@endsection

@section('content')

    <div class="box">
        <h1>Setting Up Business Hours for {{ $business['business_name'] }}</h1>

        <form action="/business/hour/register/submit" method="post">
            {{ csrf_field() }}

            <h3 for="time">Opening Hour</h3>
            <input type="time" name="opening_time_all" min="00:00" max="24:00" step="1800" required>
            to
            <input type="time" name="closing_time_all" min="00:00" max="24:00" step="1800" required>
            <div class="error">{{ $errors->first('opening_time_all') }}</div>
            <div class="error">{{ $errors->first('closing_time_all') }}</div>
            <div class="error">{{ $errors->first('opening_hour_all') }}</div>

            <h3>Special Days</h3>
            <h4>Enter 00:00 for both to indicate a closed day</h4>
            <div>
                @foreach($days as $day)
                    <label>
                        <input id="checkbox-{{ $day['short'] }}" class="checkbox" type="checkbox" name="special_days[]" value="{{ $day['short'] }}">
                        {{ $day['full'] }}
                    </label>
                    <div id="opening-hour-{{ $day['short'] }}" hidden>
                        <input type="time" name="opening_time_{{ $day['short'] }}" min="00:00" max="24:00" step="1800" required>
                        to
                        <input type="time" name="closing_time_{{ $day['short'] }}" min="00:00" max="24:00" step="1800" required>
                        <div class="error">{{ $errors->first('opening_time_'.$day['short']) }}</div>
                        <div class="error">{{ $errors->first('closing_time_'.$day['short']) }}</div>
                        <div class="error">{{ $errors->first('opening_hour_'.$day['short']) }}</div>
                    </div>
                @endforeach
            </div>

            <button type="submit" name="next">next</button>
        </form>

    </div>

@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/opening-hour.js') }}"></script>
@endsection
