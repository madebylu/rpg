@extends('app')

@section('content')

    <h2>Item</h2>

    @if(!isset($item))
        {!!Form::open(array('action' => 'ItemController@store')) !!}
    @else
        {!!Form::model($item, array('route' => array('item.update', $item->id))) !!}
    @endif

    {!!Form::label('title') !!}
    {!!Form::text('title') !!}

    {!!Form::label('category_id', 'Category') !!}
    {!!Form::select('category_id', $categories) !!}

    {!!Form::label('content') !!}
    {!!Form::textarea('content') !!}

    {!!Form::label('cost') !!}
    {!!Form::text('cost') !!}

    {!!Form::submit('Save') !!}

    {!!Form::close() !!}


@endsection
