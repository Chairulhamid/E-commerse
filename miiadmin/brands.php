<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once("connect.php");

ob_start();
session_start();

if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'] != ''){
	$id = $_GET['user_id'];
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
	<title>Nayya Humam Store | Merek</title>
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
				<li class="sidebar-child"><a href="#"><i class="fa fa-th"></i> Data Produk <i class="sidebar-fa fa fa-angle-down pull-right"></i></a>
					<ul class="sidebar-second-child" style="display:block;">
						<li class="active"><a href="brands.php">Merek Produk</a></li>
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
		</aside>
		<?php
		include "connect.php";
		
		$act = @$_GET['act'];
		
		switch($act){
			default:
			
		?>
		<div id="page-content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
					<h1 style="color: black;" ><center>Data Merek</center></h1>
						<a href="?act=add" class="btn btn-warning"><i class="fa fa-plus"></i> Tambah Baru</a>
						<div class="clearfix"></div>
						
						<div class="table-responsive" style="margin-top:10px;">
							<table id="data" class="table table-bordered table-striped results border border-dark">
								<thead class="" style="background-color: #008080;color:white">
									<tr>
										<th width="10">No</th>
										<th>Nama Merek</th>
										<th>Edit</th>
										<th>Hapus</th>
									</tr>
								</thead>
								<tbody>
		<?php
			$sql = "SELECT * FROM brands";
			$query = mysqli_query($conn, $sql);
			$no = 0;
			while($row = mysqli_fetch_assoc($query)){
				
		?>
									<tr>
										<td width="10" align="center"><?php echo ++$no; ?></td>
										<td><?php echo $row['brand']; ?></td>
										<td width="50" align="center">
											<a class="btn btn-info" href="?act=edit&id=<?php echo $row['brd_id']; ?>" class="mybtn"><i class="fa fa-pencil-square-o"></i></a>
										</td>
										<td width="50" align="center">
											<a class="btn btn-danger" href="<?php echo $row['brd_id']; ?>" data-target="#confirm-delete_<?php echo $row['brd_id']; ?>" data-toggle="modal" class="mybtn btn-show"><i class="fa fa-trash-o"></i></a>
											<div class="modal fade" id="confirm-delete_<?php echo $row['brd_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
															<h4 class="modal-tittle">
																<i class="fa fa-trash-o"></i> Konfirmasi
															</h4>
														</div>
														<div class="modal-body">
															<p>Yakinkah Anda ingin menghapus data ini?</p>
														</div>
														<div class="modal-footer">
															<a href="?act=delete&id=<?php echo $row['brd_id']; ?>" class="btn btn-danger" id="<?php echo $row['brd_id']; ?>">Ya</a>
															<a href="#" type="button" class="btn btn-default btn-cancel" data-dismiss="modal" aria-hidden="true">Tidak</a>
														</div>
													</div>
												</div>
											</div>	
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
			case 'add':
		?>
		<div id="page-content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
										

		<?php
							$error = false;
							
							$brand = "";
							$catErr = "";
							
							if(isset($_POST['save'])){
								$query = mysqli_query($conn,"SELECT * FROM brands WHERE brand='".$_POST['nama_merek']."'");
								
								if($_SERVER['REQUEST_METHOD'] == "POST"){	
									if(empty($_POST['nama_merek'])){
										$error = true;
										$catErr = "Masukkan isi nama merek";
									}else{
										$brand = $_POST['nama_merek'];
										if(!preg_match("/^[a-zA-Z ]*$/",$_POST['nama_merek'])){
											$error = true;
											$catErr = "Isi nama kategori harus menggunakan huruf dan spasi";
										}
									}
								}
								
								if(!$error){
									if(mysqli_num_rows($query) > 0){
										echo "<div class='alert alert-danger'>merek <b>$brand</b> sudah masih ada!</div>";
									}else{
										mysqli_query($conn,"INSERT INTO brands VALUES (NULL,'$brand')");
										header('location: brands.php');
									}
								}
							}
		?>
						
						<form action="?act=add" class="form-horizontal" method="POST">
							<legend>Tambah Data Baru</legend>
							<!-- brand Name -->
							<div class="form-group">
								<label class="col-md-2 control-label">Nama merek</label>
								<div class="col-md-10">
									<input type="text" name="nama_merek" placeholder="Masukkan nama Merek" class="form-control" value="<?php echo isset($brand) ? $brand : ' ';?>">
									<span class="text-danger"><?php echo $catErr ; ?></span>
								</div>
							</div>
							<!-- Button -->
							<div class="form-group">
								<label class="col-md-2 control-label"></label>
								<div class="text-right col-md-10">
									<button type="submit" class="btn btn-success" name="save">Simpan</button>
									<a href="brands.php"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
							</div>	
						</form>
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

					$query = mysqli_query($conn, "SELECT * FROM brands WHERE brd_id = '".$id."'");
					$data = mysqli_fetch_array($query);
					
					$error = false;
					$brand = "";
					$catErr = "";
							
					if(isset($_POST['update'])){
								
						if($_SERVER['REQUEST_METHOD'] == "POST"){
							if(empty($_POST['nama_merek'])){
								$error = true;
								$catErr = "Please enter the brand name";
							}else{
								$brand = mysqli_real_escape_string($conn,$_POST['nama_merek']);
								if(!preg_match("/^[a-zA-Z ]*$/",$_POST['nama_merek'])){
									$error = true;
									$catErr = "Name must contain only alphabets and space";
								}
							}
						}
								
						if(!$error){
							mysqli_query($conn,"UPDATE brands SET brand='$brand' WHERE brd_id='".$id."'");
							header('location: brands.php');
						}
					}
					
		?>
						<form action="?act=edit&id=<?php echo $_GET['id'];?>" class="form-horizontal" method="POST">
							<legend>Edit Merek</legend>
							<!-- brand Name -->
							<div class="form-group">
								<label class="col-md-2 control-label">Nama Merek </label>
								<div class="col-md-10">
									<input type="text" name="nama_merek" value="<?php echo isset($_POST['nama_merek']) ? $_POST['nama_merek'] : $data['brand']; ?>" class="form-control">
									<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
									<span class="text-danger"><?php echo $catErr ; ?></span>
								</div>
							</div>
							<!-- Button -->
							<div class="form-group">
								<label class="col-md-2 control-label"></label>
								<div class="text-right col-md-10">
									<button type="submit" class="btn btn-success" name="update">Update</button>
									<a href="brands.php"><button type="button" class=" btn btn-danger">Kembali</button></a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
		break;
		case "delete":
			if(isset($_GET['id'])){
				$id = $_GET['id'];
				$query = "DELETE FROM brands WHERE brd_id = '$id'";
				if(!$res = mysqli_query($conn,$query)){
					exit(mysqli_error());
				}
				header('location: brands.php');
			}
		?>
		
		<?php
		break;
		}
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

<?php
ob_end_flush();
?>