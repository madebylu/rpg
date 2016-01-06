$(document).ready(function() {
      function clone(o) {return $.extend(true,{},o);}

      var Line = function(startX, startY, endX, endY, line_style){
        this.startX = startX;
        this.startY = startY;
        this.endX = endX;
        this.endY = endY;
        this.line_style = line_style;
        this.draw = function(context){
          context.beginPath();
          context.moveTo(this.startX, this.startY);
          context.lineTo(this.endX, this.endY);
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
        this.draw = function(context){
          context.fillStyle = this.fill_style;
          context.fillRect(this.startX, this.startY, this.w, this.h);
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
        this.draw = function(context){
          context.beginPath();
          context.fillStyle = this.fill_style;
          context.arc(this.posX , this.posY, 20 , 0 , 2*Math.PI, false);
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
        for (i = 0; i<background_items.length; i++){
          background_items[i].draw(context);
        }
        for (i = 0; i<foreground_items.length; i++){
          foreground_items[i].draw(context);
        }
        for (i = 0; i<actors.length; i++){
          actors[i].draw(context);
        }
      }

      function push_shape_to_active_layer(shape){
        if(active_layer == "background"){
          background_items.push(clone(shape));
        }
        if(active_layer == "foreground"){
          foreground_items.push(clone(shape));
        }
        if(active_layer == "actors"){
          actors.push(clone(shape));
        }
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
        if(active_layer == "background"){
          background_items = [];
        }
        if(active_layer == "foreground"){
          foreground_items = [];
        }
        if(active_layer == "actors"){
          actors = [];
        }
        refresh_canvas(context);
      });
      $('input[name="layer"]').on('change', function(event){
        active_layer = $(this).val();
      });

      //set starting points
      $('#drawing-board').on('mousedown', function(event) {
        event.preventDefault();
        if(canvas_click_action == "line"){
          next_line.set_start(event.offsetX, event.offsetY, $('#drawing-colour').val());
        }
        if(canvas_click_action == "squiggle"){
          next_line_section.set_start(event.offsetX, event.offsetY, $('#drawing-colour').val());
        }
        if(canvas_click_action == "rect"){
          next_rect.set_start(event.offsetX, event.offsetY, $('#drawing-colour').val());
        }
        //try and "grab" an actor / token if it's nearby.
        if(canvas_click_action == "move_actor"){
          for (i=0; i<actors.length; i++){
            diff_x = actors[i].posX - event.offsetX;
            diff_y = actors[i].posY - event.offsetY;
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
            next_line.set_end(event.offsetX, event.offsetY);
            next_line.draw(context);
          }
          if(canvas_click_action == "squiggle"){
            next_line_section.set_end(event.offsetX, event.offsetY);
            next_line_section.draw(context);
            background_items.push(clone(next_line_section));
            //this is a special case, set next line section to start where this one ends
            next_line_section.set_start(event.offsetX, event.offsetY, $('#drawing-colour').val());

          }
          if(canvas_click_action == "rect"){
            next_rect.set_width_and_height(event.offsetX, event.offsetY);
            next_rect.draw(context);
          }
          if(canvas_click_action == "move_actor"){
            var safe_move = true;
            for (i=0; i<actors.length; i++){
              diff_x = actors[i].posX - event.offsetX;
              diff_y = actors[i].posY - event.offsetY;
              if (i != active_actor_index && Math.pow(diff_x,2) + Math.pow(diff_y, 2) < 400){ //400 is r squared
                console.log('colission');
                safe_move = false;
              }
            }
            if (safe_move){
              actors[active_actor_index].posX = event.offsetX;
              actors[active_actor_index].posY = event.offsetY;
            }
          }

        }
      });

      //finalise 'shape'
      $('#drawing-board').on('mouseup', function(event) {
        if(canvas_click_action == "line"){
          next_line.set_end(event.offsetX, event.offsetY);
          push_shape_to_active_layer(next_line);
          next_line.reset();
        }
        if(canvas_click_action == "squiggle"){
          next_line_section.set_end(event.offsetX, event.offsetY);
          push_shape_to_active_layer(next_line_section);

        }
        if(canvas_click_action == "rect"){
          next_rect.set_width_and_height(event.offsetX, event.offsetY);
          next_rect.draw(context);
          push_shape_to_active_layer(next_rect);
        }
        if(canvas_click_action == "add_actor"){
          next_actor = new Actor(event.offsetX, event.offsetY, $('#drawing-colour').val());
          next_actor.draw(context);
          push_shape_to_active_layer(next_actor);
        }

        drawing = false;
        refresh_canvas(context);
      });
    });
