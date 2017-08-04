

<?php
    
    session_start();
    
    
   if(!$_SESSION['user']=='admin' || !$_SESSION['user']=='staff'){
       header("Location: index.php");
   }
   
    
  
  
    header('Content-type: application/json; charset=UTF-8');
    require 'dbconnection.php';
    
    $obj =new CheckOut();
    $obj->checkoutCustomer();
    
    class CheckOut{
        
     private $customerID;
     private $curDate;
     public $response;
     private $conn;
     private $curTime;


     public function __construct() {
         $this->customerID   =         $_POST['id'];
         $this->curDate      =         date('d-m-Y');
         $this->response     =         array();
         $this->curTime      =         date('G:i:s');
         $this->conn         =         new dbconnection();
         
         
         
     }
     
     public function checkoutCustomer(){
         
         
       
         
        header('Content-type: application/json; charset=UTF-8');
        
        
        
	if (!empty($this->customerID)) {
            
            
          
		
                $checkinCustomer= "INSERT INTO checkout (c_id,checkout_time) VALUES (:c_id,:time) ";

                $this->conn->query($checkinCustomer);
                $this->conn->bind(':c_id', $this->customerID);
                $this->conn->bind(':time', $this->curTime);
                $this->conn->execute();
                $rs=$this->conn->resultSet();
                
                
                $updateCheckOutDate="UPDATE customers SET checkout_date=:curDate WHERE c_id=:c_id";
                $this->conn->query($updateCheckOutDate);
                $this->conn->bind(':curDate', $this->curDate);
                $this->conn->bind(':c_id', $this->customerID);
                $this->conn->execute();
                $rs1=$this->conn->resultSet();
                
               
                
                        
                
                
                if($rs && $rs1){
                    
                    $response['status']  = 'success';
                    $response['message'] = ' Check-out Successfull' ;
                    
                }else{
			$response['status']  = 'error';
			$response['message'] = 'Unable to Check-out ...';
		}
		
	}else{
           
            $response['status']  = 'Error';
            $response['message'] = 'Customer not found!';
        }
         
       
	
        echo json_encode($response);
     }
     
        
        
        
        
    }


	
	
	
        
        
 ?>

