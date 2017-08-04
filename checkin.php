

<?php
    
    session_start();
    
    
   if(!$_SESSION['user']=='admin' || !$_SESSION['user']=='staff'){
       header("Location: index.php");
   }
   
    //$checkin_time= date('h:g:s');
  
  
    header('Content-type: application/json; charset=UTF-8');
    require 'dbconnection.php';
    
    $obj =new checkin();
    $obj->checkinCustomer();
    
    class checkin{
        
     private $customerID;
     private $inDate;
     public $response;
     private $conn;
     private $roomID;
     
     private $curTime;
     
     public function __construct() {
         $this->customerID  = filter_input(INPUT_POST, id) ;
         $this->inDate      =         date('d-m-Y');
         $this->response    =         array();
         $this->curTime      =         date('G:i:s');
         
         $this->conn        =         new dbconnection();
         $this->roomID      =  filter_input(INPUT_POST, roomid) ;
         
         
         
     }
     
     public function checkinCustomer(){
         
         
       
         
        header('Content-type: application/json; charset=UTF-8');
        
        
        
	if (!empty($this->customerID) && !empty($this->roomID)) {
            
            
              
            $checkBookin="SELECT * FROM customers INNER JOIN bookings ON customers.c_id = bookings.c_id WHERE date(customers.checkin_date)<=date(:in) AND date(customers.checkout_date)> date(:in) AND customers.c_id=:c_id AND  customers.room=:room ";
            $this->conn->query($checkBookin);
            $this->conn->bind(':in', $this->inDate);
            $this->conn->bind(':c_id', $this->customerID);
            $this->conn->bind(':room', $this->roomID);
            $this->conn->execute();
            $resulSet=$this->conn->resultSet();
            
            
                    
            
            if($resulSet){    
		
                $checkinCustomer= "INSERT INTO checkin (c_id,checkin_time) VALUES (:c_id,:time) ";

                $this->conn->query($checkinCustomer);
                $this->conn->bind(':c_id', $this->customerID);
                $this->conn->bind(':time', $this->curTime);
                $this->conn->execute();
                $rs=$this->conn->resultSet();
                
                
                
                
                
                $updateCheckOutDate="UPDATE customers SET checkin_date=:inDate WHERE c_id=:c_id";
                $this->conn->query($updateCheckOutDate);
                $this->conn->bind(':inDate', $this->inDate);
                $this->conn->bind(':c_id', $this->customerID);
                $this->conn->execute();
                $rs1=$this->conn->resultSet();
                
                $deleteEntry="DELETE FROM bookings WHERE c_id = :c_id";
                $this->conn->query($deleteEntry);
                $this->conn->bind(':c_id', $this->customerID);
                $this->conn->execute();
                $rs2=$this->conn->resultSet();
                
                $paidCustomer= "INSERT INTO paid (c_id) VALUES (:c_id) ";
                

                $this->conn->query($paidCustomer);
                $this->conn->bind(':c_id', $this->customerID);
                $this->conn->execute();
                $rs3=$this->conn->resultSet();
           
                        
                
                
                if($rs && $rs1 && $rs2 && $rs3){
                    
                    $response['status']  = 'success';
                    $response['message'] = ' Check-in Successfull' ;
                    
                }else{
			$response['status']  = 'error';
			$response['message'] = 'Unable to Check-in ...';
		}
            }else{
                 $response['status']  = 'error';
                 $response['message'] = 'Customer did not book for the current date ' ;
                    
            }
	}else{
           
            $response['status']  = 'error';
            $response['message'] = 'Customer not found!';
        }
         
       
	
        echo json_encode($response);
     }
     
        
        
        
        
    }


	
	
	
        
        
 ?>

