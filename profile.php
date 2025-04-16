<?php 
    session_start();
    include 'config.php';
    $pageTitle = 'Profile';
    include 'includes/tmp/header.php';
    include 'includes/tmp/navbar.php';
    // 
    $id = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT * FROM `premissions` WHERE `user_id`= $id");
    $stmt->execute();
    $user = $stmt->fetch();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userEmail = trim($_POST['uEmail']);
        $userFName = trim($_POST['fullName']);
        // 
        $user_name = $_FILES['imageUser']['name'];
        $temp_file = $_FILES['imageUser']['tmp_name'];
        move_uploaded_file($temp_file,"layout/images/".$user_name);
        $user_img = $_FILES['imageUser'] == '' ? 'avatar.png' : $_FILES['imageUser']['name'];
        $stmt = $conn->query("UPDATE
                                    `premissions`
                                SET
                                    `fullName` = '$userFName',
                                    `userEmail` = '$userEmail',
                                    `user_avatar` = '$user_img'
                                WHERE
                                    `user_id` = $id");

        header("location: profile.php");
    }
?>
    <div class="container index profile">
        <h1 class="text-primary mt-3 mb-5 title"><strong>حسابي</strong></h1>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="form-horizontal mt-3 mb-3" enctype="multipart/form-data">
            <div class="form-group">
                <label for="uName">اسم المستخدم</label>
                <input type="text" readonly="" name="username" value="<?php echo $user['userName'];?>" id="uName" class="form-control mb-2" placeholder="UserName">
            </div>
            <div class="form-group">
                <label for="uEmail">الايميل</label>
                <input type="text" name="uEmail" id="uEmail" value="<?php echo $user['userEmail'];?>" class="form-control mb-2" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="uFName">الاسم الكامل</label>
                <input type="text" name="fullName" id="uFName" value="<?php echo $user['fullName'];?>" class="form-control mb-2" placeholder="Full Name">
            </div>
            <div class="form-group">
                <div id="prev">
                    <?php 
                        if ($user['user_avatar'] == '') {
                            echo '<img src="layout/images/avatar.png">';
                        }else{
                            echo '<img src="layout/images/'. $user["user_avatar"] .'">';
                        }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <input type="file" style="display: none" name="imageUser" id="image_change">
                        <label for="image_change" class="btn btn-success text-white">تغير الصورة</label>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary mt-2 mb-2"> <i class="fa fa-edit"></i> تحديث</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php include_once 'includes/tmp/footer.php';?>