<?php
include('../includes/functions.php');
session_start();
make_connection();
if(is_loggedin());
else
  redirect_to('index.php');
$user=get_user();
if(!$user)
 redirect_to('index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include('../includes/head.php');?>
  <title>VIT Book Share- Home</title>
  <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="materialize/css/materialize.min.css">
  <link rel="stylesheet" type="text/css" href="css/home.css">
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
        <li class="no-padding">
          <ul class="collapsible collapsible-accordion">
            <li>
              <a class="collapsible-header waves-effect waves-teal"><i class="fa fa-pencil" aria-hidden="true"></i>Edit details</a>
              <div class="collapsible-body">
                <ul>
                  <li><a href="editpersonaldetails.php"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;personal details</a></li>
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
        <h2 class="center">Welcome!</h2>
      </header>
      <p class="centerr">We all have encountered situations where we need a book and we think that the book must be there with a senior. Or we no longer need a book and we want to sell/donate it to a junior. Now with <b>VIT Book Share</b>, Book Sharing just got easier!</p> 
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
</body>
</html>