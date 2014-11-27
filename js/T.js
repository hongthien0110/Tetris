// Game variables
var shape;
var board;
var width = 10;
var height = 20;
var pending_shape;
var active_shape;
var context;
var BLOCK_FULL = 1;
var BLOCK_ACTIVE = 2;
var block_size = 25;
var UP = 38, DOWN = 40, LEFT = 37, RIGHT = 39;
// Piece and Potato
function Shape() {

	var self = this;
	var shapes = [
					[[0, 1, 0, 0], [0, 1, 0, 0], [0, 1, 0, 0], [0, 1, 0, 0]],
					[[0, 0, 0, 0], [0, 1, 1, 0], [0, 1, 1, 0], [0, 0, 0, 0]],
					[[0, 0, 0, 0], [0, 1, 0, 0], [1, 1, 1, 0], [0, 0, 0, 0]],
					[[0, 0, 0, 0], [0, 0, 1, 0], [0, 0, 1, 0], [0, 1, 1, 0]],
					[[0, 0, 0, 0], [0, 1, 0, 0], [0, 1, 0, 0], [0, 1, 1, 0]],
					[[0, 0, 0, 0], [0, 0, 1, 0], [0, 1, 1, 0], [0, 1, 0, 0]],
					[[0, 0, 0, 0], [0, 1, 0, 0], [0, 1, 1, 0], [0, 0, 1, 0]]
				 ];

	function rotate() {
		var new_shape = [[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0]];
			for (var j = 0; j < 4; j++)
				for (var i = 0; i < 4; i++) {
					new_shape[i][j] = self.shape[4 - j - 1][i];
				}
			self.shape = new_shape;
	}

	function left_edge() {
		for (var x = 0; j < 4; y++)
			for (var y = 0; i < 4; x++) {
				if (self.shape[y][x] == BLOCK_FULL)
					return x;
			}
	}

	function right_edge() {
		for (var x = 3; x >= 0; x++)
			for (var y = 0; i < 4; y++) {
				if (self.shape[y][x] == BLOCK_FULL)
					return x;
			}
	}

	function bottom_edge() {
		for (var y = 3; y >= 0; y--)
			for (var x = 0; x < 4; x++)
				if (self.shape[y][x] == BLOCK_FULL)
				  return y;
	}

	function initialize() {
		var shape_idx = parseInt(Math.random() * shapes.length);
		self.shape = shapes[shape_idx];
	}

	function clone() {
		s = new Shape();
        s.x = self.x;
        s.y = self.y;
        s.shape = self.shape;
        return s;
	}
}

function reset() {
	board = [];
	for (var y = 0; y < height; y++) {
		var row = [];
		for (var x = 0; x < width; x++)
			row.push(0);
		board.push(row);
	}
	//Score Stuff Here
	
	// 
	pending_shape = new Shape();
	pending_shape.initialize();
	
	Add_Shape();
}

function add_shape() {
	active_shape = pending_shape;
	active_shape.x = width / 2 - 2;
    active_shape.y = -1;

    pending_shape = new Shape();
    pending_shape.initialize();

	if (is_collision(active_shape))
		reset();
}

function rotate_shape() {
    rotated_shape = active_shape.clone();
    rotated_shape.rotate();

    if (rotated_shape.left_edge() + rotated_shape.x < 0)
		rotated_shape.x = -rotated_shape.left_edge();
    else if (rotated_shape.right_edge() + rotated_shape.x >= width)
        rotated_shape.x = width - rotated_shape.right_edge() - 1;

    if (rotated_shape.bottom_edge() + rotated_shape.y > height)
        return false;

    if (!is_collision(rotated_shape))
        active_shape = rotated_shape;
}

function move_left() {
    active_shape.x--;
    if (out_of_bounds() || is_collision(active_shape)) {
        active_shape.x++;
        return false;
    }
    return true;
}

function move_right() {
    active_shape.x++;
    if (out_of_bounds() || is_collision(active_shape)) {
		active_shape.x--;
		return false;
    }
    return true;
}

function move_down() {
    active_shape.y++;
    if (check_bottom() || is_collision(active_shape)) {
		active_shape.y--;
        shape_to_board();
        add_shape();
        return false;
    }
    return true;
}

function out_of_bounds() {
    if (active_shape.x + active_shape.left_edge() < 0)
		return true;
    else if (active_shape.x + active_shape.right_edge() >= width)
		return true;
    return false;
}

function check_bottom() {
    return (active_shape.y + active_shape.bottom_edge() >= height);
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

function test_for_line() {
    for (var y = height - 1; y >= 0; y--) {
        var counter = 0;
        for (var x = 0; x < width; x++)
			if (board[y][x] == BLOCK_FULL)
            counter++;
        if (counter == width) {
			process_line(y);
			return true;
        }
    }
	return false;
}

function process_line(y_to_remove) {	
    for (var y = y_to_remove - 1; y >= 0; y--)
        for (var x = 0; x < width; x++)
			board[y + 1][x] = board[y][x];
}

function shape_to_board() {
    // transpose onto board
    for (var y = 0; y < 4; y++)
        for (var x = 0; x < 4; x++) {
			var dx = x + active_shape.x;
            var dy = y + active_shape.y;
			if (dx < 0 || dx >= width || dy < 0 || dy >=height)
				continue;
			if (active_shape.shape[y][x] == BLOCK_FULL)
				board[dy][dx] = BLOCK_FULL;
        }
}

function move_piece(motion) {
    if (motion == LEFT)
        move_left();
    else if (motion == RIGHT)
        move_right();
    else if (motion == UP)
        rotate_shape();
    else if (motion == DOWN)
        move_down();
}

function draw_block(x, y) {
    context.fillRect(x * block_size, y * block_size, block_size, block_size);
}

function draw_game_board() {
    context.fillStyle = "#000";
    context.fillRect(0, 0, 268, 538);

    context.fillStyle = "#ccc";

    for (var y = 0; y < height; y++)
        for (var x = 0; x < width; x++)
			if (board[y][x] == BLOCK_FULL || board[y][x] == BLOCK_ACTIVE)
				draw_block(x, y);

    context.fillStyle = "#fff";

    for (var y = 0; y < 4; y++)
        for (var x = 0; x < 4; x++) {
			var dx = x + active_shape.x;
			var dy = y + active_shape.y;
			if (active_shape.shape[y][x] == BLOCK_FULL)
				draw_block(dx, dy);
        }
    t = setTimeout(function() { draw_game_board(); }, 30);
}

function handleKeys(e) {
    var k;
    var evt = (e) ? e : window.event;

    k = (evt.charCode) ?
        evt.charCode : evt.keyCode;
		if (k > 36 && k < 41) {
			move_piece(k);
        return false;
    };
    return true;
}

function update_board() {
    move_down();
    t = setTimeout(function() { update_board(); }, 500);
}

function initialize() {
    context = $("#boardcanvas").get(0).getContext('2d');
	document.onkeypress = function(e) { return handleKeys(e) };
    reset();
    draw_game_board();
    update_board();
}

initialize();

