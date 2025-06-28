<?php 
 
    $HOST = "localhost";
    $USER = "root";
    $PASS = "";
    $DBNAME = "noots";


    $conn = new PDO("mysql:host=$HOST; dbname=$DBNAME",$USER,$PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


