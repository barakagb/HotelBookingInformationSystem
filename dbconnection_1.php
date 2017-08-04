<?php





try{
    $conn = new PDO("pgsql:host='localhost';dbname='HotelBookingSystem';", 'postgres', 'root');
    
    
} catch (PDOException $ex) {
    echo ("connection failed " .$ex->getMessage());

}



?>

