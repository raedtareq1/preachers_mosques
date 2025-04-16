<?php 
    session_start();
    if (!isset($_SESSION['userPreacher'])) {
        header('location: login.php');
    }
    require_once 'config.php';
    $pageTitle = 'برنامج ادارة الخطباء';
    include_once 'includes/tmp/header.php';
    $do = '';
    if (isset($_GET['do'])) {
        $do = $_GET['do'];
    }
?>
    <?php include 'includes/tmp/navBar.php'; ?>
    <?php if ($do == 'insert') {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $prch_name      = trim($_POST['preacher']);
        $prch_mosque    = trim($_POST['mosque']);
        $week           = $_POST['week'];
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
            $conn->query("INSERT INTO `schedules`
                                                ( `preacher_id`, `mosque_id`, `date`,      `schd_week` )
                                        VALUES
                                                ( $id_prech    ,  $id_mosq  , '$prch_date'  ,'$week'   )");
            header("location: index.php");
        } else{
            foreach ($errs as $err) {
                echo '<div class="container alert alert-danger"> '.$err.'</div>';
                header("refresh: 3; url=index.php");
            }
        }
    }
    }elseif ($do == 'edit') {
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT schedules.*,preachers.name AS prech_name,mosques.name AS name_mosque FROM schedules
                                        INNER JOIN preachers
                                        ON
                                        schedules.preacher_id = preachers.id
                                        INNER JOIN mosques
                                        ON
                                        schedules.mosque_id = mosques.id
                                        WHERE schedules.id_sch  = $id");
        $stmt->execute();
        $prech_data = $stmt->fetch();
    ?>
        <div class="container index">
            <h1 class="text-primary"><strong>تعديل البيانات</strong></h1>
            <form action="?do=update" method="POST">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <label for="preacher">اختر الخطيب:</label>
                        <select id="preacher" name="preacher">
                            <?php 
                                $id = $_GET['id'];
                                $stmt = $conn->query("SELECT * FROM `preachers`");
                                $data = $stmt->fetchall();
                                echo "<option value='1'>...</opton>";
                                foreach ($data as $value) {?>
                                    <option value='<?php echo $value['name']?>' <?php if($value['name'] == $prech_data['prech_name']) echo 'selected';?>> <?php echo $value['name'];?> </option>
                                <?php 
                                }
                                echo '<input type="hidden" value="'.$id.'" name="id">';
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3">
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
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <label for="date">تاريخ الخطبة:</label>
                        <input type="date" id="date" name="date" value="<?php echo $prech_data['date']?>">
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <label for="countPreachers">مرات الخطابة:</label>
                        <input type="text" id="countPreachers" name="schd_week" value="<?php echo $prech_data['schd_week']?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">تحديث</button>
            </form>
        </div>
    <?php } elseif ($do == 'update') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            echo $id;
            $prech_name = $_POST['preacher'];
            $mosq_name = $_POST['mosque'];
            $mosq_date = $_POST['date'];
            $schd_week = $_POST['schd_week'];
            // preacher ------------------------------
            $sql1 = $conn->prepare("SELECT `id` FROM `preachers` WHERE `name` = '$prech_name'");
            $sql1->execute();
            $name_preacher = $sql1->fetch()['id'];
            // mosques ------------------------------
            $sql2 = $conn->prepare("SELECT `id` FROM `mosques` WHERE `name` = '$mosq_name'");
            $sql2->execute();
            $name_mosq = $sql2->fetch()['id'];
            // ------------------------------
            $conn->query("UPDATE
                                `schedules`
                            SET
                                `preacher_id` = '$name_preacher',
                                `mosque_id` = '$name_mosq',
                                `date` = '$mosq_date',
                                `schd_week` = '$schd_week'
                            WHERE `id_sch`= $id");
            header('location: index.php');
        }
    } elseif ($do == 'del') {
        $id = $_GET['id'];
        $conn->query("DELETE FROM `schedules` WHERE `id_sch` = $id");
        header("location: ?");
    } else{ ?>
        <div class="container index">
            <h1 class="text-primary mt-3 mb-5 title"><strong>برنامج ادارة الخطباء</strong></h1>
            <form action="?do=insert" method="POST">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <label for="preacher">اختر الخطيب:</label>
                        <select id="preacher" name="preacher">
                            <?php 
                                echo "<option value='1'>...</option>";
                                $stmt = $conn->query("SELECT preachers.name FROM preachers WHERE preachers.id NOT IN (SELECT schedules.preacher_id FROM schedules)");
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
                                $stmt = $conn->query("SELECT mosques.name FROM mosques WHERE mosques.id NOT IN (SELECT schedules.mosque_id FROM schedules)");
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
                        <label for="chose_week">الاسبوع:</label>
                        <select id="chose_week" name="week">
                            <?php
                                $arr = ["الأول","الثاني","الثالث","الرابع"];
                                echo "<option value=''>...</option>";
                                foreach ($arr as $key => $week) {
                                    echo "<option value='" . ++$key . "'>" . $week . "</option>";
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
                <button type="submit" class="btn btn-success btn-sm confairm-submited">اضافة</button>
            </form>
            <!--  -->
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <!-- -------------------------- -->
                    <label for="week">اختر الاسبوع لعرض البيانات:</label>
                    <select id="week" name="select-week" onchange="getWeek(this.value)">
                        <?php 
                            $arr = ["الأول","الثاني","الثالث","الرابع"];
                            echo "<option value=''>الكل</option>";
                            foreach ($arr as $key => $week) {
                                echo "<option value='" . ++$key . "'>" . $week . "</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            <!--  -->
            <?php 
                $stmt = $conn->query("SELECT schedules.*,mosques.name AS name_mosque,preachers.name AS name_preacher FROM `schedules`
                                        INNER JOIN mosques
                                        ON mosques.id = schedules.mosque_id
                                        INNER JOIN preachers
                                        ON preachers.id = schedules.preacher_id ORDER BY preachers.name ASC" );
                $countReslut = $stmt->rowCount();
                $data = $stmt->fetchAll();
                if ($countReslut > 0) {
                    echo '<div class="table-responsive-sm">';
                        echo '<table class="table text-center table-hover">';
                            echo '<thead class="table-dark">';
                                echo '<th>#</th>';
                                echo '<th>الخطيب</th>';
                                echo '<th>المسجد</th>';
                                echo '<th>تاريخ الخطابة</th>';
                                echo '<th>مرات الخطابة</th>';
                                echo '<th>الحدث</th>';
                                echo '<th>ملاحظات</th>';
                            echo '</thead>';
                            echo '<tbody class="result-data">';
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
<?php include 'includes/tmp/footer.php';?>
<!-- AJAX METHOD -->
<script>
    function getWeek(week){
        let xml = new XMLHttpRequest();
        xml.open("GET",`actions/get-data-weeks.php?week=${week}`,true);
        xml.onreadystatechange = function (){
            if (this.readyState == 4 && this.status == 200) {
                let data = JSON.parse(this.response);
                if (data.length>0) {
                    let tr = '';
                    let indexing = 1;
                    for (let index = 0; index < data.length; index++) {
                        tr += `<tr>
                            <td>${indexing++}</td>
                            <td>${data[index].prch_name}</td>
                            <td>${data[index].mosq_name}</td>
                            <td>${data[index].date}</td>
                            <td class="limit_precher">${data[index].schd_week}</td>
                            <td>
                                <a href="?do=edit&id=${data[index].id_sch}" class="btn btn-primary btn-sm" title="edit"><i class="fa fa-edit"></i></a>
                                <a  href="?do=del&id=${data[index].id_sch}" class="btn btn-danger btn-sm" onclick="return do_sure()" title="delete"><i class="fa fa-trash"></i></a>
                            </td>
                            <td>-</td>
                        </tr>`
                    }
                    document.querySelector(".result-data").innerHTML = tr
                }else{
                    document.querySelector(".result-data").innerHTML = '<div class="alert alert-danger">لا يوجد بيانات لعرضها</div>'
                }
            }
        }
        xml.send();
        for (let index = 0; index < array.length; index++) {
            
        }
        let limtPreacher = document.querySelectorAll(".limit_precher").length;
        console.log(limtPreacher);
    }
</script>