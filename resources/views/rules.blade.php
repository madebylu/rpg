@extends('app')

@section('content')

    <aside>
        <ul>
            <div><li class="collapsable"><strong>Rules</strong> <a href="/rules/add">+</a></li>
                @foreach($rules as $rule)
                @if($rule->subsection == 0)
                @if($rule->section != 1)
                    </div>
                @endif
                <div>
                  @endif
                    <li @if($rule->subsection == 0) class="section"
                        @else class="subsection" @endif>
                        <a href="#{{$rule->section}}.{{$rule->subsection}}">
                            {{$rule->section}}.{{$rule->subsection}}
                         - {{$rule->title}}</a>
                    </li>
                  @endforeach
                </div>
            </div>

            <div><li class="collapsable startCollapsed"><strong>Skills</strong> <a href="/skill/add">+</a></li>
            @foreach( $skill_categories as $skill_category )
                <div>
                    <li class="section"><a href="#{{$skill_category->title}}Skills">{{ $skill_category->title }} Skills</a></li>
                    @foreach( $skill_category->skill as $skill )
                    <li class="subsection"><a href="#{{$skill->title}}">{{ $skill->title }}</a></li>

                    @endforeach
                </div>
            @endforeach
            </div>

            <div><li class="collapsable startCollapsed"><strong>Edges</strong> <a href="/edge/add">+</a></li>
              @foreach( $edge_categories as $edge_category)
                <div>
                <li class="section"><a href="#{{$edge_category->title}}Edges">{{ $edge_category->title }} Edges</a></li>
                  @foreach($edge_category->edge as $edge)
                    <li class="subsection"><a href="#edge{{$edge->id}}">{{$edge->title}}</a></li>
                  @endforeach
                </div>
              @endforeach
            </div>
            <div><li class="collapsable startCollapsed"><strong>Items</strong> <a href="/item/add">+</a></li>
              @foreach( $item_categories as $item_category)
                <div>
                <li class="section"><a href="#{{$item_category->title}}Items">{{ $item_category->title }} Items</a></li>
                  @foreach($item_category->item as $item)
                    <li class="subsection"><a href="#item{{$item->id}}">{{$item->title}}</a></li>
                  @endforeach
                </div>
              @endforeach
            </div>
            <div><li class="collapsable startCollapsed"><strong>Backgrounds</strong> <a href="/background/add">+</a></li>
              @foreach( $backgrounds as $background)
                <div>
                <li class="section"><a href="#Background{{$background->id}}">{{ $background->title }}</a></li>
                </div>
              @endforeach
            </div>

        </ul>
    </aside>
    <div id="rules">
        <h1>Rules</h1>

        @foreach($rules as $rule)
            <h2 id="{{$rule->section}}.{{$rule->subsection}}">{{$rule->section}}.{{$rule->subsection}} - {{$rule->title }}
                <span class="edit"><a href="/rules/edit/{{$rule->id}}">~</a></span></h2>
            <p>{!! nl2br($rule->content) !!}</p>
        @endforeach

        <h1>Skills</h1>

        @foreach($skill_categories as $skill_category)
            <h2 id="{{$skill_category->title}}Skills">{{$skill_category->title}} Skills</h2>
            @foreach($skill_category->skill as $skill)
                <h3 id="{{$skill->title}}">{{$skill->title}}
                    <span class="edit"><a href="/skill/edit/{{$skill->id}}">~</a></span></h3>
                    <p><strong>Scope: </strong>{{$skill->scope}}</p>
                    <p>{{$skill->content}}</p>
                <table>
                    <tr><td>Trivial <br/>(TS 9)</td><td>{{$skill->trivial_tasks}}</td></tr>
                    <tr><td>Average <br/>(TS 12)</td><td>{{$skill->average_tasks}}</td></tr>
                    <tr><td>Challenging <br/>(TS 15)</td><td>{{$skill->challenging_tasks}}</td></tr>
                    <tr><td>Impossible <br/>(TS 18)</td><td>{{$skill->impossible_tasks}}</td></tr>
                    <tr><td>Opposed</td><td>{{$skill->opposed_tasks}}</td></tr>
                </table>
            @endforeach
        @endforeach

        <h1>Edges</h1>

        @foreach($edge_categories as $edge_category)
        <h2 id="{{$edge_category->title}}Edges">{{$edge_category->title}} Edges</h2>
            @foreach($edge_category->edge as $edge)
                <h3 id="edge{{$edge->id}}">{{$edge->title}} <span class="edit"><a href="/edge/edit/{{$edge->id}}">~</a></span></h3>
                <p>{{$edge->slug}}</p>
                <p><strong>Prerequesites:</strong> {{$edge->requirements}}</p>
                <p>{!! nl2br($edge->content) !!}</p>
            @endforeach
        @endforeach

        <h1>Items</h1>

        @foreach($item_categories as $item_category)
        <h2 id="{{$item_category->title}}Items">{{$item_category->title}} Items</h2>
            @foreach($item_category->item as $item)
                <h3 id="item{{$item->id}}">{{$item->title}} <span class="edit"><a href="/item/edit/{{$item->id}}">~</a></span></h3>
                <p>{{$item->cost}}</p>
                <p>{!! nl2br($item->content) !!}</p>
            @endforeach
        @endforeach

        <h1>Backgrounds</h1>
        @foreach($backgrounds as $background)
            <h3 id="Background{{$background->id}}">{{$background->title}}<span class="edit"><a href="/background/edit/{{$background->id}}">~</a></span></h3>
            <p>{!! nl2br($background->content) !!}</p>
        @endforeach

    </div>
<script>
    $(document).ready(function() {
        $("li.subsection").hide();
        $(".startCollapsed").siblings().hide();
        if ($(window).width() <= 400){
            $(".collapsable").siblings().hide();
        }

        $(".collapsable").click( function(e) {
            $(this).siblings().toggle(400);
            $(this).parent().siblings().find(".collapsable").siblings().hide(400);
        });

        $("li.section").click( function(e) {
            $(this).parent().siblings().find("li.subsection").hide(400);
            $(this).siblings(".subsection").toggle(400);

        });
        $("#rules").click(function (e) {
            if ($(window).width() <= 400){
                $(".collapsable").siblings().hide(400);
            }
        });
    });

</script>


@stop
