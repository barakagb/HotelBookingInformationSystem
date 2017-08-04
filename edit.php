<?php


session_start();


if(!$_SESSION['user']=='admin' || !$_SESSION['user']=='staff'){
header("Location: index.php");
}



$obj = new EditRoom();
$obj->updateRoom();


class EditRoom{
    
    private $roomID;
    private $newPrice;
    private $conn;
    private $rs;
    private $rs1;
    private $rs2;
    private $roomName;
    private $roomPrice;
    private $username;
    private $pass;
    private $pass2;
    private $password;




    public function __construct() {
        require 'dbconnection.php';
        
        $this->conn                     =        new dbconnection();
        $this->roomID                   =       filter_input(INPUT_POST, id);
        $this->newPrice                 =       filter_input(INPUT_POST, new_price);
        $this->roomName                 =       strtoupper(filter_input(INPUT_POST, roomName));
        $this->roomPrice                =       filter_input(INPUT_POST, roomPrice);
        $this->username                 =       filter_input(INPUT_POST, username);
        $this->pass                     =       filter_input(INPUT_POST, pass);
        $this->pass2                    =       filter_input(INPUT_POST, repass);
        
    }
    
    public function updateRoom(){
        
        if (isset($this->roomID)&& !empty($this->roomID)){
            
            $_SESSION['roomID']=  $this->roomID;
            
            $this->conn->query("SELECT * FROM rooms WHERE room_id=:id");
            $this->conn->bind(':id', $this->roomID);
            $this->conn->execute();
            $this->rs=  $this->conn->resultSet();
            
            if($this->rs){
                
                foreach ($this->rs as $row){
                    
                    echo "Room Name : " . strtoupper($row['room_name']);
                
                
                }
                
                
                
                
            }else {
                echo 'Aww something is wrong could not fetch data ';
            }
        
        }
        elseif (isset($this->newPrice) && !empty($this->newPrice) && isset($_SESSION['roomID'] )    ){
            
            
            $this->conn->query("UPDATE rooms SET room_price=:price WHERE room_id=:id");
            $this->conn->bind(':id', $_SESSION['roomID']);
            $this->conn->bind(':price', $this->newPrice);
            $this->conn->execute();
            $this->rs1=  $this->conn->resultSet();
            
            
            if($this->rs1){
                
                echo  'Room Price Updated Succesfully';
                unset($_SESSION['roomID']);
                
            }else{
                
                echo 'Aww something is wrong could not update ' ;
                unset($_SESSION['roomID']);
            }
    
            
            
    }
        elseif (isset($this->roomName) && isset($this->roomPrice)  &&!empty ($this->roomName) && !empty ($this->roomPrice)){
            
            
            $this->conn->query("INSERT INTO rooms (room_name ,room_price)  VALUES (:roomName,:roomPrice) RETURNING room_name");
            $this->conn->bind(':roomName', $this->roomName);
            $this->conn->bind(':roomPrice', $this->roomPrice);
            $this->conn->execute();
            $this->rs2=  $this->conn->resultSet();
            
            
            if($this->rs2){
                
                echo  'Room Added Succesfully';
               
                
            }else{
                
                echo 'Aww something is wrong could not update ' ;
               
            }
    
            
            
    }
    
    
             elseif (isset($this->username) && isset($this->pass)  && isset($this->pass2) && !empty($this->username) && !empty($this->pass) && !empty($this->pass2)){
                 
                 
                if($this->pass == $this->pass2){
                     
                    $this->conn->query("INSERT INTO users (uname, pwd) VALUES (:uname,:pwd) RETURNING id");
                    $this->conn->bind(':uname', $this->username);
                    $this->conn->bind(':pwd', $this->pass);
                    $this->conn->execute();
                    $this->rs2=  $this->conn->resultSet();
                    
                    
                    if($this->rs2){
                
                         echo  $this->username .' Added Succesfully';
               
                
                    }else{
                
                         echo 'Aww something is wrong could not update ' ;
               
                    }
                     
                     
                     
                     
                     
                }else {
                     
                     echo 'Passwords do not Match';
                     
                 }
            
 
             }
    
    
    else{
        echo 'Some field(s) empty !';
        
       
        
        
    }
    
       
}
 
}

?>