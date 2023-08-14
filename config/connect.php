<?php
    $conn = mysqli_connect('localhost','root','','okosotthon2','3306');
    if (!$conn) {
        die("hiba a kapcsolódás során ".mysqli_connect_error());
    }
    if (!$conn -> set_charset("utf8")) {
        echo "Karakterkodólás sikertelen";
    }