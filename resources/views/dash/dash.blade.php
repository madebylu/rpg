@extends('app')

@section('content')

<aside>
<p><a href="/character/add">New Character</a></p>
<h3>Active Characters</h3>

<ul>
@foreach($characters as $character)
    <a href="/character/view/{{$character->id}}"<li>{{$character->name}}</li></a>
@endforeach
</ul>
</aside>



<div id="dash">
    <h2>Dash - Hi {{$user_name}}.</h2>



<p>Admin stuff: <a href="/background/add">New Background</a></p>

</div>



@endsection
