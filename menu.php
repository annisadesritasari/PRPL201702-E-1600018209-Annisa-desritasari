<?php 
include "koneksi.php";
?>
<section class="main clearfix">

	<?php
			
	//query ke database dg SELECT table menu diurutkan berdasarkan Id paling besar
	$query = mysql_query("SELECT * FROM menu ORDER BY kode DESC") or die(mysql_error());

	//cek, apakakah hasil query di atas mendapatkan hasil atau tidak (data kosong atau tidak)
	if(mysql_num_rows($query) == 0)
	{	//ini artinya jika data hasil query di atas kosong
				
	//jika data kosong, maka akan menampilkan row kosong
	echo 'Tidak ada data!';
	
	//else ini artinya jika data hasil query ada (data di database tidak kosong), jika data tidak kosong, maka akan melakukan perulangan while			
	}else
		{	

			//perulangan while dg membuat variabel $data yang akan mengambil data di database
			while($data = mysql_fetch_assoc($query))
			{	
				$data['hrg_jual']; 
				$angka_format = number_format($data['hrg_jual'],2,",",".");
			echo'
				<div class="work">
					<a href="detail.php?kode='.$data['kode'].'">
					<img src="img/'.$data['gambar'].'" class="media" alt=""/>
					<div class="caption">
						<div class="work_title">
							<h1>'.$data['nama'].'<br/><br/>
								Rp '.$angka_format.'
							</h1>
						</div>
					</div>
					</a>
				</div>
				';			
			}			
		}
	?>
</section>

</body>
</html>