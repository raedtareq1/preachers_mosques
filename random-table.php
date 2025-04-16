<?php 
    session_start();
    if (!isset($_SESSION['userPreacher'])) {
        header('location: login.php');
    }
    include 'config.php';
    $pageTitle = "انشاء جدول تلقائي";
    $stmt = $conn->prepare("SELECT schedules.*, mosques.*,preachers.* FROM schedules INNER JOIN mosques INNER JOIN preachers");
    $stmt->execute();
    $data = $stmt->fetchall();
    include 'includes/tmp/header.php';
    include 'includes/tmp/navbar.php'; ?>
<div class="container index">
    <h1 class="text-primary mt-3 mb-5 title">
        <strong>توليد جدول خطابة تلقائي</strong>
    </h1>
    <form class="form" onsubmit="return false" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        <div class="row">
            <button type="submit" class="btn btn-primary my-3 confairm-submited" onclick="show_data()"><i class="fa fa-refresh"></i> توليد</button>
        </div>
    </form>
    <div class="table-responsive-sm">
        <table class="table text-center table-hover">
            <thead>
                <th>#</th>
                <th>اسم الخطيب</th>
                <th>اسم المسجد</th>
                <th>مرات الخطابة</th>
            </thead>
            <tbody class="result-data">

            </tbody>
        </table>
    </div> 
    <button class="btn btn-success btn-confirm" onclick="confirm_prechers_mosques()">اعتماد</button>
</div>
<?php include 'includes/tmp/footer.php'; ?>
<script>
    // 
    let xhr = new XMLHttpRequest();
    xhr.open("GET","actions/get_all_precher.php",true)
    xhr.onreadystatechange = function (){
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.response);
        }
    }
    // 
    let btn_confirm = document.querySelector(".btn-confirm").style.display = 'none';
    let result_data = document.querySelector(".result-data");
    function show_data(){
        let btn_confirm   = document.querySelector(".btn-confirm").style.dispaly = 'none';
        let xhr = new XMLHttpRequest();
        xhr.open("GET","schedule.php",true)
        xhr.onreadystatechange = function (){
            if (this.readyState == 4 && this.status == 200) {
                let data = JSON.parse(this.response);
                let tr ='';
                let counter = 1;
                document.querySelector(".btn-confirm").style.display = 'block';
                for (let index = 0; index < data.length; index++) {
                    tr += `
                    <tr>
                        <td>${counter++}</td>
                        <td>${data[index]['prech_name']}</td>
                        <td>${data[index]['mosq_name']}</td>
                        <td>-</td>
                    </tr>`;
                }
                result_data.innerHTML = tr;
            }
        }
        xhr.send();
    }
    function confirm_prechers_mosques(data){
        alert("تم الاعتماد.");
        result_data.innerHTML = ''
        document.querySelector(".btn-confirm").style.display = 'none'
    }
    
</script>