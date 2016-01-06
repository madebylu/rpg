@extends('app')

@section('content')

<h2>{{$character->name}} - Background</h2>

    {!!Form::model($character, array('route' => array('character.store_backgrounds', $character->id))) !!}
    
    @foreach($backgrounds as $background)
        <div class="checkable">
        {!!Form::label($background->title) !!}
        <p>{{$background->content}}</p>
        </div>
        {!!Form::checkbox('background_id[]', $background->id) !!}
        
    @endforeach
    {!!Form::submit('Save') !!}

    {!!Form::close() !!}


<script>
$(document).ready(function(){
    console.log('success');
    $('input[type=checkbox]').hide();
    $('div.checkable').click( function(event){
        console.log('clicked');
        $(this).toggleClass('selected');
        $(this).next('input[type=checkbox]').click();
        
    });
});

</script>

@endsection