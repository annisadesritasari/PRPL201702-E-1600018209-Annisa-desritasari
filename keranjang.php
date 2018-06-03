<?php
include "koneksi.php";
$op=isset($_GET['op'])?$_GET['op']:null;
if($op=='ambilbarang'){
    $data=mysql_query("select * from menu");
    echo"<option>Pilih Menu</option>";
    while($r=mysql_fetch_array($data)){
        echo "<option value='$r[kode]'>$r[nama]</option>";
    }
}elseif($op=='ambildata'){
    $kode=$_GET['kode'];
    $dt=mysql_query("select * from menu where kode='$kode'");
    $d=mysql_fetch_array($dt);
    echo $d['nama']."|".$d['hrg_jual']."|".$d['stok'];
}elseif($op=='barang'){
    $brg=mysql_query("select * from tblsementara");
    echo "<thead>
            <tr style='background-color:#f1f1f1; color:#000;'>
                <th>Nama</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>";
    $total=mysql_fetch_array(mysql_query("select sum(subtotal) as total from tblsementara"));
    while($r=mysql_fetch_array($brg)){
        echo "<tr>
                <td>$r[nama]</td>
                <td>$r[harga]</td>
                <td>$r[jumlah]<input type='hidden' name='jum' value='$r[jumlah]'></td>
                <td>$r[subtotal]</td>
                <td><a href='keranjang.php?op=hapus&kode=$r[kode]' id='hapus'>Hapus</a></td>
            </tr>";
    }
    echo "<tr>
        <td style='background-color:#f1f1f1; color:#000;' colspan='3'>Total Bayar</td>
        <td colspan='2'>$total[total]</td>
    </tr>";
}elseif($op=='tambah'){
    $kode=$_GET['kode'];
    $nama=$_GET['nama'];
    $harga=$_GET['harga'];
    $jumlah=$_GET['jumlah'];
    $subtotal=$harga*$jumlah;
    
    $tambah=mysql_query("INSERT into tblsementara (kode,nama,harga,jumlah,subtotal)
                        values ('$kode','$nama','$harga','$jumlah','$subtotal')");
    
    if($tambah){
        echo "sukses";
    }else{
        echo "ERROR";
    }
}elseif($op=='hapus'){
    $kode=$_GET['kode'];
    $del=mysql_query("delete from tblsementara where kode='$kode'");
    if($del){
        echo "<script>window.location='transaksi.php?page=penjualan&act=tambah';</script>";
    }else{
        echo "<script>alert('Hapus Data Berhasil');
            window.location='index.php?page=penjualan&act=tambah';</script>";
    }
}elseif($op=='proses'){
    $nota=$_GET['nota'];
    $tanggal=$_GET['tanggal'];
    $to=mysql_fetch_array(mysql_query("select sum(subtotal) as total from tblsementara"));
    $tot=$to['total'];
    $simpan=mysql_query("insert into penjualan(nonota,tanggal,total)
                        values ('$nota','$tanggal','$tot')");
    if($simpan){
        $query=mysql_query("select * from tblsementara");
        while($r=mysql_fetch_row($query)){
            mysql_query("insert into detailpenjualan(nonota,kode,harga,jumlah,subtotal)
                        values('$nota','$r[0]','$r[2]','$r[3]','$r[4]')");
            mysql_query("update menu set stok=stok-'$r[3]'
                        where kode='$r[0]'");
        }
        //hapus seluruh isi tabel sementara
        mysql_query("truncate table tblsementara");
        echo "sukses";
    }else{
        echo "ERROR";
    }
}
?>