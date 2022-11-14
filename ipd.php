<?php
//เรียกใช้งานไฟล์เชื่อมต่อฐานข้อมูล
require_once 'connect.php';
//คิวรี่ข้อมูลหาผมรวมยอดขายโดยใช้ SQL SUM
$stmt = $conn->prepare(" SELECT count(hn) AS total, DATE_FORMAT(dateadm, '%Y') AS datesave
FROM ipd.ipd
where ipd.dateadm BETWEEN 20180101 and date(now())
GROUP BY DATE_FORMAT(dateadm, '%Y%')
ORDER BY DATE_FORMAT(dateadm, '%Y%') asc");
$stmt->execute();
$result = $stmt->fetchAll();

//นำข้อมูลที่ได้จากคิวรี่มากำหนดรูปแบบข้อมุลให้ถูกโครงสร้างของกราฟที่ใช้ 
$datesave = array();
$total = array();
foreach ($result as $rs) {
  $datesave[] = "\"".$rs['datesave']."\""; 
  $total[] = "\"".$rs['total']."\""; 
}
//ตัด commar อันสุดท้ายโดยใช้ implode เพื่อให้โครงสร้างข้อมูลถูกต้องก่อนจะนำไปแสดงบนกราฟ
$datesave = implode(",", $datesave); 
$total = implode(",", $total); 
?>
<html>
  <head>
    <title>รายงานผู้มารับบริการผู้ป่วยในโรงพยาบาลปลวกแดง</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- bootstrap5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <!-- call js -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-9"> <br>
          <h3>รายงานผู้มารับบริการผู้ป่วยในโรงพยาบาลปลวกแดง 5 ปี(ตั้งแต่ปี 2518-ปัจจุบัน)</h3>
 <!--devbanban.com-->
              <canvas id="myChart" width="800px" height="300px"></canvas>
              <script>
              var ctx = document.getElementById("myChart").getContext('2d');
              var myChart = new Chart(ctx, {
                  type: 'bar',
                  data: {
                      labels: [<?php echo $datesave;?>
                  
                      ],
                      datasets: [{
                          label: 'รายงานภาพรวม แยกตามปี (ครั้ง)',
                          data: [<?php echo $total;?>
                          ],
                          backgroundColor: [
                              'rgba(255, 99, 132, 0.2)',
                              'rgba(54, 162, 235, 0.2)',
                              'rgba(255, 206, 86, 0.2)',
                              'rgba(75, 192, 192, 0.2)',
                              'rgba(153, 102, 255, 0.2)',
                              'rgba(255, 159, 64, 0.2)'
                          ],
                          borderColor: [
                              'rgba(255,99,132,1)',
                              'rgba(54, 162, 235, 1)',
                              'rgba(255, 206, 86, 1)',
                              'rgba(75, 192, 192, 1)',
                              'rgba(153, 102, 255, 1)',
                              'rgba(255, 159, 64, 1)'
                          ],
                          borderWidth: 1
                      }]
                  },
                  options: {
                      scales: {
                          yAxes: [{
                              ticks: {
                                  beginAtZero:true
                              }
                          }]
                      }
                  }
              });
              </script>  
        </div>
      </div>
    </div>
  </body>
  
</html>


