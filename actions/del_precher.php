<?php 
	include '../config.php';
	$id = $_GET['id'];
	echo $id;
	$stmt = $conn->query("DELETE FROM `preachers` WHERE `id` = $id");
	//header("location: ../index.php");
?>