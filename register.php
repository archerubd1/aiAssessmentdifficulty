<?php
$conn = mysqli_connect("localhost","root","root","assessment");

if(!$conn){
    die("Database Connection Failed: " . mysqli_connect_error());
}

if(isset($_POST['register']))
{
    $name = $_POST['name'];
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];

    $sql = "INSERT INTO students(name,student_id,password)
            VALUES('$name','$student_id','$password')";

    if(mysqli_query($conn,$sql))
    {
        echo "<script>
        alert('Registration Successful');
        window.location='index.php';
        </script>";
    }
    else
    {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Registration</title>

<style>

body{
font-family:Arial;
background:#e3f2fd;
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
background:green;
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

<h2>Student Register</h2>

<form method="POST">

<input type="text" name="name" placeholder="Student Name" required>

<input type="text" name="student_id" placeholder="Student ID" required>

<input type="password" name="password" placeholder="Password" required>

<br>

<button type="submit" name="register">Register</button>

</form>

<br>

<a href="index.php">Already Registered? Login</a>

</div>

</body>
</html>