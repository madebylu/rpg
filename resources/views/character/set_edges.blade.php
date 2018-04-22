@extends('app')

@section('content')
<h1>{{$character->name}} - Select Edges to add from the list below</h1>
<div>
    {!!Form::model($character, array('route' => array('character.store_edges', $character->id))) !!}
    
        <div class="row"> 
            <div class="col-sm-3"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edge</strong></div>
            <div class="col-sm-4"><strong>Requirements</strong></div>
            <div class="col-sm-5"><strong>Description</strong></div>
        </div>
    @foreach($edges as $edge)
        <div class="checkable row" title="{{$edge->content}}">
            <div class="col-sm-2">{{$edge->title}}</div>
            <div class="col-sm-4">{{$edge->requirements}}</div>
            <div class="col-sm-6">{{$edge->slug}}</div>
            <div class="col-sm-12 content hidden"><br /><p><em>{{$edge->content}}</em></p></div>
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
        $(this).toggleClass('selected');
        $(this).children('.content').toggleClass('hidden');
        $(this).next('input[type=checkbox]').click();
        
    });
});

</script>

@endsection
