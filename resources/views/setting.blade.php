@extends('app')

@section('heading')
<h2>Setting</h2>
@endsection

@section('content')

    <aside>
        <ul>
            <li><strong>Setting Information</strong> {!!HTML::link('/setting/add','+')!!}</li>
            @foreach($setting_information as $section)
            <li>{{$section->section}}.{{$section->subsection}} - {{$section->title}}</li>
            @endforeach
            <li><strong>Places</strong> {!!HTML::link('/place/add','+')!!}</li>
            @foreach($places as $place)
            <li>{{$place->title}}</li>
            @endforeach
            <li><strong>Faiths</strong> {!!HTML::link('/faith/add','+')!!}</li>
            @foreach($faiths as $faith)
            <li>{{$faith->title}}</li>
            @endforeach

        </ul>
    </aside>

    <div id="settingInformation">
        <h2>Setting stuff goes here</h2>

        @foreach($setting_information as $section)
            <h2>{{$section->section}}.{{$section->subsection}} - {{$section->title }}
                <span class="edit">{!! HTML::link('/setting/edit/'.$section->id, 'edit') !!}</span></h2>
            <p>{!! nl2br($section->content) !!}</p>
        @endforeach

        <h2>Places</h2>
        @foreach($places as $place)
            <h2>{{$place->title}}
                <span class="edit">{!! HTML::link('/place/edit/'.$place->id, 'edit') !!}</span></h2>
            <p>{{$place->key_words}}</p>
            <p>{{$place->terrain}}</p>
            <p>{{$place->rulership}}</p>
            <p>{{$place->relationships}}</p>
            <p>{{$place->content}}</p>
        @endforeach

        <h2>Faiths</h2>
        @foreach($faiths as $faith)
            <h2>{{$faith->title}} - {{$faith->honourific}}
                <span class="edit">{!! HTML::link('/faith/edit/'.$faith->id, 'edit') !!}</span></h2>
            <p>{{$faith->beliefs}}</p>
            <p>{{$faith->structure}}</p>
        @endforeach

    </div>
@stop
