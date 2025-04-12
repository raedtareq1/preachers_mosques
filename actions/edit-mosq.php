<?php
	include '../config.php';
	include '../includes/header.php';
	include '../includes/navBar.php';
	$id = $_GET['id'];
	$stmt = $conn->prepare("SELECT * FROM `mosques` WHERE `id_mosque` = $id");
	$stmt->execute();
	$prech = $stmt->fetch();
?>
	<div class="container index">
        <h1>تعديل بيانات الخطيب</h1>
        <form id="scheduleForm" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
            <label for="preach_name">اسم الخطيب:</label>
			<input type="text" name='prech_name' id='preach_name' value="<?php echo $prech['name_mosque'];?>">
            <!-- -------------------------- -->
            <button type="submit" class="btn btn-primary btn-sm">حفظ</button>
        </form>
	</div>
<?php include '../includes/footer.php'; ?>