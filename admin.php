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
if($_SESSION['level'] != 'admin'){
    echo "<script>
    alert('anda tidak punya akses ');  
    document.location.href ='index.php';
    </script>
    ";
    exit;
}





include 'database/app.php';

$nama    = $_SESSION['nama'];
$level   = $_SESSION['level'];
$id_akun = $_SESSION['id_akun'];
$data_bylogin = mysqli_query($conn,"SELECT * FROM akun WHERE nama='$nama' AND level='$level' AND id_akun='$id_akun'");


// jika tombol ubah ditekan jalankan script di bawah
if (isset($_POST['ubah'])) {
    if (update($_POST) > 0) {
        echo "<script>
        alert('akun berhasil diubah');
        document.location.href ='admin.php';
        </script>";
    } else {
        echo "<script>
        alert('akun gagal diubah');
        </script>";
    }
}

if (isset($_POST['tambah'])) {
    if (create_akun($_POST) > 0) {
        echo"<script>
        alert ('akun berhasil ditambahkan);
        </script>";
    }else{
        "<script>
        alert ('akun gagal ditambahkan ditambahkan);
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
            
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <?php if ($_SESSION['level'] == 'admin'): ?>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#user">
                    Tambah
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
<section class="section">
        <div class="row" id="table-striped">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Akun</h4>
                    </div>
                    <div class="card-content">
                        <!-- table striped -->
                        <div class="table-responsive" >
                            <table class="table table-striped mb-0 "id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <TH>NO</TH>
                                        <th>NAME</th>
                                        <th>USERNAME</th>
                                        <th>PASSWORD</th>
                                        <th>LEVEL</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data_akun = mysqli_query($conn, "SELECT * FROM akun");
                                    $no = 1; 
                                    while($dta = mysqli_fetch_array($data_akun)){
                                        $id         = $dta['id_akun'];
                                        $nama       = $dta ['nama'];
                                        $username   = $dta ['username'];
                                        $password   = $dta ['password'];
                                        $level      = $dta ['level'];
                                        ?>
                                    <tr>
                                        <td><?=$no++?></td>
                                        <td><?=$nama?></td>
                                        <td><?=$username?></td>
                                        <td>end-to-end</td>
                                        <td><?=$level?></td>
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
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Akun</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="post">
                                                    <input type="hidden" name="id_akun" value="<?= $id ?>">
                                                    <div class="mb-3">
                                                        <label for="nama">Nama</label>
                                                        <input type="text" name="nama" id="nama" value="<?= $nama ?>" class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="username">Username</label>
                                                        <input type="text" name="username" value="<?= $username?>" id="username" class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="password">Password <small>(Masukan Password baru/lama)</small></label>
                                                        <input type="password" name="password" id="password" class="form-control" required minlength="6">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="password2">Konfirmasi Password</label>
                                                        <input type="password" name="password2" id="password2" class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <select name="level" id="level" class="form-control">Level
                                                            <?= $level ?>
                                                            <option value="admin" <?= $level == 'admin'? 'selected' : null ?>>admin</option>
                                                            <option value="operator" <?= $level == 'operator'? 'selected' : null ?>>staff 1</option>
                                                            <option value="dokumen" <?= $level == 'dokumen'? 'selected' : null ?>>staff 2</option>
                                                        </select>
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
<div class="modal fade" id="user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Akun</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="post">
            <div class="mb-3">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required minlength="6">
            </div>
            <div class="mb-3">
                <label for="password2">Konfirmasi Password</label>
                <input type="password" name="password2" id="password2" class="form-control" required>
            </div>
            <div class="mb-3">
                <select name="level" id="level" class="form-control" required>Level
                    <option value="" >--pilih level--</option>
                    <option value="admin">admin</option>
                    <option value="operator">operator</option>
                    <option value="dokumen">dokumen</option>
                </select>
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