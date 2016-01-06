@extends('app')

@section('content')
<aside>
    <p><a href=# id="combat_on"><img src="/img/combat32.png" alt="combat on" /></a>
        <a href=# id="reset_dice"><img src="/img/reset32.png" alt="reset" /></a>
        <a href=# id="combat_off"><img src="/img/nocombat32.png" alt="combat off" /></a>
    </p>

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
    </div>

    <h3>Update</h3>
    <p><a href="/character/set_backgrounds/{{$character->id}}">Backgrounds</a></p>
    <p><a href="/character/set_edges/{{$character->id}}">Edges</a></p>
    <p><a href="/character/set_skills/{{$character->id}}">Skills</a></p>
    <p><a href="#" id="toggle_edit">Toggle Editing</a></p>
<div>
    <h3>Table</h3>
    <p>Colour: <input type="color" id="drawing-colour"></input></p>
    <p><input type="button" id="line" value="Line" /></p>
    <p><input type="button" id="squiggle" value="Freehand" /></p>
    <p><input type="button" id="rect" value="Box" /></p>
    <p><input type="button" id="add-actor" value="Add a token" /></p>
    <p><input type="button" id="move-actor" value="Move tokens" /></p>
    <p><input type="button" id="clear" value="Clear Current Layer" /></p>
    <p>Background <input type="radio" name="layer" value="background" class="layer"checked/><br />
    Foreground 
    <input type="radio" name="layer" value="foreground" class="layer"/><br />
    Tokens <input type="radio" name="layer" value="actors" class="layer"/></p>
</div>
</aside>
<div id="character_sheet" class="container-fluid">
<h2>
    <a href="/character/edit/{{$character->id}}">{{$character->name}}</a></h2>
        <h2>Level {{$character->level}} {{$character->heritage->title}}</h2>
    @foreach($character->background as $background)
        <p>{{$background->title}} - {{$background->content}}</p>
    @endforeach

</h2>

<p>{{$character->description}}</p>
<div class="row">
<div class="col-sm-4">
<h3>Edges</h3>
@foreach($character->edge as $edge)
    <p title="{{$edge->content}}">{{$edge->title}} - {{$edge->slug}}</p>
@endforeach
</div>

<div class="col-sm-8">
<h3>Skills</h3>

<div id="character_skills"></div>
<table class="skill_table">
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
            <th>Roll</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>General</td>
            <td class="skill_points">0</td>
	    <td class="edit"></td>
            <td class="skill_bonus">0</td>
	    <td class="edit"></td>
	    <td class="edit"></td>
            <td class="skill_total">0</td>
	    <td class="edit"></td>
            <td class="roll_dice"><span class="num_dice">3</span>d6 + 0</td>
        </tr>

@foreach($character->skill as $skill)
        <tr data-skill-id="{{$skill->id}}">
            <td title="{{$skill->content}}"><span class="edit delete"> <span class="glyphicon glyphicon-remove"></span> </span>{{$skill->title}}</td>
            <td class="points edit minus"><a href=#>-</a></td>
 	    <td class="skill_points">{{$skill->pivot->points}}</td>
            <td class="points edit plus"><a href=#>+</a></td>
            <td class="bonus edit minus"><a href=#>-</a></td>
            <td class="skill_bonus">{{$skill->pivot->bonus}}</td>
            <td class="bonus edit plus"><a href=#>+</a></td>
            <td class="skill_total">{{$skill->pivot->total}}</td>
            <td class="roll_dice"><span class="num_dice">3</span>d6 + <span class="skill_total">{{$skill->pivot->total}}</span></td>
        </tr>
@endforeach
        <tr class="edit">
            {!! Form::model($character) !!}
            <td>
                {!! Form::select('add_skill', $learnable_skills) !!}

            </td>
            <td>
              {!! Form::submit('Add', ['id'=>'add_skill']) !!}
              {!! Form::close() !!}

            </td>
        </tr>
    </tbody>
</table>
</div>
<div class="col-sm-4">
<h3>Stuff</h3>
{!! Form::model($character) !!}
{!! Form::label('inventory') !!}
{!! Form::textarea('inventory')!!}
{!! Form::close() !!}
<p>Chat:<input type="text" id="chat-box"></input></p>
</div>

<div class="col-sm-8">
<h3>Chat</h3>
<ul id="chat_messages">
<li>...</li>
</ul>

</div>


<div class="col-sm-12">
    <canvas id="drawing-board" width="750" height="500px"></canvas>
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
		console.log(new_obj);
		switch (new_obj.obj_type){
			case "line":
			var incoming_line = new Line(new_obj.startX, new_obj.startY, new_obj.endX, new_obj.endY, new_obj.line_style);
			push_shape_to_active_layer(incoming_line, new_obj.layer, false);
			refresh_canvas(context);
			break;
			case "rect":
			var incoming_rect= new Rect(new_obj.startX, new_obj.startY, new_obj.w, new_obj.h, new_obj.fill_style);
			push_shape_to_active_layer(incoming_rect, new_obj.layer, false);
			refresh_canvas(context);
			break;
			case "squiggle":
			var incoming_line = new Line(new_obj.startX, new_obj.startY, new_obj.endX, new_obj.endY, new_obj.line_style);
			push_shape_to_active_layer(incoming_line, new_obj.layer, false);
			refresh_canvas(context);
			break;
			case "new_actor":
			var new_actor = new Actor(new_obj.posX, new_obj.posY, new_obj.fill_style);
			push_shape_to_active_layer(new_actor, new_obj.layer, false);
			refresh_canvas(context);
			break;
			case "move_actor":
			//var new_actor = new Actor(new_obj.posX, new_obj.posY, new_obj.fill_style);
			//push_shape_to_active_layer(new_actor, new_obj.layer, false);
			//need to locate or identify an actor and then relocate it.
			actors[new_obj.index].posX = new_obj.posX;
			actors[new_obj.index].posY = new_obj.posY;
			refresh_canvas(context);
			break;
			case "clear":
			clear_a_layer(new_obj.layer);
			break;
			case "message":
			console.log(new_obj);
			$('#chat_messages li').last().append('<li>' + new_obj.display_name + ': '  + new_obj.message + '</li>');
			break;
			case "check":
			console.log(new_obj);
			$('#chat_messages li').last().append('<li>' + new_obj.display_name + ' '  + new_obj.message + '</li>');
			break;
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
          context.moveTo(this.startX * canvas_scale_unit / 40, this.startY * canvas_scale_unit / 40);
          context.lineTo(this.endX * canvas_scale_unit / 40, this.endY * canvas_scale_unit / 40);
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

      var Rect = function(startX, startY, w, h, fill_style){
        this.startX = startX;
        this.startY = startY;
        this.w = w;
        this.h = h;
        this.fill_style = fill_style;
	this.obj_type = "rect";
        this.draw = function(context){
          context.fillStyle = this.fill_style;
          context.fillRect(this.startX * canvas_scale_unit / 40, this.startY * canvas_scale_unit / 40, this.w * canvas_scale_unit / 40, this.h * canvas_scale_unit / 40);
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
          context.arc(this.posX  * canvas_scale_unit / 40, this.posY * canvas_scale_unit / 40, canvas_scale_unit/2 , 0 , 2*Math.PI, false);
          context.fill();
        }
        this.reset = function(){
          this.posX = 0;
          this.posY = 0;
          this.line_style = "black";
        }
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
	for (i = 0; i < (750 / canvas_scale_unit); i++) {
	  context.moveTo(i * canvas_scale_unit + 0.5, 0);
	  context.lineTo(i * canvas_scale_unit + 0.5, 500);
	}
	for (i = 0; i < (500 / canvas_scale_unit); i++) {
	  context.moveTo(0, i * canvas_scale_unit + 0.5);
	  context.lineTo(750, i * canvas_scale_unit + 0.5);
	}
	
	  context.strokeStyle = "lightgrey";
	  context.stroke();
      }

      function push_shape_to_active_layer(shape, layer, pass_to_socket){
        if(layer == "background"){
          background_items.push(clone(shape));
	  console.log(typeof(shape));
        }
        if(layer == "foreground"){
          foreground_items.push(clone(shape));
        }
        if(layer == "actors"){
          actors.push(clone(shape));
        }
	if(pass_to_socket){
	  shape.layer = active_layer;
	  conn.send(JSON.stringify(shape));
	}
      }
	
      function clear_a_layer(layer) {
        if(layer == "background"){
          background_items = [];
        }
        if(layer == "foreground"){
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
      var active_layer = "background";
      var background_items = [];
      var foreground_items = [];
      var actors = [];
      var next_line = new Line(0,0,0,0, "black");
      var next_line_section = new Line(0,0,0,0, "black");
      var next_rect = new Rect(0, 0, 0, 0,  "black");
      var drawing = false;
      var canvas_click_action = "line";
      var canvas_scale_unit = 40;
      context.fillStyle = "white";
      context.fillRect(0,0,750,500);

      $('#line').on('click', function(event){
        canvas_click_action = "line";
      });
      $('#squiggle').on('click', function(event){
        canvas_click_action = "squiggle";
      });
      $('#rect').on('click', function(event){
        canvas_click_action = "rect";
      });
      $('#add-actor').on('click', function(event){
        canvas_click_action = "add_actor";
        active_layer = "actors";
        $('input[value="actors"]').prop('checked', true);
      });
      $('#move-actor').on('click', function(event){
        canvas_click_action = "move_actor";
        active_layer = "actors";
        $('input[value="actors"]').prop('checked', true);
      });
      $('#clear').on('click', function(event){
	clear_a_layer(active_layer);
	var reset_obj = {"obj_type": "clear", "layer": active_layer };
	conn.send(JSON.stringify(reset_obj));
      });
      $('input[name="layer"]').on('change', function(event){
        active_layer = $(this).val();
      });

      //set starting points
      $('#drawing-board').on('mousedown', function(event) {
        event.preventDefault();
        if(canvas_click_action == "line"){
          next_line.set_start(event.offsetX * 40 / canvas_scale_unit, event.offsetY* 40 / canvas_scale_unit, $('#drawing-colour').val());
        }
        if(canvas_click_action == "squiggle"){
          next_line_section.set_start(event.offsetX * 40 / canvas_scale_unit, event.offsetY * 40 / canvas_scale_unit, $('#drawing-colour').val());
        }
        if(canvas_click_action == "rect"){
          next_rect.set_start(event.offsetX * 40 / canvas_scale_unit, event.offsetY * 40 / canvas_scale_unit, $('#drawing-colour').val());
        }
        //try and "grab" an actor / token if it's nearby.
        if(canvas_click_action == "move_actor"){
          for (i=0; i<actors.length; i++){
            diff_x = actors[i].posX - event.offsetX * 40 / canvas_scale_unit;
            diff_y = actors[i].posY - event.offsetY * 40 / canvas_scale_unit;
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
        if(drawing){
          refresh_canvas(context);
          if(canvas_click_action == "line"){
            next_line.set_end(event.offsetX * 40 / canvas_scale_unit, event.offsetY * 40 / canvas_scale_unit);
            next_line.draw(context);
          }
          if(canvas_click_action == "squiggle"){
            next_line_section.set_end(event.offsetX * 40 / canvas_scale_unit, event.offsetY * 40 / canvas_scale_unit);
            next_line_section.draw(context);
          push_shape_to_active_layer(next_line_section, active_layer, true);
            //this is a special case, set next line section to start where this one ends
            next_line_section.set_start(event.offsetX * 40 / canvas_scale_unit, event.offsetY * 40 / canvas_scale_unit, $('#drawing-colour').val());

          }
          if(canvas_click_action == "rect"){
            next_rect.set_width_and_height(event.offsetX * 40 / canvas_scale_unit, event.offsetY * 40 / canvas_scale_unit);
            next_rect.draw(context);
          }
          if(canvas_click_action == "move_actor"){
            var safe_move = true;
            for (i=0; i<actors.length; i++){
              diff_x = actors[i].posX - event.offsetX * 40 / canvas_scale_unit;
              diff_y = actors[i].posY - event.offsetY * 40 / canvas_scale_unit;
              if (i != active_actor_index && Math.pow(diff_x,2) + Math.pow(diff_y, 2) < 400){ //400 is r squared
                console.log('colission');
                safe_move = false;
              }
            }
            if (safe_move){
              actors[active_actor_index].posX = event.offsetX * 40 / canvas_scale_unit;
              actors[active_actor_index].posY = event.offsetY * 40 / canvas_scale_unit;
	      var moving_actor = clone(actors[active_actor_index]);
	      moving_actor.obj_type = "move_actor";
	      moving_actor.index = active_actor_index;
	      conn.send(JSON.stringify(moving_actor));
            }
          }

        }
      });

      //finalise 'shape'
      $('#drawing-board').on('mouseup', function(event) {
        if(canvas_click_action == "line"){
          next_line.set_end(event.offsetX * 40 / canvas_scale_unit, event.offsetY * 40 / canvas_scale_unit);
          push_shape_to_active_layer(next_line, active_layer, true);
          next_line.reset();
        }
        if(canvas_click_action == "squiggle"){
          next_line_section.set_end(event.offsetX * 40 / canvas_scale_unit, event.offsetY * 40 / canvas_scale_unit);
          push_shape_to_active_layer(next_line_section, active_layer, true);

        }
        if(canvas_click_action == "rect"){
          next_rect.set_width_and_height(event.offsetX * 40 / canvas_scale_unit, event.offsetY * 40 / canvas_scale_unit);
          next_rect.draw(context);
          push_shape_to_active_layer(next_rect, active_layer, true);
        }
        if(canvas_click_action == "add_actor"){
          next_actor = new Actor(event.offsetX * 40 / canvas_scale_unit, event.offsetY * 40 / canvas_scale_unit, $('#drawing-colour').val());
          next_actor.draw(context);
	  next_actor.obj_type = "new_actor";
          push_shape_to_active_layer(next_actor, active_layer, true);
        }

      $('#drawing-board').on('mousewheel', function(event) {
	if (event.originalEvent.wheelDelta > 0 && canvas_scale_unit < 99) {
	  canvas_scale_unit++;
	}
	if (event.originalEvent.wheelDelta < 0 && canvas_scale_unit > 3){
	  canvas_scale_unit--
	}
	event.preventDefault();
	console.log(canvas_scale_unit);
	refresh_canvas(context);
      });

        drawing = false;
	//conn.send({"background": background_items, "foreground": foreground_items, "actors": actors});
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
                parseInt($('#d1').text()) +
                parseInt($('#d2').text()) +
                parseInt($('#d3').text()) +
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
			conn.send(JSON.stringify(new_message));
			$(this).val("");
			$('#chat_messages li').last().append('<li>' + new_message.display_name + ': '  + new_message.message + '</li>');
			$("#chat_messages").animate({ scrollTop: $("#chat_messages")[0].scrollHeight}, 1000);
		}
	});

        $('.roll_dice').click( function() {
            //set dice to 0 and hide them.
            $('#d1, #d2, #d3').text(0);
            $('#d1, #d2, #d3').hide();

            //get number of dice to roll
            var n = parseInt($(this).find('.num_dice').text());

            //get total modifier for currently selected skill.
            var mod = parseInt($(this).siblings('.skill_total').text());
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
                    }
                case 1:
                    roll_d6('#d1');
                break;
            }

            update_total();
	    var user_roll_message = {"obj_type": "check", "display_name": "{{$character->name}}", "message": "made a check"};
	    var check_type = $(this).siblings('td').first().text();
	    var check_total = $('#dice_total').text();
	    user_roll_message.message = " uses " + check_type + " and scores " + check_total;
	    $('#chat_messages li').last().append('<li>' + user_roll_message.display_name + ' '  + user_roll_message.message + '</li>');
	    conn.send(JSON.stringify(user_roll_message));
        });

        //rerolling single dice for boons etc
        $('.reroll').click(function(e){
            roll_d6('#' + $(this).attr('id') );
            update_total();
        });

        //player end diminishment management
        $('#combat_on').click(function (e) {
            status = "combat_on";
            $('#combat_off').css('opacity', '0.5');
            $('#reset_dice').css('opacity', '1');
            $('#combat_on').css('opacity', '1')
            e.preventDefault();
        });
        $('#combat_off').click(function (e) {
            //this seems to be doing nothing. scope issue?
            status = "combat_off";
            //set all dice spans to 3
            $('.num_dice').text('3');
            $('#combat_on').css('opacity', '0.5');
            $('#reset_dice').css('opacity', '0.5');
            $('#combat_off').css('opacity', '1');
            e.preventDefault();
        });
	$('#toggle_edit').click( function(event) {
		event.preventDefault();
		$('.edit').toggle();
	});
        $('#reset_dice').click(function (e) {
            //set all dice spans to 3
            $('.num_dice').text('3');
            e.preventDefault();
        });
	$('.minus a').click( function(event) {
		event.preventDefault();
		new_value=parseInt($(this).parent().next().text());
		new_value--;
		$(this).parent().next().text(new_value);
		skill_total = parseInt($(this).parent().siblings('td').find('.skill_total').text());
		skill_total--;
		$(this).parent().parent().find('.skill_total').text(skill_total);
		save_skill_values($(this).parent().parent());
	});

	$('.plus a').click( function(event) {
		event.preventDefault();
		new_value=parseInt($(this).parent().prev().text());
		new_value++;
		$(this).parent().prev().text(new_value);
		skill_total = parseInt($(this).parent().siblings('td').find('.skill_total').text());
		skill_total++;
		$(this).parent().parent().find('.skill_total').text(skill_total);
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
			$new_row = $('<tr> <td >'+$skill_name+'  </td> <td class="skill_points">1</td> <td class="skill_bonus">0</td> <td class="skill_total">1</td> <td class="roll_dice"><span class="num_dice">3</span>d6 + 1</td></tr>');
			$('#add_skill').closest('tr').prev().after($new_row);
			$('select[name="add_skill"]').children(':selected').remove();

		}
           })
        })
    });
</script>

@endsection
