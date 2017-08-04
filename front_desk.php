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
        
       
        <link rel="stylesheet" href="sweetalert/dist/sweetalert.css">
        <script src="sweetalert/dist/sweetalert.min.js"></script>
   
        
        <link rel="stylesheet" href="css/bootstrap.min.css" />  
        <script src="js/bootstrap.min.js"></script>  
        <script src="js/jquery-2.2.0.js"></script>  
           
      
        <script src="jquery-ui-1.12.1/jquery-ui.js"></script>
        <link rel="stylesheet" href="jquery-ui-1.12.1/jquery-ui.css">
           
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
                            <li class="active"><a href="customerManagement.php">Records Book</a></li>
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
       
        <div class="container" style="margin-bottom: 40px;">
            
            
            
            <form  id='formPost' name="formPost" data-parsley-validate=" " >
                <div class="form-group">
                    
                    <p><label>First Name</label> <input name="fname" type="text" class="form-control"  required="" id="fname" placeholder="First Name"></p>
                </div>
            
                 <div class="form-group">
                    
                    <p><label>Second Name</label> <input name="mname"  type="text" class="form-control" id="mname" placeholder="Second Name"></p>
                </div>
            
                 <div class="form-group">
                    
                    <p><label>Last Name</label> <input name="lname" type="text" class="form-control" id="lname" placeholder="Last Name"></p>
                    <p><label>Phone Number</label> <input name="pnum" type="tel" class="form-control" id="lname" placeholder="Phone number"></p>
                </div>
            
            
                    
                <div class="form-group">
                    <p> <input name="checkin_date" id='in-dpicker' class="date" placeholder="Check In Date"  />
                        <input name="checkout_date" id='out-dpicker' class="date" placeholder="Check Out Date" />
                    </p>
                    <p><label>Available Rooms</label>
         
                            
                            
                            <select id="room" name="room" class="form-control"><option>Select Room</option> 
                   
                            <?php 
                                
                                require 'dbconnection.php';

                                $conn= new dbconnection();


                                $selectRoom = "SELECT * FROM rooms ORDER BY room_id";

                                $conn->query($selectRoom);
                                $conn->execute();
                                $rooms=$conn->resultSet();

                                foreach ($rooms as $roomName){echo '<option>'. strtoupper($roomName['room_name']) .'</option>';  }

                            ?>                 
                            </select>
                      
                    
                    </p>
                      
                      
                    <p><label>Price : <label id="room_price"></label> </label></p>
                          
                   
                    
                   
                    
                 
                       <p><label>Paid ?:  </label></p>
                       
                    <p>                        
                      <input type="radio" name="paid" value="1"> Yes
                      <input type="radio" name="paid" value="2">No<br>
                
                    </p>
                   
                </div>
             
                 
           
           
            <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit"  />  
             <input class="btn btn-success  glyphicon glyphicon-refresh" id='refresh' type="button" value="Auto Check-Out"/>
         
             
        </form>
        </div>
        
       
       
       
    </body>
    

 
<script>  
    $(document).ready(function(){ 
        
        
        function disableBtn(data){
            
             if (jQuery.trim(data) === "SORRY !, ROOM BOOKED FOR SELECTED DATE(S)" || jQuery.trim(data) ==="SORRY !, ROOM FOR SELECTED DATE" || jQuery.trim(data) === "Checkout date must be later than Check-in  date" ){
                     
                     $('#submit').prop('disabled',true);
                     
                     
                 }else{
                     $('#submit').prop('disabled',false);
                 }
               
            
        }
        
        $('.date').change(function(){ 
         
            $.ajax({  
                url:"price.php",  
                method:"POST",  
                data:$('#room,#out-dpicker,#in-dpicker').serialize(),  
                success:function(data)  
                
                {
                 
                 disableBtn(data);
          
                   
               
                 $('#room_price').text(data);
                
                
                 //$('#dropdown').text(data);
                    
                }  
            });  
        
        
        });  
      
        
        $('select').change(function(){ 
            
            
            
         
            $.ajax({  
                url:"price.php",  
                method:"POST",  
                data:$('#room,#out-dpicker,#in-dpicker').serialize(),  
                success:function(data)  
                
                {
                 disableBtn(data);
                 $('#room_price').text(data);
                // $('#dropdown').text(data);
                    
                }  
            });  
        
        
        }); 
      
        $('#refresh').click(function(){            
            $.ajax({  
                url:"roomRefresh.php",  
                method:"POST",  
                data:$('#refresh').serialize(),  
                success:function(data)  
                {  
                      alert(data),
                      window.location.href= "front_desk.php";
                  
                }  
            });  
        }); 
     
        $('#submit').click(function(){            
            $.ajax({  
                url:"post.php",  
                method:"POST",  
                data:$('#formPost').serialize(),  
                success:function(data)  
                {  
                    swal({
                        title: "Info!",
                        text: data,
                        type: "info",
                        confirmButtonText: "I , understand"
                    }),
                    $('#formPost')[0].reset();
                    $('#room_price').text("");
                    
                    
                }  
            });  
        }); 
      
   
  

$("#in-dpicker").datepicker({
    dateFormat: "dd-mm-yy",
     minDate: 0,
     onSelect: function () {
            $('#out-dpicker').datepicker('option', {
             minDate: $(this).datepicker('getDate')
    });
}
});  
        
        
        
        

$("#out-dpicker").datepicker({
    dateFormat: "dd-mm-yy",
     minDate: 0

});  
        
        
        
        
 }); 
 
 </script>  
 
</html>
 
