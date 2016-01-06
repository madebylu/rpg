@extends('app')

@section('content')
    
    {!!Form::open(array('action' => 'RuleController@store')) !!}

    {!!Form::label('section') !!}
    {!!Form::text('section') !!}

    {!!Form::label('subsection') !!}
    {!!Form::text('subsection') !!}
    
    {!!Form::label('title') !!}
    {!!Form::text('title') !!}

    {!!Form::label('content') !!}
    {!!Form::textarea('content') !!}

    {!!Form::submit('Add') !!}

    {!!Form::close() !!}
    
    
@endsection