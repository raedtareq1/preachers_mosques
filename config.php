<?php
	try {
		$conn = new PDO("mysql:host=localhost;dbname=preacherdistribution","root","",
		[PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
	} catch (Exception $e) {
		echo $e;
	}
?>