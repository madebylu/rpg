@extends('app')

@section('content')

    <h2>Character</h2>

    @if(!isset($character))
        {!!Form::open(array('action' => 'CharacterController@store')) !!}
    @else
        {!!Form::model($character, array('route' => array('character.update', $character->id))) !!}
    @endif

    {!!Form::label('name') !!}
    {!!Form::text('name') !!}

    {!!Form::label('heritage_id', 'Heritages') !!}
    {!!Form::select('heritage_id', $heritages) !!}

    {!!Form::label('description') !!}
    {!!Form::textarea('description') !!}

    {!!Form::label('level') !!}
    {!!Form::select('level', [2=>2,3=>3,4=>4,5=>5,6=>6]) !!}

    {!!Form::label('armour', 'Minimum Defence') !!}
    {!!Form::text('armour') !!}

    {!!Form::label('boons_max', 'Boons') !!}
    {!!Form::text('boons_max') !!}

    {!!Form::submit('Save') !!}

    {!!Form::close() !!}


@endsection
