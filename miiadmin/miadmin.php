<?php

error_reporting(E_ALL ^ E_NOTICE);
require_once("connect.php");

session_start();

if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'] != ''){
	$id = $_COOKIE['user_id'];
}else if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){
	$id = $_SESSION['user_id'];
}else{
	header('location: index.php');
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Nayya Humman Store | Dashboard</title>
	<meta name="keywords" content="men, women, clothing, home" />
	<meta name="author" content="Victory Webstore"/>
	<!-- Favicon -->
	<link rel="shortcut icon" type="image/png" href="../logo/logoToko.jpeg" />
	
	<!-- mobile specific -->
	<meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1" />
	<!-- CSS Offline -->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="css/miiadmin.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
	<link href="https://fonts.googleapis.com/css2?family=Lobster&family=Permanent+Marker&display=swap" rel="stylesheet">
	
	
</head>
<body>
	<?php
	$query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '".$id."'");
	$data = mysqli_fetch_array($query);
	?>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<a href="#main-toggle" id="menu-toggle" class="sidebar-toggle">
					<span class="sr-only">Toggle Navigation</span>
				</a>
				<a href="#" class="navbar-brand" style="color: white;font-family: Permanent Marker, cursive;"><strong><i>Nayya Humam Store</i></strong></a>
			</div>
			
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown user-menu">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-users"></i> <?php echo $data['fullname']; ?> <span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="profil.php?id=<?php echo $id;?>"><i class="fa fa-user"></i> Profil</a></li>
						<li><a href="logout.php"><i class="fa fa-sign-out"></i> Keluar</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
	
	<div id="wrapper">
		<nav id="sidebar-wrapper">
			<ul class="sidebar-nav">
				<li class="active"><a href="miadmin.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li><a href="order_confirmation.php"><i class="fa fa-shopping-cart"></i> Pesanan</a></li>
			<li class="sidebar-child"><a href="#"><i class="fa fa-th"></i> Data Produk <i class="sidebar-fa fa fa-angle-down pull-right"></i></a>
					<ul class="sidebar-second-child">
						<li ><a href="brands.php">Merek Produk</a></li>
						<li><a href="categories.php">Kategori Produk</a></li>
						<li><a href="subcategories.php">Sub kategori Produk</a></li>
						<li><a href="colors.php">Warna Produk</a></li>
						<li><a href="product.php">Data ALL Produk</a></li>
					</ul>
				</li>
				<li class="sidebar-child"><a href="#"><i class="fa fa-th"></i> Laporan <i class="sidebar-fa fa fa-angle-down pull-right"></i></a>
					<ul class="sidebar-second-child">
						<li><a href="item_report.php">Laporan Data Produk</a></li>
						<li><a href="item_catsubcat_report.php">Laporan Data Produk Berdasarkan Kategori / Subkategori</a></li>
						<li><a href="order_report.php">Laporan Data Pemesanan</a></li>
						<li><a href="order_report_bydate.php">Laporan Data Pemesanan Berdasarkan Tanggal</a></li>
						
					</ul>
				</li>
		
				<li><a href="http://localhost/nayya_humam" target="_blank"><i class="fa fa-globe"></i> Home</a></li>
			</ul>
		</nav>
		<?php 
		include "connect.php";
		$sql = "SELECT (
					SELECT counter_visit FROM counter WHERE DATE(counter_date) = DATE(CURRENT_DATE)
				) AS today,
					(SELECT counter_visit FROM counter WHERE DATE(counter_date) = DATE(CURRENT_DATE - INTERVAL 1 DAY)
				) AS yesterday,
					(SELECT SUM(counter_visit) FROM counter WHERE WEEKOFYEAR(counter_date) = WEEKOFYEAR(CURRENT_DATE - INTERVAL 1 WEEK)
				) AS last_week,
					(SELECT SUM(counter_visit) FROM counter WHERE WEEKOFYEAR(counter_date) = WEEKOFYEAR(CURRENT_DATE)
				) AS this_week,
					(SELECT SUM(counter_visit) FROM counter WHERE MONTH(counter_date) = MONTH(CURRENT_DATE) AND YEAR(counter_date) = YEAR(CURRENT_DATE)
				) AS this_month,
					(SELECT SUM(counter_visit) FROM counter WHERE YEAR(counter_date) = YEAR(CURRENT_DATE)
				) AS this_year";
		$query = mysqli_query($conn, $sql);
		$visit = mysqli_fetch_array($query);
		?>
		<div id="page-content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
							<legend><h1 style="color: black;" ><center>Dashboard</h1></legend>
						<div class="col-md-12">
							<div class="panel panel-default">
								<div style="background-color:#008080; border-radius:10px;" class="panel-body">
									<div class="canvas-wrapper text-center">
										<h1 style="color:white;letter-spacing:2px;font-family: Permanent Marker, cursive;"><b>Selamat Datang Di</b></h1>
										<h2 style="color:white;font-family: Permanent Marker, cursive;"> Aplikasi Nayya Humam Store</h2>
										<h3 style="color:white;font-family: Permanent Marker, cursive;">Alamat: Rambai kota Pariaman</h3>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br>
			<div class="canvas-wrapper text-center">
						<div class="col-xs-3 counters" >
							<i class="fa fa-users"></i> <h4> Total Pengunjung Hari Ini</h4>
							<div class="counter_hit"><strong>
								<?php 
								if(!empty($visit['today'])){
									echo $visit['today'];
								} else {
									echo '0';
								}
								?>
								</strong>
							</div>
						</div>
						<div class=" col-xs-3 counters">
							<i class="fa fa-users"></i> <h4>Total Pengunjung Minggu Ini</h4></span>
							<div class="counter_hit"><strong>
								<?php 
								if(!empty($visit['this_week'])){
									echo $visit['this_week'];
								} else {
									echo '0';
								}
								?>
								</strong>
							</div>
						</div>
						<div class="col-xs-3 counters">
							<i class="fa fa-users"></i> <h4>Total Pengunjung Bulan Ini</h4></span>
							<div class="counter_hit"><strong>
								<?php 
								if(!empty($visit['this_month'])){
									echo $visit['this_month'];
								} else {
									echo '0';
								}
								?>
								</strong>
							</div>
						</div>
						<div class=" col-xs-3 counters">
							<i class="fa fa-users"></i> <h4>Total Pengunjung Tahun Ini</h4></span>
							<div class="counter_hit"><strong>
								<?php 
								if(!empty($visit['this_year'])){
									echo $visit['this_year'];
								} else {
									echo '0';
								}
								?>
								</strong>
							</div>
						</div> 
			</div>
		</div>
	</div>
	<footer class="footer-bottom">
		<div class="footer-right">
			All rights reserved &copy; <?= date('Y'); ?> | PRATIWI ERVIA NORA 
		</div>
		<div class="clearfix"></div>
	</footer>
	
	<!-- JS Offline -->
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
	
</body>
</html>
