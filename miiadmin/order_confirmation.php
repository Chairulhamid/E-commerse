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

ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Nayya Humman Store | Pesanan</title>
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
	
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
        <script src="js/html5shiv.min.js"></script>
        <script src="js/respond.min.js"></script>
    <![endif]-->
	<style type="text/css" media="print">
	@page { size: portrait; }
	</style>
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
		<nav id="sidebar-wrapper">
			<ul class="sidebar-nav">
				<li ><a href="miadmin.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li class="active"><a href="order_confirmation.php"><i class="fa fa-shopping-cart"></i> Pesanan</a></li>
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
		include "library.php";
		
		$act = @$_GET['act'];
		
		switch($act){
			default:
			
		?>
		<div id="page-content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
						<h1 style="color: black;" ><center>Data Pesanan</center></h1>
						<div class="clearfix"></div>
						<div class="table-responsive">
							<table id="data" class="table table-bordered table-striped results border border-dark">
								<thead class="" style="background-color: #008080;color:white">
									<tr>
										<th width="10">No</th>
										<th>Kode Pesanan</th>
										<th>Tanggal</th>
										<th>Total Belanja</th>
										<th>Status Pengiriman</th>
										<th>Print</th>
										<th>Edit</th>
									</tr>
								</thead>
								<tbody>
		<?php
			$sql = "SELECT * FROM orders";
			$query = mysqli_query($conn, $sql);
			$no = 0;
			while($row = mysqli_fetch_assoc($query)){
		?>
									<tr>
										<td width="10" align="center"><?php echo ++$no; ?></td>
										<td width="15" align="center"><?php echo $row['order_id']; ?></td>
										<td align="center"><?php echo fixdate($row['creation_date']); ?></td>
										<td align="center"><?php echo 'Rp '.number_format($row['totals'],0,".","."); ?></td>
										<td align="center"><?php echo $row['order_status']; ?></td>
										<td width="50" align="center">
											<a class="btn btn-info" href="?act=view&id=<?php echo $row['order_id']; ?>" class="mybtn"><i class="fa fa-print"></i></a>
										</td>
										<td width="50" align="center">
											<a class="btn btn-success" href="?act=edit&id=<?php echo $row['order_id']; ?>" class="mybtn"><i class="fa fa-pencil-square-o"></i></a>
										</td>
									</tr>
		<?php } ?>
								</tbody>
							</table>
						</div>
					</div>			
				</div>
			</div>
		</div>	
		<?php
		break;
		case "edit":
		?>
		<div id="page-content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
		<?php
					
					$id = $_GET['id'];

					$query = mysqli_query($conn, "SELECT * FROM orders INNER JOIN members ON members.member_id = orders.customer_id WHERE orders.order_id = '".$id."'");
					$data = mysqli_fetch_array($query);
					
					$error = false;
					$order = "";
					$orderErr = "";
							
					if(isset($_POST['update'])){
								
						if($_SERVER['REQUEST_METHOD'] == "POST"){
							if(empty($_POST['orderstatus'])){
								$error = true;
								$orderErr = "Pilih yang mana";
							}else{
								$order = $_POST['orderstatus'];
							}
						}
								
						if(!$error){
							date_default_timezone_set('Asia/Jakarta');
							$regdate = date('Y-m-d');
							$regtime = date('G:i:s');
							
							mysqli_query($conn,"UPDATE orders SET order_status='".$order."', order_valid_date = '".$regdate."', order_valid_time = '".$regtime."' WHERE order_id='".$id."'");
							header('location: order_confirmation.php');
						}
					}
					
		?>
						<form action="?act=edit&id=<?php echo $_GET['id'];?>" class="form-horizontal" method="POST">
							<legend>Ubah Status Pengiriman</legend>
							<!-- Category Name -->
							<div class="form-group">
								<label class="col-md-2 control-label">Kode Pemesanan : </label>
								<div class="col-md-10">
									<label class="control-label" style="font-size:16px;"><?php echo $data['order_id']; ?></label>
									<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Nama Pembeli : </label>
								<div class="col-md-10">
									<label class="control-label"><?php echo $data['fullname']; ?></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Bank Asal : </label>
								<div class="col-md-10">
									<label class="control-label"><?php echo $data['cardbank_type']; ?></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Status Pembayaran : </label>
								<div class="col-md-10">
									<label class="control-label"><?php echo $data['payment_status']; ?></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Status Pengiriman : </label>
								<div class="col-md-10">
									<div class="checkboxcss">
										<?php
										if($data['order_status'] == "PENDING"){
											echo '<input type="radio" name="orderstatus" value="PENDING" checked>PENDING ';
											echo '<input type="radio" name="orderstatus" value="SENT">SENT ';
										}elseif($data['order_status'] == "SENT"){
											echo '<input type="radio" name="orderstatus" value="PENDING">PENDING ';
											echo '<input type="radio" name="orderstatus" value="SENT" checked>SENT ';
										}else{
											echo '<input type="radio" name="orderstatus" value="PENDING">PENDING ';
											echo '<input type="radio" name="orderstatus" value="SENT">SENT ';
										}
										?>
									</div>
									<span class="text-danger"><?php echo $orderErr ; ?></span>
								</div>
							</div>
							<!-- Button -->
							<div class="form-group">
								<label class="col-md-2 control-label"></label>
								<div class="col-md-10">
									<button type="submit" class="btn btn-success" name="update">Update</button>
									<a href="order_confirmation.php">
									<button type="button" class="btn btn-danger">Batal</button></a>
								</div>
							</div>
						</form>
					</div>
					<div class="col-lg-12" style="margin-top:-6%; margin-bottom: 3%;">
						<div class="table-responsive">
							<table id="data" class="table table-bordered table-striped results border border-dark">
								<thead class="" style="background-color: #008080;color:white">
									<tr>
										<th>No</th>
										<th>Kode Produk</th>
										<th>Gambar</th>
										<th>Nama Produk</th>
										<th>Warna</th>
										<th>Ukuran</th>
										<th>Jumlah</th>
									</tr>
								</thead>
								<tbody>
								<?php
								$query = mysqli_query($conn, "SELECT * FROM order_detail WHERE order_id = '".$_GET['id']."'");
								$no = 1;
								while($row = mysqli_fetch_array($query)){
								?>
									<tr>
										<td width="10" align="center"><?php echo $no; ?></td>
										<td width="10" align="center"><?php echo $row['item_code']; ?></td>
										<td align="center"><img src="img/<?php echo $row['bgimg']; ?>" class="img-small"></td>
										<td><?php echo $row['item_name']; ?></td>
										<td align="center"><?php echo $row['color']; ?></td>
										<td align="center"><?php echo $row['size']; ?></td>
										<td align="center"><?php echo $row['qty']; ?></td>
									</tr>
								<?php
									$no++;
								}
								?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		break;
		case "view";
		?>
		<div id="page-content-wrapper">
			<div class="container-fluid">
				<div class="row" style="margin-top: 10px;">
					<div class="col-lg-12 ">
						<div class="back-right btn btn-warning"><a href="order_confirmation.php"><i class="fa fa-arrow-left"></i> KEMBALI</a></div>
						<button class=" back-right btn btn-info" onclick="PrintDiv('divToPrint')">Print</button>
					</div>
				</div>
				<div id="divToPrint">
					<div class="row" style="margin-bottom: 10px;">
						<div class="col-lg-12 " style="margin-bottom:40px;margin-right:20px;margin-left:20px;margin-top:50px;">
							
							<div class=" text-center" style="margin-top: 10px;">
								<address><legend>
								<b>Nayya Humam Store</b><br/>
								<b> Rambai Kota Pariaman Provinsi Sumatera Barat</b> <br>
								<b>Telepon : 081266399739</b><br/>
								</legend>
								</address>
							</div>
						
						
						
							<?php
							$query = "SELECT * FROM orders INNER JOIN members ON members.member_id = orders.customer_id WHERE orders.order_id = '".$_GET['id']."'";
							$result = mysqli_query($conn, $query);
							while($row = mysqli_fetch_array($result)){
							?>
							<div class="col-xs-12 text-center">
								<h3>Faktur Pemesanan</h3>
								<center><h5>No pemesanan : <?php echo $_GET['id']; ?></h5></center>
							</div>
							<hr>
							<div class="row" style="margin-bottom: 10px;">
								<div class="col-xs-6">
									<strong>Kepada Yth:</strong>
									<address>
									<?php 
									echo '
									'.$row['fullname'].'<br/>
									'.$row['address'].',
									'.$row['city'].',
									'.$row['state'].'<br/>
									'.$row['zip_code'].'<br/>
									Telp '.$row['phone'].'
									'; 
									?>
									</address>
								</div>
								<div class="col-xs-6 text-right">
									<strong>Tanggal Pemesanan</strong><br/>
									<?php 
									$date = ''.$row['creation_date'].'';
									$date_detail = date('d-m-Y', strtotime($date));
									echo $date_detail.' '.$row['creation_time'];
									?>
								</div>
								<div class="col-xs-6 text-right">
									<strong>Tanggal Pengiriman</strong><br/>
									<?php 
									$sentdate = ''.$row['order_valid_date'].'';
									$sentdate_detail = date('d-m-Y', strtotime($sentdate));
									echo $sentdate_detail.' '.$row['order_valid_time'];
									?>
								</div>
							</div>
							<?php
							}
							?>
							<div class="col-lg-12">
								<div class="table-responsive">
									<table class="timetable_sub">
										<thead>
											<tr>
												<th>No</th>
												<th>Produk</th>
												<th>Jumlah</th>
												<th>Diskon</th>
												<th>Harga</th>
												<th>Total Belanja</th>
											</tr>
										</thead>
										<?php
										$query = mysqli_query($conn, "SELECT * FROM order_detail WHERE order_id = '".$_GET['id']."'");
										$no = 1;
										while($row = mysqli_fetch_array($query)){
											$totalDisc = $row['price']-($row['price'] * $row['disc']/100);
											$subtotal = $row['qty'] * $totalDisc;
											$total = $total + $subtotal;
										?>
										<tr>
											<td align="center"><?php echo $no; ?></td>
											<td>
												<div class="table-column-left">
													<img src="img/<?php echo $row['bgimg']; ?>" class="img-small">
												</div>
												<div class=" table-column-right">
													Kode : <?php echo $row['item_code']; ?><br/>
													Nama : <?php echo $row['item_name']; ?><br/>
													Warna : <?php echo $row['color']; ?><br/>
													Ukuran : <?php echo $row['size']; ?><br/>
												</div>
											</td>
											<td align="center"><?php echo $row['qty']; ?></td>
											<td align="center"><?php echo $row['disc']; ?>%</td>
											<td align="center"><?php echo 'Rp '.number_format($row['price'],0,".","."); ?></td>
											<td align="center"><?php echo 'Rp '.number_format($subtotal,0,".","."); ?></td>
										</tr>
										<?php
										$no++;
										}
										?>
										<tr>
											<td colspan="5" align="right">Total</td>
											<td align="center"><?php echo 'Rp '.number_format($total,0,".","."); ?></td>
										</tr>
									</table>
								</div>
								<div style="margin-top:3%;"><p>Terima kasih atas pembelian Anda di Nayya Hummam Store, Semoga Anda Senang Dengan Belanjaanya dan kami berharap dapat melayani Anda kembali.</p></div>
								
							</div>
						</div>
				</div>
			</div>
		</div>
		<?php
		break;
		case "delete":
			if(isset($_GET['id'])){
				$id = $_GET['id'];
				$qty = $_GET['qty'];
				$query = "DELETE orders, order_detail FROM orders INNER JOIN order_detail ON order_detail.order_id = orders.order_id WHERE orders.order_id = '$id'";
				mysqli_query($conn,"UPDATE items INNER JOIN order_detail ON order_detail.item_code = items.item_id SET items.stock = items.stock + order_detail.qty WHERE items.item_id = '$qty'");
				
				if(!$res = mysqli_query($conn,$query)){
					exit(mysqli_error($conn));
				}
				header('location: order_confirmation.php');
			}
		?>
		
		<?php
		break;
		}
		
		ob_end_flush();
		?>
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
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
	
</body>
</html>