@extends('layouts.template')

@section('title')
    Business hours - BookMe
@endsection

@section('nav')
    @include('nav.dashboard')
    @include('nav.logout')
@endsection

@section('content')

    <div class="dashboard">
        <h1>Business Hours</h1>

        <table>
            <thead>
            <tr>
                <th>Day</th>
                <th>Opening Hours</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($days AS $shortDay => $longDay)
                <tr>
                    <td>{{ $longDay }}</td>
                    @if($businessHours[$shortDay]['opening_time'] != '00:00')
                        <td>{{ $businessHours[$shortDay]['opening_time'] }} ~ {{ $businessHours[$shortDay]['closing_time'] }}</td>
                    @else
                        <td>Closed</td>
                    @endif
                    <td><span class="update-business-hour" data-day="{{ $shortDay }}"><img id="edit" src="/img/edit.png" alt="edit"></span></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <form method="post">
            {{ csrf_field() }}
            @foreach($days AS $shortDay => $fullDay)
                <div id="opening-hour-{{ $shortDay }}"
                     @if(!$errors->first('opening_hour_'.$shortDay))
                     hidden
                     @endif
                >
                    <label id="day">
                        <input id="checkbox-{{ $shortDay }}"
                               type="checkbox" name="day"
                               value="{{ $shortDay }}" hidden
                               @if($errors->first('opening_hour_'.$shortDay))
                               checked
                                @endif
                        >
                        {{ $fullDay }}
                    </label>
                    <div class="business-hrs">
                        <div class="hrs-open">
                            <label>Opening</label>
                            <input id="opening-time-{{ $shortDay }}" type="time"
                                   name="opening_time_{{ $shortDay }}"
                                   min="00:00" max="23:59" step="1800"
                                   placeholder="hh:mm"
                                   value="{{ $businessHours[$shortDay]['opening_time'] }}">
                        </div>
                        <div class="hrs-close">
                            <label>Closing</label>
                            <input id="closing-time-{{ $shortDay }}" type="time"
                                   name="closing_time_{{ $shortDay }}"
                                   min="00:00" max="23:59" step="1800"
                                   placeholder="hh:mm"
                                   value="{{ $businessHours[$shortDay]['closing_time'] }}">
                        </div>
                    </div>
                    <div class="error">{{ $errors->first('opening_time_'.$shortDay) }}</div>
                    <div class="error">{{ $errors->first('closing_time_'.$shortDay) }}</div>
                    <div class="error">{{ $errors->first('opening_hour_'.$shortDay) }}</div>
                    <button class="update-controls" type="submit" formaction="/business/hour/update/submit/{{ $shortDay }}">Update</button>
                    <button class="update-controls" type="submit" formaction="/business/hour/update/submit/all">Update All</button>
                </div>
            @endforeach
        </form>
    </div>

@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/opening-hour.js') }}"></script>
@endsection
