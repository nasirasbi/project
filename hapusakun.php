<?php
include 'database/app.php';
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
$id = $_GET['id_akun'];

if(hapus($id)>0){
    echo "<script>
                alert ('data berhasil di hapus');
                document.location.href ='admin.php';
        </script>";
    } else {
        echo "<script>
            alert ('data gagal di hapus');
            document.location.href ='admin.php';
        </script>";
}