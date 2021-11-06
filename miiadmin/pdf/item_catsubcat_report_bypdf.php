<?php
error_reporting(E_ALL ^ E_NOTICE);

session_start();
require '../vendor/autoload.php';
use Dompdf\Dompdf;

ob_start();

include "../connect.php";
include "../library.php";
?>

<!DOCTYPE html>
<html>
<head>
	<!-- meta -->
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8"/>
	<title>Nayya Humam Store | Laporan Data Produk </title>
	<meta name="keywords" content="men, women, clothing, home" />
	<meta name="author" content="Victory Webstore"/>
	
	<!-- mobile specific -->
	<meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1" />

	<!-- CSS Offline -->
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="./css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="./css/jquery.dataTables.css" />

	<style>
		@font-face {
			font-family: 'Source Sans Pro Light';
			src: url(././fonts/sourcesanspro-light-webfont.ttf);
		}
		
		body{
		   font-family: 'Source Sans Pro Light', sans-serif !important;
		   font-weight: normal;
	   }
	   
		.table > thead > tr > th  {
		   text-align: center;
		   vertical-align: middle;
		   border: 1px solid #000;
		   color: #000;
		   height: 40px;
	   }
	   
	   .table > tbody > tr > td {
		   vertical-align: middle;
		   border: 1px solid #000;
		   color: #000;
	   }
	   
	   img { display: -dompdf-image !important; }
	   
	   .logo-img {
		   margin-top: 15px;
		   display: block;
		   width: 15px;
	   }
	   
	</style>
</head>
<body>

	<section>
		<div class="container-fluid">
			<div class="row">
				
					<legend>
						<center>Nayya Humam Store</center><br/>
						<center>Rambai Kota Pariaman Provinsi Sumatera Barat</center><br/>
						<center>Telepon : 081266399739</center>
                    </legend>
				</div>
				<div class="col-lg-12">
					 <h4 style="margin-left: 30px;"  style="color: #800000;">Laporan Data Produk berdasarkan kategori & sub kategori</h4>
                    <hr>
					<div class="clearfix"></div>
					<table id="data" class="table table-bordered table-striped results border border-dark">
                                <thead class="" style="background-color: #008080;color:white">
							<tr>
								<th width="10">No</th>
								<th>Tanggal Input</th>
								<th colspan="6">Nama Produk</th>
								<th>Harga</th>
								<th>Diskon</th>
								<th>Harga Diskon</th>
								<th>Stok</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$scat = $_GET['scat'];
						$sql = "SELECT * FROM items INNER JOIN colors ON colors.clr_id = items.clr_id INNER JOIN brands ON brands.brd_id = items.brd_id 
								INNER JOIN categories ON categories.cat_id = items.cat_id INNER JOIN subcategories ON subcategories.scat_id = items.scat_id 
								WHERE items.scat_id = '".$scat."'";
						$query = mysqli_query($conn, $sql);
						$no = 0;
						while($row = mysqli_fetch_assoc($query)){
							$totalDisc = $row['price']-($row['price'] * $row['discount']/100);
							$total1 = $total1 + $totalDisc;
							$total2 = $total2 + $row['stock'];
						?>
							<tr>
								<td width="10" align="center"><?php echo ++$no; ?></td>
								<td align="center"><?php echo fixdate($row['creation_date']); ?></td>
								
								<td colspan="6">
									Kode : <?php echo $row['item_id']; ?><br/>
									Nama : <?php echo $row['item_name']; ?><br/>
									Warna : <?php echo $row['color']; ?><br/>
									Ukuran : <?php echo $row['size']; ?><br/>
									Brand : <?php echo $row['brand']; ?><br/>
									Kategori : <?php echo $row['category']; ?><br/>
									Subkategori : <?php echo $row['subcategory']; ?><br/>
								</td>
								<td align="center"><?php echo 'Rp '.number_format($row['price'],0,".","."); ?></td>
								<td align="center"><?php echo $row['discount']; ?>%</td>
								<td align="center"><?php echo 'Rp '.number_format($totalDisc,0,".","."); ?></td>
								<td align="center"><?php echo $row['stock']; ?></td>
							</tr>
						<?php } ?>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
	
</body>
</html>

<?php
$html = ob_get_clean();

$dompdf = new DOMPDF();
$dompdf -> loadHtml($html);
$dompdf -> setPaper('letter', 'landscape');
$dompdf -> set_option('font_height_ratio', '0.70');
$dompdf -> render();
$font = $dompdf -> getFontMetrics() -> get_font("helvetica", "normal");
$dompdf -> getCanvas() -> page_text(45, 570, "Nayya Humam Store", $font, 8, array(0,0,0));
$dompdf -> getCanvas() -> page_text(705, 570, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 8, array(0,0,0));
$dompdf -> stream("Nayya Humam Store.pdf", array("Attachment" => 0));
exit;
?>