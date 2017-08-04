<?php

session_start();


if(!$_SESSION['user']=='admin' || !$_SESSION['user']=='staff'){
header("Location: index.php");
}

use PDO;

class dbconnection{
    
    private $host ='localhost';
    private $user ='postgres';
    private $pass ='root';
    private $dbname='HotelBookingSystem';
    private $pst;
    private $conn;
 
    
    
    
    public function __construct() {
        
 
        try{
            $this->conn = new PDO("pgsql:host=$this->host;dbname=$this->dbname;", $this->user, $this->pass);
            
           
        
            }catch (PDOException $ex) {
                 echo ("connection failed " .$ex->getMessage());

            }

    }

    
    public  function query($query){
        
        $this->pst=  $this->conn->prepare($query);
         
    }
    
    public function bind($param,$value,$type=null){
        if(is_null($type)){
            switch (true){
                case is_int($value);
                $type= PDO::PARAM_INT;
                break; 
            
                case is_bool($value);
                $type= PDO::PARAM_BOOL;
                break; 
            
                case is_null($value);
                $type= PDO::PARAM_NULL;
                break; 
            
            
                case is_string($value);
                $type= PDO::PARAM_STR;
                break; 
            }
            
            
        }
             
        $this->pst->bindValue($param,$value,$type);
    }
    
    
    public function execute(){
        try{
        return $this->pst->execute();
        }  catch (PDOException $e){
            return FALSE;
        }
    }
    public function resultSet(){
        
        try{
        return $this->pst->fetchAll(PDO::FETCH_ASSOC);
        }  catch (PDOException $e){
            return FALSE;
        }
    }
    
    public function getResultSet(){
        if($this->pst->fetch(PDO::FETCH_ASSOC)){
            return TRUE;
    }
        else {
            return FALSE;
        }
    }
        
        
     
}       
        
        

        