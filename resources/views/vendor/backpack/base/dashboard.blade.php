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

@endsection
