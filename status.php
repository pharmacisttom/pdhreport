<?php
//เชื่อมต่อฐานข้อมูล
$condb= mysqli_connect("192.168.111.254","root","boom123","opd") or die("Error: " . mysqli_error($condb));
mysqli_query($condb, "SET NAMES 'utf8' ");
 // คิวรี่ข้อมูลจากตาราง
$query = "select b.roomname,count(a.hn)as total from opd.opd a
LEFT JOIN hos.roomno b on a.regroom = b.roomcode
where a.regdate = date(now())
GROUP BY a.regroom asc";
$result = mysqli_query($condb, $query);

    //นำข้อมูลที่ได้จากคิวรี่มากำหนดรูปแบบข้อมุลให้ถูกโครงสร้างของกราฟที่ใช้ *อ่าน docs เพิ่มเติม
    $datax = array();
    foreach ($result as $k) {
      $datax[] = "['".$k['roomname']."'".", ".$k['total']."]";
    }

    //cut last commar
    $datax = implode(",", $datax);
    //แสดงข้อมูลก่อนนำไปแสดงบนกราฟ
    echo $datax; //ถ้าอยากเอาออก ก็ใส่ double slash ข้างหน้าครับ
?>
<html>
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  <title>รายงานผู้มารับบริการผู้ป่วยนอกวันนี้โรงพยาบาลปลวกแดง</title>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <h3>รายงานผู้มารับบริการผู้ป่วยนอกโรงพยาบาลปลวกแดง</h3>
  <nav class="navbar navbar-expand-sm bg-secondary navbar-dark">
  <ul class="navbar-nav">
    <li class="nav-item active">
      <a class="nav-link" href="#">Active</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Link</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Link</a>
    </li>
    <li class="nav-item">
      <a class="nav-link disabled" href="#">Disabled</a>
    </li>
  </ul>
</nav>
    <!-- เรียก js มาใช้งาน -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Summary per product_type'],
          <?php echo $datax;?>
        ]);

        var options = {
          title: 'การลงทะเบียนรับบริการประจำวัน'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
  </body>
</html>
