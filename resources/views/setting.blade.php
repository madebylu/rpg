@extends('app')

@section('heading')
<h2>Setting</h2>
@endsection

@section('content')

    <aside>
        <ul>
            <li><strong>Setting Information</strong> <a href="/setting/add">+</a></li>
            @foreach($setting_information as $section)
            <li>
                <a href="#Setting{{$section->section}}.{{$section->subsection}}">
                {{$section->section}}.{{$section->subsection}} - {{$section->title}}</a>
            </li>
            @endforeach
            <li><strong>Places</strong> <a href="/place/add">+</a></li>
            @foreach($places as $place)
            <li><a href="#Place{{$place->title}}">{{$place->title}}</a></li>
            @endforeach
            <li><strong>Faiths</strong> <a href="/faith/add">+</a></li>
            @foreach($faiths as $faith)
            <li><a href="#Faith{{$faith->title}}">{{$faith->title}}</a></li>
            @endforeach

        </ul>
    </aside>

    <div id="settingInformation">
        <h2>Setting stuff goes here</h2>

        @foreach($setting_information as $section)
            <h2 id="Setting{{$section->section}}.{{$section->subsection}}">
                {{$section->section}}.{{$section->subsection}} - {{$section->title }}
                <span class="edit"><a href="/setting/edit/{{$section->id}}">~</a></span>
            </h2>
            <p>{!! nl2br($section->content) !!}</p>
        @endforeach

        <h2>Places</h2>
        @foreach($places as $place)
            <h2 id="Place{{$place->title}}">{{$place->title}}
                <span class="edit"><a href="/place/edit/{{$place->id }}">~</a></span>
            </h2>
            <p>Key Words: {{$place->key_words}}</p>
            <p>Terrain: {{$place->terrain}}</p>
            <p>Rulership: {{$place->rulership}}</p>
            <p>Relationships: {!! nl2br($place->relationships)!!}</p>
            <p>{!!nl2br($place->content)!!}</p>
        @endforeach

        <h2>Faiths</h2>
        @foreach($faiths as $faith)
            <h2 id="Faith{{$faith->title}}">{{$faith->title}} - {{$faith->honourific}}
                <span class="edit"><a href="/faith/edit/{{$faith->id}}">~</a><span></h2>
            <p>Beliefs: {{$faith->beliefs}}</p>
            <p>Structure: {{$faith->structure}}</p>
        @endforeach

    </div>
@stop
