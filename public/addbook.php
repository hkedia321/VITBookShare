<?php
include('../includes/functions.php');
include('../includes/validations.php');
session_start();
make_connection();
if(is_loggedin());
else
  redirect_to('index.php');
$user=get_user();
if(!$user)
 redirect_to('index.php');

if($user['block']==99)
  $userisdayscholar=true;
else
  $userisdayscholar=false;
$addbookerrors=array();//empty array
//if form submits
if(isset($_POST['addbooksubmit']))
{
  $name=$_POST['name'];
  $author=$_POST['author'];
  $course=$_POST['course'];
  $price=$_POST['price'];
  $health=$_POST['health'];
  
  if(isset($_POST['dispmyname']) && $_POST['dispmyname'])
  {
    $dispmyname=true;
    $owner_name=$user['name'];
  }
  else
  {
    $dispmyname=false;
    $owner_name="Annonymous";
  }
  if(isset($_POST['dispmyemail']) && $_POST['dispmyemail'])
  {
    $dispmyemail=true;
    $owner_email=$user['email'];
  }
  else
  {
    $dispmyemail=false;
    $owner_email=0;
  }
  if(isset($_POST['dispmyphone']) && $_POST['dispmyphone'])
  {
    $dispmyphone=true;
    $owner_phone=$user['phone'];
  }
  else
  {
    $dispmyphone=false;
    $owner_phone=0;
  }
  if(isset($_POST['dispmyhostel']) && $_POST['dispmyhostel'])
  {
    $dispmyhostel=true;
    $owner_block=$user['block'];
    $owner_roomno=$user['roomno'];
  }
  else
  {
    $dispmyhostel=false;
    $owner_block="null";
    $owner_roomno="null";
    if($userisdayscholar)
    {
     $owner_block="99";
     $owner_roomno="99";
   }
 }

 $addbookerrors=validate_addbook($name,$author,$price,$health);
 if(!$dispmyemail && !$dispmyphone)
  $addbookerrors['phone']="please select atleast 1 out of email or phone.";
if(empty($addbookerrors))
{
  $owner_id=$user['regno'];
  $visible=1;
  $name=sql_escape($name);
  $author=sql_escape($author);
  $course=sql_escape($course);
  $price=sql_escape($price);
  $health=sql_escape($health);
  $owner_id=sql_escape($owner_id);
  $owner_name=sql_escape($owner_name);
  $owner_email=sql_escape($owner_email);
  $owner_phone=sql_escape($owner_phone);
  $owner_block=sql_escape($owner_block);
  $owner_roomno=sql_escape($owner_roomno);
  if(isset($_FILES['photoofbook']) && !empty($_FILES['photoofbook']))
    $imageuploaded=1;
  else
    $imageuploaded=0;
  $imageuploaded=sql_escape($imageuploaded);
  $visible=sql_escape($visible);
  $query="INSERT INTO book(name,author,course,price,health,owner_id,owner_name,owner_email,owner_phone,owner_block,owner_roomno,imageuploaded,visible)";
  $query.=" values('$name','$author','$course',$price,'$health','$owner_id','$owner_name','$owner_email',$owner_phone,$owner_block,'$owner_roomno',$imageuploaded,$visible);";
  $result=mysqli_query($connection,$query);
 // echo $query;
  if(!$result)
    $addbookerrors['addbookerror']="database update failed. Please try later.";
  else
    $book_id=mysqli_insert_id($connection);

//upload file
  if(empty($addbookerrors) && isset($_FILES['photoofbook']) && !empty($_FILES['photoofbook']) && !empty($_FILES["photoofbook"]["tmp_name"]))
  {
    $target_dir = "book_images/";
    $target_file = $target_dir . $book_id.".png";//new name of the file
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["photoofbook"]["tmp_name"]);
    if($check !== false) {
      //echo "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
    } else {
      $addbookerrors['file']= "File is not an image.";
      $uploadOk = 0;
    }
// Check file size
    if ($_FILES["photoofbook"]["size"] > 500000) {
      $addbookerrors['file']= "Sorry, your file is too large.";
      $uploadOk = 0;
    }
// Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif" ) {
      $addbookerrors['file']= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }
// Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    if(!isset($addbookerrors['file']))
    $addbookerrors['file']= "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["photoofbook"]["tmp_name"], $target_file)) {
      $to_echo_to_check= "The file ". basename( $_FILES["photoofbook"]["name"]). " has been uploaded.";
    } else {
      if(!isset($addbookerrors['file']))
      $addbookerrors['file']= "Sorry, there was an error uploading your file.";
    }
  }
}
}
if(empty($addbookerrors))
{
    //success. now insert into database
  $addbookerrors['addbooksuccess']="successfully added a new book";
}
else
{
  if(!isset($addbookerrors['addbookerror']))
    $addbookerrors['addbookerror']="Some error occurred. Please try again later";
  if(isset($book_id))
  {
    $query="DELETE FROM book WHERE id=$book_id";
    $result=mysqli_query($connection,$query);
  }

}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include('../includes/head.php');?>
  <title>VIT Book Share- Add new book</title>
  <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="materialize/css/materialize.css">
  <link rel="stylesheet" type="text/css" href="css/home.css">
  <link rel="stylesheet" type="text/css" href="css/searchbook.css">
  <style>
  .blackcolor{
    color:#000 !important;
    font-size: 20px !important;
  }
  *{

  }
  html,body{
    overflow-x:hidden;
  }
  .dispmydiv label{
    vertical-align: middle;
    display: inline-block;
  }
  .dispmydiv input{
    vertical-align: middle;
    
  }
  .addbookerror,.redcolorerror{
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
        <li class="no-padding">
          <ul class="collapsible collapsible-accordion">
            <li>
              <a class="collapsible-header waves-effect waves-teal"><i class="fa fa-pencil" aria-hidden="true"></i>Edit details</a>
              <div class="collapsible-body">
                <ul>
                  <li><a href="editpersonaldetails.php"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;personal details</a></li>
                  <li class=""><a href="editbookdetails.php"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;book details</a></li>
                </ul>
              </div>
            </li>
          </ul>
        </li>
        <li class=""><a class="" href="searchbook.php"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;&nbsp;Search for a book</a></li>
        <li class="active"><a class="" href="addbook.php"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Add a new book</a></li>
        <li><a class="" href="logout.php" onclick="return confirm('Are you sure you want to logout?')"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;&nbsp;logout</a></li>
      </div>
    </ul>
  </div>
  <a href="#" data-activates="slide-out" class="button-collapse navbutton"><i class="fa fa-bars fa-3x" aria-hidden="true"></i></a>

  <div class="main">
    <div class="container">
      <header>
        <h2 class="center">Add a new book</h2>
        <?php if(isset($addbookerrors) && isset($addbookerrors['addbookerror']))
        {
          ?>
          <div class="addbookerror">
            <h5 class="center redcolorerror"><?php echo $addbookerrors['addbookerror'];?></h5>
          </div>
          <?php
        }
        ?>
        <?php if(isset($addbookerrors) && isset($addbookerrors['addbooksuccess']))
        {
          ?>
          <div class="addbooksuccess">
            <h5 class="center greencolor"><?php echo $addbookerrors['addbooksuccess'];?></h5>
          </div>
          <?php
        }
        ?>
        <br>
      </header>
      <div class="addnewbook">
        <form action="addbook.php" method="POST" enctype="multipart/form-data">
          <div class="row">
           <div class="input-field col s12 m6">
            <input required id="name" name="name" class="validate" type="text" value="<?php if(isset($name) && empty($addbookerrors)) echo $name; ?>">
            <label for="name">Book Name</label>
          </div>
          <div class="input-field col s12 m6">
            <input required id="author" name="author" class="validate" type="text" value="<?php if(isset($author) && empty($addbookerrors)) echo $author; ?>">
            <label for="author">author</label>
          </div>
        </div>
        <div class="row">
         <div class="input-field col s12 m6">
          <input required id="course" class="validate" name="course" placeholder="Course it is useful for?" value="<?php if(isset($course) && empty($addbookerrors)) echo $course; ?>" type="text">
          <label for="course">Course code</label>
        </div>
        <div class="input-field col s12 m6">
          <i class="fa fa-inr prefix blackcolor valign" aria-hidden="true"></i>
          <input required id="price" value="<?php if(isset($price) && empty($addbookerrors)) echo $price;else echo 0; ?>" name="price" class="validate valign" placeholder="price you want to sell it at" type="number">
          <label for="price">price</label>
        </div>
      </div>
      <div class="row">
        <span>upload a photo of the book(<i>optional</i>)</span>
        <div class="file-field input-field">
          <div class="btn">
            <span>File</span>
            <input type="file" name="photoofbook" id="photoofbook">
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text">
          </div>
        </div>
        <span class="addbookerror">
          <?php 
          if(isset($addbookerrors) && isset($addbookerrors['file']))
            echo $addbookerrors['file'];
          ?>
        </span>
      </div>
      <div class="row">
        <div class="input-field col s12"><!-- 
          <i class="material-icons prefix">mode_edit</i> -->
          <textarea required name="health" id="health" length="200" placeholder="Why should the user buy this book? How good is the condition?" class="materialize-textarea" value="<?php if(isset($health) && empty($addbookerrors)) echo $health; ?>"></textarea>
          <label for="health">Describe the condition of your book..</label>
        </div>
      </div>
      <div class="row">
        <h5>Select your contact information to be displayed:</h5>
      </div>
      <div class="dispmydiv">
        <div class="row">

          <input type="checkbox" onclick="dispallclicked()" class="filled-in valign" name="dispall" id="dispall"/>
          <label for="dispall" class="valign"><b>display all</b></label>

        </div>
        <div class="row">
          <input type="checkbox" name="dispmyname" id="dispmyname"/>
          <label for="dispmyname" class="valign">display my Name</label>
        </div>
        <div class="row">
          <input type="checkbox" name="dispmyemail" class="" id="dispmyemail"/>
          <label for="dispmyemail">display my Email</label>
        </div>
        <div class="row">
          <input type="checkbox" name="dispmyphone" class="" id="dispmyphone"/>
          <label for="dispmyphone">display my Phone no.</label>
        </div>
        <?php
        if(!($userisdayscholar))
        {
          ?>
          <div class="row">
            <input type="checkbox" name="dispmyhostel" class="" id="dispmyhostel"/>
            <label for="dispmyhostel">display my Hostel information</label>
          </div>
          <?php
        }
        ?>

        <div >
          <span id="phoneerror" class="addbookerror"><?php if(isset($addbookerror['phone']) &&!empty($addbookerror['phone'])) echo $addbookerror['phone'];?></span>
        </div>
      </div>
      <button class="btn waves-effect waves-light col s12 m4 right" onclick="return validate_addbook();" type="submit" name="addbooksubmit">Add this book
        <i class="material-icons right">send</i>
      </button>
      <br>
      <br>
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
var dispall=document.getElementById('dispall');
var dispmyname=document.getElementById('dispmyname');
var dispmyemail=document.getElementById('dispmyemail');
var dispmyphone=document.getElementById('dispmyphone');
dispall.checked=true;
dispallclicked();
<?php
if(!($userisdayscholar))
{
  ?>
  var dispmyhostel=document.getElementById('dispmyhostel');
  <?php
}
?>
function dispallclicked()
{
  if(dispall.checked)//it is now checked
  {
    dispmyname.checked=true;
    dispmyemail.checked=true;
    dispmyphone.checked=true;
    <?php
    if(!($userisdayscholar))
    {
      ?>
      document.getElementById('dispmyhostel').checked=true;
      <?php
    }
    ?>
  }
  else
  {
    dispmyname.checked=false;
    dispmyemail.checked=false;
    dispmyphone.checked=false;
    <?php
    if(!($userisdayscholar))
    {
      ?>
      document.getElementById('dispmyhostel').checked=false;
      <?php
    }
    ?>
  }
}
</script>
<script>
function validate_addbook()
{
  if(dispmyemail.checked==false && dispmyphone.checked==false)
  {
    document.getElementById('phoneerror').innerHTML="Please select atleast 1 out of email or phone.";
    return false;
  }
  else
  {
   document.getElementById('phoneerror').innerHTML="";
   return true;
 }
}
</script>
<?php
if(isset($health) && empty($addbookerrors))
{
  ?>
<script>
health.value="<?php echo $health;?>";
$('#health').trigger('autoresize');
</script>
<?php
}
?>
</body>
</html>