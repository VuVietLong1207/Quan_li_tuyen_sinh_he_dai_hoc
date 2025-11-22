<?php
session_start();
session_destroy();
header('Location: /BTL/index.php');
exit();
?>