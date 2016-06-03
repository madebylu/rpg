@extends('app')

@section('content')
<style>
    div.checkable{
        min-height: 20em;
    }
    div.checkable:nth-of-type(3n+1){
        clear: left;
    }
</style>
<h2>{{$character->name}} - Edges</h2>
<div class="row">
    {!!Form::model($character, array('route' => array('character.store_edges', $character->id))) !!}
    
    @foreach($edges as $edge)
        <div class="checkable col-sm-4">
        {!!Form::label($edge->title) !!}
        <p>Requirements: {{$edge->requirements}}</p>
        <p>{{$edge->content}}</p>
        </div>
        {!!Form::checkbox('edge_id[]', $edge->id) !!}
        
    @endforeach
    <div class="col-sm-12">
    {!!Form::submit('Save') !!}
    </div>
    {!!Form::close() !!}
</div>

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
