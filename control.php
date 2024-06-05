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
if($_SESSION['level'] != 1){
    echo "<script>
    alert('anda tidak punya akses ');  
    document.location.href ='index.php';
    </script>
    ";
    exit;
}





include 'database/app.php';


// jika tombol ubah ditekan jalankan script di bawah
if (isset($_POST['ubah'])) {
    if (update_lokasi($_POST) > 0) {
        echo "<script>
        alert('lokasi berhasil diubah');
        document.location.href ='control.php';
        </script>";
    } else {
        echo "<script>
        alert('lokasi gagal diubah');
        </script>";
    }
}

if (isset($_POST['tambah'])) {
    if (create_lokasi($_POST) > 0) {
        echo"<script>
        alert ('lokasi berhasil ditambahkan);
        </script>";
    }else{
        "<script>
        alert ('lokasi gagal ditambahkan ditambahkan);
        </script>";
    }
}
include 'views/sidebar.php';

?>

<div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
<section class="section">
        <div class="row" id="table-striped">
            <div class="col-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Lokasi</h4>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#lokasi">
                          Tambah
                      </button>
                    </div>
                    <div class="card-content">
                        <!-- table striped -->
                        <div class="table-responsive" >
                            <table class="table table-striped mb-0 "id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Lokasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data_akun = mysqli_query($conn, "SELECT * FROM lokasi");
                                    $no = 1; 
                                    while($dta = mysqli_fetch_array($data_akun)){
                                        $id         = $dta['id'];
                                        $lokasi       = $dta ['lokasi'];
                                        ?>
                                    <tr>
                                        <td><?=$no++?></td>
                                        <td><?=$lokasi?></td>
                                        <td>
                                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#edit<?= $id ?>">
                                            <i class="bi bi-pencil-square"></i>
                                            </button>
                                                <input type="hidden" name="user" value="<?= $nama ;?>">
                                                <button type="button" name="hapususer" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#hapususer<?= $nama ;?>">
                                                    <i class="bi bi-trash3-fill">
                                                    </i>
                                                </button>
                                        </td>
                                    </tr>
                                    <!-- Modal ubah user -->
                                    <div class="modal fade" id="edit<?= $id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header bg-success">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Lokasi</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="post">
                                                    <input type="hidden" name="id" value="<?= $id ?>">
                                                    <div class="mb-3">
                                                        <label for="lokasi">Lokasi</label>
                                                        <input type="text" name="lokasi" id="lokasi" value="<?= $lokasi ?>" class="form-control" required>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="sumbit" class="btn btn-outline-success" name="ubah">Ubah</button>
                                            </div>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="hapususer<?= $nama ;?>">
                                                <form action="" method="post">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Hapus Barang</h4>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Yakin ingi Menghapus akun <?= $nama ?> ?
                                                                <input type="hidden" name="idakun" id="<?= $id_akun ?>"><br><br>
                                                                <a href="hapusakun.php?id_akun=<?= $id ?>" class="btn btn-outline-danger">Hapus</a>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                                <?php 
                                            }
                                            ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
<!-- Modal tambah user -->
<div class="modal fade" id="lokasi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Lokasi</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="post">
            <div class="mb-3">
                <label for="lokasi">lokasi</label>
                <input type="text" name="lokasi" id="lokasi" class="form-control" autocomplete="off" required>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
        <button type="sumbit" class="btn btn-primary" name="tambah">Simpan</button>
      </div>
    </form>
    </div>
  </div>
</div>
<?php include 'views/footer.php'; ?>