

<?php

   session_start();
    
    
   if(!$_SESSION['user']=='admin' || !$_SESSION['user']=='staff'){
       header("Location: index.php");
   }
   
require 'dbconnection.php';

$obj = new post();
$obj->formPost();



class post{
    
    private $name;
    private $fname;
    private $mname;
    private $lname;
    private $pnum;
    private $roomName;
    private $status;
    private $paid;
    private $inDate;
    private $outDate;
    private $roomPrice;
    private $conn;
    private $roomID;
    private $cID;


    public function __construct() {
        
        $this->conn= new dbconnection();
        
        $this->fname            =       strtoupper(filter_input(INPUT_POST, fname));
        $this->mname            =       strtoupper(filter_input(INPUT_POST, mname));
        $this->lname            =       strtoupper( filter_input(INPUT_POST, lname));
        $this->pnum             =       filter_input(INPUT_POST, pnum);
        $this->roomName         =       filter_input(INPUT_POST, room);
        $this->paid             =       filter_input(INPUT_POST, paid);
        $this->inDate           =       date(filter_input(INPUT_POST, checkin_date));
        $this->outDate          =       date(filter_input(INPUT_POST, checkout_date));
        //$this->roomPrice=  filter_input(INPUT_POST, roomPrice);
         $this->curTime      =         date('G:i:s');
         
       
       
    }
    
    
    

        public function formPost(){
            
           // $checkin_time= date('h:g:s');
            
            
            if($this->inDate ==  date('d-m-Y')){
                 $this->status           =   1;
                
                
            }
           else{
                $this->status           =   2;
               
           }
            
            
           $selectPrice   = "SELECT * FROM rooms WHERE room_name=:name ";
 
           try {
                $this->conn->query($selectPrice);
                $this->conn->bind(':name',  $this->roomName);
                $this->conn->execute();
                $rsN=$this->conn->resultSet();
            
            
                foreach ($rsN as $price){
                
                 $dateDiff=abs(strtotime($this->outDate) -strtotime($this->inDate))/(60 * 60 * 24) ;
                
                    $this->roomPrice= $price['room_price'] * $dateDiff;
                    $this->roomID=$price['room_id'];
                }
                
               
                        
                
            }catch (Exception $ex) {
                
                echo 'Error' .$ex->getMessage();
               
           }
           
          
            
           if(!empty($this->fname) && !empty($this->lname) && !empty($this->roomName) && !empty($this->status) && !empty($this->paid) && !empty($this->inDate) && !empty($this->outDate) ){
               
              
                $this->name=  $this->fname . " " . $this->mname . " " .  $this->lname;
                $insertCustomer = "INSERT INTO customers (c_name, room, checkin_date, checkout_date, price) VALUES ( :name, :room, :checkin_date, :checkout_date, :price ) RETURNING c_id" ;
                   
                $this->conn->query($insertCustomer);
                $this->conn->bind(':name'           ,  $this->name);
                $this->conn->bind(':room'           ,  $this->roomID);
                $this->conn->bind(':checkin_date'   ,  $this->inDate);
                $this->conn->bind(':checkout_date'  ,  $this->outDate);
                $this->conn->bind(':price'          ,  $this->roomPrice);
                $this->conn->execute();
                $rsCID= $this->conn->resultSet();
                        
                while ($rsCID){        
                    if ($rsCID){

                        echo $this->name ;

                        foreach($rsCID as $cid){
                            $customerID=$cid['c_id'];

                        }
                        

                    }else {
                        echo "Unsuccessful";
                    }
                
                    $insertPhone = "INSERT INTO phone (c_id,pnumber) VALUES (:c_id,:pnumber) RETURNING c_id " ;
                  
                    $this->conn->query($insertPhone);
                    $this->conn->bind(':c_id', $customerID);
                    $this->conn->bind(':pnumber', $this->pnum);
                    $this->conn->execute();
                    $pnumRS=  $this->conn->resultSet();
                    
                    if($pnumRS){
                        
                        echo " ".$this->pnum." ";
                    }
                    
                    break;
                
                
                }
                
               
                       
                   
                   
                if($this->status== 1){
                       
                    $insertCheckin = "INSERT INTO checkin (c_id,checkin_time) VALUES (:c_id,:time) RETURNING c_id " ;
                  
                    $this->conn->query($insertCheckin);
                    $this->conn->bind(':c_id', $customerID);
                    $this->conn->bind(':time', $this->curTime);
                    $this->conn->execute();
                    $cRS=$this->conn->resultSet();
                            
                    if ($cRS ){

                    echo ' checked-in succesfully ! ';
                   
               

                    }  else {
                        echo ' Check-in failed! ';
                    }
                        
                }
               
                elseif($this->status== 2){
                       
                    $insertBookings = "INSERT INTO bookings (c_id) VALUES (:c_id) RETURNING c_id " ;
                  
                    $this->conn->query($insertBookings);
                    $this->conn->bind(':c_id', $customerID);
                    $this->conn->execute();
                    $bRS=$this->conn->resultSet();

                    if ($bRS ){

                        echo ' Booked succesfully !';

                    }else {
                       echo ' Booked failed ! ';

                    }
               
                }
               
                
                if($this->paid == 1){
                       
                    $insertPaid = " INSERT INTO public.paid (c_id) VALUES (:c_id) RETURNING c_id " ;

                    $this->conn->query($insertPaid);
                    $this->conn->bind(':c_id',$customerID);
                    $this->conn->execute();
                    $pRS=$this->conn->resultSet();
                    
                    

                    if($pRS){

                        echo ' Paid ';


                    }else {
                        echo 'Paid unsuccesful';
                    }
                        
                    
                       
                }elseif($this->paid == 2){
                    
                    echo ' not paid ';
                    
                }
        
        
        
            }else {
                echo 'All fields required! ';
            }
   
    } 

}

?>

