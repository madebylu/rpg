@extends('app_wide')

@section('content')
<aside>
    <div class="dice_box">
        <div id="dice">
            <div id="d1" class="reroll">1</div>
            <div id="d2" class="reroll">1</div>
            <div id="d3" class="reroll">1</div>
            <div class="operator"> + </div>
            <div id="mod">0</div>
            <div class="operator"> = </div>
            <div id="dice_total">1</div>
        </div>

        <div>
            <!-- notes on how combat works - need to be relocated as they're too verbose for the available sapce.
            <p>Start combat with 1 focus, gain 1 at the beginning of each of your turns, lose one whenever you get hit. Reset your unfocus at the beginning of your turn.</p>
            -->
            <p style="clear: both">
                <span id="combat_status">in combat</span><br /> <a href=# id="combat_off"><img src="/img/nocombat32.png" alt="combat off" /></a>
 <a href=# id="combat_on"><img src="/img/combat32.png" alt="combat on" /></a>
            </p>
            <p>
                Focus: <span id="focus">1</span>
                <span id="add_focus" class="glyphicon glyphicon-plus"></span>
                <span id="remove_focus" class="glyphicon glyphicon-minus"></span>
            <p>
                Unfocussed Penalty: <span id="unfocus">-1</span>
                <a href=# id="reset_dice"><span class="glyphicon glyphicon-refresh"></span></a>
            </p>
            <p>
                Boons: <span id="boons">{{ $character->boons_current }}</span> / {{ $character->boons_max }}
                <span id="reset_boons" class="glyphicon glyphicon-refresh"></span>
            <p>
            <p>
                Armour: <span id="armour">{{ $character->armour }}</span>
            </p>
            <p>
                Fatigue: <span id="fatigue">0</span>
                <span id="add_fatigue" class="glyphicon glyphicon-plus"></span>
                <span id="remove_fatigue" class="glyphicon glyphicon-minus"></span>
            </p>
            <p>
                Advantage: <span id="situational_slider_label">0</span>
                <input id="situational_slider" type="range" min="-3" max="3" step="1" />
            </p>
            <p>
                <input type="button" class="btn" id="get_hit_location" value="Get hit location">
            </p>
        </div>

        <div>
            <ul id="chat_messages">
                <li>...</li>
            </ul>
            <p><input type="text" id="chat-box"></input></p>
        </div>

        <div id="character_wounds">
            {!! Form::model($character) !!}
            {!! Form::label('wounds_text', 'Wounds') !!}
            {!! Form::textarea('wounds_text')!!}
            {!! Form::close() !!}
        </div>
    </div>

    <!--
    <h3>Update</h3>
    <p><a href="/character/set_backgrounds/{{$character->id}}">Backgrounds</a></p>
    <p><a href="/character/set_edges/{{$character->id}}">Edges</a></p>
    -->
</aside>
<div id="character_sheet" class="container-fluid">
    <div class="row">
        <!-- user selectable content - choice of canvas or editable character bits-->
        <div class="col-sm-9">
            <h3>
                <a href=# id="show-character">Character</a> |
                <a href=# id="show-canvas">Map</a> |
                <a href=# id="show-edges">Edges</a> |
                <a href=# id="show-skills">Skills</a>
            </h3>
            <div class="main-pane main-pane-character">
                <h2>
                    <a href="/character/edit/{{$character->id}}">{{$character->name}}</a>
                </h2>
                <h2>Level {{$character->level}} {{$character->heritage->title}}</h2>
            @foreach($character->background as $background)
                <p>{{$background->title}} - {{$background->content}}</p>
            @endforeach

            <p>{{$character->description}}</p>
            </div>

            <div class="main-pane main-pane-canvas">
                <canvas id="drawing-board" width="750" height="500"></canvas>
                <div>
                    Colour: <input type="color" id="drawing-colour"></input>
                    <input type="button" class="btn btn-primary"  id="line" value="Line" />
                    <input type="button" class="btn" id="squiggle" value="Freehand" />
                    <input type="button" class="btn" id="box" value="Box" />
                    <input type="button" class="btn" id="add-actor" value="Add a token" />
                    <input type="button" class="btn" id="move-actor" value="Move tokens" />
                    <input type="button" class="btn" id="clear" value="Clear Current Layer" />
                </div>
            </div>

            <div class="main-pane main-pane-skills">
                <h3>Skills <a href="#" class="toggle_edit">
                    <span class="glyphicon glyphicon-edit"></span></a>
                </h3>

                <div id="character_skills">
                    <table class="skill_table table">
                        <thead>
                            <tr>
                                <th>Skill</th>
                                <th class="edit"></th>
                                <th>Points</th>
                                <th class="edit"></th>
                                <th class="edit"></th>
                                <th>Bonus</th>
                                <th class="edit"></th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>General</td>
                                <td class="edit"></td>
                                <td class="skill_points">0</td>
                                <td class="edit"></td>
                                <td class="edit"></td>
                                <td class="skill_bonus">0</td>
                                <td class="edit"></td>
                                <td class="skill_total">0</td>
                            </tr>

                        @foreach($character->skill as $skill)
                            <tr data-skill-id="{{$skill->id}}">
                                <td title="{{$skill->content}}">
                                    <span class="edit remove_skill">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </span>
                                    {{ $skill->title }}
                                </td>
                                <td class="points edit minus"><a href=#>-</a></td>
                                <td class="skill_points">{{ $skill->pivot->points }}</td>
                                <td class="points edit plus"><a href=#>+</a></td>
                                <td class="bonus edit minus"><a href=#>-</a></td>
                                <td class="skill_bonus">{{ $skill->pivot->bonus }}</td>
                                <td class="bonus edit plus"><a href=#>+</a></td>
                                <td class="skill_total">{{ $skill->pivot->total }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="edit">
                        {!! Form::model($character) !!}
                        <p>
                            {!! Form::select('add_skill', $learnable_skills) !!}
                            {!! Form::submit('Add', ['id'=>'add_skill']) !!}
                            {!! Form::close() !!}
                        </p>
                    </div>
                </div>
            </div> <!-- end skills pane -->

            <!-- edges pane -->
            <div class="main-pane main-pane-edges">
                <h3>Edges
                    <a href="#" class="toggle_edit"><span class="glyphicon glyphicon-edit"></a>
                    <a href="/character/set_edges/{{$character->id}}"><span class="edit glyphicon glyphicon-plus"></span></a>
                </h3>
                @foreach($character->edge as $edge)
                    <div data-edge-id="{{$edge->id}}">
                        <h4>
                            <span class="edit remove_edge">
                                <span class="glyphicon glyphicon-remove"></span>
                            </span>
                            {{ $edge->title }}
                        </h4>
                        <p>{!! nl2br($edge->content) !!}</p>
                    </div>
                @endforeach
            </div>

            <div id="character_notes">
                {!! Form::model($character) !!}
                {!! Form::label('notes') !!}
                {!! Form::textarea('notes')!!}
                {!! Form::close() !!}
            </div>


        </div> <!-- end col-sm-9 -->

        <!-- small sidebar info -->
        <div class="col-sm-3">


            <!-- Skills summary -->

            <h3>Skills
                <input type="button" class="btn" id="use_focus" value="Use Focus" />
            </h3>

            <div id="character_skills_summary">
                <table class="skill_table table">
                    <thead>
                        <tr>
                            <th>Skill</th>
                            <th>Roll</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>General</td>
                            <td class="roll_dice">+ <span class="skill_total">0</span>
                                <span class="skill_unfocus_penalty" style="color: red"> -1 </span>
                            </td>
                        </tr>

                @foreach($character->skill as $skill)
                        <tr data-skill-id="{{$skill->id}}">
                            <td title="{{$skill->content}}">{{$skill->title}}</td>
                            <td class="roll_dice">
                                 + <span class="skill_total">{{$skill->pivot->total}}</span>
                                 <span class="skill_unfocus_penalty" style="color: red"> -1 </span>
                            </td>
                        </tr>
                @endforeach
                    </tbody>
                </table>
            </div>
            <!-- edges summary -->
            <div id="character_edges_summary">

                <h3>Edges</h3>
                @foreach($character->edge as $edge)
                <p title="{{$edge->content}}" data-edge-id="{{$edge->id}}">{{ $edge->title }}</p>
                @endforeach
            </div>
            <div id="character_inventory_summary">
                <h3>Inventory</h3>
                {!! Form::model($character) !!}
                {!! Form::label('inventory') !!}
                {!! Form::textarea('inventory')!!}
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>
</div>

<script>

    $( document ).ready(function() {
        var conn = new WebSocket('ws://beta.unfetteredhorizons.com:8080');
        conn.onopen = function(e) {
            console.log("connection made");
        };

        conn.onmessage = function(e){
            //console.log(e.data);
            var new_obj = JSON.parse(e.data);
            //console.log(new_obj);
            if (new_obj[0].obj_type == 'box'){
                background_items = [];
            }
            if (new_obj[0].obj_type == 'line'){
                foreground_items = [];
            }
            if (new_obj[0].obj_type == 'actor'){
                actors = [];
            }

            for (var i = 0; i < new_obj.length; i++){
                switch (new_obj[i].obj_type){
                    case "line":
                    var incoming_line = new Line(new_obj[i].startX, new_obj[i].startY, new_obj[i].endX, new_obj[i].endY, new_obj[i].line_style);
                    //console.log(incoming_line);
                    foreground_items.push(incoming_line);
                    //push_shape_to_active_layer(incoming_line, new_obj.layer, false);
                    refresh_canvas(context);
                    break;
                    case "box":
                    var incoming_box = new Box(new_obj[i].startX, new_obj[i].startY, new_obj[i].w, new_obj[i].h, new_obj[i].fill_style);
                    //console.log(incoming_box);
                    background_items.push(incoming_box);
                    //push_shape_to_active_layer(incoming_box, new_obj.layer, false);
                    console.log(background_items);
                    refresh_canvas(context);
                    break;
                    case "squiggle":
                    var incoming_line = new Line(new_obj[i].startX, new_obj[i].startY, new_obj[i].endX, new_obj[i].endY, new_obj[i].line_style);
                    //push_shape_to_active_layer(incoming_line, new_obj.layer, false);
                    refresh_canvas(context);
                    break;
                    case "actor":
                    var new_actor = new Actor(new_obj[i].posX, new_obj[i].posY, new_obj[i].fill_style);
                    actors.push(new_actor);
                    //push_shape_to_active_layer(new_actor, new_obj.layer, false);
                    refresh_canvas(context);
                    break;
                    case "clear":
                    clear_a_layer(new_obj[i].layer);
                    break;
                    case "message":
                    console.log(new_obj[i]);
                    $('#chat_messages li').last().append('<li>' + new_obj[i].display_name + ': '  + new_obj[i].message + '</li>');
                    $("#chat_messages").animate({ scrollTop: $("#chat_messages")[0].scrollHeight}, 1000);
                    break;
                    case "check":
                    console.log(new_obj[i]);
                    $('#chat_messages li').last().append('<li>' + new_obj[i].display_name + ' '  + new_obj[i].message + '</li>');
                    $("#chat_messages").animate({ scrollTop: $("#chat_messages")[0].scrollHeight}, 1000);
                    break;
                }
            }
        };
        function clone(o) {return $.extend(true,{},o);}

        var Line = function(startX, startY, endX, endY, line_style){
            this.startX = startX;
            this.startY = startY;
            this.endX = endX;
            this.endY = endY;
            this.line_style = line_style;
            this.obj_type = "line";
            this.draw = function(context){
                context.beginPath();
                context.moveTo((this.startX + canvas_offset_x) * scale, (this.startY + canvas_offset_y) * scale);
                context.lineTo((this.endX + canvas_offset_x) * scale, (this.endY + canvas_offset_y) * scale);
                context.strokeStyle = this.line_style;
                context.stroke();
            }
            this.set_start = function(startX, startY, line_style){
              this.startX = startX;
              this.startY = startY;
              this.line_style = line_style;
            }
            this.set_end = function(endX, endY){
              this.endX = endX;
              this.endY = endY;
            }
            this.reset = function(){
              this.startX = 0;
              this.startY = 0;
              this.endX = 0;
              this.endY = 0;
              this.line_style = "black";
            }
        }

        var Box = function(startX, startY, w, h, fill_style){
            this.startX = startX;
            this.startY = startY;
            this.w = w;
            this.h = h;
            this.fill_style = fill_style;
            this.obj_type = "box";
            this.draw = function(context){
              context.fillStyle = this.fill_style;
              context.fillRect((this.startX + canvas_offset_x) * scale, (this.startY + canvas_offset_y) * scale, this.w * scale, this.h * scale);
            }
            this.set_start = function(startX, startY, fill_style){
              this.startX = startX;
              this.startY = startY;
              this.fill_style = fill_style;
            }
            this.set_width_and_height = function(mouse_pos_X, mouse_pos_Y){
              this.w = mouse_pos_X - this.startX;
              this.h = mouse_pos_Y - this.startY;
            }
            this.reset = function(){
              this.startX = 0;
              this.startY = 0;
              this.endX = 0;
              this.endY = 0;
              this.line_style = "black";
            }
        }
        var Actor = function(posX, posY, fill_style){
            this.posX = posX;
            this.posY = posY;
            this.fill_style = fill_style;
            this.obj_type = "actor";
            this.draw = function(context){
              context.beginPath();
              context.fillStyle = this.fill_style;
              context.arc((this.posX + canvas_offset_x)  * scale, (this.posY + canvas_offset_y) * scale, scale * 20 , 0 , 2*Math.PI, false);
              context.fill();
            }
            this.reset = function(){
              this.posX = 0;
              this.posY = 0;
              this.line_style = "black";
            }
        }

        var Layer = function(description){
            this.description = description;
            this.shapes = [];
        }

        function refresh_canvas(context){
            context.clearRect(0, 0, 750, 500);
            context.fillStyle = "white";
            context.fillRect(0,0,750,500);
            for (i = 0; i<background_items.length; i++){
              background_items[i].draw(context);
            }
            for (i = 0; i<foreground_items.length; i++){
              foreground_items[i].draw(context);
            }
            for (i = 0; i<actors.length; i++){
              actors[i].draw(context);
            }
            context.beginPath();
            for (i = 0; i < (750 / scale); i++) {
              context.moveTo(i * scale * 40 + 0.5 + (scale * canvas_offset_x % (scale * 40)), 0);
              context.lineTo(i * scale * 40 + 0.5 + (scale * canvas_offset_x % (scale * 40)), 500);
            }
            for (i = 0; i < (500 / scale); i++) {
              context.moveTo(0, i * scale * 40 + 0.5 + (scale * canvas_offset_y % (scale * 40)));
              context.lineTo(750, i * scale * 40 + 0.5 + (scale * canvas_offset_y % (scale * 40)));
            }

          context.strokeStyle = "lightgrey";
          context.stroke();
      }

      function push_shape_to_active_layer(shape, layer, pass_to_socket){
        if(layer == "background_items"){
            background_items.push(clone(shape));
            if(pass_to_socket){
              shape.layer = active_layer;
              broadcast_layer(background_items);
            }
            console.log(typeof(shape));
        }
        if(layer == "foreground_items"){
            console.log(shape);
            foreground_items.push(clone(shape));
            if(pass_to_socket){
              shape.layer = active_layer;
              broadcast_layer(foreground_items);
            }
        }
        if(layer == "actors"){
            actors.push(clone(shape));
            if(pass_to_socket){
              shape.layer = active_layer;
              broadcast_layer(actors);
            }
        }
        //if(pass_to_socket){
        //    shape.layer = active_layer;
        //    conn.send(JSON.stringify(shape));
        //}
      }

      function broadcast_layer(layer){
          conn.send(JSON.stringify(layer));
      }

      function clear_a_layer(layer) {
        if(layer == "background_items"){
          background_items = [];
        }
        if(layer == "foreground_items"){
          foreground_items = [];
        }
        if(layer == "actors"){
          actors = [];
        }
        refresh_canvas(context);
      }

      var canvas = $('#drawing-board').get(0);
      var context = canvas.getContext("2d");
      var active_actor_index = -1; //no active actors on page load
      var active_layer = "background_items";
      var background_items = [];
      var foreground_items = [];
      var actors = [];
      var layers = [background_items, foreground_items, actors];
      var next_line = new Line(0,0,0,0, "black");
      var next_line_section = new Line(0,0,0,0, "black");
      var next_box = new Box(0, 0, 0, 0,  "black");
      var drawing = false;
      var canvas_click_action = "box";
      var scale = 1;
      var canvas_offset_x = 0;
      var canvas_offset_y = 0;
      var update_offset_x = 0;
      var update_offset_y = 0;
      context.fillStyle = "white";
      context.fillRect(0,0,750,500);
      refresh_canvas(context);


      $('#line').on('click', function(event){
        canvas_click_action = "line";
        active_layer = "foreground_items";
        $(this).parent().parent().find('.btn').removeClass('btn-primary');
        $(this).addClass('btn-primary');
      });
      $('#squiggle').on('click', function(event){
        canvas_click_action = "squiggle";
        active_layer = "foreground_items";
        $(this).parent().parent().find('.btn').removeClass('btn-primary');
        $(this).addClass('btn-primary');
      });
      $('#box').on('click', function(event){
        canvas_click_action = "box";
        active_layer = "background_items";
        $(this).parent().parent().find('.btn').removeClass('btn-primary');
        $(this).addClass('btn-primary');
      });
      $('#add-actor').on('click', function(event){
        canvas_click_action = "add_actor";
        active_layer = "actors";
        $(this).parent().parent().find('.btn').removeClass('btn-primary');
        $(this).addClass('btn-primary');
      });
      $('#move-actor').on('click', function(event){
        canvas_click_action = "move_actor";
        active_layer = "actors";
        $(this).parent().parent().find('.btn').removeClass('btn-primary');
        $(this).addClass('btn-primary');
      });
      $('#clear').on('click', function(event){
        clear_a_layer(active_layer);
        var reset_obj = {"obj_type": "clear", "layer": active_layer };
        conn.send(JSON.stringify(reset_obj));
      });
      $(canvas).bind("contextmenu", function(e) {
        e.preventDefault();
       });
      //set starting points
      $('#drawing-board').on('mousedown', function(event) {
        event.preventDefault();
        if(event.which == 3){
            console.log("moving...");
            update_offset_x = event.offsetX;
            update_offset_y = event.offsetY;
        }
        else if(canvas_click_action == "line"){
          next_line.set_start(event.offsetX / scale - canvas_offset_x, event.offsetY / scale - canvas_offset_y, $('#drawing-colour').val());
        }
        if(canvas_click_action == "squiggle"){
          next_line_section.set_start(event.offsetX / scale - canvas_offset_x, event.offsetY / scale - canvas_offset_y, $('#drawing-colour').val());
        }
        if(canvas_click_action == "box"){
          next_box.set_start(event.offsetX / scale - canvas_offset_x, event.offsetY / scale - canvas_offset_y, $('#drawing-colour').val());
        }
        //try and "grab" an actor / token if it's nearby.
        if(canvas_click_action == "move_actor"){
          for (i=0; i<actors.length; i++){
            diff_x = actors[i].posX - (event.offsetX / scale - canvas_offset_x);
            diff_y = actors[i].posY - (event.offsetY / scale - canvas_offset_y);
            if (Math.pow(diff_x,2) + Math.pow(diff_y, 2) < 400){ //400 is r squared
              active_actor_index = i;
              console.log(i);
            }
          }
        }
        drawing = true;
      });

      //set interim end points
      $('#drawing-board').on('mousemove', function(event) {
        refresh_canvas(context);
        if(event.which == 3){
          canvas_offset_x += (event.offsetX - update_offset_x) / scale;
          canvas_offset_y += (event.offsetY - update_offset_y) / scale;
          update_offset_x = event.offsetX;
          update_offset_y = event.offsetY;
        }
        else if(drawing){
          refresh_canvas(context);
          if(canvas_click_action == "line"){
            next_line.set_end(event.offsetX / scale - canvas_offset_x, event.offsetY / scale - canvas_offset_y);
            next_line.draw(context);
          }
          if(canvas_click_action == "squiggle"){
            next_line_section.set_end(event.offsetX / scale - canvas_offset_x, event.offsetY / scale - canvas_offset_y);
            next_line_section.draw(context);
            push_shape_to_active_layer(next_line_section, active_layer, false);
            //this is a special case, set next line section to start where this one ends
            next_line_section.set_start(event.offsetX / scale - canvas_offset_x, event.offsetY / scale - canvas_offset_y, $('#drawing-colour').val());

          }
          if(canvas_click_action == "box"){
            next_box.set_width_and_height(event.offsetX / scale - canvas_offset_x, event.offsetY / scale - canvas_offset_y);
            next_box.draw(context);
          }
          if(canvas_click_action == "move_actor"){
            var safe_move = true;
            for (i=0; i<actors.length; i++){
              diff_x = actors[i].posX - (event.offsetX / scale - canvas_offset_x);
              diff_y = actors[i].posY - (event.offsetY / scale - canvas_offset_y);
              if (i != active_actor_index && Math.pow(diff_x,2) + Math.pow(diff_y, 2) < 400){ //400 is r squared
                console.log('colission');
                safe_move = false;
              }
            }
            if (safe_move){
              actors[active_actor_index].posX = event.offsetX / scale - canvas_offset_x;
              actors[active_actor_index].posY = event.offsetY / scale - canvas_offset_y;
              var moving_actor = clone(actors[active_actor_index]);
              moving_actor.obj_type = "actor";
              moving_actor.index = active_actor_index;
            }
          }

        }
      });

      //finalise 'shape'
      $('#drawing-board').on('mouseup', function(event) {
        if(event.which == 3){
          event.preventDefault();
          refresh_canvas(context);
        } else {
            if(canvas_click_action == "line"){
              next_line.set_end(event.offsetX / scale - canvas_offset_x, event.offsetY / scale - canvas_offset_y);
              push_shape_to_active_layer(next_line, active_layer, true);
              console.log(next_line);
              console.log(foreground_items);
              next_line.reset();
            }
            if(canvas_click_action == "squiggle"){
              next_line_section.set_end(event.offsetX / scale - canvas_offset_x, event.offsetY / scale - canvas_offset_y);
              push_shape_to_active_layer(next_line_section, active_layer, true);

            }
            if(canvas_click_action == "box"){
              next_box.set_width_and_height(event.offsetX / scale - canvas_offset_x, event.offsetY / scale - canvas_offset_y);
              next_box.draw(context);
              push_shape_to_active_layer(next_box, active_layer, true);
            }
            if(canvas_click_action == "add_actor"){
              next_actor = new Actor(event.offsetX / scale - canvas_offset_x, event.offsetY / scale - canvas_offset_y, $('#drawing-colour').val());
              next_actor.draw(context);
              next_actor.obj_type = "actor";
              push_shape_to_active_layer(next_actor, active_layer, true);
            }
            if(canvas_click_action == "move_actor"){
              broadcast_layer(actors);
            }
        }

        drawing = false;
        //conn.send({"background": background_items, "foreground": foreground_items, "actors": actors});
        refresh_canvas(context);
      });
      $('#drawing-board').on('mousewheel', function(event) {
        if (event.originalEvent.wheelDelta > 0 && scale < 5) {
          scale = scale * 1.05;
        }
        if (event.originalEvent.wheelDelta < 0 && scale > 0.4){
          scale = scale / 1.05;
        }
        event.preventDefault();
        console.log(scale);
        refresh_canvas(context);
      });
        var status = "combat_on"; //default.
        $('#combat_off').css('opacity', '0.5')
        $('.edit').hide();

        //dice
        //roll one of the 3 on screen dice
        function roll_d6(display_id){
            var score = Math.ceil(Math.random()*6);
            console.log('rolled a ' + score);
            $(display_id).text(score);
            $(display_id).fadeIn('slow');
        }

        function update_total(){
            var new_total =
                Math.min(
                    parseInt($('#d1').text()) +
                    parseInt($('#d2').text()) +
                    parseInt($('#d3').text()),
                    18 - parseInt($('#fatigue').text())  //cap roll before score at 18 - fatigue
                ) +
                parseInt($('#mod').text());
            $('#dice_total').text(new_total);
        }
    function save_skill_values(table_row){
        skill_points = parseInt($(table_row).find('.skill_points').text());
        skill_bonus = parseInt($(table_row).find('.skill_bonus').text());
        skill_id = parseInt($(table_row).data('skill-id'));
        console.log(skill_id + ': ' + skill_points + ' ' + skill_bonus);

        $.ajax({
            method: "POST",
            url: "/character/ajax_update_skill",
            data:{
                _token: "{{csrf_token()}}",
                character_id: "{{$character->id}}",
                skill_id: skill_id,
                skill_points: skill_points,
                skill_bonus: skill_bonus
            },
            success: function(data){
                console.log(data);
            }

        })
    }

    $('input#chat-box').on('keyup', function(event) {
        if (event.which == 13 || event.keyCode == 13){
            user_message = $(this).val();
            //make json to send to server, assing and obj_type so it can be sorted by the switch
            var new_message = {"obj_type": "message", "message": user_message, "display_name": "{{$user_name}}"};
            conn.send(JSON.stringify([new_message]));
            $(this).val("");
            $('#chat_messages li').last().append('<li>' + new_message.display_name + ': '  + new_message.message + '</li>');
            $("#chat_messages").animate({ scrollTop: $("#chat_messages")[0].scrollHeight}, 1000);
        }
    });

    //dice stuff
    var use_focus = false;

    $('#use_focus').on('click', function(event) {
      if(!use_focus) {
        use_focus = true;
        $('#use_focus').val('Using Focus');
        $('.skill_unfocus_penalty').text(' ');
        event.preventDefault();
      } else {
        use_focus = false;
        $('#use_focus').val('Use Focus');
        $('.skill_unfocus_penalty').text(parseInt($('#unfocus').text()));
      }
    });
    $('.roll_dice').click( function() {
        //set dice to 0 and hide them.
        $('#d1, #d2, #d3').text(0);
        $('#d1, #d2, #d3').hide();

        //get number of dice to roll
        //currently locked at 3 for testing
        var n = 3; //parseInt($(this).find('.num_dice').text());

        //get total modifier for currently selected skill.
        var mod = parseInt($(this).find('.skill_total').text()) + parseInt($('#situational_slider').val());
        var focus = parseInt($('#focus').text());
        var unfocus = parseInt($('#unfocus').text());
        console.log('Unfocus penalty: ' + unfocus);
        var check_message_append = ' (Unfocused)';
        if (!use_focus){
            if (status == "combat_on") {
                mod = mod + unfocus; //unfocus is a negative
                unfocus--;
                $('#unfocus').text(unfocus);
            }
        } else {
            use_focus = false;
            $('#use_focus').val('Use Focus');
            $('#focus').text(focus - 1);
            check_message_append = ' (Focused)';
        }
        if (status == "combat_off") { check_message_append = ' (Out of Combat)'; }
        $('.skill_unfocus_penalty').each(function() {
            $(this).text(unfocus);
        });
        $('#mod').text(mod);

        //roll n dice. rolled dice become visible.
        //diminish n if appropriate
        switch(n){
            case 3:
                roll_d6('#d3');
            case 2:
                roll_d6('#d2');
                if (status == "combat_on") {
                    $(this).find('.num_dice').text(n-1);
                    //$('#focus').text(focus - 1);
                }
            case 1:
                roll_d6('#d1');
            break;
        }

        update_total();

        var user_roll_message = {"obj_type": "check", "display_name": "{{$character->name}}", "message": "made a check"};
        var check_type = $(this).siblings('td').first().text();
        var check_total = $('#dice_total').text();
        user_roll_message.message = " uses " + check_type + " and scores " + check_total + check_message_append ;

        $('#chat_messages li').last().append('<li>' + user_roll_message.display_name + ' '  + user_roll_message.message + '</li>');
        $("#chat_messages").animate({ scrollTop: $("#chat_messages")[0].scrollHeight}, 1000);
        conn.send(JSON.stringify([user_roll_message]));
    });

    //rerolling single dice for boons etc
    $('.reroll').click(function(e){
        boons = parseInt($('#boons').text());
        //if the character has a boon, spend it to reroll a dice
        if (boons > 0){
            roll_d6('#' + $(this).attr('id') );
            update_total();
            boons--;
            $('#boons').text(boons);
            //need to send a chat message about the reroll:
            var check_total = $('#dice_total').text();
            var user_roll_message = {"obj_type": "check", "display_name": "{{ $character->name }}", "message": "made a check"};

            user_roll_message.message = " uses divine power and scores " + check_total + " instead" ;

            $('#chat_messages li').last().append('<li>' + user_roll_message.display_name + ' '  + user_roll_message.message + '</li>');
            $("#chat_messages").animate({ scrollTop: $("#chat_messages")[0].scrollHeight}, 1000);
            conn.send(JSON.stringify([user_roll_message]));

            //finally, update boons_current in the database
            $.ajax({
                method: "POST",
                url: "/character/update_boons_current",
                data:{
                    _token: "{{ csrf_token() }}",
                    id: "{{ $character->id }}",
                    boons_current: boons
                }

            })
        }
    });

    //player end diminishment management
    $('#combat_on').click(function (e) {
        status = "combat_on";
        $('#combat_off').css('opacity', '0.5');
        $('#reset_dice').css('opacity', '1');
        $('#combat_on').css('opacity', '1')
        $('#combat_status').text('in combat');
        $('.skill_unfocus_penalty').show();
        e.preventDefault();
    });
    $('#combat_off').click(function (e) {
        //this seems to be doing nothing. scope issue?
        status = "combat_off";
        $('#combat_on').css('opacity', '0.5');
        $('#reset_dice').css('opacity', '0.5');
        $('#combat_off').css('opacity', '1');
        $('#combat_status').text('out of combat');
        $('.skill_unfocus_penalty').hide();
        e.preventDefault();
    });
    $('.toggle_edit').click( function(event) {
        event.preventDefault();
        $('.edit').toggle();
    });
    $('#reset_dice').click(function (e) {
        //set unfocus penalty to -1
        $('#unfocus').text('-1');
        $('.skill_unfocus_penalty').each(function() {
            $(this).text('-1');
        });
        e.preventDefault();
    });
    $('#add_focus').click(function (e) {
        var focus = parseInt($('#focus').text());
        $('#focus').text(focus + 1);
        e.preventDefault();
    });
    $('#remove_focus').click(function (e) {
        var focus = parseInt($('#focus').text());
        $('#focus').text(focus - 1);
        e.preventDefault();
    });
    $('#add_fatigue').click(function (e) {
        var fatigue = parseInt($('#fatigue').text());
        $('#fatigue').text(fatigue + 1);
        e.preventDefault();
    });
    $('#remove_fatigue').click(function (e) {
        var fatigue = parseInt($('#fatigue').text());
        $('#fatigue').text(fatigue - 1);
        e.preventDefault();
    });
    $('#reset_boons').click(function (e) {
        $('#boons').text( {{ $character->boons_max }} );
        $.ajax({
            method: "POST",
            url: "/character/update_boons_current",
            data:{
                _token: "{{ csrf_token() }}",
                id: "{{ $character->id }}",
                boons_current: {{ $character->boons_max }}
            }

        })
        e.preventDefault();
    });
    $('#get_hit_location').on('click', function(e) {
        e.preventDefault();
        $(this).hide();
        var hit_location_number = Math.floor(Math.random()*6) + 1;
        var hit_location = "";
        switch(hit_location_number) {
            case 1:
                hit_location = "Left Leg";
                break;
            case 2:
                hit_location = "Right Leg";
                break;
            case 3:
                hit_location = "Left Arm";
                break;
            case 4:
                hit_location = "Right Arm";
                break;
            case 5:
                hit_location = "Torso";
                break;
            case 6:
                hit_location = "Head";
                break;
        }
        $(this).val(hit_location);
        $(this).fadeIn();

    });
    $('#situational_slider').on('change', function(e) {
        var slider_val = $(this).val();
        $('#situational_slider_label').text(slider_val);
    });

    //main pane management
    $('.main-pane-character').siblings('.main-pane').hide();
    $('#show-character').on('click', function(event) {
        event.preventDefault();
        $('.main-pane-character').siblings('.main-pane').hide();
        $('.main-pane-character').fadeIn(800);
    });
    $('#show-canvas').on('click', function(event) {
        event.preventDefault();
        $('.main-pane-canvas').siblings('.main-pane').hide();
        $('.main-pane-canvas').fadeIn(800);
    });
    $('#show-edges').on('click', function(event) {
        event.preventDefault();
        $('.main-pane-edges').siblings('.main-pane').hide();
        $('.main-pane-edges').fadeIn(800);
    });
    $('#show-skills').on('click', function(event) {
        event.preventDefault();
        $('.main-pane-skills').siblings('.main-pane').hide();
        $('.main-pane-skills').fadeIn(800);
    });

    $('.minus a').click( function(event) {
        event.preventDefault();
        new_value=parseInt($(this).parent().next().text());
        new_value--;
        $(this).parent().next().text(new_value);
        skill_total = parseInt($(this).parent().parent().find('.skill_total').text());
        skill_total--;
        $(this).parent().parent().find('.skill_total').text(skill_total);
        skill_id = $(this).parent().parent().data('skill-id');
        $('#character_skills_summary').find("tr[data-skill-id='" + skill_id + "']").find('.skill_total').text(skill_total);
        save_skill_values($(this).parent().parent());
    });

    $('.plus a').click( function(event) {
        event.preventDefault();
        new_value=parseInt($(this).parent().prev().text());
        new_value++;
        $(this).parent().prev().text(new_value);
        skill_total = parseInt($(this).parent().parent().find('.skill_total').text());
        skill_total++;
        $(this).parent().parent().find('.skill_total').text(skill_total);
        skill_id = $(this).parent().parent().data('skill-id');
        $('#character_skills_summary').find("tr[data-skill-id='" + skill_id + "']").find('.skill_total').text(skill_total);
        save_skill_values($(this).parent().parent());
    });
        //Save inventory on keyup.
        $('#inventory').keyup(function(e){
            inventory = $(this).val();
            $.ajax({
                method: "POST",
                url: "/character/update_inventory",
                data:{
                    _token: "{{csrf_token()}}",
                    id: "{{$character->id}}",
                    inventory: inventory
                }

            })
        });
        //Save notes on keyup.
        $('#notes').keyup(function(e){
            notes = $(this).val();
            $.ajax({
                method: "POST",
                url: "/character/update_notes",
                data:{
                    _token: "{{csrf_token()}}",
                    id: "{{$character->id}}",
                    notes: notes
                }

            })
        });

        //Save wounds text on keyup.
        $('#wounds_text').keyup(function(e){
            wounds_text = $(this).val();
            $.ajax({
                method: "POST",
                url: "/character/update_wounds_text",
                data:{
                    _token: "{{csrf_token()}}",
                    id: "{{$character->id}}",
                    wounds_text: wounds_text
                }

            })
        });
        $('#add_skill').on('click', function(event){
            event.preventDefault();
            console.log('request made');
            $skill_id = $('select[name="add_skill"]').val();
            $skill_name = $('select[name="add_skill"]').children(':selected').text();
            $character_id = {{$character->id}};
            console.log($skill_name);
            $.ajax({
                method: "POST",
                url: "/character/ajax_add_skill",
                data:{
                    _token: "{{csrf_token()}}",
                    character_id: $character_id,
                    skill_id: $skill_id
                },
            success: function(data) {
                //alert(data);
                $new_row = $("<tr> \
                    <td><span class='edit remove_skill'> <span class='glyphicon glyphicon-remove'></span>"+$skill_name+"  </td> \
                    <td class='points edit minus'><a href=#>-</a></td>\
                    <td class='skill_points'>1</td> \
                    <td class='points edit plus'><a href=#>+</a></td>\
                    <td class='bonus edit minus'><a href=#>-</a></td>\
                    <td class='skill_bonus'>0</td> \
                    <td class='bonus edit plus'><a href=#>+</a></td>\
                    <td class='skill_total'>1</td> \
                    <td class='roll_dice'>\
                         + <span class='skill_total'>1</span> \
                         <span class='skill_unfocus_penalty' style='color: red'> -1 </span>\
                    </td>\
                ");
                $('.skill_table').find('tr').last().after($new_row);
                $('select[name="add_skill"]').children(':selected').remove();

                }
            })
        })
        $('.remove_skill').on('click', function(event){
            event.preventDefault();
            console.log('request made');
            $skill_id = $(this).closest('tr').data('skill-id');
            $skill_row =  $(this).closest('tr');
            $character_id = {{$character->id}};
            console.log($skill_id);
            $.ajax({
                method: "POST",
                url: "/character/ajax_remove_skill",
                data:{
                    _token: "{{csrf_token()}}",
                    character_id: $character_id,
                    skill_id: $skill_id
                },
            success: function(data) {
                console.log(data);
                $skill_row.hide();
                //add the skill back into the drop list
                }
            })
        })
        $('.remove_edge').on('click', function(event){
            event.preventDefault();
            console.log('request made');
            edge_id = $(this).closest('div').data('edge-id');
            edge_html = $(this).closest('div');
            character_id = {{$character->id}};
            console.log(edge_id);
            $.ajax({
                method: "POST",
                url: "/character/ajax_remove_edge",
                data:{
                    _token: "{{csrf_token()}}",
                    character_id: character_id,
                    edge_id: edge_id
                },
            success: function(data) {
                console.log(data);
                //remove the edge from the display
                edge_html.hide();
                $('#character_edges_summary').find("p[data-edge-id='" + edge_id + "']").hide();
                }
            })
        })

    });
</script>

@endsection
