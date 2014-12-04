<!DOCTYPE html>
<html>
    <head>
		<title>Tetris</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<style>		
			div {
				margin-top: 9px;
			}
			
			#load {
				margin-top: 20px;
			}
			
			.bot {
				margin-top: 0px;
			}
			
			.xs-well {
				font-size: 12px;
			}
			
			body {
				background-color: #303030 ;
				overflow:hidden;
			}
			
			@media (max-width: 1024px) { 
				div[class^="col"]{padding-left:10px; padding-right:10px;}
			}
			
			@media (max-width: 921px) {
				canvas {
					background-color: black;
					width: 100%;
					height: auto;
					border: solid 5px black;
				}
			}
			
			canvas {
				background-color: black;
				border: solid 5px black;
			}
			
			#nextcanvas {
				background-color: #eee;
			}
			
			@media (min-width: 1024px) { 
				#nextcanvas {
					width: 100%;
					height:20%;
				}
			}
			
			.container-fluid {
				margin-top: 0px;
			}

		</style>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-11 visible-lg">
					<div class="btn-group pull-right">
						<a href="#" class="btn btn-sm btn-default"><?php
						session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.htm");
							include 'connect.php';
							$user=mysqli_query($conn, "SELECT * FROM user WHERE user_id=".$_SESSION['user_id']);
							if (mysqli_num_rows($user) != 0) {
								while ($row = mysqli_fetch_array($user)) {
									echo $row['username'];
								}
							}
						?></a>
						
						<button class="btn btn-sm btn-default" id="logout">Logout</button>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-11 visible-md">
					<div class="btn-group pull-right">
						<a href="#" class="btn btn-sm btn-default"><?php
							$user=mysqli_query($conn, "SELECT * FROM user WHERE user_id=".$_SESSION['user_id']);
							if (mysqli_num_rows($user) != 0) {
								while ($row = mysqli_fetch_array($user)) {
									echo $row['username'];
								}
							}
						?></a>
						<button class="btn btn-sm btn-default" id="logout">Logout</button>
					</div>
				</div>
				<div class="visible-lg col-lg-3  col-lg-offset-1">
					<div class="panel panel-warning info">
						<div class="panel-heading text-center "><strong>TOP 10<strong></div>
						<div class="panel-body" >
							<ol class="text-primary" id="top10">
								<?php
    $user_score=mysqli_query($conn, "SELECT * FROM user, user_score WHERE user.user_id=user_score.user_id ORDER BY user_score.high_score DESC LIMIT 0,10;");
    if (mysqli_num_rows($user_score) > 0){
        while($row=mysqli_fetch_array($user_score)){
                echo "<li>".$row['username']."<span class='pull-right'>".$row['high_score']."<span></li>";
            }
        }
?>								
							</ol>
						</div>
					</div>
					<div class="well well-sm">
						<h4 class="text-center"><small>GAME MANUAL</small></h4>
						<ul class="text-info">
							<li>Press "p" to pause the game</li>
							<li>Press right or left arrow key to move the tetrominoes</li>
							<li>Press up arrow key to rotate the tetrominoes</li>
							<li>Press down arrow key to drop the tetrominoes faster</li>
						</ul>
					</div>
				</div>
				<div class="visible-md col-md-4">
					<div class="panel panel-warning">
						<div class="panel-heading text-center "><strong>TOP 10<strong></div>
						<div class="panel-body" id="top10">
							<ol class="text-primary">
								<?php

    $user_score=mysqli_query($conn, "SELECT * FROM user, user_score WHERE user.user_id=user_score.user_id ORDER BY user_score.high_score DESC LIMIT 0,10;");
    if (mysqli_num_rows($user_score) > 0){
        while($row=mysqli_fetch_array($user_score)){
                echo "<li>".$row['username']."<span class='pull-right'>".$row['high_score']."<span></li>";
            }
        }
?>								
							</ol>
						</div>
					</div>
					<div class="well well-xs">
						<h4 class="text-center"><small>GAME MANUAL</small></h4>
						<ul class="text-info">
							<li>Press "p" to pause the game</li>
							<li>Press right or left arrow key to move the tetrominoes</li>
							<li>Press up arrow key to rotate the tetrominoes</li>
							<li>Press down arrow key to drop the tetrominoes faster</li>
						</ul>
					</div>
				</div>
				<div id="canvascontainer" class="col-xs-8 col-sm-8 col-md-4 col-lg-4">
					<canvas id="boardcanvas" class="center-block" width="268" height="538"></canvas>
				</div>
				<div class="col-xs-4 col-sm-4 col-md-4 col-lg-3">
					<canvas id="nextcanvas" width="268" height="156">
					</canvas>
					<div class="well well-sm hidden-xs">
						<p class="text-primary">Level<span class="pull-right level"></span></p>
					</div>
					<div class="well well-sm visible-xs">
						<p class="text-primary text-center xs-well level">Level <br>1</br></p>
					</div>
					<div class="well well-sm hidden-xs">
						<p class="text-primary">Score<span class="pull-right score">0</span></p>
					</div>
					<div class="well well-sm visible-xs">
						<p class="text-primary text-center xs-well score">Score <br>0</br></p>
					</div>
					<div class="well well-sm hidden-xs highscore">
						<?php
    $user=mysqli_query($conn, "SELECT * FROM user_score WHERE user_score.user_id=".$_SESSION['user_id'].";");
    if (mysqli_num_rows($user) > 0){
        while($row=mysqli_fetch_array($user)){
                echo '<p class="text-danger">Highest Score<span class="pull-right">'.$row["high_score"].'</span></p>';
            }
        }else echo '<p class="text-danger">Highest Score<span class="pull-right">0</span></p>';
?>
					</div>
					<div class="well well-sm visible-xs highscore">
						<?php
    $user=mysqli_query($conn, "SELECT * FROM user_score WHERE user_score.user_id=".$_SESSION['user_id'].";");
    if (mysqli_num_rows($user) > 0){
        while($row=mysqli_fetch_array($user)){
                echo '<p class="text-danger text-center" style="font-size: 10px; line-height: 19px; ">Highest score <br>'.$row["high_score"].'</br></p>';
            }
        }else echo '<p class="text-danger">Highest Score<span class="pull-right">0</span></p>';
?>
					</div>
					<div class="visible-lg">
						<button class="btn btn-primary btn-lg btn-block bsave"><strong>SAVE</strong>
						</button>
						<button class="btn btn-primary btn-lg btn-block bload" id="load"><strong>LOAD</strong>
						</button>
					</div>
					<div class="visible-md">
						<button class="btn btn-primary btn-lg btn-block bsave"><strong>SAVE</strong>
						</button>
						<button class="btn btn-primary btn-lg btn-block bload" id="load"><strong>LOAD</strong>
						</button>
					</div>
					<div class="visible-sm">
					</div>
					<div class="visible-xs">
						<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target=".modal">
							<span class="glyphicon glyphicon-pause">
						</button>
					</div>
					<div class="modal fade">
						<div class="modal-dialog modal-sm">
							<div class="modal-content">
								<div class="modal-header">
									<ul class="nav nav-pills">
										<li class="disabled"><a href="#">Username</a></li>
										<li class="active"><a href="#guide" data-toggle="tab">Guide</a></li>
										<li><a href="#Top" data-toggle="tab">Top 10</a></li>
									</ul>
								</div>
								<div class="modal-body">
									<div class="tab-content">
										<div class="tab-pane active" id="guide">
											<ul class="text-info">
												<li>Press right or left arrow button to move the tetrominoes</li>
												<li>Press rotate button to rotate the tetrominoes</li>
												<li>Press down arrow key to drop the tetrominoes faster</li>
											</ul>
										</div>
										<div class="tab-pane" id="Top">
											<ol class="text-warning">
												<?php
    $user_score=mysqli_query($conn, "SELECT * FROM user, user_score WHERE user.user_id=user_score.user_id ORDER BY user_score.high_score DESC LIMIT 0,10;");
    if (mysqli_num_rows($user_score) > 0){
        while($row=mysqli_fetch_array($user_score)){
                echo "<li>".$row['username']."<span class='pull-right'>".$row['high_score']."<span></li>";
            }
        }
?>								
											</ol>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-primary bsave">Save</button>
									<button type="button" class="btn btn-primary bload">Load</button>
									<button type="button" class="btn btn-primary" data-dismiss="modal">
										<span class="glyphicon glyphicon-play">
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row visible-xs">
				<div class="btn-group pull-right">
					<button class="btn btn-lg btn-primary bleft"><span class="glyphicon glyphicon-arrow-left"></span>
					</button>
					<button class="btn btn-lg btn-primary bright"><span class="glyphicon glyphicon-arrow-right"></span>
					</button>
				</div>
				<div class="btn-group pull-left">
					<button class="btn btn-lg btn-primary brotate"><span class="glyphicon glyphicon-repeat"></span>
					</button>
					<button class="btn btn-lg btn-primary bdown"><span class="glyphicon glyphicon-arrow-down"></span>
					</button>
				</div>
			</div>

		</div>
		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/Tetris.js"></script>
		<script>
        $(document).ready(function(){
            $("#logout").click(function(){
                var answer = confirm("Do you want to logout?");
                if(answer) self.location='logout.php';
                });
        });
	</script>
	</body>
</html>