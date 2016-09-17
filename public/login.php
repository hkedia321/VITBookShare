<?php
$loginerrors=array();
include('../includes/validations.php');
include('../includes/functions.php');
session_start();
if(isset($_POST['loginsubmit']))
{
    $regno=$_POST['regno'];
    $password=$_POST['password'];
    make_connection();
    $success=attempt_login($regno,$password);
    if(!$success)
        $loginerrors['login']="Wrong registration no./password";
    else
    {
        //authentication successfull
        $_SESSION['login']=$regno;//**********ID for recognizing user*********
        redirect_to("home.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
       <?php include('../includes/head.php');?>
    <title>VIT Book Share- Login</title>
    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="materialize/css/materialize.min.css">
    <link rel="stylesheet" type="text/css" href="css/signup.css">
    <style>

    </style>
</head>
<body>
    <div class="main">
        <div class="fbdiv">
            <a class="facebook" href="http://facebook.com/ACM.VITU"><span></span><i class="fa fa-facebook fa-2x" aria-hidden="true"></i></a>
        </div>
        <h1 class="vittitle">VIT Book Share</h1>
        <hr class="underlinehr">
        <main>
          <h2 class="center signuph2">Login</h2>
           <?php
          if(isset($loginerrors['login']) && !empty($loginerrors['login']))
            echo "<h5 class='center redcolor'>{$loginerrors['login']}</h5>";
        ?>
          <h4 class="center red-text"></h4>
          <div class="container">
            <div class="card z-depth-3">
              <div class="container">
                <div class="row">
                   <form action="login.php" method="post">
                    <div class="col s12 l12 m12 input-field"> 
                        <input required type="text" name="regno" value="<?php if(isset($regno)) echo "$regno";?>" id="regno"/>
                        <label class="" for="regno">Register no.</label>
                    </div>
                    <div class="col s12 l12 m12 input-field">
                     <input required type="password" name="password" id="password" />
                     <label class="active" for="password">Password</label>
                 </div>
                 <a class="waves-effect waves-light btn right">
                    <input type="submit" name="loginsubmit" value="Login"/><i class="material-icons left"></i>
                  </a>
                </form>  
                <div class="waves-effect waves-light btn left white"><a href="signup.php">Sign Up</a></div>
            </div>
        </div>
    </div>
</div>
</main>
<br><br>
</div>
<script src="js/jquery.js"></script>
<script src="materialize/js/materialize.min.js"></script>
</body>
</html>