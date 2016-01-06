@extends('app')

@section('content')

    <h2>Place</h2>

    @if(!isset($place))
        {!!Form::open(array('action' => 'PlaceController@store')) !!}
    @else
        {!!Form::model($place, array('route' => array('place.update', $place->id))) !!}
    @endif

    {!!Form::label('title') !!}
    {!!Form::text('title') !!}

    {!!Form::label('key_words') !!}
    {!!Form::text('key_words') !!}
    
    {!!Form::label('terrain') !!}
    {!!Form::textarea('terrain') !!}

    {!!Form::label('rulership') !!}
    {!!Form::textarea('rulership') !!}

    {!!Form::label('relationships') !!}
    {!!Form::textarea('relationships') !!}

    {!!Form::label('content') !!}
    {!!Form::textarea('content') !!}

    {!!Form::submit('Save') !!}

    {!!Form::close() !!}
    
    
@endsection