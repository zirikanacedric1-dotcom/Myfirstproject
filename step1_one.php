<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$con = new mysqli('localhost', 'root', '', 'work_db');

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error . "<br>Please check:");
    echo "<ul>";
    echo "<li>MySQL server is running in XAMPP</li>";
    echo "<li>Database 'work_db' exists</li>";
    echo "<li>Username 'root' with no password is correct</li>";
    echo "</ul>";
}

if (isset($_POST['submit'])) {
    // Get and sanitize input
    $full = trim($_POST['full_name']);
    $email = trim($_POST['email_address']);
    $class = trim($_POST['class']);
    $bod = trim($_POST['date_of_birth']);
    
    // Validate inputs
    if (empty($full) || empty($email) || empty($class) || empty($bod)) {
        echo "<script>alert('Please fill all required fields!');</script>";
    } else {
        // Check if table exists
        $table_check = $con->query("SHOW TABLES LIKE 'work_tb'");
        if ($table_check->num_rows == 0) {
            echo "<script>alert('Error: Table \"work_tb\" does not exist. Please create the table first.');</script>";
        } else {
            // Use prepared statements to prevent SQL injection
            $stmt = $con->prepare("INSERT INTO work_tb(full_name, email_address, class, date_of_birth) VALUES (?, ?, ?, ?)");
            
            if ($stmt === false) {
                echo "<script>alert('Prepare failed: " . $con->error . "');</script>";
            } else {
                $stmt->bind_param("ssss", $full, $email, $class, $bod);
                
                if ($stmt->execute()) {
                    echo "<script>alert('Registration successful!');</script>";
                    // Clear form fields after successful submission
                    echo "<script>window.location.reload();</script>";
                } else {
                    echo "<script>alert('Error inserting data: " . $stmt->error . "');</script>";
                    echo "<script>console.log('Debug - Full Name: " . addslashes($full) . "');</script>";
                    echo "<script>console.log('Debug - Email: " . addslashes($email) . "');</script>";
                    echo "<script>console.log('Debug - Class: " . addslashes($class) . "');</script>";
                    echo "<script>console.log('Debug - DOB: " . addslashes($bod) . "');</script>";
                }
                
                $stmt->close();
            }
        }
    }
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-container { max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="date"], select { 
            width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; 
        }
        input[type="submit"] { 
            background-color: #007cba; color: white; padding: 10px 20px; 
            border: none; border-radius: 4px; cursor: pointer; 
        }
        input[type="submit"]:hover { background-color: #005a87; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Registration</h1>
        <form action="" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" placeholder="Enter Your Name" required>
            </div>
            
            <div class="form-group">
                <label for="email_address">Email Address:</label>
                <input type="email" id="email_address" name="email_address" placeholder="Enter Your Email" required>
            </div>
            
            <div class="form-group">
                <label for="class">Class:</label>
                <select id="class" name="class" required>
                    <option value="">Choose Your Class</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                    <option value="S3">S3</option>
                    <option value="S4">S4</option>
                    <option value="S5">S5</option>
                    <option value="S6">S6</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" id="date_of_birth" name="date_of_birth" required>
            </div>
            
            <div class="form-group">
                <input type="submit" name="submit" value="Submit">
            </div>
        </form>
        
        <!-- Debug Information -->
        <div style="margin-top: 20px; padding: 10px; background-color: #f8f9fa; border-radius: 4px;">
        </div>
    </div>
</body>
</html>
