@extends(backpack_view('blank'))

@php
    $widgets['content'][] = [
        'type' => 'script',
        'content' => 'https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js'
    ];

    $widgets['after_content'][] = [
        'type'        => 'div',
        'class' => 'row',
        'content'     =>  $widgets['content'],
    ];
@endphp

@section('content')

    <div class="row">
        @include('dashboard.diskWidgets')
        @include('dashboard.componentTemperatures')
    </div>

    <div class="row">
        @include('dashboard.weatherGraph')
        @include('dashboard.currentWeather')
    </div>

    <div class="row">
        @include('dashboard.weatherCards')
    </div>

    <div class="row">
        @include('dashboard.torrentsList')
    </div>
@endsection
