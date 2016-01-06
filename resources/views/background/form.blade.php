@extends('app')

@section('content')

<h2>Background</h2>

    @if(!isset($background))
        {!!Form::open(array('action' => 'BackgroundController@store')) !!}
    @else
        {!!Form::model($background, array('route' => array('background.update', $background->id))) !!}
    @endif

    {!!Form::label('title') !!}
    {!!Form::text('title') !!}

    {!!Form::label('content') !!}
    {!!Form::textarea('content') !!}

    {!!Form::submit('Save') !!}

    {!!Form::close() !!}

@endsection