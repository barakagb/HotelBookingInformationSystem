<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Hotel Booking System</title>
        <link rel="stylesheet" href="css/css.css" type="text/css">
        <link rel="stylesheet" href="css/style.css">
        <script src="js/jquery-2.2.0.js"></script>
    </head>
    <body contenteditable="false">
        
          
        <h3>Hotel Booking System</h3>
        
            <div class="form col-sm-4 col-sm-offset-8 col-lg-4 col-lg-offset-7">
                
                <div class="forceColor"></div>
                
                    <div class="topbar">
                        
                        <div class="spanColor"></div>
                        
                        <form action="authenticate.php" method="POST">

                            <input type="text"  name="uname" class="input" id="username" placeholder="username"/>

                            <input type="password" name="pwd" class="input" id="password" placeholder="Password"/>
                            
                            <button class="submit" id="submit" type="submit" >Login</button>
                        </form>

                    </div>
                
                    
                    
            </div>
        
        <div id="login_error">
            
            
            <?php 
                session_start();

                if(!empty($_SESSION['message'])){

                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);

                } 
                
          ?>
            
            
        </div>
        
       
        
            
         <script src="js/index.js"></script>
         
         <script type ="text/javascript">
             
                setInterval(function(){
                     $("#login_error").fadeOut();
                 },15000);
    
        </script>
        
    </body>
</html>
