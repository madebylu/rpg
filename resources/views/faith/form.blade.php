@extends('app')

@section('content')

    <h2>Faith</h2>

    @if(!isset($faith))
        {!!Form::open(array('action' => 'FaithController@store')) !!}
    @else
        {!!Form::model($faith, array('route' => array('faith.update', $faith->id))) !!}
    @endif

    {!!Form::label('title') !!}
    {!!Form::text('title') !!}

    {!!Form::label('honourific') !!}
    {!!Form::text('honourific') !!}
    
    {!!Form::label('beliefs') !!}
    {!!Form::textarea('beliefs') !!}

    {!!Form::label('structure') !!}
    {!!Form::textarea('structure') !!}

    {!!Form::submit('Save') !!}

    {!!Form::close() !!}
    
    
@endsection