@extends('app')

@section('content')
    
    <h2>Setting Information</h2>

    @if(!isset($settingInformation))
        {!!Form::open(array('action' => 'SettingInformationController@store')) !!}
    @else
        {!!Form::model($settingInformation, array('route' => array('settingInformation.update',$settingInformation->id))) !!}
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