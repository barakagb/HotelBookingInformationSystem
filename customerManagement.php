
<?php 

       session_start();
    
    
   if(!$_SESSION['user']=='admin' || !$_SESSION['user']=='staff'){
       header("Location: index.php");
   }

?>


<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
        <title>GBHotel Booking System</title>
        
        
        <link rel="stylesheet" href="css/bootstrap.min.css" />  
      
        <link rel="stylesheet" href="js/jquery-ui.css">
        <link rel="stylesheet" href="sweetalert/dist/sweetalert.css">
        
       
        <script src="js/jquery-2.2.0.js"></script>  
        <script src="js/jquery-3.1.1.min.js"></script>  
        <script src="js/bootstrap.min.js"></script>  
      <script src="sweetalert/dist/sweetalert.min.js"></script>
        <script src="js/jquery-ui.js"></script>
        
 

         <script src="js/bootstrap.min.js"></script>
         <script src="js/jquery.dataTables.min.js"></script>  
         <script src="js/dataTables.bootstrap.min.js"></script>            
         <link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />  
    
           
           
        
</head>
<body contenteditable="false">
       
        <nav class="navbar navbar-inverse navbar-static-top">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a href="#" class="navbar-brand">hbIS</a>
                    </div>
                    <!-- Collection of nav links and other content for toggling -->
                    <div id="navbarCollapse" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="front_desk.php">Front Desk</a></li>
                            
                             <?php
                                    if($_SESSION['user']=='admin'){
                                    
                                        echo ' <li class="active"><a href="admin.php">admin panel</a></li>';
                                        
                                    }
                                ?>
                            
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                </div>
        </nav>
       
        
        <h2 style="text-align: center ;"> Room Reservation Page</h2>
         
        <hr>
        
        
        <div class="container">
            
             
           <ul class="nav nav-tabs">
                    
                    <li ><a data-toggle="tab" href="#booking">Bookings</a></li>
                    <li ><a href="#checkedin" data-toggle="tab">Checked In Clients </a></li>
                    <li ><a href="#checkedOut" data-toggle="tab">Checked Out Clients</a></li>
                    
                    
                </ul>
            
            <?php
                    
                        require 'dbconnection.php';

                        $conn= new dbconnection();
            ?>            
            
          
            
            <div class="tab-content">
                <div id="booking" class="tab-pane fade">
                    
                    <?php

                        $selectBooking = "SELECT customers.c_id ,customers.c_name, customers.checkin_date, customers.checkout_date, customers.price, phone.pnumber , rooms.room_name, rooms.room_id FROM customers

                            INNER JOIN bookings ON customers.c_id = bookings.c_id
                            INNER JOIN phone ON customers.c_id= phone.c_id
                            INNER JOIN rooms ON  rooms.room_id = customers.room ORDER BY customers.c_id DESC";

                        $conn->query($selectBooking);
                        $conn->execute();
                        $row=$conn->resultSet();
                        
                        
                      
                        
                        
                        
                    ?>
                        
                        
                    <h3 style="text-align: center ;"> Bookings</h3>
                
                    <div  >
                           <table  id="bk" class="table  table-striped  table-bordered table-hover" style=" width: 100% !important; ">
                               <thead>
                                    <tr>
                                        <td>Name</td>
                                        <td>Room</td> 
                                        <td>Check In Date</td>
                                        <td>Check Out Date</td>
                                        <td>Phone Number</td>
                                        <td>Price</td>
                                        <td>Paid</td>
                                        <td>Action</td>
                                        
                                        
                                    </tr>
                                    
                               </thead>
                           
                           
                    <?php
                       
                        foreach ($row as $row){
                           
                            $cID=$row['c_id'];
                            $name=$row['c_name']  ;
                            $roomID=$row['room_id'];
                            
                            $selectPaid = "SELECT * FROM PAID WHERE paid.c_id = :c_id";
                            $conn->query($selectPaid);
                            $conn->bind(':c_id', $cID);
                            $conn->execute();
                            $row3=$conn->resultSet();
                            
                    ?>
                          <tr> 
                    <?php
                           

                           echo   "<td>" . $row['c_name']                   ."</td>"
                                . "<td>" . $row['room_name']                ."</td>"
                                . "<td>" . $row['checkin_date']             ."</td>"
                                . "<td>" . $row['checkout_date']            ."</td>"
                                . "<td>" . $row['pnumber']                  ."</td>"
                                . "<td>" . number_format($row['price'] )    ."</td>";
                           
                            if($row3){
                                     echo  "<td>" ."YES"."</td>";
                            
                            }else{
                                     echo  "<td>" ."NO"."</td>";   
                            }
                            
                            

                    ?>


                                <td>
                                    <p>
                                        <a class="btn  btn-success glyphicon glyphicon-log-in"  onclick="checkin(<?php echo $cID ; ?>,'<?php echo $name ; ?>',<?php echo $roomID ; ?>)" id="checkin_id<?php echo $cID; ?>" data-id="<?php echo $cID; ?>" ></button></a>
                                        <a class="btn  btn-warning glyphicon glyphicon-remove"  onclick="cancelBooking(<?php echo $cID ; ?>,'<?php echo $name ; ?>')" data-id="<?php echo $cID; ?>" ></button></a> 
                                    </p>
                                </td>




                            </tr>

                            <?php

                         }
                         
                      
                           
                  
                    ?>
                      
                       </table>
                    </div>
                </div>
                
                <div id="checkedin" class="tab-pane fade">
                    
                    <?php
                    
                        

                        $selectCheckedIn = "SELECT customers.c_id ,customers.c_name, customers.checkin_date, customers.checkout_date, rooms.room_name, customers.price,phone.pnumber,checkin.checkin_time FROM customers

                            INNER JOIN checkin ON customers.c_id = checkin.c_id
                            INNER JOIN phone ON customers.c_id= phone.c_id
                            INNER JOIN rooms ON  rooms.room_id = customers.room 
                            WHERE customers.c_id  NOT IN (SELECT checkout.c_id FROM checkout)
                            ORDER BY customers.c_id DESC";

                        $conn->query($selectCheckedIn);
                        $conn->execute();
                        $row1=$conn->resultSet();
                     ?>   
                        
                        
                       <h3 style="text-align: center ;">Checked-In</h3>
                
                       <div >
                           <table id="ci" class="table  table-striped  table-bordered table-responsive table-hover" style="width:100% !important;">
                           <thead>
                                    <tr>
                                        <td>Name</td>
                                        <td>Room</td> 
                                        <td>Check In Date</td>
                                        <td>Check In Time</td>
                                        <td>Check Out Date</td>
                                        <td>Phone Number</td>
                                        <td>Price</td>
                                        <td>Action</td>
                                        
                                        
                                    </tr>
                                    
                               </thead>
                       
                           
                        <?php
                        
                        foreach ($row1 as $row){
                           
                            $cID=$row['c_id'];
                            $name=$row['c_name']  ;
                            
                            
                        ?>
                            <tr>
                        <?php
                            echo  "<td>" . $row['c_name']                   ."</td>"
                                . "<td>" . $row['room_name']                ."</td>"
                                . "<td>" . $row['checkin_date']             ."</td>"
                                . "<td>" . $row['checkin_time']             ."</td>"
                                . "<td>" . $row['checkout_date']            ."</td>"
                                . "<td>" . $row['pnumber']                  ."</td>"
                                . "<td>" . number_format($row['price'] )                   ."</td>"

                        ?>


                                <td>
                                    <p>
                                        <a class="btn  btn-success glyphicon glyphicon-log-out"  onclick="checkout(<?php echo $cID ; ?>,'<?php echo $name ; ?>')" id="checkin_id<?php echo $cID; ?>" data-id="<?php echo $cID; ?>" ></button></a>
                                       
                                    </p>
                                </td>




                            </tr>

                            <?php

                         }
                    ?>
                          
                    </table>
                   </div>  
                   
                    
                </div>
                
                
                <div id="checkedOut" class="tab-pane fade">
                    
                    <?php
                    
                        

                        $selectCheckedOut = "SELECT 
                                              customers.c_id,
                                              customers.c_name, 
                                              customers.checkin_date, 
                                              customers.checkout_date, 
                                              customers.price, 
                                              checkin.checkin_time, 
                                              checkout.checkout_time, 
                                              phone.pnumber, 
                                              rooms.room_name
                                            FROM 
                                              public.checkin, 
                                              public.checkout, 
                                              public.customers, 
                                              public.phone, 
                                              public.rooms
                                            WHERE 
                                              checkin.c_id = customers.c_id AND
                                              checkout.c_id = customers.c_id AND
                                              phone.c_id = customers.c_id AND
                                              rooms.room_id = customers.room

                                              ORDER BY customers.c_id DESC";

                        $conn->query($selectCheckedOut);
                        $conn->execute();
                        $row2=$conn->resultSet();
                    ?>   
                       
                       <h3 style="text-align: center ;">Checked-Out</h3>
                
                     
                       <table id="co" class="table table-striped  table-bordered  table-hover" style=" width: 100% !important;">
                           <thead>
                                    <tr>
                                        <td>Name</td>
                                        <td>Room</td> 
                                        <td>Check In Date</td>
                                         <td>Check In Time</td>
                                        <td>Check Out Date</td>
                                        <td>Check Out Time</td>
                                        <td>Phone Number</td>
                                        <td>Price</td>
                                        
                                        
                                        
                                    </tr>
                                    
                               </thead>
                          
                     <?php
                     
                        foreach ($row2 as $row){
                           
                            $cID=$row['c_id'];
                            $name=$row['c_name']  ;
                            
                          
                            echo "<tr>"

                                . "<td>" . $row['c_name']                   ."</td>"
                                . "<td>" . $row['room_name']                ."</td>"
                                . "<td>" . $row['checkin_date']             ."</td>"
                                . "<td>" . $row['checkin_time']             ."</td>"
                                . "<td>" . $row['checkout_date']            ."</td>"
                                . "<td>" . $row['checkout_time']             ."</td>"
                                . "<td>" . $row['pnumber']                  ."</td>"
                                . "<td>" . number_format($row['price'] )                   ."</td>"

                               ?>


                              




                            </tr>

                            <?php

                         }
                         
                      
                           
                  
                    ?>
                               
                        </table>
                       </div>
                   
                    
                </div>
                
             
                
                
                
            </div>
            
            
            
      
        
        
        
        
        
        
</body>

</html>
     



<script>
$(document).ready(function() {
   $('#bk').DataTable();
   
} );
 
 
 
   $(document).ready(function(){  
      $('#ci').DataTable( );
      
 });
   $(document).ready(function(){  
      $('#co').DataTable();  
 });
 
 

         
         
function checkin(id,name,roomid){ 
    this.id=id;
    this.roomid=roomid;
    
    swal({
    title: "Check-in " +name ,
    text: "Confirm check-in ?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    confirmButtonText: "Yes, Checkin!",
    cancelButtonText: "Cancel",
    closeOnConfirm: false,
    closeOnCancel: false
  },
    function (isConfirm) {
      if (isConfirm) {
        $.ajax({
          type: 'POST',
          url: 'checkin.php',
          data: {id:id , roomid:roomid},
          dataType: 'json',
          success: function (response) {
            swal(response.status,response.message, response.status);
            reload();
          },
          error: function (data) {
            swal("Error!", "Something blew up.", "error");
            reload();
          }
        });
      } else {
        swal("Cancelled", " :)", "error");
      }
    });

  return false;
}

         
function cancelBooking(id,name){ 
    
    swal({
    title:name,
    text: "Confirm cancel booking ?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    confirmButtonText: "Yes, Cancel Booking!",
    cancelButtonText: "Keep Booking",
    closeOnConfirm: false,
    closeOnCancel: false
  },
    function (isConfirm) {
      if (isConfirm) {
        $.ajax({
          type: 'POST',
          url: 'cancelBooking.php',
          data: 'id='+id,
          dataType: 'json',
          success: function (response) {
            swal(response.status,response.message, response.status);
            reload();
          },
          error: function (data) {
            swal("Error!", "Something blew up.", "error");
            reload();
          }
        });
      } else {
        swal("Cancelled", " :)", "error");
      }
    });

  return false;
}







        
function reload(){
    
    
setInterval(function(){
    
 window.location.href= "customerManagement.php";
 
    },4000);



 

 
}

function checkout(id,name){ 
    
    swal({
    title: "Check-out " +name,
    text: "Confirm check-out ?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    confirmButtonText: "Yes, Check-out!",
    cancelButtonText: "Cancel",
    closeOnConfirm: false,
    closeOnCancel: false
  },
    function (isConfirm) {
      if (isConfirm) {
        $.ajax({
          type: 'POST',
          url: 'checkout.php',
          data: 'id='+id,
          dataType: 'json',
          success: function (response) {
            swal(response.status,response.message, response.status);
            reload();
          },
          error: function (data) {
            swal("Error!", "Something blew up.", "error");
            reload();
          }
        });
      } else {
        swal("Cancelled", " :)", "error");
      }
    });

  return false;
}
     
     
</script>