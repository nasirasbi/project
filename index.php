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

$stok = mysqli_query($conn, "SELECT * FROM stok");


?>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Barang</h3>
            </div>
            <?php if ($_SESSION['level'] == 'admin' or $_SESSION['level'] == 'operator'): ?>
        <div class="buttons ">
            <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal"
                data-bs-target="#tambah">
                    <i class="bi bi-plus-square">
                        Tambah
                    </i>
            </button>
            <a href="export/export_stok_barang.php" target="_blank">
                <button class="btn btn-outline-primary" type="button" style="float: right;">Export</button>
            </a>
        </div>
        <?php endif; ?>
        </div>
    </div>
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
            <h5 class="card-title">
                        Data Barang
                    </h5>
                    <?php
                         $ambildatastok = mysqli_query($conn, "SELECT * FROM stok WHERE stok <2");
                            while($fecth=mysqli_fetch_array($ambildatastok)){
                              $barang = $fecth['namabarang'];
                            ?>
                          <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong style="color:red;"> <i class="bi bi-exclamation-triangle"> </i>Perhatian!</strong> Stok <?=$barang?> Telah Habis, Mohon Diorder
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                      <?php
                    }
                    ?> 
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table1">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <?php if ($_SESSION['level'] =='admin' or $_SESSION['level'] == 'operator' ):?>
                            <th>Aksi</th>
                            <?php endif;?>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $ambilsemuadatastok = mysqli_query($conn, "SELECT * FROM stok ");
                              $i = 1;
                              while($data = mysqli_fetch_array($ambilsemuadatastok)){
                                // $lokasi = $data['lokasi'];
                                $namabarang = $data['namabarang'];
                                $stok = $data['stok'];
                                $satuan = $data['satuan'];
                                $idb = $data['idbarang'];
                          ?>
                          <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $namabarang ; ?></td>
                            <td><?= $stok ; ?></td>
                            <td><?= $satuan ; ?></td>
                            <?php if($_SESSION['level']== 'admin' or $_SESSION ['level'] == 'operator' ) :?>
                            <td>
                              <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#edit<?= $idb; ?>">
                                <i class="bi bi-pencil-square"></i>
                              </button>
                              <input type="hidden" name="idbaranghapus" value="<?= $idb ;?>">
                              <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#hapus<?= $idb; ?>">
                                <i class="bi bi-trash3-fill"></i>
                              </button>
                            </td>
                            <?php endif?>
                          </tr>
                          <!-- modal edit -->
                          <div class="modal fade" id="edit<?= $idb ;?>">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title">Edit Barang</h4>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal">&times;</button>
                                </div>
                                <form action="" method="post">
                                  <div class="modal-body">
                                    <input type="text" id="namabarang" name="namabarang" value="<?= $namabarang; ?>" class="form-control"required><br>
                                    <input type="text" id="satuan" name="satuan" value="<?= $satuan; ?>" class="form-control"  required><br>
                                    <input type="hidden" name="idb" value="<?= $idb ; ?>"><br>
                                    <button type="submit" class=" btn btn-primary" name="updatestokbarang">Submit</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                          <!-- modal hapus -->                               
                          <div class="modal fade" id="hapus<?= $idb; ?>">
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
                                    <br><br>
                                    <button type="submit" class=" btn btn-danger" name="hapusbarang">Hapus</button>
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
              <input type="text" name="namabarang" placeholder="Nama Barang" class="form-control" required><br>
              <input type="text" name="satuan" placeholder="Satuan" class="form-control" required><br>
              <input type="number" name="stok" class="form-control" placeholder="Stok" required><br>
              <button type="submit" class=" btn btn-primary" name="addnewbarang">Submit</button>
          </div>
          </form>
      </div>
  </div>
</div>

<?php include 'views/footer.php';?>