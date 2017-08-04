<?php

require 'dbconnection.php';
session_start();
class authenticate{
    
    private $uname;
    private $pwd;
    private $conn;
    private $errorMsg;
    
    
    function __construct() {
        
        
        $this->conn= new dbconnection();
    }

    


    public function login(){
        
        
        
        $uname=filter_input(INPUT_POST, uname);
        $pwd=  filter_input(INPUT_POST,pwd);
        
        $query="SELECT * FROM users WHERE uname=:uname and pwd= :pwd";
        
        if(isset($uname) && isset($pwd)){
            
             
            $this->conn->query($query);
            $this->conn->bind(':uname',$uname);
            $this->conn->bind(':pwd',$pwd);
            $this->conn->execute();
            $rs=$this->conn->resultSet();
            
            
            if ($rs){
                 
                
                if($uname=='admin'){
                    
                    $_SESSION['user']='admin';
                    
                     header("Location: admin.php");
                    
                }else {
                    $_SESSION['user']='staff';
                    header("Location: front_desk.php");
                }
                
            }else {
                
               $errorMsg= 'invalid username/password';
               $this->loginErr($errorMsg);
            }
            
        }else {
            $errorMsg= 'All fields required!';
            $this->loginErr($errorMsg);
        }
        
      
    
        
    }
    
    public function loginErr($errorMsg){
        
        $_SESSION['message']=$errorMsg;
        header("Location: index.php");
        
    }
    
     
    
   
    
    
}

$auth=new authenticate();
$auth->login();
