<?php

function redirect_to($page)
{
	header("Location: {$page}");
	exit;
}
function make_connection(){
	global $connection;
	
	$dbhost="localhost";
	$dbuser="root";
	$dbpass="secret";
	$dbname="vitbookshare";
	$connection=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
	if(mysqli_connect_errno()){
		die("Database connection failed:".mysqli_conect_error()."(".mysqli_connect_errorno().")"
			);
	}
}
function sql_escape($input){
	global $connection;
	return mysqli_real_escape_string($connection,$input);
}
function confirm_query($result){
	if(!$result){
		echo "DataBase Query failed.";
	}
}

function register_user($name,$regno,$email,$phone,$block,$roomno,$password)
{
	global $connection;
	$name=sql_escape($name);
	$regno=sql_escape($regno);
	$email=sql_escape($email);
	$phone=sql_escape($phone);
	$block=sql_escape($block);
	$roomno=sql_escape($roomno);
	$password=sql_escape($password);
	$password=password_hash($password,PASSWORD_DEFAULT);
	$query="INSERT INTO user ";
	$query.="VALUES('{$name}','{$regno}','{$email}',{$phone},'{$block}','{$roomno}','{$password}');";
	$result=mysqli_query($connection,$query);
	if(!$result)
	{
		return 0;
	}
	return 1;
}
function update_user($name,$regno,$email,$phone,$block,$roomno,$password)
{
	global $connection;
	$name=sql_escape($name);
	$regno=sql_escape($regno);
	$email=sql_escape($email);
	$phone=sql_escape($phone);
	$block=sql_escape($block);
	$roomno=sql_escape($roomno);
	$password=sql_escape($password);
	$password=password_hash($password,PASSWORD_DEFAULT);
	$query="REPLACE INTO user ";
	$query.="VALUES('{$name}','{$regno}','{$email}',{$phone},'{$block}','{$roomno}','{$password}');";
	$result=mysqli_query($connection,$query);
	if(!$result)
	{
		return 0;
	}
	return 1;
}
function attempt_login($regno,$password)
{
	global $connection;
	$query="SELECT password FROM user WHERE regno='{$regno}' limit 1;";
	$result=mysqli_query($connection,$query);
	if(!$result)
	{
		return 0;
	}
	$row=mysqli_fetch_assoc($result);
	$password_db=$row['password'];
	if(password_verify($password,$password_db))
		return 1;
	else
		return 0;
}
function is_loggedin(){
	if(isset($_SESSION['login']) && !empty($_SESSION['login']) && get_user())
		return true;
	else
		return false;
}
function get_user()
{
	global $connection;
	if((!isset($_SESSION['login'])) || empty($_SESSION['login']))
		return null;
	$regno=$_SESSION['login'];
	$query="SELECT * FROM user WHERE regno='{$regno}' LIMIT 1;";
	$result=mysqli_query($connection,$query);
	if(!$result)
		return null;
	$row=mysqli_fetch_assoc($result);
	if(!$row)
		return null;
	return $row;
}
function get_all_books()
{
	global $connection;
	$query="SELECT * FROM book order by price;";
	$result=mysqli_query($connection,$query);
	if(!$result)
		return null;
	return $result;
}
function get_my_books($regno)
{
	global $connection;
	$query="SELECT * FROM book WHERE owner_id='{$regno}';";
	$result=mysqli_query($connection,$query);
	if(!$result)
		return null;
	return $result;
}
function print_a_book($row)
{
	$id=$row['id'];
	$name=htmlspecialchars($row['name']);
	$author=$row['author'];
	$course=$row['course'];
	$price=$row['price'];
	$symbol="&#8377;";

	$owner_name=$row['owner_name'];
	$owner_email=$row['owner_email'];
	$owner_phone=$row['owner_phone'];
	$owner_block=$row['owner_block'];
	$owner_roomno=$row['owner_roomno'];
	$imageuploaded=$row['imageuploaded'];
	if($owner_block==99)
	{
		$owner_block="dayscholar";
		$owner_roomno="";
	}
	if(empty($owner_block))
	{
		$owner_block="";
		$owner_roomno="";
	}
	if(empty($owner_roomno))
		$owner_roomno="";
	$visible=$row['visible'];
	if($visible==0)
		return "";// donot display book
	if($price==0||empty($price))
	{
		$symbol="FREE";
		$price='';
	}
	$health=$row['health'];
	if($imageuploaded)
		$pathtoimage="book_images/{$id}.png";
	else
		$pathtoimage="book_images/default_image.png";
	$toprint="

	<section class='book card-panel hoverable modal-trigger' href='#modal{$id}'>
	<div class='row'>
	<h5 class='center booktitle'>{$name}</h5>
	<div class='col s4'>
	<a href='{$pathtoimage}' target='_blank'>
	<img src='{$pathtoimage}' class='bookimg responsive-img'>
	</a>
	<div class='bookauthor center'>by <i>{$author}</i></div>
	</div>
	<div class='col s8'>
	<div class='bookcourse'><b>{$course}</b></div>
	<div class='bookhealth'>{$health}</div>
	</div>
	<div class='right bookprice'>{$symbol}{$price}</div>
	<div class='clicktoview'><a href=''>view owner</a></div>
	</section>";
	//now the modal
	$toprint.="
	<div id='modal{$id}' class='modal'>
	<div class='modal-content center'>
	<h4 class='center booktitle'>{$name} </h4>
	<p class='ownedby'>Owned by -</p>
	<div class='row'>
	<i class='fa fa-user fa-2x hide-on-small-only' aria-hidden='true'></i>
	<i class='fa fa-user fa-1x hide-on-med-and-up' aria-hidden='true'></i>
	<span class='bookperson'>&nbsp;{$owner_name}</span>
	</div>
	<div class='row'>";
	if(!empty($owner_block))
	{
		$toprint.="
		<i class='fa fa-map-marker fa-2x hide-on-small-only' aria-hidden='true'></i>
		<i class='fa fa-map-marker fa-1x hide-on-med-and-up' aria-hidden='true'></i>
		<span class='bookblock'>{$owner_block}</span>
		<span class='bookroomno'>&nbsp;&nbsp;{$owner_roomno}</span>";
	}
	$toprint.="
	</div>
	<div class='row'>
	";

	if(!empty($owner_email))
	{
		$toprint.="
		<i class='fa fa-envelope fa-2x hide-on-small-only' aria-hidden='true'></i>
		<i class='fa fa-envelope fa-1x hide-on-med-and-up' aria-hidden='true'></i>
		<span class='bookemail'>&nbsp;{$owner_email}</span>
		&nbsp;&nbsp;";
	}
	if(!empty($owner_phone))
	{
		$toprint.="
		<i class='fa fa-phone fa-2x hide-on-small-only' aria-hidden='true'></i>
		<i class='fa fa-phone fa-1x hide-on-med-and-up' aria-hidden='true'></i>
		<span class='bookphone'>&nbsp;{$owner_phone}</span>";
	}
	$toprint.="
	</div>
	</div>
	<div class='modal-footer'>
	<a class=' modal-action modal-close waves-effect waves-green btn-flat'>Close</a>
	<a class=' modal-action modal-close waves-effect waves-green btn'>Send Request</a>
	</div>
	</div>

	";
	return $toprint;

}
function print_my_book($row)
{
	$name=htmlspecialchars($row['name']);
	$author=$row['author'];
	$course=$row['course'];
	$price=$row['price'];
	$symbol="&#8377;";
	if($price==0||empty($price))
	{
		$symbol="FREE";
		$price='';
	}
	$health=$row['health'];
	$pathtoimage='images/book2.png';
	$toprint="
	  <a href='editthisbook.php'>
	<section class='book card-panel hoverable'>
	<div class='row'>
	<h5 class='center booktitle'>{$name}</h5>
	<div class='col s4'>
	<a href='{$pathtoimage}' target='_blank'>
	<img src='{$pathtoimage}' class='bookimg responsive-img'>
	</a>
	<div class='bookauthor center'>by <i>{$author}</i></div>
	</div>
	<div class='col s8'>
	<div class='bookcourse'><b>{$course}</b></div>
	<div class='bookhealth'>{$health}</div>
	</div>
	<div class='right bookprice'>{$symbol}{$price}</div>
	<div class='clicktoview'>click to edit..</div>
	</section>
	</a>
	";
	return $toprint;

}
?>