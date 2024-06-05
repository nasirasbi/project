<?php

include 'connect.php';

global $conn;
// function query
function query($query){
    global $conn;
    $result = mysqli_query($conn,$query);
    $rows = [''];
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
}
// menambah barang baru
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $satuan = $_POST['satuan'];
    $stok = $_POST['stok'];

    $addtotable = mysqli_query($conn, "INSERT INTO stok (namabarang, satuan, stok) VALUES('$namabarang','$satuan','$stok')");
    if($addtotable){
        header('location:index.php');
    }else{
        echo 'Gagal menambahkan barang baru. Error: ' . mysqli_error($conn);
        header('location:index.php');
    }
}

//menambah barang masuk

if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $tanggal = $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];
    
    $cekstoksekarang = mysqli_query($conn,"SELECT * FROM stok WHERE idbarang='$barangnya'");
    $ambildata = mysqli_fetch_array($cekstoksekarang);

    $stoksekarang = $ambildata['stok'];
    $tamabahkanstoksekarangdenganjlh = $stoksekarang + $jumlah;

    $addtomasuk = mysqli_query($conn, "INSERT INTO barangmasuk (idbarang, keterangan, tanggal, jumlah) VALUES ('$barangnya', '$penerima', '$tanggal','$jumlah')");
    $updatestokmasuk = mysqli_query($conn, "UPDATE stok SET stok = '$tamabahkanstoksekarangdenganjlh' WHERE idbarang='$barangnya'");
    if($addtomasuk && $updatestokmasuk){
        header('location:barangmasuk.php');
    } else {
        echo 'Gagal menambahkan barang masuk.';
        header('location:barangmasuk.php');
    }
}

//menambah barang keluar

if(isset($_POST['barangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $tanggal = $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];
    
    $cekstoksekarang = mysqli_query($conn,"SELECT * FROM stok WHERE idbarang='$barangnya'");
    $ambildata = mysqli_fetch_array($cekstoksekarang);

    $stoksekarang = $ambildata['stok'];
    if($stoksekarang >= $jumlah){
        // jika barang cukup
        $tamabahkanstoksekarangdenganjlh = $stoksekarang - $jumlah;
        
        $addtokeluar= mysqli_query($conn, "INSERT INTO barangkeluar (idbarang, penerima, tanggal, jumlah) VALUES ('$barangnya', '$penerima', '$tanggal','$jumlah')");
        $updatestokmasuk = mysqli_query($conn, "UPDATE stok SET stok = '$tamabahkanstoksekarangdenganjlh' WHERE idbarang='$barangnya'");
        if($addtokeluar&& $updatestokmasuk){
            header('location:barangkeluar.php');
        } else {
            echo 'Gagal menambahkan barang masuk.';
            header('location:barangkeluar.php');
        }
    }else{
        // kalau barangnya tidak cukup
        echo '<script>alert("stok saat ini tidak mencukupi"); window.location.href="barangkeluar.php";</script>';
    }
}


// edit stok barang

if(isset($_POST['updatestokbarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $satuan = $_POST['satuan'];
    $update = mysqli_query($conn, "UPDATE stok SET namabarang = '$namabarang', satuan = '$satuan'  WHERE idbarang = '$idb'");
    if($update){
        header('location:index.php');
    } else {
        echo 'Gagal edit.';
        header('location:index.php');
    }
}



// aksi hapus stok barang
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn, "DELETE FROM stok WHERE idbarang='$idb'");
    if($hapus){
        header('location:index.php');
    } else {
        echo 'Gagal hapus.';
        header('location:index.php');
    }
}


// edit stok barang masuk
if(isset($_POST['updatebarangmasuk'])){
    $idb        = $_POST['idb'];
    $idm        = $_POST['idm'];
    $keterangan = $_POST['keterangan'];
    $jumlah     = $_POST['jumlah'];

    $stoksekarang    = mysqli_query($conn, "SELECT stok FROM stok WHERE idbarang='$idb'");
    $stoknya        = mysqli_fetch_array($stoksekarang);
    $lihatstok       = $stoknya['stok'];

    $jmlh = mysqli_query($conn, "SELECT * FROM barangmasuk WHERE idmasuk='$idm'");
    $jmlhnya = mysqli_fetch_array($jmlh);
    $jumlahsekarang = $jmlhnya['jumlah'];

    if ($jumlah < $jumlahsekarang) {
        $selisih = $jumlah - $jumlahsekarang;
        $stokbaru = $lihatstok - $selisih;
        $updatestok = mysqli_query($conn,"UPDATE stok SET stok='$stokbaru' WHERE idbarang='$idb'");
        $updatejumlah = mysqli_query($conn,"UPDATE barangmasuk SET jumlah='$jumlah', keterangan='$keterangan' WHERE idmasuk='$idm'");

        if ($updatestok && $updatejumlah) {
            header('location:barangmasuk.php');
        } else {
            echo 'Gagal edit.';
            header('location:barangmasuk.php');
        }
    } else {
        $selisih = $jumlahsekarang - $jumlah;
        $stokbaru = $lihatstok - $selisih;
        $updatestok = mysqli_query($conn,"UPDATE stok SET stok='$stokbaru' WHERE idbarang='$idb'");
        $updatejumlah = mysqli_query($conn,"UPDATE barangmasuk SET jumlah='$jumlah', keterangan='$keterangan' WHERE idmasuk='$idm'");

        if ($updatestok && $updatejumlah) {
            header('location:barangmasuk.php');
        } else {
            echo 'Gagal edit.';
            header('location:barangmasuk.php');
        }
    }
}

// hapus barang masuk
if (isset($_POST['hapusbarangmasuk'])) {
    $idb = $_POST['idb'];
    $jmlh = $_POST['jumlah'];
    $idm = $_POST['idm'];

    $getdatastok = mysqli_query($conn, "SELECT * FROM stok where idbarang= '$idb'");
    $data = mysqli_fetch_array($getdatastok);
    $stok = $data['stok'];

    $selisih = $stok-$jmlh;
    $update = mysqli_query($conn, "UPDATE stok SET stok='$selisih' WHERE idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"DELETE FROM barangmasuk WHERE idmasuk='$idm'");
    if ($update&&$hapusdata) {
        echo "<script>
                alert ('data berhasil di hapus');
                document.location.href ='barangmasuk.php';
        </script>";
    } else {
        echo "<script>
            alert ('data gagal di hapus');
            document.location.href ='barangmasuk.php';
        </script>";
    }
}
// edit stok barang keluar
if(isset($_POST['updatebarangkeluar'])){
    $idb        = $_POST['idb'] ;
    $idk        = $_POST['idk'] ;
    $penerima   = $_POST['penerima'] ;
    $jumlah     = $_POST['jumlah'] ;

        $stoksekarang    = mysqli_query($conn, "SELECT stok FROM stok WHERE idbarang='$idb'");
        $stoknya         = mysqli_fetch_array($stoksekarang);
        $lihatstok       = $stoknya['stok'];

        $jmlh = mysqli_query($conn, "SELECT * FROM barangkeluar WHERE idkeluar='$idk'");
        $jmlhnya = mysqli_fetch_array($jmlh);
        $jumlahsekarang = $jmlhnya['jumlah'] ?? 0;

        if ($jumlah < $jumlahsekarang) {
            $selisih = $jumlahsekarang - $jumlah;
            $stokbaru = $lihatstok+ $selisih;
            $updatestok = mysqli_query($conn,"UPDATE stok SET stok='$stokbaru' WHERE idbarang='$idb'");
            $updatejumlah = mysqli_query($conn,"UPDATE barangkeluar SET jumlah='$jumlah', penerima='$penerima' WHERE idkeluar='$idk'");

            if ($updatestok && $updatejumlah) {
                header('location:barangkeluar.php');
            } else {
                echo 'Gagal edit.';
                header('location:barangkeluar.php');
            }
        } else {
            $selisih = $jumlah - $jumlahsekarang;
            $stokbaru = $lihatstok - $selisih;
            $updatestok = mysqli_query($conn,"UPDATE stok SET stok='$stokbaru' WHERE idbarang='$idb'");
            $updatejumlah = mysqli_query($conn,"UPDATE barangkeluar SET jumlah='$jumlah', penerima='$penerima' WHERE idkeluar='$idk'");

            if ($updatestok && $updatejumlah) {
                header('location:barangkeluar.php');
            } else {
                echo 'Gagal edit.';
                header('location:barangkeluar.php');
            }
        }
    
}

// hapus barang keluar
if (isset($_POST['hapusbarangkeluar'])) {
    $idb = $_POST['idb'];
    $jmlh = $_POST['jumlah'];
    $idk = $_POST['idk'];

    $getdatastok = mysqli_query($conn, "SELECT * FROM stok where idbarang= '$idb'");
    $data = mysqli_fetch_array($getdatastok);
    $stok = $data['stok'];

    $selisih = $stok + $jmlh;
    $update = mysqli_query($conn, "UPDATE stok SET stok='$selisih' WHERE idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"DELETE FROM barangkeluar WHERE idkeluar='$idk'");
    if ($update && $hapusdata) {
        echo "<script>
                alert ('data berhasil di hapus');
                document.location.href ='barangkeluar.php';
        </script>";
    } else {
        echo "<script>
            alert ('data gagal di hapus');
            document.location.href ='barangkeluar.php';
        </script>";
    }
}
// tambahkan user baru
function create_akun($post){
    global $conn;
    $nama           = strtolower(stripslashes($post['nama']));
    $username       = strtolower(stripslashes($post['username']));
    $password       = mysqli_real_escape_string($conn, $post['password']);
    $password2      = mysqli_real_escape_string($conn, $post['password2']);
    $level          = strip_tags($post['level']);

    // cek konfirmasi password
    if ($password !== $password2){
        echo "<script>
                alert('konfirmasi password tidak sesuai !');
            </script>";
        return false;
    }else{
        // Cek apakah username sudah ada atau belum
        $result = mysqli_query($conn, "SELECT * FROM akun WHERE username = '$username'");
        if(mysqli_fetch_assoc($result)) {
            echo "<script>
                    alert('Username sudah terdaftar!');
                  </script>";
            return false;
        }else{
            // Cek apakah username sudah ada atau belum
        $result = mysqli_query($conn, "SELECT * FROM akun WHERE nama = '$nama'");
        if(mysqli_fetch_assoc($result)) {
            echo "<script>
                    alert('nama sudah terdaftar!');
                  </script>";
            return false;
        }
        }
    }

    // Encrypt the password before storing it
    $password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO akun (nama, username, password, level) VALUES ('$nama', '$username', '$password', '$level')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// hapus user
function hapus($id){
    global $conn;
    mysqli_query($conn, "DELETE FROM akun WHERE id_akun=$id");
    return mysqli_affected_rows($conn);
}
// ubah user 
function update($post){
    global $conn;
    $id_akun        = strip_tags($post['id_akun']);
    $nama           = strtolower(stripslashes($post['nama']));
    $username       = strtolower(stripslashes($post['username']));
    $password       = mysqli_real_escape_string($conn, $post['password']);
    $password2      = mysqli_real_escape_string($conn, $post['password2']);
    $level          = strip_tags($post['level']);

    // cek konfirmasi password
    if ($password !== $password2){
        echo "<script>
                alert('konfirmasi password tidak sesuai !');
            </script>";
        return false;
    }else{
        // Cek apakah username sudah ada atau belum
        $result = mysqli_query($conn, "SELECT * FROM akun WHERE username = '$username'");
        if(mysqli_fetch_assoc($result)) {
            echo "<script>
                    alert('Username sudah terdaftar!');
                  </script>";
            return false;
        }
    }

    // Encrypt the password before storing it
    $password = password_hash($password, PASSWORD_DEFAULT);

    $query = "UPDATE akun SET nama='$nama', username ='$username', password ='$password', level = '$level' WHERE id_akun='$id_akun'";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}
