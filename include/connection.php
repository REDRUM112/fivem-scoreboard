<?php
include 'C:\dummy\inventory\fmsb\example-esx\include\config.php';

 $link = mysqli_connect($host, $user, $password, $db);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>