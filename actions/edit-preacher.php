<?php 
    include '../config.php';
    $id = $_GET['id'];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $prech_name = $_POST['prech_name'];
        $stmt = $conn->query("UPDATE
                                        `preachers`
                                    SET
                                        `name` = ' $prech_name'
                                    WHERE
                                        id = $id");
        $prech = $stmt->fetch();
        header("location: ../preachers.php");
    }
?>
<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المساجد</title>
    <link rel="stylesheet" href="../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../layout/css/style.css">
</head>
<body>
    <?php #include '../includes/navBar.php';?>
	<div class="container index">
        <h1>تعديل البيانات</h1>
        <form action="edit-preacher.php?id=<?php echo $id;?>" method="POST">
            <?php 
                $stmt = $conn->prepare("SELECT * FROM `preachers` WHERE id = $id");
                $stmt->execute();
                $prech = $stmt->fetch();
            ?>
            <label for="preach_name">اسم الخطيب:</label>
            <input type="hidden" name="id" value="<?php echo $prech['id'];?>">
			<input type="text" name='prech_name' id='preach_name' value="<?php echo $prech['name'];?>">
            <!-- -------------------------- -->
            <button type="submit" class="btn btn-primary btn-sm">حفظ</button>
        </form>
        <a href="../preachers.php" class="btn btn-primary">للخلف</a>
    </div>
	<footer class="bg-dark text-white">
		this is footer
	</footer>
	<script src="js/script.js"></script>
</body>
</html>