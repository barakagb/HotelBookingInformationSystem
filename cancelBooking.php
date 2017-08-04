

<?php
     session_start();
    
    
   if(!$_SESSION['user']=='admin' || !$_SESSION['user']=='staff'){
       header("Location: index.php");
   }
 
   
    //$checkin_time= date('h:g:s');
  
  
    header('Content-type: application/json; charset=UTF-8');
    require 'dbconnection.php';
    
    $obj =new CancelBooking();
    $obj->cancelBooking();
    
    class CancelBooking{
        
     private $customerID;
     private $inDate;
     public $response;
     private $conn;
     
     public function __construct() {
         $this->customerID  =         $_POST['id'];
         $this->inDate      =         date('d-m-Y');
         $this->response    =         array();
         
         $this->conn        =         new dbconnection();
         
         
         
     }
     
     public function cancelBooking(){
         
         
       
         
        header('Content-type: application/json; charset=UTF-8');
        
        
        
	if (!empty($this->customerID)) {
            
            
                
                $deleteEntry="DELETE FROM bookings WHERE c_id = :c_id";
                $this->conn->query($deleteEntry);
                $this->conn->bind(':c_id', $this->customerID);
                $this->conn->execute();
                $rs=$this->conn->resultSet();
                
                        
                
                
                if($rs){
                    
                    $response['status']  = 'success';
                    $response['message'] = ' Booking Cancelled Successfully' ;
                    
                }else{
			$response['status']  = 'error';
			$response['message'] = 'Unable to cancel booking ...';
		}
		
	}else{
           
            $response['status']  = 'Error';
            $response['message'] = 'Customer not found!';
        }
         
       
	
        echo json_encode($response);
     }
     
        
        
        
        
    }


	
	
	
        
        
 ?>

