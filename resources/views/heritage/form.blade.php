@extends('app')

@section('content')

    <h2>Heritage</h2>

    @if(!isset($heritage))
        {!!Form::open(array('action' => 'HeritageController@store')) !!}
    @else
        {!!Form::model($heritage, array('route' => array('heritage.update', $heritage->id))) !!}
    @endif

    {!!Form::label('title') !!}
    {!!Form::text('title') !!}

    {!!Form::label('content') !!}
    {!!Form::textarea('content') !!}

    {!!Form::submit('Save') !!}

    {!!Form::close() !!}
    
    
@endsection