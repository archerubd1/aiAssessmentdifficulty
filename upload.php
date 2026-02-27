<?php
include 'engine.php';

$message = "";

if (isset($_POST['submit'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    
    if ($_FILES['csv_file']['size'] > 0) {
        // Open the file for reading
        $handle = fopen($file, "r");
        $count = 0;

        // Prepare the SQL statement for better performance and security
        $stmt = $conn->prepare("INSERT INTO questions (question_text, correct_option, difficulty) VALUES (?, ?, ?)");

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // $data[0] = Question, $data[1] = Answer, $data[2] = Difficulty
            $stmt->bind_param("ssd", $data[0], $data[1], $data[2]);
            $stmt->execute();
            $count++;
        }

        fclose($handle);
        $message = "Successfully imported $count questions!";
    } else {
        $message = "Please upload a valid CSV file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Question Bank Uploader</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; display: flex; justify-content: center; padding-top: 50px; }
        .upload-box { background: white; padding: 30px; border-radius: 8px; shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .msg { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <div class="upload-box">
        <h2>Upload Question Bank</h2>
        <?php if($message) echo "<p class='msg'>$message</p>"; ?>
        
        <form action="" method="post" enctype="multipart/form-data">
            <p>Select CSV file (Format: Question, Answer, Difficulty)</p>
            <input type="file" name="csv_file" accept=".csv" required>
            <br><br>
            <button type="submit" name="submit">Start Import</button>
        </form>
        <br>
        <a href="quiz.php">Go to Quiz</a>
    </div>
</body>
</html>