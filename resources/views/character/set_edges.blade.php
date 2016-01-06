@extends('app')

@section('content')

<h2>{{$character->name}} - Edges</h2>

    {!!Form::model($character, array('route' => array('character.store_edges', $character->id))) !!}
    
    @foreach($edges as $edge)
        <div class="checkable">
        {!!Form::label($edge->title) !!}
        <p>Requirements: {{$edge->requirements}}</p>
        <p>{{$edge->content}}</p>
        </div>
        {!!Form::checkbox('edge_id[]', $edge->id) !!}
        
    @endforeach
    {!!Form::submit('Save') !!}

    {!!Form::close() !!}


<script>
$(document).ready(function(){
    $('input[type=checkbox]').hide();
    $('div.checkable').click( function(event){
        console.log('clicked');
        $(this).toggleClass('selected');
        $(this).next('input[type=checkbox]').click();
        
    });
});

</script>

@endsection
