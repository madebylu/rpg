@extends('app')

@section('content')

    <h2>Game</h2>

    @if(!isset($game))
        {!!Form::open(array('action' => 'GameController@store')) !!}
    @else
        {!!Form::model($game, array('route' => array('game.update', $game->id))) !!}
    @endif

    {!!Form::label('title') !!}
    {!!Form::text('title') !!}

    {!!Form::label('content') !!}
    {!!Form::textarea('content') !!}

    {!!Form::submit('Save') !!}

    {!!Form::close() !!}
    
    
@endsection
