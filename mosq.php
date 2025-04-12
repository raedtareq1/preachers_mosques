<?php 
    include 'config.php';
    $pageTitle = "المساجد";
    include 'includes/header.php';
    include 'includes/navBar.php';
    // 
    $mosq = "";
    if(isset($_GET['mosq'])){
        $mosq = $_GET['mosq'];
    }
    if ($_SERVER["REQUEST_METHOD"] =="GET" && isset($_GET["text_search"])) {
        $text = $_GET["text_search"];
        echo $text;
    }
?>
	<div class="container index">
        <?php 
        if($mosq == "add"){?>
            <form action="?mosq=insert" method="POST">
                <div class="form-group">
                    <label for="prech_name">ادخل اسم المسجد</label>
                    <input type="text" class="form-control" name="mosq_name" id="prech_name">
                </div>
                <button type="submit" class="btn btn-primary btn-sm"> اضافة خطيب</button>
            </form>
        <?php } elseif ($mosq == "insert") {
            $mosq_name = $_POST['mosq_name'];
            $conn->query("INSERT INTO `mosques`( `name`) VALUES ('$mosq_name')");
            header("location: ?");
        }elseif ($mosq == "edit") {
            $id = $_GET['id'];
                    $stmt = $conn->prepare("SELECT * FROM `mosques` WHERE id = $id");
                    $stmt->execute();
                    $data = $stmt->fetch();
            ?>
            <form action="?mosq=update" method="POST">
                <div class="form-group">
                    <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                    <label for="prech_name">ادخل اسم المسجد</label>
                    <input type="text" class="form-control" value="<?php echo $data['name'];?>" name="mosq_name" id="prech_name">
                    <textarea name="notes" class="form-control mb-3" id="notes" placeholder="اكتب ملاحظة"><?php echo $data['notes'];?></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm"> حفظ</button>
            </form>
        <?php } elseif($mosq == "update"){
            $id = $_POST['id'];
            $mosq_name = $_POST['mosq_name'];
            $mosq_notes = trim($_POST['notes']);
            $conn->query("UPDATE
                                `mosques`
                            SET
                                `name` = '$mosq_name',
                                `notes` = '$mosq_notes'
                            WHERE
                                `id` = $id");
                                header("location: mosq.php");
        }elseif($mosq == "del"){
            $id = $_GET['id'];
            $stmt = $conn->query("DELETE FROM `mosques` WHERE `id` = $id");
            header("location: ?");
        }else{?>
            <input type="text"  name="text_search" class="form-control border-primary text-danger" onkeyup="getvalueinput(this.value)" placeholder="ابحث في الجدول">
            <div class="table-responsive-sm">
                <table class="table text-center table-hover">
                    <thead>
                        <th>#</th>
                        <th>اسم المسجد</th>
                        <th>الحدث</th>
                        <th>ملاحظات</th>
                    </thead>
                    <tbody class="resualt_data">
                        <?php 
                        $stmt = $conn->query("SELECT * FROM `mosques` ORDER BY `name` ASC");
                        $data = $stmt->fetchAll();
                        $indexing = 1;
                        foreach ($data as $value) {
                                echo '<tr>';
                                    echo '<td>'. $indexing++.'</td>';
                                    echo '<td>'. $value["name"].'</td>';
                                    echo '<td>';
                                        echo '<a href="?mosq=edit&id='. $value["id"] .'" class="btn btn-primary btn-sm"title="edit"><i class="fa fa-edit"></i></a> ';
                                        echo '<a href="?mosq=del&id='. $value["id"] .'" class="btn btn-danger btn-sm" onclick="return do_sure()"title="delete"> <i class="fa fa-trash"></i></a>';
                                    echo '</td>';
                                    echo '<td>'. $value["notes"] .'</td>';
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
                <a href="?mosq=add" class="btn btn-primary my-3"><i class="fa fa-plus"></i> اضافة مسجد</a>
            </div> 
        <?php } ?>
    </div>
    <?php include 'includes/footer.php';?>
    
<script>
        function getvalueinput(value){
            let resualt_data = document.querySelector(".resualt_data");
            let xhr = new XMLHttpRequest();
            xhr.open("GET",`actions/search_mosq.php?name=${value}`,true)
            xhr.onreadystatechange = function (){
                if (this.readyState == 4 && this.status == 200) {
                    let data = JSON.parse(this.response);
                    let tr = ''
                    let indexing = 1
                    for (let index = 0; index < data.length; index++) {
                        console.log(data[index]['name']);
                        tr += `
                        <tr>
                                    <td>${indexing++}</td>
                                    <td>${data[index].name}</td>
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