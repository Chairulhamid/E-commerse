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
	<title>Nanyya Human Store | Laporan Data Penjulan</title>
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
          
	   }
	   
	   .table > tbody > tr > td {
		   vertical-align: middle;
		   border: 1px solid #000;
		   color: #000;
           
	   }
	   
	   
	</style>
</head>
<body>

	<section>
		<div class="container-fluid">
			<div class="row">
				
				<div class=" text-center col-sm-6 text-right" style="margin-top: 10px;">
					<legend>
						<center>Nayya Humam Store</center><br/>
						<center>Rambai Kota Pariaman Provinsi Sumatera Barat</center><br/>
						<center>Telepon : 081266399739</center>
                    </legend>
				</div>
				<div  class=" text-center col-lg-12">
                   <h4 style="margin-left: 30px;"  style="color: #800000;">Laporan Data Penjualan</h4>
                    <hr>
					<div class="clearfix"></div>
					<table style="margin-left: 70px;" id="data" class="table table-bordered table-striped results border border-dark" >
									<?php
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
											<td colspan="12" style="background-color: #008080;color:white">
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
											<td align="center" colspan="2">Harga</td>
											<td align="center" colspan="3">Diskon</td>
											<td align="center" colspan="3">Harga Promo</td>
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
											<td  colspan="2" align="center">
											<?php
											foreach($price as $price){
												echo 'Rp '.number_format($price,0,".",".")."<br/>";
											}
											?>
											</td>
											<td  colspan="3" align="center">
											<?php
											foreach($disc as $discount){
												echo $discount."%<br/>";
											}
											?>
											</td>
											<td  colspan="3" align="right">
											<?php
											foreach($afterdisc as $special){
												echo 'Rp '.number_format($special,0,".",".")."<br/>";
											}
											?>
											</td>
                                            
										</tr>
										<tr>
											<td align="right" colspan="7">Subtotal</td>
											<td  colspan="5" align="right"><?php echo 'Rp '.number_format($row['total'],0,".","."); ?>
                                         </td>
										</tr>
                                        
									</tbody>
									<?php
									}
									?>
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