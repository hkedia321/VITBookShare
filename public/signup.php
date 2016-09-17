<?php
$regerrors=array();
include('../includes/validations.php');
include('../includes/functions.php');
if(isset($_POST['regsubmit']))
{
   $name=$_POST['name'];
   $regno=$_POST['regno'];
   $email=$_POST['email'];
   $phone=$_POST['phone'];

   if(isset($_POST['block']))
    $block=$_POST['block'];
else
    $block="";
if(isset($_POST['roomno']))
    $roomno=$_POST['roomno'];
else
    $roomno="";
if(isset($_POST['dayscholar']))
{
   $dayscholar=$_POST['dayscholar'];
   $block="99";
   $roomno="99";
}
else
    $dayscholar=false;
$password=$_POST['password'];
$passwordconfirm=$_POST['passwordconfirm'];
$regerrors=validate_signup($name,$regno,$email,$phone,$block,$roomno,$dayscholar,$password,$passwordconfirm);
if(!($dayscholar) && (empty($block)||empty($roomno)))
    $regerrors['block']="Enter hostel information or choose dayscholar";
if(empty($regerrors))
{
    //success in checking bad fields
    make_connection();
    $success=register_user($name,$regno,$email,$phone,$block,$roomno,$password);
    if(!$success)
    {
        $regerrors['submiterror']="Some error occured. Please try again.";
    }
    else
    {
        //successfully registered information in SQL
        session_start();
        $_SESSION['login']=$regno;//**********ID for recognizing user*********
        redirect_to("home.php");
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php include('../includes/head.php');?>
   <title>VIT Book Share- Signup</title>
   <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css">
   <link rel="stylesheet" type="text/css" href="materialize/css/materialize.min.css">
   <link rel="stylesheet" type="text/css" href="css/common.css">
   <link rel="stylesheet" type="text/css" href="css/signup.css">
</head>
<body>
    <div class="main">
        <div class="fbdiv">
            <span class="pull-left"><a href="index.php"><i class="fa fa-home fa-2x" aria-hidden="true"></i></a></span>
            <a class="facebook" href="http://facebook.com/ACM.VITU"><span></span><i class="fa fa-facebook fa-2x" aria-hidden="true"></i></a>
        </div>
        <h1 class="vittitle">VIT Book Share</h1>
        <hr class="underlinehr">
        <main>
          <h2 class="center signuph2">Signup</h2>
          <?php
          if(isset($regerrors['submiterror']) && !empty($regerrors['submiterror']))
            echo "<h3 class='center redcolor'>{$regerrors['submiterror']}</h3>";
        ?>

        <h4 class="center red-text"></h4>
        <div class="container">
            <div class="card z-depth-3">
              <div class="container">
                <div class="row">
                  <form id="reg_form" action="signup.php" method="post" autofocus>
                    <div class="col s12 l12 m12 input-field">
                        <input type="text" required class="" value="<?php if(isset($name)) echo $name;?>" name="name" id="name" />
                        <label class="active" for="name">Name</label>
                        <span class="regerror">
                            <?php
                            if(isset($regerrors) && isset($regerrors['name']))
                                echo $regerrors['name'];
                            ?>
                        </span>
                    </div>
                    <div class="col s12 l12 m12 input-field">
                        <input required type="text" name="regno" value="<?php if(isset($regno)) echo $regno;?>" id="regno" />
                        <label <?php if(isset($regno)) echo " class='active' ";?>for="regno">Registration No.</label>
                        <span class="regerror">
                            <?php
                            if(isset($regerrors) && isset($regerrors['regno']))
                                echo $regerrors['regno'];
                            ?>
                        </span>
                    </div>
                    <div class="col s12 l6 m6 input-field">
                        <input type="email" name="email" class="validate" value="<?php if(isset($email)) echo $email;?>" id="email" />
                        <label data-error="wrong email" <?php if(isset($email)) echo " class='active' ";?> for="email">Email</label>
                        <span class="regerror">
                            <?php
                            if(isset($regerrors) && isset($regerrors['email']))
                                echo $regerrors['email'];
                            ?>
                        </span>
                    </div>
                    <div class="col s12 l6 m6 input-field">
                        <input type="number" name="phone" class="validate" value="<?php if(isset($phone)) echo $phone;?>" id="phone" />
                        <label <?php if(isset($phone)) echo " class='active' ";?> for="phone">Phone</label>
                        <span class="regerror">
                            <?php
                            if(isset($regerrors) && isset($regerrors['phone']))
                                echo $regerrors['phone'];
                            ?>
                        </span>
                        <?php
                        if(isset($regerrors['email']) && !empty($regerrors['email']))
                            echo "<br><br>";
                        ?>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <select name="block" id="block">
                            <option value="0">select an option..</option>
                            <optgroup label="Mens hostel">
                                <option value="1">MH A</option>
                                <option value="2">MH B</option>
                                <option value="3">MH B-annex</option>
                                <option value="4">MH C</option>
                                <option value="5">MH D</option>
                                <option id="6" value="6">MH D-annex</option>
                                <option value="7">MH E</option>
                                <option value="8">MH F</option>
                                <option value="9">MH G</option>
                                <option value="10">MH H</option>
                                <option value="11">MH J</option>
                                <option value="12">MH K</option>
                                <option value="13">MH L</option>
                                <option value="14">MH M</option>
                                <option value="15">MH M-annex</option>
                                <option value="16">MH N</option>
                                <option value="17">MH P</option>
                            </optgroup>
                            <optgroup label="Girls Hostel">
                                <option value="18">LH A</option>
                                <option value="19">LH B</option>
                                <option value="20">LH C</option>
                                <option value="21">LH D</option>
                                <option value="22">LH E</option>
                                <option value="23">LH F</option>
                                <option value="24">LH G</option>
                            </optgroup>
                        </select>
                        
                        <label>Hostel Block</label>
                        <span class="regerror">
                            <?php
                            if(isset($regerrors) && isset($regerrors['block']))
                                echo $regerrors['block'];
                            ?>
                        </span>
                    </div>


                    <div class="col s12 l6 m6 input-field">
                        <input type="text" name="roomno" value="<?php if(isset($roomno)) echo $roomno;?>" id="roomno" />
                        <label <?php if(isset($roomno)) echo " class='active' ";?> for="roomno">Room no.</label>
                        <span class="regerror">
                            <?php
                            if(isset($regerrors) && isset($regerrors['roomno']))
                                echo $regerrors['roomno'];
                            ?>
                            <?php
                            if(isset($regerrors) && isset($regerrors['block']))
                                echo "&nbsp;";
                            ?>
                        </span>
                        <br>
                    </div>
                    <div class="row">
                        <div class="col s12 m4">
                            &nbsp;&nbsp;
                            <input type="checkbox" name="dayscholar" onclick="dayscholarclicked()" class="filled-in" id="dayscholar"/>
                            <label for="dayscholar">I am a Day Scholar</label>
                        </div>
                    </div>

                    <div class="col s12 l6 m6 input-field">
                        <input required type="password" name="password" id="password" />
                        <label for="password">Password</label>
                        <span class="regerror">
                            <?php
                            if(isset($regerrors) && isset($regerrors['password']))
                                echo $regerrors['password'];
                            ?>
                        </span>
                    </div>
                    <div class="col s12 l6 m6 input-field">
                        <input required type="password" name="passwordconfirm" id="passwordconfirm" />
                        <label for="passwordconfirm">Password Confirmation</label>
                        <span class="regerror">
                            <?php
                            if(isset($regerrors) && isset($regerrors['passwordconfirm']))
                                echo $regerrors['passwordconfirm'];
                            ?>
                        </span>
                    </div>
                    <a class="waves-effect waves-light btn right">
                        <input type="submit" name="regsubmit" value="Register" />
                        <i class="material-icons"></i>
                    </a>

                </form>
                <div class="waves-effect waves-light btn left white"><a href="login.php">Login</a></div>
            </div>
        </div>
    </div>
</div>
</main>
<br><br>
</div>
<script src="js/jquery.js"></script>
<script src="materialize/js/materialize.min.js"></script>
<script>
$(document).ready(function() {
    $('select').material_select();
});
</script>
<script>
var options=document.getElementsByTagName('option');
<?php 
if(isset($block))
{
    ?>
    options[<?php echo (int)$block;?>].selected=true;
    $('select').material_select();
    <?php
}
else
{
    ?>
    options[0].selected=true;
    $('select').material_select();
    <?php
}
?>
</script>
<script>
var dayscholar=document.getElementById('dayscholar');
var block=document.getElementById('block');
var roomno=document.getElementById('roomno');
function dayscholarclicked(){
    if(dayscholar.checked)
    {
        block.disabled=true;
         $('select').material_select();
        roomno.disabled=true;
    }
    else
    {
       block.disabled=false;
        $('select').material_select();
       roomno.disabled=false;
   }
}
</script>
</body>
</html>