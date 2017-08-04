

<?php 

   session_start();
    
    
   if(!$_SESSION['user']=='admin' || !$_SESSION['user']=='staff'){
       header("Location: index.php");
   }

require 'dbconnection.php';
$obj = new price();
$obj->retrunPrice();

class price{
    
    private $conn;
            
    function __construct() {
        $this->conn= new dbconnection();
    }


    public function retrunPrice(){
        
        $roomName= strtoupper(filter_input(INPUT_POST, room));
        
        $selectPrice   = "SELECT * FROM rooms WHERE room_name=:name ";
 
        $this->conn->query($selectPrice);
        $this->conn->bind(':name',$roomName);
        $this->conn->execute();
        $rsN=$this->conn->resultSet();



        foreach($rsN as $price){
           
           $roomPrice =$price['room_price'];
           $roomID =$price['room_id'];
          
        }

        $checkout_date=(filter_input(INPUT_POST, checkout_date));
        $checkin_date=(filter_input(INPUT_POST, checkin_date));
     

        $outDate=date($checkout_date);
        $inDate= date($checkin_date);
       
        
      
        if(empty($checkout_date)){
            
            
            
    
            $checkBookin="SELECT * FROM customers INNER JOIN checkin ON customers.c_id = checkin.c_id WHERE date(customers.checkin_date)<= date(:in) AND date(customers.checkout_date)> date(:in) AND  room=:room ";
            $this->conn->query($checkBookin);
            $this->conn->bind(':in', $inDate);
            $this->conn->bind(':room', $roomID);
            $this->conn->execute();
            $rs1=$this->conn->resultSet();
            
        
            
            $checkCheckin="SELECT * FROM customers INNER JOIN bookings ON customers.c_id = bookings.c_id  WHERE date(c.checkin_date)<= date(:in) AND date(c.checkout_date)> date(:in) AND c.room=:room ";
            $this->conn->query($checkCheckin);
            $this->conn->bind(':in', $inDate);
            $this->conn->bind(':room', $roomID);
            $this->conn->execute();
            $rs2=$this->conn->resultSet();
    
         
            if($roomName == "SELECT ROOM" || $roomName === "SELECT ROOM" ){
             echo " No room selected ";
             
            }
            elseif ($rs1 || $rs2){
             
                echo 'SORRY !, ROOM FOR SELECTED DATE';
             
            }
            else{
                    
                    echo number_format($roomPrice) ." TZS ";
            } 
            
            }elseif (empty($checkin_date)){
            
                echo 'Select Check-in date';
             
        }elseif(!empty($checkout_date) && !empty($checkin_date) ){
            
           
            
            $dateDiff=(strtotime($outDate) -strtotime($inDate))/(60 * 60 * 24);
            
            if($dateDiff>0){
                
                 
     // ---------------------------check booking status----------------------------------------------------
      $checkBookin="SELECT * FROM customers AS c INNER JOIN bookings AS b ON(c.c_id=b.c_id) WHERE date(c.checkin_date)<= date(:in) AND date(c.checkout_date)>date(:in) AND c.room=:room ";
            $this->conn->query($checkBookin);
            $this->conn->bind(':in', $inDate);
            $this->conn->bind(':room',$roomID);
            $this->conn->execute();
            $in_rs=$this->conn->resultSet();
    
        $checkBookout="SELECT * FROM customers AS c INNER JOIN bookings AS b ON(c.c_id=b.c_id) WHERE date(c.checkin_date) < date(:out) AND date(c.checkout_date) >= date(:out)  AND c.room=:room ";
            $this->conn->query($checkBookout);
            $this->conn->bind(':out', $outDate);
            $this->conn->bind(':room',$roomID);
            $this->conn->execute();
            $out_rs=$this->conn->resultSet(); 
            
        $checkBook1="SELECT * FROM customers AS c INNER JOIN bookings AS b ON(c.c_id=b.c_id) WHERE date(c.checkin_date) > date(:in) AND date(c.checkin_date) < date(:out) AND date(c.checkout_date) <= date(:out)  AND c.room=:room ";
            $this->conn->query($checkBook1);
            $this->conn->bind(':in', $inDate);
            $this->conn->bind(':out', $outDate);
            $this->conn->bind(':room', $roomID);
            $this->conn->execute();
            $io_rs=$this->conn->resultSet();
        //--------------------------------------------------------------------------------------------------------------
            
            
    // ---------------------------check booking status----------------------------------------------------
      $checkCheckin="SELECT * FROM customers AS c INNER JOIN checkin AS b ON(c.c_id=b.c_id) WHERE date(c.checkin_date)<= date(:in) AND date(c.checkout_date)>date(:in) AND c.room=:room ";
            $this->conn->query($checkCheckin);
            $this->conn->bind(':in', $inDate);
            $this->conn->bind(':room',$roomID);
            $this->conn->execute();
            $in_rs1=$this->conn->resultSet();
    
        $checkCheckin1="SELECT * FROM customers AS c INNER JOIN checkin AS b ON(c.c_id=b.c_id) WHERE date(c.checkin_date) < date(:out) AND date(c.checkout_date) >= date(:out)  AND c.room=:room ";
            $this->conn->query($checkCheckin1);
            $this->conn->bind(':out', $outDate);
            $this->conn->bind(':room',$roomID);
            $this->conn->execute();
            $out_rs1=$this->conn->resultSet();
            
        $checkCheckin2="SELECT * FROM customers AS c INNER JOIN checkin AS b ON(c.c_id=b.c_id) WHERE date(c.checkin_date) > date(:in) AND date(c.checkin_date) < date(:out) AND date(c.checkout_date) <= date(:out)  AND c.room=:room ";
            $this->conn->query($checkCheckin2);
            $this->conn->bind(':in', $inDate);
            $this->conn->bind(':out', $outDate);
            $this->conn->bind(':room',$roomID);
            $this->conn->execute();
            $io_rs1=$this->conn->resultSet();
        //--------------------------------------------------------------------------------------------------------------
            
            
            
              
            if($roomName === "SELECT ROOM" || $roomName == "SELECT ROOM"){
                
             echo " No room selected ";
             
            }
         
            elseif ($in_rs || $out_rs || $io_rs || $in_rs1 || $out_rs1 || $io_rs1) {
             
                echo 'SORRY !, ROOM BOOKED FOR SELECTED DATE(S)';
           
            }else{
                    $dateDiff=abs(strtotime($outDate) -strtotime($inDate))/(60 * 60 * 24) ;

                    echo (number_format($roomPrice*$dateDiff) .": TZS  TOTAL "  . $dateDiff. " DAY(s)   " .number_format($roomPrice). " TZS /Night");
                }       
           
                
                
            }
            elseif($dateDiff<=0){
                
                echo '  Checkout date must be later than Check-in  date ';
            }
      
            
            
        
    }
    






    }

}
  





 
 

     
  
       
 
 
   
  

    

