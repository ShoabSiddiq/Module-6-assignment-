<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
	$name = $_POST['name'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$profile_pic = $_FILES['profile_pic'];
	
	if(empty($name) || empty($email) || empty($password) || empty($profile_pic['name'])) {
		die("All fields are required.");
	}
	
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		die("Invalid email format.");
	}
	
	$uploads_dir = 'uploads/';
	$filename = uniqid() . '-' . basename($profile_pic['name']);
	$target_file = $uploads_dir . $filename;
	
	if(move_uploaded_file($profile_pic['tmp_name'], $target_file)) {
		$file = fopen('users.csv', 'a');
		fputcsv($file, [$name, $email, $filename, date('Y-m-d H:i:s')]);
		fclose($file);
		
		setcookie('name', $name, time() + 86400, '/');
		
		header('Location: users.php');
		exit();
	} else {
		die("Error uploading file.");
	}
}

?>
