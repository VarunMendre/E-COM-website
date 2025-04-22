<?php
    session_start();

    if (!isset($_SESSION['userid'])) {                
        die("Error: Unauthorized access.");
    }

    $userid=$_SESSION['userid'];
    $pid=$_GET['pid'];

    include "../shared/connection.php";

    $status = mysqli_query($conn,"insert into cart(userid,pid,status)values($userid,$pid,1)");

    if($status){
        echo"Product added successfully!";
        header("location:viewcart.php");
    }


    
?>