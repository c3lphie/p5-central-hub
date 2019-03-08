<?php
require_once '../lib/database.php';

$conn = GetConnection();

$query = $conn->query("SHOW DATABASES");

while($row = mysqli_fetch_assoc($query)){
    foreach($row as $cname => $cvalue){
        print "$cname: $cvalue\t";
    }
    print "\r\n";
}