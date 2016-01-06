@extends('app')

@section('content')

<h2>{{$character->name or 'Select'}} - Skills</h2>

    {!!Form::model($character, array('route' => array('character.store_skills', $character->id))) !!}



    @foreach($skills_by_category as $category)
        <h3 class="category_title">{{$category->title}}</h3>
        <div class="skill_category">
        @foreach($category->skill as $skill)
            <div class="skill_selection_row">
                <div class="skill_title">{!!Form::label($skill->title) !!}</div>
                <div class="radio_box_descriptor">Points <br /><!-- POINTS -->
                    <div class="radio_box_set">

                    @foreach(['-',1,2,3,4,5,6] as $i)
                        {!!Form::label('points['.$skill->id.']-'.$i, $i , ['class' => 'radio_label']) !!}

                        {!!Form::radio('points['.$skill->id.']',
                            $i, $i == $character_skills[$skill->id],
                            ['id' => 'points['.$skill->id.']-'.$i]) !!}
                    @endforeach

                    </div>
                </div>
                <div class="radio_box_descriptor">Bonus <br /><!-- BONUS -->
                    <div class="radio_box_set">
                        @foreach(['-',1,2,3,4,5,6] as $i)

                            {!!Form::label('bonus['.$skill->id.']-'.$i, $i , ['class' => 'radio_label']) !!}

                            {!!Form::radio('bonus['.$skill->id.']',
                                $i, $i == $character_bonuses[$skill->id],
                                ['id' => 'bonus['.$skill->id.']-'.$i]) !!}
                        @endforeach
                    </div>
                </div>

                {!!Form::checkbox('skill_data[]', $skill->id, $character_skills[$skill->id]!=0) !!}
                </div>
                <div style="clear: both; min-width:95%;">&nbsp;</div>
                @endforeach


        </div>
    @endforeach

    {!!Form::submit('Save') !!}

    {!!Form::close() !!}


<script>
$(document).ready(function(){

    $('.skill_selection_row input[type=checkbox]').hide();
    $('.radio_label').click(function(e) {
        $(this).closest('.skill_selection_row').find('input[type=checkbox]').prop("checked", true);
    });
    $('.category_title').click(function(e) {
        $(this).next('div').toggle();
    });
});

</script>

@endsection
