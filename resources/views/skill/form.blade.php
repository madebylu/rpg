@extends('app')

@section('content')
    
    <h2>Skill</h2>

    @if(!isset($skill))
        {!!Form::open(array('action' => 'SkillController@store')) !!}
    @else
        {!!Form::model($skill, array('route' => array('skill.update',$skill->id))) !!}
    @endif

    {!!Form::label('title') !!}
    {!!Form::text('title') !!}

    {!!Form::label('category') !!}
    {!!Form::select('category_id', $categories) !!}
    
    {!!Form::label('scope') !!}
    {!!Form::textarea('scope') !!}

    {!!Form::label('content') !!}
    {!!Form::textarea('content') !!}

    {!!Form::label('trivial_tasks') !!}
    {!!Form::textarea('trivial_tasks') !!}

    {!!Form::label('average_tasks') !!}
    {!!Form::textarea('average_tasks') !!}

    {!!Form::label('challenging_tasks') !!}
    {!!Form::textarea('challenging_tasks') !!}

    {!!Form::label('impossible_tasks') !!}
    {!!Form::textarea('impossible_tasks') !!}

    {!!Form::label('opposed_tasks') !!}
    {!!Form::textarea('opposed_tasks') !!}


    {!!Form::submit('Save') !!}

    {!!Form::close() !!}
    
    
@endsection