<?php
    include 'config.php';
    $pageTitle = "الخطباء";
    include 'includes/header.php';
    include 'includes/navBar.php';
    $prch = '';
    if (isset($_GET['prch'])) {
        $prch = $_GET['prch'];
    }
    if ($_SERVER["REQUEST_METHOD"] =="GET" && isset($_GET["text_search"])) {
        $text = $_GET["text_search"];
        echo $text;
    }
?>
<div class="container index">
    <?php 
        if ($prch == "add") {?>
            <form action="?prch=insert" method="POST">
                <div class="form-group">
                    <label for="prech_name">ادخل اسم الخطيب</label>
                    <input type="text" class="form-control" name="prech_name" id="prech_name">
                </div>
                <button type="submit" class="btn btn-primary btn-sm"> اضافة خطيب</button>
            </form>
        <?php }elseif($prch == "insert"){
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $prech_name = $_POST['prech_name'];

                $stmt = $conn->query("INSERT INTO `preachers`(`name`)VALUES('$prech_name')");
                header('location: preachers.php');
            }
        }elseif($prch == "edit"){
                $id = $_GET['id'];
                $stmt = $conn->prepare("SELECT * FROM `preachers` WHERE id = $id");
                $stmt->execute();
                $data = $stmt->fetch();
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $prech_name = trim($_POST['prech_name']);
                    $stmt = $conn->query("UPDATE `preachers` SET `name` = '$prech_name' WHERE `id` = $id");
                }
            ?>
            <form action="?prch=update" method="POST">
                <div class="form-group">
                    <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                    <label for="prech_name">ادخل اسم الخطيب</label>
                    <input type="text" class="form-control" value="<?php echo $data['name'];?>" name="prech_name" id="prech_name">
                    <textarea name="notes" class="form-control mb-3" id="notes" placeholder="اكتب ملاحظة"><?php echo $data['notes'];?></textarea>
                </div>
            <button type="submit" class="btn btn-primary btn-sm"> تحديث</button>
            </form>
        <?php } elseif($prch == "update"){ 
            $id = $_POST['id'];
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $prech_name = $_POST['prech_name'];
                $prech_notes = trim($_POST['notes']);
                $stmt = $conn->query("UPDATE `preachers` SET `name` = '$prech_name',`notes` = '$prech_notes' WHERE `id` = $id");
                header("location: preachers.php");
            }
            } elseif($prch == "del"){
            $id = $_GET['id'];
            $stmt = $conn->query("DELETE FROM `preachers` WHERE `id` = $id");
            header("location: ?");
        }else{?>
            <input type="text"  name="text_search" class="form-control border-primary text-danger" onkeyup="getvalueinput(this.value)" placeholder="ابحث في الجدول">
            <div class="table-responsive-sm">
                <table class="table text-center table-hover">
                    <thead>
                        <th>#</th>
                        <th>اسم الخطيب</th>
                        <th>صورته</th>
                        <th>مرات الخطابة</th>
                        <th>الحدث</th>
                        <th>ملاحظات</th>
                    </thead>
                    <tbody class="resualt_data">
                        <?php 
                        $stmt = $conn->query("SELECT * FROM `preachers` ORDER BY `name` ASC");
                        $data = $stmt->fetchAll();
                        $indexing = 1;
                        foreach ($data as $value) {
                            echo '<tr>';
                                echo '<td>'.$indexing++.'</td>';
                                echo '<td>'.$value["name"].'</td>';
                                echo '<td>img</td>';
                                echo '<td>1</td>';
                                echo '<td>';
                                    echo '<a href="?prch=edit&id='. $value["id"] .'" class="btn btn-primary btn-sm" title="edit"><i class="fa fa-edit"></i></a> ';
                                    echo '<a href="?prch=del&id='. $value["id"] .'" class="btn btn-danger btn-sm" onclick="return do_sure()" title="delete"><i class="fa fa-trash"></i></a>';
                                echo '</td>';
                                echo '<td>'.$value["notes"].'</td>';
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="?prch=add" class="btn btn-primary my-3"><i class="fa fa-plus"></i> اضافة خطيب</a>
            </div> 
        <?php }
    ?>
</div>
<?php include 'includes/footer.php';?>
<script>
        function getvalueinput(value){
            let resualt_data = document.querySelector(".resualt_data");
            let xhr = new XMLHttpRequest();
            xhr.open("GET",`actions/search_preachers.php?name=${value}`,true)
            xhr.onreadystatechange = function (){
                if (this.readyState == 4 && this.status == 200) {
                    let data = JSON.parse(this.response);
                    let tr = ''
                    for (let index = 0; index < data.length; index++) {
                        console.log(data[index]['name']);
                        tr += `
                        <tr>
                                    <td>${data[index].id}</td>
                                    <td>${data[index].name}</td>
                                    <td>img</td>
                                    <td>1</td>
                                    <td>
                                        <a href="?prch=edit&id='. $value["id"] .'" class="btn btn-primary btn-sm" title="edit"><i class="fa fa-edit"></i></a> 
                                        <a href="?prch=del&id='. $value["id"] .'" class="btn btn-danger btn-sm" onclick="return do_sure()" title="delete"><i class="fa fa-trash"></i></a>
                                    </td>
                                    <td>-</td>
                                </tr>
                        `;
                        resualt_data.innerHTML = tr
                    }
                }
            }
            xhr.send();
        }
</script>