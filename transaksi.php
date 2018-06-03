<!DOCTYPE html>
    <html>
        <head>
            <link rel="stylesheet" href="assets/css/bootstrap.css">
            <script src="assets/js/jQuery-2.1.4.min.js"></script>
            <script src="assets/js/jquery.ui.datepicker.js"></script>
            <script>
                //mendeksripsikan variabel yang akan digunakan
                var nota;
                var tanggal;
                var kode;
                var nama;
                var harga;
                var jumlah;
                var stok;
                $(function(){
                    //meload file pk dengan operator ambil barang dimana nantinya
                    //isinya akan masuk di combo box
                    $("#kode").load("keranjang.php","op=ambilbarang");
                    
                    //meload isi tabel
                    $("#barang").load("keranjang.php","op=barang");
                    
                    //mengkosongkan input text dengan masing2 id berikut
                    $("#nama").val("");
                    $("#harga").val("");
                    $("#jumlah").val("");
                    $("#stok").val("");
                                
                    //jika ada perubahan di kode barang
                    $("#kode").change(function(){
                        kode=$("#kode").val();
                        
                        //tampilkan status loading dan animasinya
                        $("#status").html("loading. . .");
                        $("#loading").show();
                        
                        //lakukan pengiriman data
                        $.ajax({
                            url:"proses.php",
                            data:"op=ambildata&kode="+kode,
                            cache:false,
                            success:function(msg){
                                data=msg.split("|");
                                
                                //masukan isi data ke masing - masing field
                                $("#nama").val(data[0]);
                                $("#harga").val(data[1]);
                                $("#stok").val(data[2]);
                                $("#jumlah").focus();
                                //hilangkan status animasi dan loading
                                $("#status").html("");
                                $("#loading").hide();
                            }
                        });
                    });
                    
                    //jika tombol tambah di klik
                    $("#tambah").click(function(){
                        kode=$("#kode").val();
                        stok=$("#stok").val();
                        jumlah=$("#jumlah").val();
                        if(kode=="Kode Barang"){
                            alert("Kode Barang Harus diisi");
                            exit();
                        }else if(jumlah > stok){
                            alert("Stok tidak terpenuhi");
                            $("#jumlah").focus();
                            exit();
                        }else if(jumlah < 1){
                            alert("Jumlah beli tidak boleh 0");
                            $("#jumlah").focus();
                            exit();
                        }
                        nama=$("#nama").val();
                        harga=$("#harga").val();
                        
                                                
                        $("#status").html("sedang diproses. . .");
                        $("#loading").show();
                        
                        $.ajax({
                            url:"keranjang.php",
                            data:"op=tambah&kode="+kode+"&nama="+nama+"&harga="+harga+"&jumlah="+jumlah,
                            cache:false,
                            success:function(msg){
                                if(msg=="sukses"){
                                    $("#status").html("Berhasil disimpan. . .");
                                }else{
                                    $("#status").html("ERROR. . .");
                                }
                                $("#loading").hide();
                                $("#nama").val("");
                                $("#harga").val("");
                                $("#jumlah").val("");
                                $("#stok").val("");
                                $("#kode").load("keranjang.php","op=ambilbarang");
                                $("#barang").load("keranjang.php","op=barang");
                            }
                        });
                    });
                    
                    //jika tombol proses diklik
                    $("#proses").click(function(){
                        nota=$("#nota").val();
                        tanggal=$("#tanggal").val();
                        
                        $.ajax({
                            url:"keranjang.php",
                            data:"op=proses&nota="+nota+"&tanggal="+tanggal,
                            cache:false,
                            success:function(msg){
                                if(msg=='sukses'){
                                    $("#status").html('Transaksi Pembelian berhasil');
                                    alert('Transaksi Berhasil');
                                    exit();
                                }else{
                                    $("#status").html('Transaksi Gagal');
                                    alert('Transaksi Gagal');
                                    exit();
                                }
                                $("#kode").load("keranjang.php","op=ambilbarang");
                                $("#barang").load("keranjang.php","op=barang");
                                $("#loading").hide();
                                $("#nama").val("");
                                $("#harga").val("");
                                $("#jumlah").val("");
                                $("#stok").val("");
                            }
                        })
                    })
                });
            </script>
        </head>
        <body>

                <?php
                include "koneksi.php";
                $p=isset($_GET['act'])?$_GET['act']:null;
                switch($p){
                    default:
                    include 'template.php';
                        echo "
                
                <section class='main clearfix'>
                <section class='wrapper'>
                    <div class='content'>
                    <div class='panel-body'>
                    <div class='row'>
                    <div class='col-xs-4'>
                        <h3 align='center'>Laporan Penjualan</h3>
                        <table class='table table-bordered'>
                                <tr style='background-color:#f1f1f1; color:#000;'>
                                    <th>No</th>
                                    <th>Kode Nota</th>
                                    <th>Tanggal</th>
                                    <th>Total Bayar</th>
                                    <th>Aksi</th>
                                </tr>";
                                $query=mysql_query("select * from penjualan order by nonota desc");
                                $no=1;
                                while($r=mysql_fetch_array($query)){
                                    echo "<tr>
                                            <td>$no</td>
                                            <td>#$r[nonota] / $r[tanggal] / Annisa Desritasari</td>
                                            <td>$r[tanggal]</td>
                                            <td>$r[total]</td>
                                            <td><a href='?page=penjualan&act=detail&nota=$r[nonota]' target='_blank'>Cetak Nota</a></td>
                                        </tr>";
                                $no++;}
                                echo"</table></div></div></div></div></section></section>";
                        
                        break;
                    case "tambah":
                        $tgl=date('Y-m-d');
                        //untuk autonumber di nota
                        $auto=mysql_query("select * from penjualan order by nonota desc limit 1");
                        $no=mysql_fetch_array($auto);
                        $angka=$no['nonota']+1;
                        include 'template.php';
                        echo "
                        <section class='main clearfix'>
                <section class='wrapper'>
                    <div class='content'>
                    <div class='panel-body'>
                    <div class='row'>
                            <h3 align='center'>Transaksi Penjualan</h3>
                        <div class='navbar-form pull-right'>
                                Nota : <input type='text' id='nota' value='$angka' readonly >
                                Tanggal : <input type='text' id='tanggal' value='$tgl' readonly>   
                            </div>";
                            
                            echo'
                            <label>Pilihan Menu</label>
                            <select id="kode"></select>
                            <label>Menu Terpilih</label>
                            <input type="text" id="nama" placeholder="Nama Menu" readonly>
                            <input type="text" id="harga" placeholder="Harga" class="span2" readonly>
                            <input type="text" id="jumlah" placeholder="Jumlah Beli" class="span1"><br>
                            <button id="tambah" class="btn btn-info">Masukan Keranjang</button><br><br>
                            
                            <span id="status"></span>
                            <table id="barang" class="table table-bordered">
                                    
                            </table>
                            <div align="right">
                            <button type="button" class="btn btn-success btn-lg" id="proses">Proses Transaksi</button>
                            </div></div></div></div></div></section></section>';
                        break;
                    case "detail":
                        echo "<div class='container'>
                                <legend align='center'>
                                <b>Resto Seafood</b> <br> Koki Annisa<br>
                                Jl. Janturan No 12, Yogyakarta</legend>";
                        $nota=$_GET['nota'];
                        $query=mysql_query("select penjualan.nonota,detailpenjualan.kode,menu.nama,
                                           detailpenjualan.harga,detailpenjualan.jumlah,detailpenjualan.subtotal
                                           from detailpenjualan,penjualan,menu
                                           where penjualan.nonota=detailpenjualan.nonota and menu.kode=detailpenjualan.kode
                                           and detailpenjualan.nonota='$nota'");
                        $nomor=mysql_fetch_array(mysql_query("select * from penjualan where nonota='$nota'"));
                        echo "<table ><p style='text-align: right;'>
                                #$nomor[nonota] / $nomor[tanggal] / Annisa Desritasari</p>
                            </table>";
                        echo "<table class='table table-hover'>
                                <thead>
                                    <tr style='background-color:#f1f1f1; color:#000;'>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th style='text-align:right;'>Harga</th>
                                        <th style='text-align:center;'>Jumlah</th>
                                        <th style='text-align:right;'>Total Harga</th>
                                    </tr>
                                </thead>";
                                $no=1;
                                while($r=mysql_fetch_row($query)){
                                    echo "<tr>
                                            <td>$no</td>
                                            <td>$r[2]</td>
                                            <td style='text-align:right;'>$r[3]</td>
                                            <td style='text-align:center;'>$r[4]</td>
                                            <td style='text-align:right;'>$r[5]</td>
                                        </tr>";
                                $no++;}
                                echo "<tr>
                                        <td colspan='4'><h5 align='left'>Total Bayar</h5></td>
                                        <td style='text-align:right;' colspan='1'><h4>$nomor[total]</h4></td>

                                    </tr>
                                    </table><legend></legend>
                                <p style='text-align: center;'>
                                *** Terimakasih, selamat berkunjung kembali ***</p>

                                    </div><script type='text/javascript'>
                                                    <!--
                                                    window.print();
                                                    //-->
                                                </script>";
                        break;
                }
                ?>

        </body>
    </html>