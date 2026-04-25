<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$con = new mysqli('localhost', 'root', '', 'work_db');

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        // Check if any clubs were selected
        if (isset($_POST['clubs']) && is_array($_POST['clubs'])) {
            $selected_clubs = $_POST['clubs'];
            
            // Get student ID from session (assuming it was set in step1)
            $student_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : 1;
            
            // Insert each selected club into database
            $success_count = 0;
            $stmt = $con->prepare("INSERT INTO student_clubs (student_id, club_name) VALUES (?, ?)");
            
            if ($stmt === false) {
                die("Prepare failed: " . $con->error);
            }
            
            foreach ($selected_clubs as $club) {
                $stmt->bind_param("is", $student_id, $club);
                if ($stmt->execute()) {
                    $success_count++;
                }
            }
            
            $stmt->close();
            
            if ($success_count > 0) {
                echo "<script>alert('Successfully registered for $success_count club(s)!');</script>";
                // Redirect to next step or success page
                // header("Location: step3.php");
            } else {
                echo "<script>alert('Error registering for clubs. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Please select at least one club!');</script>";
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
    <title>Choose Club</title>
    <style>
        body {
            font-family: serif;
            text-align: center;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            font-style: italic;
        }

        .club-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .club-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            transition: background-color 0.3s;
        }

        .club-item:hover {
            background-color: #e9e9e9;
        }

        .club-item input[type="checkbox"] {
            margin: 0;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        input[type="submit"] {
            background-color: #007cba;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #005a87;
        }

        .back-btn {
            background-color: #6c757d;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #545b62;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Choose Your Club(s)</h1>
        
        <form action="step2_two.php" method="POST">
            <div class="club-container">
                <div class="club-item">
                    <input type="checkbox" name="clubs[]" value="Science Club" id="science">
                    <label for="science">Science Club</label>
                </div>
                
                <div class="club-item">
                    <input type="checkbox" name="clubs[]" value="Coding Club" id="coding">
                    <label for="coding">Coding Club</label>
                </div>
                
                <div class="club-item">
                    <input type="checkbox" name="clubs[]" value="Debate Club" id="debate">
                    <label for="debate">Debate Club</label>
                </div>
                
                <div class="club-item">
                    <input type="checkbox" name="clubs[]" value="Music Club" id="music">
                    <label for="music">Music Club</label>
                </div>
                
                <div class="club-item">
                    <input type="checkbox" name="clubs[]" value="Sport Club" id="sport">
                    <label for="sport">Sport Club</label>
                </div>
                
                <div class="club-item">
                    <input type="checkbox" name="clubs[]" value="Art Club" id="art">
                    <label for="art">Art Club</label>
                </div>
                
                <div class="club-item">
                    <input type="checkbox" name="clubs[]" value="Environment Club" id="environment">
                    <label for="environment">Environment Club</label>
                </div>
            </div>

            <div class="button-group">
                <input type="submit" name="submit" value="Submit">
                <a href="step1_one.php" class="back-btn">Back</a>
            </div>
        </form>
    </div>
</body>
</html>
