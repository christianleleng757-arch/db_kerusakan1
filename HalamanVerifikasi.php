<?php
session_start();
session_destroy();
header("Location: HalamanLogin.php");
?>