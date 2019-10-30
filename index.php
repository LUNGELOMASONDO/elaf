<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		<title> ELaF - Lost and found at your fingertips </title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/popper.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<style>
			.carousel{
				background: #2f4357;
				margin-top: 20px;
			}
			.carousel-item{
				text-align: center;
				min-height: 280px; /* Prevent carousel from being distorted if for some reason image doesn't load */
			}
			.bs-example{
				margin: 20px;
			}
			.carousel-inner img{
				width: 100%;
				height: 100%;
			}
			@font-face {
				font-family: pac;
				src: url(css/Pacifico-Regular.ttf);
			}
			.campus {
				font-family: pac;
				cursor: pointer;
			}
			@media screen and (min-width:400px){
				.campus{
					padding-bottom: 10px;
				}
			}
		</style>
	</head>

	<body>
		<!-- nav bar -->
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
  			<a class="navbar-brand" href="index.php">ELaF</a>
  			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    			<span class="navbar-toggler-icon"></span>
  			</button>
  			<div class="collapse navbar-collapse" id="navbarNavDropdown">
			    <ul class="navbar-nav">
	      			<li class="nav-item">
				    		<a class="nav-link" href="lostsomething.php">Lost something?</a>
			        </li>
		    	    <li class="nav-item">
			    	    <a class="nav-link" href="foundsomething.php">Found something?</a>
	      			</li>
	      			<li class="nav-item">
	      				<a class="nav-link" href="adminquickaccess.php">Lost & Found Box</a>
	      			</li>
    			</ul>
  			</div>
		</nav>
		<!-- content -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-2">

				</div>
				<div class="col-lg-8">
					<div class="bs-example">
						<div id="myCarousel" class="carousel slide" data-interval="3000" data-ride="carousel">
							<!-- Carousel indicators -->
							<ol class="carousel-indicators">
								<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
								<li data-target="#myCarousel" data-slide-to="1"></li>
								<li data-target="#myCarousel" data-slide-to="2"></li>
							</ol>
							<!-- Wrapper for carousel items -->
							<div class="carousel-inner">
								<div class="carousel-item active">
									<img src="img/lostandfound.jpg" alt="First Slide">
									<div class="carousel-caption">
										<h1>ELaF</h1>
										<p> Electronic Lost and Found </p>
									</div>
								</div>
								<div class="carousel-item">
									<img src="img/jcr_content.jpg" alt="Second Slide">
								</div>
								<div class="carousel-item">
									<img src="img/0.jpg" alt="Third Slide">
									<div class="carousel-caption">
										<h1>For NWU students</h1>
										<p>by NWU students</p>
									</div>
								</div>
							</div>
							<!-- Carousel controls -->
							<a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
								<span class="carousel-control-prev-icon"></span>
							</a>
							<a class="carousel-control-next" href="#myCarousel" data-slide="next">
								<span class="carousel-control-next-icon"></span>
							</a>
						</div>
					</div>
				</div>
				<div class="col-lg-2">

				</div>
			</div>
	</body>
</html>
