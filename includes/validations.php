<?php
$errors=array();
function fieldname_as_text($fieldname){
	$fieldname=str_replace("_"," ",$fieldname);
	$fieldname=ucfirst($fieldname);
	return $fieldname;
}   
function has_presence($value)
{
	return isset($value) && $value!=="";
}
function validate_presences($required_fields){
	global $errors;
	foreach ($required_fields as $field) {
		$value=trim($_POST[$field]);
		if(!has_presence($value)){
			$errors[$field]=fieldname_as_text($field)." can't be blank";
		}
	}
	$_SESSION['errors']=$errors;
}

function has_max_length($value, $max)
{
	return strlen($value)<=$max;
}
function has_exact_length($value, $max)
{
	return strlen($value)==$max;
}
function validate_max_lengths($fields_with_max_lengths)
{
	global $errors;
	//Expects an assoc. array
	foreach ($fields_with_max_lengths as $field => $max) {
		$value=trim($_POST[$field]);
		if(!has_max_length($value,$max)){
			$errors[$field]=fieldname_as_text($field)." is too long";
		}
	}
	$_SESSION['errors']=$errors;
}
function has_inclusion_in($value,$set)
{
	return in_array($value,$set);
}

function validate_signup($name,$regno,$email,$phone,$block,$roomno,$dayscholar,$password,$passwordconfirm)
{
	$noerrors=0;
	$regerrors=array();
	
	$patt="/1[0-6]{1}[a-zA-Z]{3}[0-9]{4}$/";
	if(!has_presence($name))
	{
		$regerrors['name']="name Can't be blank.";
		$noerrors++;
	}
	elseif(!has_max_length($name,30))
	{
		$regerrors['name']="name length must be less than 30.";
		$noerrors++;
	}
	if(!has_presence($regno))
	{
		$regerrors['regno']="Registration no. Can't be blank.";
		$noerrors++;
	}
	elseif(!has_exact_length($regno,9))
	{
		$regerrors['regno']="registration no. length must be 9.";
		$noerrors++;
	}
	elseif(!preg_match($patt,$regno))
	{
		$regerrors['regno']="Please enter a valid registration no.";
		$noerrors++;
	}
	if(!has_presence($email) && !has_presence($phone))
	{
		$regerrors['email']="Enter atleast 1 of email or phone no.";
	}
	else
	{
		if (has_presence($email) && !has_max_length($email,50)) {
			$regerrors['email']="Email max length is 50.";
		}
		if (has_presence($phone)) {
			if(!has_max_length($phone,11))
				$regerrors['phone']="Phone max length is 11.";
			elseif(!is_numeric($phone))
				$regerrors['phone']="Please enter a numeric phone no.";
		}
	}


	if(!has_presence($dayscholar))
	{
		if(!has_presence($block) || (int)$block==0 ||empty($block))
		{
			$regerrors['block']="Block name Can't be blank.";
			$noerrors++;
		}
		elseif(!has_max_length($block,2))
		{
			$regerrors['block']="Block max length is 2.";
			$noerrors++;
		}
		elseif(!is_numeric($block))
		{
			$regerrors['block']="Block must be numeric.";
			$noerrors++;
		}
		elseif(!(($block>=1 && $block<=24)||($block==99)))
		{
			$regerrors['block']="Block must be a valid value.";
			$noerrors++;
		}
		if(!has_presence($roomno))
		{
			$regerrors['roomno']="Room no. name Can't be blank.";
			$noerrors++;
		}
		elseif(!has_max_length($roomno,6))
		{
			$regerrors['roomno']="Room no. max length is 6.";
			$noerrors++;
		}
	}
	else
	{

	}

	if(!has_presence($password))
	{
		$regerrors['password']="password Can't be blank.";
		$noerrors++;
	}
	elseif(!has_max_length($password,30))
	{
		$regerrors['password']="password max length should be 30.";
		$noerrors++;
	}
	if(!has_presence($passwordconfirm))
	{
		$regerrors['passwordconfirm']="confirm password Can't be blank.";
		$noerrors++;
	}
	elseif(!has_max_length($passwordconfirm,30))
	{
		$regerrors['passwordconfirm']="password max length should be 30.";
		$noerrors++;
	}
	elseif(!($password==$passwordconfirm))
	{
		$regerrors['passwordconfirm']="Password does not match.";
		$noerrors++;
	}
	return $regerrors;
}
function validate_addbook($name,$author,$price,$health)
{
	$addbookerrors=array();
	$noerrors=0;
	if(!has_presence($name))
	{
		$addbookerrors['name']="book name cannot be empty";
		$noerrors++;
	}
	if(!has_max_length($name,100))
	{
		$addbookerrors['name']="book name max length is 100 characters.";
		$noerrors++;
	}
	if(!has_presence($author))
	{
		$addbookerrors['author']="book author cannot be empty";
		$noerrors++;
	}
	if(!has_max_length($author,100))
	{
		$addbookerrors['author']="book author max length is 100 characters.";
		$noerrors++;
	}
	if(!isset($price))
	{
		$addbookerrors['price']="Price cannot be blank";
		$noerrors++;
	}
	elseif(!((int)$price>=0 && (int)$price<=2000))
	{
		$addbookerrors['price']="price should be between 0 and 2000";
		$noerrors++;
	}
	if(!has_presence($health))
	{
		$addbookerrors['health']="book condition cannot be empty";
		$noerrors++;
	}
	if(!has_max_length($health,200))
	{
		$addbookerrors['health']="book condition max length is 200 characters.";
		$noerrors++;
	}
	return $addbookerrors;
}
?>