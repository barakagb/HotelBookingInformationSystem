<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    
   

    <title>Login | HotelBookingInformationSystem</title>

    <!-- Bootstrap CSS -->    
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- bootstrap theme -->
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <!--external css-->
    <!-- font icon -->
    <link href="css/elegant-icons-style.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles -->
    <link href="css/style1.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

    <script src="js/jquery-2.2.0.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

  <body class="login-img3-body" contenteditable="false">

    <div class="container">

      <form class="login-form" action="authenticate.php" method="POST">        
        <div class="login-wrap">
            <p class="login-img"><i class="icon_lock_alt"></i></p>
            <div class="input-group">
              <span class="input-group-addon"><i class="icon_profile"></i></span>
              <input type="text" name="uname" class="form-control" placeholder="Username" autofocus>
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon_key_alt"></i></span>
                <input type="password" name="pwd" class="form-control" placeholder="Password">
            </div>
            
            <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
            
            <section id="errMsg">
             <?php 
                session_start();

                if(!empty($_SESSION['message'])){

                    echo "<h3 styele='text-align:center; color:red !important;'>" .  $_SESSION['message']. "</h3>" ; 
                    unset($_SESSION['message']);

                } 
                
          ?>
                
            </section>
            
        </div>
      </form>

</div>


         
         <script type ="text/javascript">
             
                setInterval(function(){
                     $("#errMsg").fadeOut();
                 },15000);
    
        </script>
           


  </body>
</html>
