var requestAnimationFrame = window.requestAnimationFrame || 
								window.mozRequestAnimationFrame || 
								window.webkitRequestAnimationFrame || 
								window.msRequestAnimationFrame;
var width = 10;
var height = 20;
var block_size = 25;
var margin_size = 2; 
var board;
var next_shape;
var current_shape;
var context;
var ncontext;
var level;
var score;
var lines;
var pause = 0;
var LockKey;
var gameover;
var color = ["gray", "yellow", "cyan", "magenta", "mediumblue", "darkorange", "red", "lime"];

//load board data first
$.ajax({                                      
    url: 'load_game.php',         
    dataType: 'json',          
    success: function(data)        
	{
		save = data[1];           
	} 
});

// arrow keys
var UP = 38, DOWN = 40, LEFT = 37, RIGHT = 39;
	
function Shape() {

	this.shapes = [
					[[0, 0, 0, 0], [0, 1, 1, 0], [0, 1, 1, 0], [0, 0, 0, 0]],
					[[0, 2, 0, 0], [0, 2, 0, 0], [0, 2, 0, 0], [0, 2, 0, 0]],
					[[0, 0, 0, 0], [0, 3, 0, 0], [3, 3, 3, 0], [0, 0, 0, 0]],
					[[0, 0, 0, 0], [0, 0, 4, 0], [0, 0, 4, 0], [0, 4, 4, 0]],
					[[0, 0, 0, 0], [0, 5, 0, 0], [0, 5, 0, 0], [0, 5, 5, 0]],
					[[0, 0, 0, 0], [0, 0, 6, 0], [0, 6, 6, 0], [0, 6, 0, 0]],
					[[0, 0, 0, 0], [0, 7, 0, 0], [0, 7, 7, 0], [0, 0, 7, 0]]
				 ];

	this.rotate = function() {
		var new_shape = [[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0]];

    for (var j = 0; j < 4; j++)
		for (var i = 0; i < 4; i++) {
			new_shape[i][j] = this.shape[4 - j - 1][i];
		}

    this.shape = new_shape;
	}

	this.left_edge = function() {
		for (var x = 0; x < 4; x++)
			for (var y = 0; y < 4; y++)
				if (this.shape[y][x])
					return x;
	}

	this.right_edge = function() {
		for (var x = 3; x >= 0; x--)
			for (var y = 0; y < 4; y++)
				if (this.shape[y][x])
					return x;
	}	

	this.bottom_edge = function() {
		for (var y = 3; y >= 0; y--)
			for (var x = 0; x < 4; x++)
				if (this.shape[y][x])
					return y;
	}
	
	this.initialize = function() {
		this.shape_num = parseInt(Math.random() * this.shapes.length);
		this.shape = this.shapes[this.shape_num];
	}

	this.clone = function() {
		s = new Shape();
		s.x = this.x;
		s.y = this.y;
		s.shape = this.shape;
		return s;
	}
}
// game mechanics
function reset() {
	gameover = 0;
	LockKey = 0;
	context.clearRect(0, 0, 268, 538);
	board = [];
	for (var y = 0; y < height; y++) {
		board[y] = [];
		for (var x = 0; x < width; x++)
			board[y][x] = 0;
	}

	score = 0;
	lines = 0;
	level = 1;
	
	$(".level").html(level);
	$(".score").html(score);
	
	next_shape = new Shape();
	next_shape.initialize();

	add_shape();
}

function add_shape() {
	current_shape = next_shape;
	current_shape.x = width / 2 - 2;
	current_shape.y = -1;

	next_shape = new Shape();
	next_shape.initialize();
	next_shape.x = width / 2 - 2;
	next_shape.y = (next_shape.shape[0][1]) ? 1 : 0.5;

	if (is_collision(current_shape)) {
		gameover = 1;
		$(".bload").prop("disabled",true);
		$(".bsave").prop("disabled",true);
		$(".bpause").prop("disabled",true);
	}
}

function rotate_shape() {
	rotated_shape = current_shape.clone();
	rotated_shape.rotate();

	if (rotated_shape.left_edge() + rotated_shape.x < 0)
		rotated_shape.x = -rotated_shape.left_edge();
	else if (rotated_shape.right_edge() + rotated_shape.x >= width)
		rotated_shape.x = width - rotated_shape.right_edge() - 1;

	if (rotated_shape.bottom_edge() + rotated_shape.y > height)
		return false;

	if (!is_collision(rotated_shape))
		current_shape = rotated_shape;
}	

function move_left() {
	current_shape.x--;
	if (out_of_bounds() || is_collision(current_shape)) {
		current_shape.x++;
		return false;
	}
	return true;
}

function move_right() {
	current_shape.x++;
	if (out_of_bounds() || is_collision(current_shape)) {
		current_shape.x--;
		return false;
	}
	return true;
}

function move_down() {
	current_shape.y++;
	if (check_bottom() || is_collision(current_shape)) {
		current_shape.y--;
		shape_to_board();
		add_shape();
		return false;
	}
	return true;
}

function ipause() {
	pause = !pause ? 1 : 0;
}

function out_of_bounds() {
	if (current_shape.x + current_shape.left_edge() < 0)
		return true;
	else if (current_shape.x + current_shape.right_edge() >= width)
		return true;
	return false;
}

function check_bottom() {
	return (current_shape.y + current_shape.bottom_edge() >= height);
}

function is_collision(shape) {
	for (var y = 0; y < 4; y++)
		for (var x = 0; x < 4; x++) {
			if (y + shape.y < 0)
				continue;
			if (shape.shape[y][x] && board[y + shape.y][x + shape.x])
				return true;
        }
	return false;
}

function check_line() {
	for (var y = height - 1; y >= 0; y--) {
		var counter = 0;
		for (var x = 0; x < width; x++)
			if (board[y][x])
				counter++;
		if (counter == width) {
			clear_line(y);
			score += 200;
			return true;
		}
	}
	$(".score").html(score);
	return false;
}

function clear_line(y_to_remove) {
	lines++;
	if ((lines % 10 == 0) && level < 15) {
		level++;
		$(".level").html(level);
	}
	for (var y = y_to_remove - 1; y >= 0; y--)
		for (var x = 0; x < width; x++)
			board[y + 1][x] = board[y][x];
}

function shape_to_board() {
	// Save shape into board
	for (var y = 0; y < 4; y++)
		for (var x = 0; x < 4; x++) {
			var dx = x + current_shape.x,
			dy = y + current_shape.y;
			if (dx < 0 || dx >= width || dy < 0 || dy >=height)
				continue;
			if (current_shape.shape[y][x])
				board[dy][dx] = current_shape.shape[y][x];
		}
	while (check_line()) {}
}

// render
function draw_board() {
	for (var i=0; i<10; i++) {
		for (var j=0; j<20; j++) {
			context.fillStyle = color[board[j][i]];
			var x = (i*block_size)+(margin_size*i);
			var y = (j*block_size)+(margin_size*j);
			context.fillRect(x, y, block_size, block_size);
		}
	}
}
	
function draw_game_board() {
	if (!pause) {
		draw_board();
		draw_next_shape();
		for (var y = 0; y < 4; y++)
			for (var x = 0; x < 4; x++) {
				context.fillStyle = color[current_shape.shape[y][x]];
				var dx = x + current_shape.x;
				var dy = y + current_shape.y;
			if (current_shape.shape[y][x])
				draw_block(dx, dy, context);
		}
	}
	if (!gameover)
		requestAnimationFrame(draw_game_board);
	else if (gameover) {
		context.clearRect(0, 0, 268, 538);
		context.font = "40px Georgia";
		context.fillText("GAME OVER", 10, 50);
		context.font = "20px Georgia";
		context.fillText("Your Score: " + score, 10, 100);
		context.fillText("Press ENTER to continue", 10, 125);
	}
}

function draw_block(x, y, ctx) {
    ctx.fillRect(x * block_size + x * margin_size, y * block_size + y * margin_size, block_size, block_size);
}

function draw_next_shape() {
	ncontext.clearRect(0, 0, 268, 156);
	for (var y = 0; y < 4; y++)
		for (var x = 0; x < 4; x++) {
			ncontext.fillStyle = color[next_shape.shape[y][x]];
			var dx = x + next_shape.x;
			var dy = y + next_shape.y;			
		if (next_shape.shape[y][x])
			draw_block( dx , dy, ncontext);
    }
}

//save & load
function save_game() {
	save = "";
	// save board
	for (var j = 0; j < height; j++) 
		for (var i = 0; i < width; i++)
			save += board[j][i];
	// save current shape x-position
	if (current_shape.x < 10) // if < 10, attach "0", e.g : x = 2 -> "02"
		save += "0"
	save += current_shape.x;
	// save current shape y-position.
	if (current_shape.y < 10) //like x
		save += "0"
	save += current_shape.y;
	// save current shape type and rotation
	for ( i = 0; i < 4; i++) 
		for ( j = 0; j < 4; j++)
			save += current_shape.shape[i][j];
	// save next shape type
	save += next_shape.shape_num;
	$.post(    
		'save_game.php',
		{
			'saved' : save,
			'score' : score,
			'level' : level,
			'line' : lines
		},
		function(data) {
		// this is for testing
		alert(data);
		}
    );
}

function load_game() {
	$.post(                                     
		"load_game.php",                  
		function(data)        
		{
			load = (data[1]);
			score = parseInt(data[2]);        
			level = parseInt(data[3]);
			lines = parseInt(data[4]);
			$(".level").html(level);
			$(".score").html(score);

			if (load != "") {
				alert ("Loading success");
				var res = "";
				var count = 0;
				for (var i = 0; i < height; i++) 
					for (var j = 0; j < width; j++) {
						res = load.slice(count, count+1);
						board[i][j] = parseInt(res);
						count++;
					}
			// load x
				res = load.slice(200, 202);
				current_shape.x = parseInt(res);
			//load y
				res = load.slice(202, 204);
				current_shape.y = parseInt(res);
				count = 204;
			// load current shape type and rotation
				for ( i = 0; i < 4; i++) 
					for ( j = 0; j < 4; j++) {
						res = load.slice(count, count+1);
						current_shape.shape[i][j] = parseInt(res);
						count++;
					}
			// load next shape type
				res = load.slice(220, 221);
				next_shape.shape = next_shape.shapes[parseInt(res)];
			}		
		},
		"json"
	);
	/*
//	if (load == "")
//		alert("No data loaded");
//	else {
	//	$.ajax({                                      
	//	url: 'load_game.php',         
	//	dataType: 'json',          
	//	success: function(data)        
	//	{;           
	//		score = parseInt(data[2]);        
	//		level = parseInt(data[3]);
	//		lines = parseInt(data[4]);
	//		$(".level").html(level);
	//		$(".score").html(score);
	//	} 
	//	});
		var count = 0;
		var res = "";
		// load board

//	}*/
}

//control
function move_piece(dir) {
	if (dir == LEFT)
		move_left();
	else if (dir == RIGHT)
		move_right();
	else if (dir == UP)
		rotate_shape();
	else if (dir == DOWN)
		move_down();
}

function keydown(e){
    var keycode = e.keyCode;
	if ((keycode > 36 && keycode < 41) && !LockKey)
		move_piece(keycode);
	if (gameover && keycode == 13)
		initialize();
	if (keycode == 80 && !gameover) {
		LockKey = !LockKey;
		ipause();
	}
}

$(".bleft").click( function() {
        move_piece(37);
		$(".bleft").blur()
    }
);

$(".bright").click( function() {
        move_piece(39);
		$(".bright").blur()
    }
);

$(".bdown").click( function() {
        move_piece(40);
		$(".bdown").blur()
    }
);

$(".brotate").click( function() {
        move_piece(38);
		$(".brotate").blur()
    }
);

$(".bsave").click( function() {
        save_game();
		$(".bsave").blur()
    }
);

$(".bload").click( function() {
        load_game();
		$(".bload").blur()
    }
);

$('.modal').on('show.bs.modal', function (e) {
	ipause();
})

$('.modal').on('hidden.bs.modal', function (e) {
	ipause();
})
	
document.addEventListener('keydown', keydown, false);

//run
function update_board() {
	if (!gameover)
		setTimeout(function() {
			if (!pause)
				move_down();
			requestAnimationFrame(update_board);
		}, 860 - (50 * level));
}

function initialize() {
	context = $("#boardcanvas").get(0).getContext('2d');
	ncontext = $("#nextcanvas").get(0).getContext('2d');
	$(".bload").prop("disabled",false);
	$(".bsave").prop("disabled",false);
	$(".bpause").prop("disabled",false);
	reset();
	draw_game_board();
	update_board();
}

initialize();