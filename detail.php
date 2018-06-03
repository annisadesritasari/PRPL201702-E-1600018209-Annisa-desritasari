<?php 
include "template.php";
?>


	<?php
	//proses mengambil data ke database untuk ditampilkan di form edit berdasarkan kode yg didapatkan dari GET kode -> edit.php?kode=kode

	//include atau memasukkan file koneksi ke database
	include "koneksi.php";

	//membuat variabel $kode yg nilainya adalah dari URL GET kode -> edit.php?kode=kode
	$kode = $_GET['kode'];
	
	//melakukan query ke database dg SELECT table menu dengan kondisi WHERE kode = '$kode'
	$show = mysql_query("SELECT * FROM menu WHERE kode='$kode'");
	
	//cek apakah data dari hasil query ada atau tidak
	if(mysql_num_rows($show) == 0){
		
		//jika tidak ada data yg sesuai maka akan langsung di arahkan ke halaman depan atau beranda -> index.php
		echo '<script>window.history.back()</script>';
		
	}else{
	
		//jika data ditemukan, maka membuat variabel $data
		$data = mysql_fetch_assoc($show);	//mengambil data ke database yang nantinya akan ditampilkan di form edit di bawah
	
	}
		$data['hrg_jual']; 
		$angka_format = number_format($data['hrg_jual'],2,",",".");
	?>

<section class="main clearfix">
	<section class="wrapper">
		<div class="content">
			<h2><a href="index.php"><button type="button" class="btn btn-default btn-lg" title="Kembali"><i class="fa fa-reply"></i></button></a></h2>
				<table class="table table-bordered">
                <thead>
                  <tr><th>
                  <tr style="color:#1a1a1a;">
                  <th style="font-size: 20px;"><img src="img/<?php echo $data['gambar']; ?>" width="200px" class="media" alt=""/></th></tr>                  
		          </th></tr>
                </thead>
                </table>
		        <table class="table table-bordered">
                <thead>
                  <tr><th>
                  <tr style="color:#1a1a1a;">
                  <th style="font-size: 20px;""><b><?php echo $data['nama']; ?></b></th></tr>                  
		          </th></tr>
                </thead>
                </table>
          <div class="col-md-6">
            <div class="card">
		        <table class="table table-bordered">
                <thead>
                    <td>
                    	<?php echo 'Rp. '.$angka_format;?> | <?php echo $data['stok']; ?> Tersedia
					</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
		</div>
	</section>
</section>
</body>
</html>