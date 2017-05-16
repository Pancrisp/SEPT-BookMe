@extends('layouts.template')

@section('title')
    Register business hours - BookMe
@endsection

@section('content')

    <div class="box">
        <h1>Setting Up Business Hours for {{ $business['business_name'] }}</h1>

        <form action="/business/hour/register/submit" method="post">
            {{ csrf_field() }}

            <h2 for="time">Business Hours</h2>
            <div class="business-hrs">
                <div class="hrs-open">
                    <label>Opening</label>
                    <input type="time" name="opening_time_all"
                           min="00:00" max="23:59" step="1800"
                           placeholder="hh:mm"
                           value="{!! old('opening_time_all') !!}"
                           required>
                </div>
                <div class="hrs-close">
                    <label>Closing</label>
                    <input type="time" name="closing_time_all"
                           min="00:00" max="23:59" step="1800"
                           placeholder="hh:mm"
                           value="{!! old('closing_time_all') !!}"
                           required>
                </div>
            </div>
            <div class="error">{{ $errors->first('opening_time_all') }}</div>
            <div class="error">{{ $errors->first('closing_time_all') }}</div>
            <div class="error">{{ $errors->first('opening_hour_all') }}</div>

            <h2>Special Days</h2>
            <h4>Enter 00:00 for both to indicate a closed day</h4>
            <div>
                @foreach($days as $shortDay => $fullDay)
                    <label>
                        <input id="checkbox-{{ $shortDay }}"
                               class="checkbox" type="checkbox"
                               name="special_days[]"
                               value="{{ $shortDay }}"
                            @if($errors->first('opening_hour_'.$shortDay))
                               checked
                            @endif
                        >
                        {{ $fullDay }}
                    </label>
                    <div id="opening-hour-{{ $shortDay }}"
                         @if(!$errors->first('opening_hour_'.$shortDay))
                            hidden
                         @else
                            class="business-hrs"
                         @endif
                    >
                        <div class="hrs-open">
                            <label>Opening</label>
                            <input id="opening-time-{{ $shortDay }}" type="time"
                                   name="opening_time_{{ $shortDay }}"
                                   min="00:00" max="23:59" step="1800"
                                   placeholder="hh:mm"
                                   value="{!! old('opening_time_'.$shortDay) !!}">
                        </div>
                        <div class="hrs-close">
                            <label>Closing</label>
                            <input id="closing-time-{{ $shortDay }}" type="time"
                                   name="closing_time_{{ $shortDay }}"
                                   min="00:00" max="23:59" step="1800"
                                   placeholder="hh:mm"
                                   value="{!! old('closing_time_'.$shortDay) !!}">
                        </div>
                    </div>
                    <div class="error">{{ $errors->first('opening_time_'.$shortDay) }}</div>
                    <div class="error">{{ $errors->first('closing_time_'.$shortDay) }}</div>
                    <div class="error">{{ $errors->first('opening_hour_'.$shortDay) }}</div>
                @endforeach
            </div>

            <button type="submit" name="next">Next</button>
        </form>

    </div>

@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/opening-hour.js') }}"></script>
@endsection
