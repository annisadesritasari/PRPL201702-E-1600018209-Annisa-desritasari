<?php
//memulai proses hapus data

//cek dahulu, apakah benar URL sudah ada GET id -> hapus.php?id=id
if(isset($_GET['id'])){
	
	//inlcude atau memasukkan file koneksi ke database
	include('koneksi.php');
	
	//membuat variabel $id yg bernilai dari URL GET id -> hapus.php?id=id
	$id = $_GET['id'];
	
	//cek ke database apakah ada data pemesanan dengan id='$id'
	$cek = mysql_query("SELECT id FROM pemesanan WHERE id='$id'") or die(mysql_error());
	
	//jika data pemesanan tidak ada
	if(mysql_num_rows($cek) == 0){
		
		//jika data tidak ada, maka redirect atau dikembalikan ke halaman beranda
		echo '<script>window.history.back()</script>';
	
	}else{
		
		//jika data ada di database, maka melakukan query DELETE table pemesanan dengan kondisi WHERE id='$id'
		$del = mysql_query("DELETE FROM pemesanan WHERE id='$id'");
		
		//jika query DELETE berhasil
		if($del){
			
		//redirect atau dikembalikan ke halaman detail menu
		echo '<h1 align="center">Pesanan berhasil dihapus</h1>';	
		echo '<p align="center"><a href="index.php?konten=pemesanan">Kembali</a></p>';
		
		}else{

			//Pesan jika proses pesan gagal
			echo '<p align="center">Gagal hapus Pesan pesanan!<br/><a href="index.php">Kembali</a></p>';		
			
		}
		
	}
	
}else{
	
	//redirect atau dikembalikan ke halaman beranda
	echo '<script>window.history.back()</script>';
	
}
?>