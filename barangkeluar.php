<?php 
session_start();
if(!isset($_SESSION['login'])){
    echo "<script>
    alert('login dulu bang');  
        document.location.href ='login.php';
    </script>
    ";
    exit;
}
include 'database/app.php';
include 'views/sidebar.php' ;
?>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            
        <div class="page-heading">
          <h3>Barang Keluar</h3>
        </div>
        <?php if ($_SESSION['level'] == 'admin' or $_SESSION['level'] == 'operator'): ?>
        <div class="buttons">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#tambah">
                    <i class="bi bi-plus-square">
                        Tambah
                    </i>
            </button>
        </div>
        <?php endif ;?>
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Data Barang
                </h5>
                <a href="export/export_barang_keluar.php" target="_blank">
                    <button class="btn btn-outline-primary" type="button" style="float: right;">Export</button>
                </a>
                <div class="row">
                    <div class="col">
                        <form action="" method="post" class="inline">
                            <input type="date" name="tanggal-mulai" class="from-control btn btn-outline-primary">
                            <input type="date" name="tanggal-akhir" class="from-control btn btn-outline-primary ml-3">
                            <button type="sumbit" name="filter" class="btn btn-outline-primary ml-3">Filter</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Barang</th>
                                <th>jumlah</th>
                                <th>Deskripsi</th>
                                <th>Penerima</th>
                                <?php if ($_SESSION['level'] =='admin' or $_SESSION['level'] =='operator' ):?>
                                <th>Aksi</th>
                                <?php endif;?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $i =1;
                        $id_akun = $_SESSION['id_akun'];
                        $databylogin = mysqli_query($conn, "SELECT * FROM barangkeluar k, stok s WHERE s.idbarang = k.idbarang ");
                        ?>
                                        
                        <?php
                        // pencarian sesuai tangal
                        if (isset($_POST['filter'])) {
                            $mulai = $_POST['tanggal-mulai'];
                            $akhir = $_POST['tanggal-akhir'];
                            if ($mulai !=null || $akhir!=null) {
                                $ambilsemuadakeluar = mysqli_query($conn, "SELECT * FROM barangkeluar k, stok s WHERE s.idbarang = k.idbarang AND tanggal BETWEEN '$mulai' AND '$akhir'");
                                }else{
                                    $ambilsemuadakeluar = mysqli_query($conn, "SELECT * FROM barangkeluar k, stok s WHERE s.idbarang = k.idbarang ");
                                }
                                }else{
                                    $ambilsemuadakeluar = mysqli_query($conn, "SELECT * FROM barangkeluar k, stok s WHERE s.idbarang = k.idbarang");
                                }
                                while($data=mysqli_fetch_array($ambilsemuadakeluar)){
                                    $idb            = $data ['idbarang'];
                                    $idk            = $data ['idkeluar'];
                                    $tanggal        = $data ['tanggal'];
                                    $namabarang     = $data ['namabarang'];
                                    $satuan         = $data ['satuan'];
                                    $jumlah         = $data ['jumlah'];
                                    $penerima       = $data ['penerima'];
                                ?>
                            <tr>
                                <td><?=$i++?></td>
                                <td><?= $tanggal; ?></td>
                                <td><?= $namabarang; ?></td>
                                <td><?= $jumlah; ?></td>
                                <td><?= $satuan; ?></td>
                                <td><?= $penerima; ?></td>
                                <?php if($_SESSION['level']== 'admin' or $_SESSION ['level'] =='operator' ) :?>
                                <td>
                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#edit<?= $idk; ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <input type="hidden" name="idbaranghapus" value="<?= $idk ;?>">
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#hapus<?= $idk; ?>">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </td>
                                <?php endif?>
                            </tr>
                            <!-- modal edit -->
                            <div class="modal fade" id="edit<?= $idk ;?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Edit Barang</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal">&times;</button>
                                        </div>
                                        <form action="" method="post">
                                            <div class="modal-body">
                                                <input type="text" name="penerima" value="<?= $penerima ?>" class="form-control"><br>
                                                <input type="number" id="jumlah" name="jumlah" value="<?= $jumlah; ?>" class="form-control" required><br>
                                                <input type="hidden" name="idb" value="<?= $idb ; ?>"><br>
                                                <input type="hidden" name="idk" value="<?= $idk ; ?>"><br>
                                                <button type="submit" class=" btn btn-primary" name="updatebarangkeluar">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- modal hapus -->                               
                            <div class="modal fade" id="hapus<?= $idk; ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Hapus Barang</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="" method="post">
                                            <div class="modal-body">
                                                Apakah Anda Yakin Ingin Menghapus <?= $namabarang ;?> ? 
                                                <input type="hidden" name="idb" value="<?= $idb ; ?>">
                                                <input type="hidden" name="jumlah" value="<?= $jumlah ; ?>">
                                                <input type="hidden" name="idk" value="<?= $idk ; ?>">
                                                <br><br>
                                                <button type="submit" class=" btn btn-danger" name="hapusbarangkeluar">Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- Basic Tables end -->
</div>

<!-- modal tambah barang--> 
<div class="modal fade text-left" id="tambah" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel160" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
        role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="myModalLabel160">Tambah Barang
                </h5>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <select name="barangnya" id="level" class="form-control">Nama Barang
                        <option value="" >--Nama Barang--</option>
                        <?php
                        $ambilsemuadatanya = mysqli_query($conn,"SELECT * FROM stok");
                        while($fetcharray = mysqli_fetch_array($ambilsemuadatanya)){
                            $namabarangnya = $fetcharray['namabarang'];
                            $idbarangnya = $fetcharray['idbarang'];
                            ?>
                        <option value="<?=$idbarangnya;?>"><?= $namabarangnya;?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <br>
                    <input type="date" name="tanggal" placeholder="Tanggal" class="form-control" required> <br>
                    <input type="text" name="penerima" placeholder="Penerima" class="form-control" required><br>
                    <input type="number" name="jumlah" placeholder="Jumlah" class="form-control" required><br>
                    <button type="submit" class=" btn btn-primary" name="barangkeluar">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'views/footer.php';?>