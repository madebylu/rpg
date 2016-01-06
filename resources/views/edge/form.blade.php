@extends('app')

@section('content')

    <h2>Edge</h2>

    @if(!isset($edge))
        {!!Form::open(array('action' => 'EdgeController@store')) !!}
    @else
        {!!Form::model($edge, array('route' => array('edge.update', $edge->id))) !!}
    @endif

    {!!Form::label('title') !!}
    {!!Form::text('title') !!}

    {!!Form::label('category_id', 'Category') !!}
    {!!Form::select('category_id', $categories) !!}
    
    {!!Form::label('slug') !!}
    {!!Form::text('slug') !!}

    {!!Form::label('requirements') !!}
    {!!Form::text('requirements') !!}

    {!!Form::label('content') !!}
    {!!Form::textarea('content') !!}

    {!!Form::submit('Save') !!}

    {!!Form::close() !!}
    
    
@endsection