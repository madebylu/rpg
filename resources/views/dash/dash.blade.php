@extends('app')

@section('content')

<aside>
<p><a href="/character/add">New Character</a></p>
</aside>



<div id="dash">
    <h2>Dash - Hi {{$user_name}}.</h2>
    
    <h3>My Characters</h3>

    @foreach($characters as $character)
        <p><a href="/character/view/{{$character->id}}">{{$character->name}}</p></a>
    @endforeach
    
    <h3>My Games</h3>
    <p>... soon ... </p>
    
    <h3>Everyone's Characters</h3>
    @foreach($games as $game)
        <h3>{{$game->title}}</h3>
        <p>{{$game->content}}</p>
        @foreach($game->characters as $character)
        <p><a href="/character/view/{{$character->id}}">{{$character->name}}</p></a>
        @endforeach
        <hr />
    @endforeach


</div>



@endsection
