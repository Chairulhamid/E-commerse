<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once("connect.php");

ob_start();
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
	<title>Nayya Humam Store | Laporan Data </title>
	<meta name="keywords" content="men, women, clothing, home" />
	<meta name="author" content="Victory Webstore"/>
	
	<!-- mobile specific -->
	<meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1" />
	
	<!-- Favicon -->
	<link rel="shortcut icon" type="image/png" href="../logo/logoToko.jpeg" />
	
	<!-- CSS Offline -->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="css/miiadmin.css" />
	<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
	<link href="https://fonts.googleapis.com/css2?family=Lobster&family=Permanent+Marker&display=swap" rel="stylesheet">

</head>
<body>
	<?php
	$queryname = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '".$id."'");
	$name = mysqli_fetch_array($queryname);
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
						<i class="fa fa-users"></i> <?php echo $name['fullname']; ?> <span class="caret"></span>
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
		<aside id="sidebar-wrapper">
			<ul class="sidebar-nav">
				<li><a href="miadmin.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li><a href="order_confirmation.php"><i class="fa fa-shopping-cart"></i> Pesanan</a></li>
				<li class="sidebar-child"><a href="#"><i class="fa fa-th"></i>Data  Produk  <i class="sidebar-fa fa fa-angle-down pull-right"></i></a>
					<ul class="sidebar-second-child">
						<li><a href="brands.php">Merek Produk</a></li>
						<li><a href="categories.php">Kategori Produk</a></li>
						<li><a href="subcategories.php">Sub Kategori Produk</a></li>
						<li><a href="colors.php">Warna Produk</a></li>
						<li><a href="product.php">Data ALL Produk</a></li>
					</ul>
				</li>
				<li class="sidebar-child"><a href="#"><i class="fa fa-th"></i> Laporan <i class="sidebar-fa fa fa-angle-down pull-right"></i></a>
					<ul class="sidebar-second-child" style="display:block;">
						<li><a href="item_report.php">Laporan Data Produk</a></li>
						<li><a href="item_catsubcat_report.php">Laporan Data Produk Berdasarkan Kategori / Subkategori</a></li>
						<li class="active"><a href="order_report.php">Laporan Data Pemesanan</a></li>
						<li><a href="order_report_bydate.php">Laporan Data Pemesanan Berdasarkan Tanggal</a></li>
						
					</ul>
				</li>
				<li><a href="http://localhost/nayya_humam" target="_blank"><i class="fa fa-globe"></i> Home</a></li>
			</ul>
		</aside>
		<div id="page-content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
							<legend><h1 style="color: black;" ><center>Laporan Data Penjualan Produk</h1></legend>
						<div style="margin-bottom: 10%;">
							<div class="col-lg-12" style="margin-top: 2px; margin-bottom: 3px;">
								<button class="btn btn-warning" type="button"  onclick="window.open('pdf/pesanan_report_bypdf.php');"> Lihat PDF</button>
							</div>
							<div class="table-responsive">
								<table id="data" class="table table-bordered table-striped results border border-dark">
									<?php
									include "library.php";
									$query = 'SELECT o1.creation_date, o1.order_id, o1.fullname, o1.item_name, o1.size, o1.color, o1.qty, o1.price, o1.disc, o1.afterdisc, o1.total FROM 
												 (SELECT orders.creation_date, orders.order_id, 
												 CONCAT(members.fullname, "(", orders.customer_id, ")") AS fullname, 
												 GROUP_CONCAT(CONCAT(order_detail.item_name, "(", order_detail.item_code, ")") SEPARATOR ",") AS item_name, 
												 GROUP_CONCAT(order_detail.size) AS size,
												 GROUP_CONCAT(order_detail.color) AS color,
												 GROUP_CONCAT(order_detail.qty) AS qty,
												 GROUP_CONCAT(order_detail.price) AS price, 
												 GROUP_CONCAT(order_detail.disc) AS disc,
												 GROUP_CONCAT(order_detail.qty * order_detail.price -(order_detail.price * order_detail.disc / 100)) as afterdisc,
												 SUM(order_detail.qty * order_detail.price -(order_detail.price * order_detail.disc / 100)) AS total
												 FROM order_detail INNER JOIN orders ON orders.order_id = order_detail.order_id 
												 INNER JOIN members ON members.member_id = orders.customer_id GROUP BY orders.order_id) o1';
									$result = mysqli_query($conn, $query);
									while($row = mysqli_fetch_assoc($result)){
										$product = explode(",", $row['item_name']);
										$size = explode(",", $row['size']);
										$color = explode(",", $row['color']);
										$qty = explode(",",$row['qty']);
										$price = explode(",", $row['price']);
										$disc = explode(",", $row['disc']);
										$afterdisc = explode(",", $row['afterdisc']);
									?>
									<tbody>
										<tr>
											<td colspan="8" style="background-color: #008080;color:white">
											Tanggal Pemesan : <?php echo fixdate($row['creation_date']); ?><br/>
											Kode Pemesan : <?php echo $row['order_id']; ?><br/>
											Nama Pelanggan : <?php echo $row['fullname']; ?><br/>
											</td>
										</tr>
										<tr>
											<td align="center">Nama Produk</td>
											<td align="center">Ukuran</td>
											<td align="center">Warna</td>
											<td align="center">Jumlah</td>
											<td align="center">Harga</td>
											<td align="center">Diskon</td>
											<td align="center">Harga Promo</td>
										</tr>
										<tr>
											<td>
											<?php
											foreach($product as $item){
												echo $item."<br/>";
											}
											?>
											</td>
											<td align="center">
											<?php
											foreach($size as $sz){
												echo $sz."<br/>";
											}
											?>
											</td>
											<td align="center">
											<?php
											foreach($color as $clr){
												echo $clr."<br/>";
											}
											?>
											</td>
											<td align="center">
											<?php
											foreach($qty as $qty){
												echo $qty."<br/>";
											}
											?>
											</td>
											<td align="center">
											<?php
											foreach($price as $price){
												echo 'Rp '.number_format($price,0,".",".")."<br/>";
											}
											?>
											</td>
											<td align="center">
											<?php
											foreach($disc as $discount){
												echo $discount."%<br/>";
											}
											?>
											</td>
											<td align="right">
											<?php
											foreach($afterdisc as $special){
												echo 'Rp '.number_format($special,0,".",".")."<br/>";
											}
											?>
											</td>
										</tr>
										<tr>
											<td align="right" colspan="6">Subtotal</td>
											<td align="right"><?php echo 'Rp '.number_format($row['total'],0,".","."); ?></td>
										</tr>
									</tbody>
									<?php
									}
									?>
								</table>
							</div>
						</div>
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
	<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
	
</body>
</html>