<?php 
    include 'config.php';
    $pageTitle = 'برنامج ادارة الخطباء';
    include 'includes/header.php';
    $do = '';
    if (isset($_GET['do'])) {
        $do = $_GET['do'];
    }
?>
    <?php include 'includes/navBar.php'; ?>
    <?php if ($do == 'insert') {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $prch_name      = trim($_POST['preacher']);
        $prch_mosque    = trim($_POST['mosque']);
        $week      = $_POST['week'];
        $prch_date      = $_POST['date'];
        // 
        $errs = [];
        if (!empty($prch_name)) {
            if ($prch_name == 1) {
                $errs[] = "يجب كتابة الاسم";
            }
        }
        // 
        if (!empty($prch_mosque)) {
            if ($prch_mosque == 1) {
                $errs[] = "يجب كتابة المسجد";
            }
        }
        // 
        if (!empty($prch_data)) {
            if ($prch_data == 1) {
                $errs[] = "يجب كتابة التاريخ";
            }
        }
        
        if (empty($errs)) {
            $stmt = $conn->prepare("SELECT * FROM `preachers` WHERE `name`= '$prch_name'");
            $stmt->execute();
            $data = $stmt->fetch();
            $id_prech = $data['id'];
            // 
            $stmt = $conn->prepare("SELECT * FROM `mosques` WHERE `name`= '$prch_mosque'");
            $stmt->execute();
            $data = $stmt->fetch();
            $id_mosq = $data['id'];
            $conn->query("INSERT INTO `schedules`( `preacher_id`, `mosque_id`, `date`, `name_preacher`, `name_mosque`,`schd_week`)
                                            VALUES($id_prech, $id_mosq, '$prch_date', '$prch_name', '$prch_mosque',$week)");
            header("location: index.php");
        }
        else{
            foreach ($errs as $err) {
                echo '<div class="container alert alert-danger"> '.$err.'</div>';
                header("refresh: 3; url=index.php");
            }
        }
    }
    }elseif ($do == 'edit') {
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM `schedules` WHERE `id_sch` = $id");
        $stmt->execute();
        $prech_data = $stmt->fetch();
    ?>
        <div class="container index">
            <h1 class="text-primary"><strong>تعديل البيانات</strong></h1>
            <form action="?do=update" method="POST">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <label for="preacher">اختر الخطيب:</label>
                        <select id="preacher" name="preacher">
                            <?php 
                                $id = $_GET['id'];
                                $stmt = $conn->query("SELECT * FROM `preachers`");
                                $data = $stmt->fetchall();
                                echo "<option value='1'>...</option>";
                                foreach ($data as $value) {?>
                                    <option value='<?php echo $value['name']?>' <?php if($value['name'] == $prech_data['name_preacher']) echo 'selected';?> name='<?php echo 'prech_name'?>' > <?php echo $value['name'];?> </option>
                                <?php }
                                echo '<input type="hidden" value="'.$id.'" name="id">';
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <!-- -------------------------- -->
                        <label for="mosque">اختر المسجد:</label>
                        <select id="mosque" name="mosque">
                            <?php 
                                echo "<option value='1'>...</option>";
                                $stmt = $conn->query("SELECT * FROM `mosques`");
                                $mosqs = $stmt->fetchall();
                                // 
                                $id = $_GET['id'];
                                $mosq = $conn->prepare("SELECT `name` FROM `mosques` WHERE `id` = $id");
                                $mosq->execute();
                                $mosq_data = $mosq->fetch();
                                foreach ($mosqs as $value) {?>
                                    <option value='<?php echo $value['name']?>' <?php if($value['name'] == $prech_data['name_mosque']) echo 'selected';?>  name='mosq_name'><?php echo $value['name'] ?></option>
                                <?php }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <!-- -------------------------- -->
                        <label for="date">تاريخ الخطبة:</label>
                        <input type="date" id="date" name="date" value="<?php echo $prech_data['date']?>">
                        <!-- -------------------------- -->
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm" onclick="return do_sure()">تحديث</button>
            </form>
        </div>
    <?php } elseif ($do == 'update') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $prech_name = $_POST['preacher'];
            $mosq_name = $_POST['mosque'];
            $mosq_date = $_POST['date'];
            // preacher
            $sql = $conn->prepare("SELECT `id` FROM `preachers` WHERE `name` = '$prech_name'");
            $sql->execute();
            $name_preacher = $sql->fetch()['id'];
            // mosques
            $sql2 = $conn->prepare("SELECT `id` FROM `mosques` WHERE `name` = '$mosq_name'");
            $sql2->execute();
            $name_mosq = $sql2->fetch()['id'];
            // 
            $conn->query("UPDATE
                            `schedules`
                        SET
                            `preacher_id`   = '$name_preacher',
                            `mosque_id`     = '$name_mosq',
                            `date`     = '$mosq_date',
                            `name_preacher` = '$prech_name',
                            `name_mosque`   = '$mosq_name'
                        WHERE
                            `id_sch` = $id");
            header('location: index.php');
        }
    } elseif ($do == 'del') {
        $id = $_GET['id'];
        $conn->query("DELETE FROM `schedules` WHERE `id_sch` = $id");
        header("location: ?");
    } else{ ?>
        <div class="container index">
            <h1 class="text-primary mt-3 mb-5"><strong>برنامج ادارة الخطباء</strong></h1>
            <form action="?do=insert" method="POST">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <label for="preacher">اختر الخطيب:</label>
                        <select id="preacher" name="preacher">
                            <?php 
                                echo "<option value='1'>...</option>";
                                $stmt = $conn->query("SELECT preachers.name FROM `preachers` WHERE preachers.name NOT IN (SELECT schedules.name_preacher FROM schedules)");
                                $data = $stmt->fetchAll();
                                $indexing = 1;
                                foreach ($data as $value) {?>
                                    <option value="<?php echo $value['name']?>"><?php echo $indexing++ ." ) " . $value['name']?></option>
                                <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <!-- -------------------------- -->
                        <label for="mosque">اختر المسجد:</label>
                        <select id="mosque" name="mosque">
                            <?php 
                                echo "<option value='1'>...</option>";
                                $stmt = $conn->query("SELECT mosques.name FROM `mosques` WHERE mosques.name NOT IN (SELECT schedules.name_mosque FROM schedules)");
                                $data = $stmt->fetchAll();
                                $indexing = 1;
                                foreach ($data as $value) {?>
                                    <option value="<?php echo $value['name']?>"><?php echo $indexing++ ." ) " .  $value['name']?></option>
                                <?php }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <!-- -------------------------- -->
                        <label for="week">الاسبوع:</label>
                        <select id="week" name="week">
                            <?php 
                                echo "<option value='1'>...</option>";
                                for ($i=1; $i <= 4; $i++) { 
                                    echo "<option >" . $i . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <!-- -------------------------- -->
                        <label for="date">تاريخ الخطبة:</label>
                        <input type="date" id="date" name="date">
                        <!-- -------------------------- -->
                    </div>
                </div>
                <button type="submit" class="btn btn-success btn-sm confairm-submited">حفظ</button>
            </form>
            <!--  -->
            <?php 
                $stmt = $conn->query("SELECT schedules.*,mosques.name,preachers.monthly_limit FROM `schedules` INNER JOIN mosques ON mosques.id = schedules.mosque_id INNER JOIN preachers ON preachers.id = schedules.preacher_id");
                $countReslut = $stmt->rowCount();
                $data = $stmt->fetchAll();
                if ($countReslut > 0) {
                    echo '<div class="table-responsive-sm">';
                        echo '<table class="table text-center table-hover">';
                            echo '<thead>';
                                echo '<th>#</th>';
                                echo '<th> الخطيب</th>';
                                echo '<th>المسجد</th>';
                                echo '<th>تاريخ الخطابة</th>';
                                echo '<th>مرات الخطابة</th>';
                                echo '<th>الحدث</th>';
                                echo '<th>ملاحظات</th>';
                            echo '</thead>';
                            echo '<tbody>';
                            $indexing = 1;
                            foreach ($data as  $value) {
                                echo '<tr>';
                                    echo '<td>'. $indexing++ .'</td>';
                                    echo '<td>'. $value["name_preacher"] .'</td>';
                                    echo '<td>'. $value["name_mosque"]   .'</td>';
                                    echo '<td>'.$value["date"].'</td>';
                                    echo '<td class="limit_precher">'.$value["schd_week"].'</td>';
                                    echo '<td>';
                                        echo '<a href="?do=edit&id='.$value["id_sch"].'" class="btn btn-primary btn-sm" title="edit"><i class="fa fa-edit"></i></a> '; 
                                        echo '<a  href="?do=del&id='.$value["id_sch"].'" class="btn btn-danger btn-sm" onclick="return do_sure()" title="delete"><i class="fa fa-trash"></i></a>';
                                    echo '</td>';
                                    echo '<td>-</td>';
                                echo "</tr>";
                            }
                            echo '</tbody>';
                            echo '</table>';
                            echo '<div class="row">';
                                echo '<div class="col-sm-12 col-md-6">';
                                    echo '<a href="actions/del_all_schd.php" class="btn btn-danger w-100" onclick="return do_sure()"><i class="fa fa-trash"></i> إفراغ الجدول</a> ';
                                echo '</div>';
                                echo '<div class="col-sm-12 col-md-6">';
                                    echo '<button class="btn btn-primary" onclick="print()"><i class="fa fa-print"></i> طباعة الجدول</button>';
                                echo '</div>';
                            echo '</div>';
                }else{
                    echo '<div class="alert alert-warning text-center">لا يوجد بيانات لعرضها.</div>';
                }
            ?>
            </div> 
        </div> 
    <?php } ?>
<?php include 'includes/footer.php';?>