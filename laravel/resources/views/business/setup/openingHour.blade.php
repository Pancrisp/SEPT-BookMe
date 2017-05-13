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

            <h2 for="time">Business Hours</h2>
            <div class="business-hrs">
                <div class="hrs">
                    <label>Opening</label>
                    <input id="open-hrs" type="time" name="opening_time_all" min="00:00" max="24:00" step="1800" required>
                </div>
                <div class="hrs">
                    <label>Closing</label>
                    <input id="close-hrs" type="time" name="closing_time_all" min="00:00" max="24:00" step="1800" required>
                </div>
            </div>
            <div class="error">{{ $errors->first('opening_time_all') }}</div>
            <div class="error">{{ $errors->first('closing_time_all') }}</div>
            <div class="error">{{ $errors->first('opening_hour_all') }}</div>

            <h2>Special Days</h2>
            <h4>Enter 00:00 for both to indicate a closed day</h4>
            <div>
                @foreach($days as $day)
                    <label>
                        <input id="checkbox-{{ $day['short'] }}" class="checkbox" type="checkbox" name="special_days[]" value="{{ $day['short'] }}">
                        {{ $day['full'] }}
                    </label>
                    <div class="business-hrs" id="opening-hour-{{ $day['short'] }}" hidden>
                        <div class="hrs" id="open">
                            <label>Opening</label>
                            <input id="opening-time-{{ $day['short'] }}" type="time" name="opening_time_{{ $day['short'] }}" min="00:00" max="24:00" step="1800">
                        </div>
                        <div class="hrs" id="close">
                            <label>Closing</label>
                            <input id="closing-time-{{ $day['short'] }}" type="time" name="closing_time_{{ $day['short'] }}" min="00:00" max="24:00" step="1800">
                        </div>
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
