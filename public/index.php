<?php
include('../includes/functions.php');
session_start();
make_connection();
if(is_loggedin())
    redirect_to('home.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <script src="js/jquery.js"></script>
    <meta name="Language" content="en" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VIT Book Share</title>
    <meta name="description" content="Now share your old books with others and find the books you need." />
    <meta name="keywords" content="VIT, Book, Share, old, rent">
    <meta name="author" content="harshit kedia,harshitkedia32@gmail.com">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="VIT Book Share" />
    <meta property="og:url" content="ACMVIT.com" />
    <meta property="og:title" content="VIT Book Share" />
    <meta property="og:description" content="Now share your old books with others and find the books you need." />
    <meta property="og:image" content="http://acmvit.com/images/ourlogo2.png" />
    <meta itemprop="name" content="VIT Book Share" />
    <meta itemprop="description" content="Now share your old books with others and find the books you need." />
    <meta itemprop="image" content="images/favicon.ico" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@vitbookshare" />
    <meta name="twitter:title" content="VIT Book Share" />
    <meta name="twitter:description" content="Now share your old books with others and find the books you need." />
    <meta name="twitter:image" content="images/favicon.ico" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="images/rjday/apple-touch-icon-152x152.png" />
    <link rel="icon" type="image/png" href="images/favicon.ico" sizes="16x16" />
    <link rel="icon" type="image/png" href="images/favicon.ico" sizes="128x128" />
    <link rel="icon" href="images/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="materialize/css/materialize.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="main">
        <div class="fbdiv">
            <a class="facebook" href="http://facebook.com/ACM.VITU"><span></span><i class="fa fa-facebook fa-2x" aria-hidden="true"></i></a>
        </div>
        <h1 class="vittitle">VIT Book Share</h1>
        <h3 class="vitmotto">Book Sharing made easy</h3>
        <hr class="underlinehr">
        <br><br>
        <div class="butdiv">
            <a href="login.php"><button class="loginbut but z-depth-4 hoverable">Log in</button></a>
            <a href="signup.php"> <button class="signupbut but z-depth-4 hoverable">Sign up</button></a>
        </div>
    </div>
</body>
</html>