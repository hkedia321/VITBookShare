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
  <title>VIT Book Share- Search book</title>
  <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="materialize/css/materialize.min.css">
  <link rel="stylesheet" type="text/css" href="css/home.css">

  <link rel="stylesheet" type="text/css" href="css/searchbook.css">
  <style>
  .navlinks{
    padding-top: 5px;
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
           <a href="home.php"> <div class="navname"><?php echo $user['name'];?></div></a>
           <a href="home.php">  <div class="navregno"><?php echo $user['regno'];?></div></a>
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
        <li class="active"><a class="" href="searchbook.php"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;&nbsp;Search for a book</a></li>
        <li><a class="" href="addbook.php"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Add a new book</a></li>
        <li><a class="" href="logout.php"  onclick="return confirm('Are you sure you want to logout?')"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;&nbsp;logout</a></li>
      </div>
    </ul>
  </div>
  <a href="#" data-activates="slide-out" class="button-collapse navbutton"><i class="fa fa-bars fa-3x" aria-hidden="true"></i></a>

  <div class="main">
    <div class="container">
      <header>
        <h2 class="center">Search for a book</h2>
      </header>
      <div class="searchform">
        <form action="searchbook.php" action="POST">
          <div class="row">
            <div class="col offset-s1 s6">
              <div class="input-field">
                <input id="searchinput" name="searchinput" type="text" class="validate">
                <label for="searchinput">Search for..</label>
              </div>
            </div>
            <div class="col s3 left-align valign">
              <br>
              <a class="waves-effect waves-light btn valign">
                <input type="submit" class="valign" name="searchsubmit" value="Search"/>&nbsp;&nbsp;<i class="fa fa-angle-right" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </form>
      </div>
      <div class="booksdiv">
        <?php
        $all_books_result=get_all_books();
        $nobooks=0;
        while($row=mysqli_fetch_assoc($all_books_result))
        {
          echo print_a_book($row);
          $nobooks++;
        }
        if($nobooks==0)
          echo "<div class='nobook'>No books found..</div>";
        ?>
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
$(document).ready(function(){
    // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
    $('.modal-trigger').leanModal();
  });
</script>
</body>
</html>