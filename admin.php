<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->



<?php 

session_start();


if(!$_SESSION['user']=='admin' || !$_SESSION['user']=='staff'){
header("Location: index.php");
}

  require 'dbconnection.php';

  $conn= new dbconnection();
  $date= date('d-m-Y');
  
  $selectPaid= "SELECT checkin_date ,SUM(price) as sum FROM customers INNER JOIN checkin ON customers.c_id = checkin.c_id  GROUP BY  checkin_date OrDER BY DATE(checkin_date) ASC";
  $selectTodaysCustomers="SELECT count(checkin.c_id) FROM checkin INNER JOIN customers ON customers.c_id=checkin.c_id WHERE checkin_date=:inDate"; 
  $selectBookings="SELECT count(bookings.c_id) FROM bookings INNER JOIN customers ON customers.c_id = bookings.c_id WHERE checkin_date =:inDate";
  $selectCheckedOut="SELECT count(checkout.c_id) FROM checkout INNER JOIN customers ON customers.c_id =checkout.c_id WHERE checkout_date = :inDate";
  $viewUsers="SELECT * FROM users";
  
  
  
  $conn->query($selectPaid);
  $conn->execute();
  $rowPaid=$conn->resultSet();
  
  
  $conn->query($selectTodaysCustomers);
  $conn->bind(':inDate', $date);
  $conn->execute();
  $row1=$conn->resultSet();
  
  
  $conn->query($selectBookings);
  $conn->bind(':inDate', $date);
  $conn->execute();
  $row2=$conn->resultSet();
  
  $conn->query($selectCheckedOut);
  $conn->bind(':inDate', $date);
  $conn->execute();
  $row3=$conn->resultSet();
  
  
  $conn->query($viewUsers);
  $conn->execute();
  $row4=$conn->resultSet();
  

 
  
  
  
  $chart_data = '';
    
  foreach ($rowPaid as $row){
            $chart_data .= "{ date:'".$row["checkin_date"]."', amount:".$row["sum"]."}, ";
    }

    $chart_data1 = substr($chart_data, 0, -2);
   
    
    foreach ($row1 as $row1){
        $count =$row1['count'];
        
    }
    
    
    
     
    foreach ($row2 as $row2){
        $bookings =$row2['count'];
        
    }
    
    
    foreach ($row3 as $row3){
        $checkouts =$row3['count'];
        
    }
     
    
                              


    $selectRoom = "SELECT * FROM rooms ORDER BY room_id";

    $conn->query($selectRoom);
    $conn->execute();
    $rooms=$conn->resultSet();

   

                            
  
  

?>


<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin Panel </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">
  
   
  
<!--  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>-->

 
<link rel="stylesheet" href="js/jquery-ui.css">
<link rel="stylesheet" href="sweetalert/dist/sweetalert.css">
<link rel="stylesheet" href="morris.js/morris.css"/>
<link rel="stylesheet" href="sweetalert/dist/sweetalert.css">
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />  

   
   
<script src="js/jquery-2.2.0.js"></script>  
<script src="js/jquery-ui.js"></script>
<script src="sweetalert/dist/sweetalert.min.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

   
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <!-- Menu toggle button -->
            
           <a href="logout.php" >log out</a>
           
          
          </li>
  
          
          <!-- Control Sidebar Toggle Button -->
         
        </ul>
      </div>
    </nav>
  </header>
  
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">


      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Quick Links</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="front_desk.php"><i class="fa fa-desktop"></i> <span>Front Desk</span></a></li>
        <li class="active"><a href="customerManagement.php"><i class="fa fa-book"></i> <span>Records Book</span></a></li>
          
      
        <li class="treeview">
          <a href="#"><i class="fa fa-tasks"></i> <span> Manage</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
              <li><a data-toggle="modal" class="fa fa-hotel" data-target="#addroom"> add room</a></li>
               <li><a data-toggle="modal" class="fa fa-user"data-target="#adduser"> add user</a></li>
               <li><a data-toggle="modal" class="fa fa-eye"data-target="#viewusers"> view users</a></li>
               <li><a data-toggle="modal" class="fa fa-eye" data-target="#viewrooms"> view rooms</a></li>
              <li><a data-toggle="modal" class="fa fa-book"data-target="#rpt"> view report</a></li>
          </ul>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
   

    <!-- Main content -->
    <section class="content container-fluid">

     <!--checked-in-->
      <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?php echo $count; ?></h3>

              <p>Today's Customers</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">Checked-in Customers </a>
          </div>
        </div>
     
     
     <!--checked-in-->
      <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php  echo $bookings ?></h3>

              <p>Today's Customers</p>
            </div>
            <div class="icon">
              <i class="ion ion-person"></i>
            </div>
            <a href="#" class="small-box-footer">Booked Customers</a>
          </div>
        </div>
     
     
      <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php  echo $checkouts ?></h3>

              <p>Today's Customers</p>
            </div>
            <div class="icon">
              <i class="ion ion-person"></i>
            </div>
            <a href="#" class="small-box-footer">Checked-Out Customers</a>
          </div>
        </div>
     
       <div class="tab-content no-padding">
              <!-- Morris chart - Sales -->
              <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>
              <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div>
            </div>
     
     <div id="chart"></div>
     
             <div class="modal fade" id="addroom" tabindex="-1" role="dialog" >
            
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                      <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <h4 class="modal-title" id="title">Add Room</h4>
                      </div>


                                <div class="modal-body" style="padding:40px 40px;">
                                    <div >
                                        <form  id="roomadd" name="roomadd">
                                                 
                                                        <p><label>Room Name </label> <input name="roomName" type="text" class="form-control" id="rname" placeholder="Room Name"></p>
                                                        <p><label>Price</label> <input name="roomPrice"  type="text" class="form-control" id="rprice" placeholder="Price in TZS"></p>
                                                

                                                        <input type="button"  id="addid" class="btn btn-info" value="Submit" />  
                                                

                                        </form>
                                    </div>
        
                                    
                                </div>

                          <div class="modal-footer">
                            <button type="button" onclick="reload()" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                </div>

            </div>
            
        </div>
         
             <div class="modal fade" id="viewrooms" tabindex="-1" role="dialog" >
            
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                      <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <h4 class="modal-title" id="title">Room Info</h4>
                      </div>


                                <div class="modal-body" style="padding:40px 40px;">
                                    <div >
                                      
                                        <table id="rt" class="table table-striped  table-bordered table-hover" style=" width: 100% !important; ">
                                            <thead>
                                                <tr>
                                                    <td>ROOM NAME</td>
                                                    <td>PRICE</td>
                                                    <td>ACTION</td>
                                                    
                                                </tr>
                                                
                                                
                                                
                                            </thead>
                                            
                                                    
                  
                                            <tr> 
                    <?php
                        foreach ($rooms as $row){
                            
                                        $roomName=  strtoupper($row['room_name']);
                                        $roomID= $row['room_id'];
                            
                                        echo  "<td>" .($roomName)                ."</td>"
                                            . "<td>" . number_format($row['room_price'] )    ."</td>";
                                        
                                       
                           
                       
                    ?>
                              
                              
                                           
                                                 <td>
                                                   <p>
                                                     <a class="btn  glyphicon glyphicon-edit"  onclick="editRoom(<?php echo $roomID; ?>)"></a> 
                                                  </p>
                                                 </td>
                                
                                             </tr>

                    <?php 
                        }
                    ?>
                                            
                                           
                                          
                                            
                                        </table>
                                        
                                        
                                    </div>
        
                                    
                                </div>

                          <div class="modal-footer">
                            <button type="button" onclick="reload()" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                </div>

            </div>
            
        </div>
      
         
            <div class="modal fade" id="updateroom" tabindex="-1" role="dialog" >
            
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                      <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <h4 class="modal-title" id="title2">Update Room</h4>
                      </div>


                                <div class="modal-body" style="padding:40px 40px;">
                                    <div >
                                        
                                         <form  id="roomupd" name="roomupd">
                                                  
                                                   
                                             <p><label id="roomname">Room Name </label>
                                             <p><label>Price</label> <input name="new_price"  type="text" class="form-control"  placeholder="Price in TZS"></p>
                                                


                                       
                                                    <input type="button"  id="updid" class="btn btn-info" value="Save Changes" />  
                                                    </form>
                                    </div>
        
                                    
                                </div>

                          <div class="modal-footer">
                              <button type="button" onclick="reload()" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                </div>

            </div>
            
        </div>
            
           <div class="modal fade" id="adduser" tabindex="-1" role="dialog" >
            
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                      <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <h4 class="modal-title" id="title">Add user</h4>
                      </div>


                                <div class="modal-body" style="padding:40px 40px;">
                                    <div >
                                        <form  id="useradd" name="useradd"  data-parsley-validate="">
                                                 
                                            <p><label>User Name </label> <input name="username" type="text" class="form-control" id="uname" required="" placeholder="User Name"></p>
                                            <p><label>Password</label> <input name="pass"  type="password" class="form-control" id="pwd" required="" placeholder="password"></p>
                                            <p><label>Re-enter password</label> <input name="repass"  type="password" class="form-control" required="" id="re-pwd" placeholder="re-type password"></p>
                                                

                                                        <input type="button"  id="newuser" class="btn btn-info" value="Submit" />  
                                                

                                        </form>
                                    </div>
        
                                    
                                </div>

                          <div class="modal-footer">
                            <button type="button" onclick="reload()" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                </div>

            </div>
            
        </div>
     
     
     
            <div class="modal fade" id="rpt" tabindex="-1" role="dialog" >
            
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                      <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <h4 class="modal-title" id="title">Report</h4>
                      </div>


                                <div class="modal-body" style="padding:40px 40px;">
                                    <div >
                                      
                                        <table id="reportable" class="table table-striped  table-bordered table-hover" style=" width: 100% !important; ">
                                            <thead>
                                                <tr>
                                                    <td>SN</td>
                                                    <td>CHECKIN-DATE</td>
                                                    <td>AMOUNT</td>
                                                    
                                                </tr>
                                                
                                                
                                                
                                            </thead>
                                            
                                                    
                  
                                            <tr> 
                    <?php
                    
                        $i=1;
                        foreach ($rowPaid as $row){
                            
                            
                            
                           
                            echo  "<td>" . $i                            ."</td>"
                                . "<td>" .$row["checkin_date"]           ."</td>"
                                . "<td>" . number_format($row["sum"])    ."</td>";
                                        
                               $i++;        
                               
                    ?>
                              
                                   </tr>

                    <?php 
                        }
                    ?>
                                            
                                           
                                          
                                            
                                        </table>
                                        
                                        
                                    </div>
        
                                    
                                </div>

                          <div class="modal-footer">
                            <button type="button" onclick="reload()" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                </div>

            </div>
            
        </div>
     
     
              <div class="modal fade" id="viewusers" tabindex="-1" role="dialog" >
            
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                      <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <h4 class="modal-title" id="title">Registered Users</h4>
                      </div>


                                <div class="modal-body" style="padding:40px 40px;">
                                    <div >
                                      
                                        <table id="vu" class="table table-striped  table-bordered table-hover" style=" width: 100% !important; ">
                                            <thead>
                                                <tr>
                                                    <td>#</td>
                                                    <td>USER NAME</td>
                                                   
                                                    
                                                </tr>
                                                
                                                
                                                
                                            </thead>
                                            
                                                    
                  
                                            <tr> 
                    <?php
                    
                        $i=1;
                        foreach ($row4 as $row){
                            
                                       
                            
                                        echo  "<td>" .$i               ."</td>"
                                            . "<td>" . $row['uname']   ."</td>";
                                        
                                       
                           $i++;
                       
                    ?>
                              
                              
                                           
                                
                                             </tr>

                    <?php 
                        }
                    ?>
                                            
                                           
                                          
                                            
                                        </table>
                                        
                                        
                                    </div>
        
                                    
                                </div>

                          <div class="modal-footer">
                            <button type="button" onclick="reload()" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                </div>

            </div>
            
        </div>
     
            
     
  </div>
         
    

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    
    <strong>GBbookingSystem </strong> All rights reserved.
  </footer>

 
  <div class="control-sidebar-bg"></div>
</div>


<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>


<script src="raphael/raphael.min.js"></script>
 
  <script src="morris.js/morris.min.js"></script>
 
  
  
<script src="js/jquery.dataTables.min.js"></script>  
<script src="js/dataTables.bootstrap.min.js"></script>  

<script>
    
$(document).ready(function() {
  
    $('#rt').DataTable();
    $('#reportable').DataTable();
     $('#vu').DataTable();
    
   
   
} );
 
    

    
 Morris.Area({
 element : 'chart',
 data:[<?php echo $chart_data1; ?>],
 xkey:'date',
 ykeys:['amount'],
 labels:['amount'],
 hideHover:'auto',
 parseTime: false,
 stacked:true,
 resize:true,
 fillOpacity: 0.5,

  behaveLikeLine: true,
 
  pointFillColors: ['blue '],
  pointStrokeColors: ['red'],
  lineColors: [' #1fd6ff ', 'white']
});

 
$('#addid').click(function(){    
$.ajax({  
    url:"edit.php",  
    method:"POST",  
    data:$('#roomadd').serialize(),  
    success:function(data)  
    {  
            swal({
                        title: "Info!",
                        text: data,
                        type: "info",
                        confirmButtonText: "I , understand"
                    }),
                            
        $('#roomadd')[0].reset();
        


    } 
    
}); 



});  
      
$('#updid').click(function(){    


$.ajax({  
    url:"edit.php",  
    method:"POST",  
    data:$('#roomupd').serialize(),  
    success:function(data)  
    {  
            swal({
                        title: "Info!",
                        text: data,
                        type: "info",
                        confirmButtonText: "I , understand"
                    }),
                            
        $('#roomupd')[0].reset();
       


    } 
    
}); 



}); 

$('#newuser').click(function(){    


$.ajax({  
    url:"edit.php",  
    method:"POST",  
    data:$('#useradd').serialize(),  
    success:function(data)  
    {  
            swal({
                        title: "Info!",
                        text: data,
                        type: "info",
                        confirmButtonText: "I , understand"
                    }),
                            
        $('#useradd')[0].reset();
       


    } 
    
}); 



}); 

      
function reload(){

window.location.href= "admin.php";

} 
    

function editRoom(id){
    
   $.ajax({  
            url:"edit.php",  
            method:"POST",  
            data:'id='+id,
            success:function(data)  
            {  
                $('#updateroom').on('show.bs.modal' ,function (){
                $('#roomname').text(data);


                }).modal('show');


            }  
       }); 
     
     
     
 }
    
</script>


</body>
</html>