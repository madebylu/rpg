@extends('app')

@section('content')

<aside>
<p><a href="/character/add">New Character</a></p>
<h3>Active Characters</h3>

@foreach($characters as $character)
    <p><a href="/character/view/{{$character->id}}">{{$character->name}}</p></a>
@endforeach
</aside>



<div id="dash">
    <h2>Dash - Hi {{$user_name}}.</h2>
    
    <h3>All Characters</h3>
    @foreach($all_characters as $character)
        <p><a href="/character/view/{{$character->id}}">{{$character->name}}</p></a>
    @endforeach

    <p>Admin stuff: <a href="/background/add">New Background</a></p>

</div>



@endsection
