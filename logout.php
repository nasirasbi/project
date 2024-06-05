<?php

session_start();
$_SESSION = [];
session_unset();
session_destroy();
setcookie('lmp','',time()-60);
setcookie('lock', '', time()-60);
header("location:login.php")
?>