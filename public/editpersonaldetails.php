<?php
include('../includes/validations.php');
include('../includes/functions.php');
session_start();
make_connection();
if(is_loggedin());
else
  redirect_to('index.php');
$user=get_user();
if(!$user)
 redirect_to('index.php');

//form processing
$name=$user['name'];
$regno=$user['regno'];
$email=$user['email'];
$phone=$user['phone'];
$block=$user['block'];
$roomno=$user['roomno'];
if((int)$block==99)
  $dayscholar=true;
else
  $dayscholar=false;
if(isset($_POST['editpersubmit']))
{
 $email=$_POST['email'];
 $phone=$_POST['phone'];
 if(isset($_POST['block']) && !empty($_POST['block']))
   $block=$_POST['block'];
 else
  $block="";
if(isset($_POST['roomno']))
 $roomno=$_POST['roomno'];
else
  $roomno="";
$password=$_POST['password'];
$passwordconfirm=$_POST['passwordconfirm'];
if(isset($_POST['dayscholar']) && $_POST['dayscholar'])
{
 $dayscholar=true;
 $block="99";
 $roomno="99";
}
else
  $dayscholar=false;
$regerrors=validate_signup($name,$regno,$email,$phone,$block,$roomno,$dayscholar,$password,$passwordconfirm);
if(!($dayscholar) && (empty($block)||empty($roomno)))
    $regerrors['block']="Enter hostel information or choose dayscholar";
if(empty($regerrors))
{
    //success in checking bad fields
  make_connection();
  $success=update_user($name,$regno,$email,$phone,$block,$roomno,$password);
  if(!$success)
  {
    $regerrors['submiterror']="Some error occured. Please try again.";
  }
  else
  {
    $regerrors['submitsuccess']="Successfully updated information.";
    $name=null;
    $regno=null;
    $email=null;
    $phone=null;
    $block=null;
    $roomno=null;
    $password=$passwordconfirm=null;
  }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include('../includes/head.php');?>
  <title>VIT Book Share- Edit Personal details</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="materialize/css/materialize.min.css">
  <link rel="stylesheet" type="text/css" href="css/home.css">
  <style>
  .regerror{
    color: red;
    font-size: 12px;

  }
  .redcolor{
    color: red;
  }
  </style>
</head>
<body>
  <div class="body row">
    <div class="nav col l6">
      <ul id="slide-out" class="side-nav fixed collapsible collapsible-accordion">
        <li>
          <div class="userView">

           <a href="home.php"> <i class="fa fa-user fa-3x" aria-hidden="true"></i></a>
           <div class="navname"><?php echo $user['name'];?></div>
           <div class="navregno"><?php echo $user['regno'];?></div>
         </div>
       </li>
       <div class="navlinks">
        <li class="no-padding active">
          <ul class="collapsible collapsible-accordion">
            <li>
              <a class="collapsible-header waves-effect waves-teal"><i class="fa fa-pencil" aria-hidden="true"></i>Edit details</a>
              <div class="collapsible-body">
                <ul>
                  <li class="active"><a href="editpersonaldetails.php"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;personal details</a></li>
                  <li><a href="editbookdetails.php"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;book details</a></li>
                </ul>
              </div>
            </li>
          </ul>
        </li>
        <li class=""><a class="" href="searchbook.php"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;&nbsp;Search for a book</a></li>
        <li><a class="" href="addbook.php"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Add a new book</a></li>
        <li><a class="" href="logout.php"  onclick="return confirm('Are you sure you want to logout?')"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;&nbsp;logout</a></li>
      </div>
    </ul>
  </div>
  <a href="#" data-activates="slide-out" class="button-collapse navbutton"><i class="fa fa-bars fa-3x" aria-hidden="true"></i></a>

  <div class="main">
    <div class="container">
      <header>
       <nav> <h2 class="center banner pinkcolor">Edit Personal details</h2></nav>
       <br>
       <?php
       if(isset($regerrors['submiterror']) && !empty($regerrors['submiterror']))
        echo "<h3 class='center redcolor'>{$regerrors['submiterror']}</h3>";
      ?>
      <?php
      if(isset($regerrors['submitsuccess']) && !empty($regerrors['submitsuccess']))
        echo "<h3 class='center greencolor'>{$regerrors['submitsuccess']}</h3>";
      ?>
    </header>
    <div class="personalformdiv">
      <form id="edit_per_form" action="editpersonaldetails.php" method="post" autofocus novalidate>
        <div class="col s12 l6 m6 input-field">
          <input type="email" name="email" value="<?php if(isset($email)) echo $email;?>" id="email" />
          <label <?php if(isset($email)) echo " class='active' ";?> for="email">Email</label>
          <span class="regerror">
            <?php
            if(isset($regerrors) && isset($regerrors['email']))
              echo $regerrors['email'];
            ?>
          </span>
        </div>
        <div class="col s12 l6 m6 input-field">
          <input type="number" name="phone" value="<?php if(isset($phone)) echo $phone;?>" id="phone" />
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
          <select name="block" id="block" >
            <option value="0">select an option..</option>
            <optgroup label="Mens hostel">
              <option value="1">MH A</option>
              <option value="2">MH B</option>
              <option value="3">MH B-annex</option>
              <option value="4">MH C</option>
              <option value="5">MH D</option>
              <option value="6">MH D-annex</option>
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
          <input required type="text" name="roomno" value="<?php if(isset($roomno) && $roomno!="99") echo $roomno;?>" id="roomno" />
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
        </div>
        <div class="row">
          <div class="col s12 m4">
            &nbsp;&nbsp;
            <input type="checkbox" name="dayscholar" onclick="dayscholarclicked()" class="filled-in" id="dayscholar"/>
            <label for="dayscholar">I am a Day Scholar</label>
          </div>
        </div>
        <div class="col s12 l6 m6 input-field">
          <input required type="password" name="password" value="<?php if(isset($password)) echo $password;?>" id="password" />
          <label <?php if(isset($password)) echo " class='active' ";?> for="password">New Password</label>
          <span class="regerror">
            <?php
            if(isset($regerrors) && isset($regerrors['password']))
              echo $regerrors['password'];
            ?>
          </span>
        </div>
        <div class="col s12 l6 m6 input-field">
          <input required type="password" name="passwordconfirm" value="<?php if(isset($passwordconfirm)) echo $passwordconfirm;?>" id="passwordconfirm" />
          <label <?php if(isset($passwordconfirm)) echo " class='active' ";?> for="passwordconfirm">New Password Confirmation</label>
          <span class="regerror">
            <?php
            if(isset($regerrors) && isset($regerrors['passwordconfirm']))
              echo $regerrors['passwordconfirm'];
            ?>
          </span>
        </div>
        <br><br>
        <a class="waves-effect waves-light btn right">
          <input type="submit" name="editpersubmit" value="Update information" />
          <i class="material-icons right">send</i>
        </a>

      </form>

    </div>
  </div>
</div>
</div>
<script src="js/jquery.js"></script>
<script src="materialize/js/materialize.js"></script>
<script>
$(".button-collapse").sideNav();
$('.button-collapse').sideNav({
      menuWidth: 300, // Default is 240
      edge: 'left', // Choose the horizontal origin
      closeOnClick: false // Closes side-nav on <a> clicks, useful for Angular/Meteor
    }
    );
</script>
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
<?php
if(isset($dayscholar) && $dayscholar)
{
  ?>
  <script>
  document.getElementById('dayscholar').checked=true;
  $('select').material_select();
  dayscholarclicked();
  </script>
  <?php
}
?>
</body>
</html>