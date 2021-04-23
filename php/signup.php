<?php
require '../vendor/autoload.php';

$signup = new Classes\Signup;
$signup->setFullname($_POST['fname']);
$signup->setLastName($_POST['lname']);
$signup->setUsername($_POST['user']);
$signup->setPassword($_POST['password']);
$signup->setImage($_FILES['image']);
$signup->registerUser();