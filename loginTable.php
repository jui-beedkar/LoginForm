<?php  
$host = 'localhost:3306';  
$user = 'user';  
$pass = '';  
$dbname = 'LoginDB';  
  
$conn = mysqli_connect($host, $user, $pass, $dbname);  
if(!$conn){  
  die('Could not connect: '.mysqli_connect_error());  
}  
echo 'Connected successfully <br/>';  
  
$sql = "CREATE TABLE user(

    password_reset_link VARCHAR(64),
    email VARCHAR(50)
    )";

if ($conn->query($sql) === TRUE) 
{
echo "Table employees created successfully";
} else 
{
echo "Error creating table: " . $conn->error;
}

$conn->close();
?>