<?php
session_start();

$conn = mysqli_connect("localhost","root","root","assessment");

if(!$conn){
    die("Database Connection Failed: " . mysqli_connect_error());
}

if(isset($_POST['login']))
{
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM students 
            WHERE student_id='$student_id' 
            AND password='$password'";

    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)==1)
    {
        $_SESSION['student_id']=$student_id;

        header("Location: home.php"); 
    }
    else
    {
        echo "<script>alert('Invalid Student ID or Password');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>AI Based Assessment Login</title>

<style>

body{
font-family: Arial;
background:#dfe9f3;
}

.box{
width:350px;
margin:120px auto;
padding:25px;
background:white;
border-radius:10px;
box-shadow:0 0 10px gray;
text-align:center;
}

input{
width:90%;
padding:10px;
margin:10px;
}

button{
padding:10px 20px;
background:blue;
color:white;
border:none;
cursor:pointer;
}

a{
text-decoration:none;
color:blue;
}

</style>

</head>

<body>

<div class="box">

<h2>AI Based Assessment</h2>

<h3>Student Login</h3>

<form method="POST">

<input type="text" name="student_id" placeholder="Student ID" required>

<input type="password" name="password" placeholder="Password" required>

<br>

<button type="submit" name="login">Login</button>

</form>

<br>

<a href="register.php">New Student? Register</a>

</div>

</body>
</html>