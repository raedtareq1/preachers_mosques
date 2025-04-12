<?php 
    include 'config.php';
    $pageTitle = "انشاء جدول تلقائي";
    $stmt = $conn->prepare("SELECT schedules.*, mosques.*,preachers.* FROM schedules INNER JOIN mosques INNER JOIN preachers");
    $stmt->execute();
    $data = $stmt->fetchall();
    include 'includes/header.php';
    include 'includes/navbar.php'; ?>
<div class="container index">
    <h1 class="text-primary mt-3 mb-5">
        <strong>توليد جدول خطابة تلقائي</strong>
    </h1>
    <form class="form" onsubmit="return false" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        <div class="row">
            <!-- <div class="col-sm-12 col-md-4 col-lg-4">
                <input type="text" class="form-control precher" placeholder="عدد المشايخ" name="rand_precher" >
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
                <input type="text" class="form-control mosque" placeholder="عدد المساجد" name="rand_mosque">
            </div> -->
            <!-- <div class="col-sm-12 col-md-4 col-lg-4">
                <input type="text" class="form-control count-row" placeholder="عدد النتائج" name="rand_count">
            </div> -->
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
<?php include 'includes/footer.php'; ?>
<script>
    document.querySelector(".btn-confirm").style.display = 'none';
    function show_data(){
        // let precher     = document.querySelector(".precher").value;
        // let mosque      = document.querySelector(".mosque").value;
        // let count_row   = document.querySelector(".count-row").value;
        let btn_confirm   = document.querySelector(".btn-confirm").style.dispaly = 'none';
        
        let xhr = new XMLHttpRequest();
        xhr.open("GET","schedule.php",true)
        xhr.onreadystatechange = function (){
            if (this.readyState == 4 && this.status == 200) {

                // if (count_row > 0) {
                //     document.querySelector(".btn-confirm").style.display = 'block';   
                // }else{
                //     alert('يجب ادخال الحقول');
                // }
                let data = JSON.parse(this.response);                
                let result_data = document.querySelector(".result-data");
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
    function confirm_prechers_mosques(){
        if (confirm("سيتم حذف البيانات المدخلة يدوياً, واعتماد هذا الجدول")) {
            document.querySelector(".result-data").innerHTML = ''
            document.querySelector(".btn-confirm").style.display = 'none';
        }
    }
</script>