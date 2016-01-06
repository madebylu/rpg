@extends('app')

@section('content')

    <h2>Rule</h2>

    @if(!isset($rule))
        {!!Form::open(array('action' => 'RuleController@store')) !!}
    @else
        {!!Form::model($rule, array('route' => array('rule.update', $rule->id))) !!}
    @endif

    {!!Form::label('section') !!}
    {!!Form::text('section') !!}

    {!!Form::label('subsection') !!}
    {!!Form::text('subsection') !!}
    
    {!!Form::label('title') !!}
    {!!Form::text('title') !!}

    {!!Form::label('content') !!}
    {!!Form::textarea('content') !!}

    {!!Form::submit('Save') !!}

    {!!Form::close() !!}
    
    
@endsection