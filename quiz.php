<?php
include 'engine.php';
session_start();

// 1. Reset Logic
if (isset($_GET['reset'])) {
    $sid = $_SESSION['user_id'];
    $conn->query("DELETE FROM response_log WHERE session_id = $sid");
    $conn->query("UPDATE user_sessions SET current_ability = 0.5, questions_answered = 0 WHERE id = $sid");
    header("Location: quiz.php");
    exit();
}

// 2. Initialize Session
if (!isset($_SESSION['user_id'])) {
    $conn->query("INSERT INTO user_sessions (user_name) VALUES ('Student_User')");
    $_SESSION['user_id'] = $conn->insert_id;
}

$sessionId = $_SESSION['user_id'];

// 3. Handle Answer Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qId = (int)$_POST['q_id'];
    $userAns = $_POST['answer'];
    
    $q = $conn->query("SELECT correct_option FROM questions WHERE id = $qId")->fetch_assoc();
    $isCorrect = ($userAns == $q['correct_option']) ? 1 : 0;
    
    // Log the response and run the AI adjustment engine
    $conn->query("INSERT INTO response_log (session_id, question_id, is_correct) 
                  VALUES ($sessionId, $qId, $isCorrect)");
    updateAbility($sessionId, $isCorrect);
    
    // Refresh to get the next adjusted question
    header("Location: quiz.php");
    exit();
}

// 4. Check Progress & Get Next Question
$res = $conn->query("SELECT questions_answered FROM user_sessions WHERE id = $sessionId");
$userData = $res->fetch_assoc();

// LIMIT: End the test after 10 questions
if ($userData['questions_answered'] >= 10) {
    header("Location: results.php");
    exit();
}

$nextQ = getNextQuestion($sessionId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AI Adaptive Assessment</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7f6; padding: 40px; }
        .quiz-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
        .progress-bar { background: #eee; height: 10px; border-radius: 5px; margin-bottom: 20px; }
        .progress-fill { background: #3498db; height: 10px; border-radius: 5px; width: <?php echo ($userData['questions_answered'] / 10) * 100; ?>%; }
        .btn { background: #3498db; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
        .difficulty-tag { font-size: 0.8em; color: #888; text-transform: uppercase; }
    </style>
</head>
<body>

<div class="quiz-card">
    <div class="progress-bar"><div class="progress-fill"></div></div>
    <span class="difficulty-tag">AI Difficulty Level: <?php echo round($nextQ['difficulty'], 2); ?></span>
    
    <h2>Question <?php echo $userData['questions_answered'] + 1; ?> of 10</h2>
    <p style="font-size: 1.2em;"><?php echo $nextQ['question_text']; ?></p>

    <form method="POST">
        <input type="hidden" name="q_id" value="<?php echo $nextQ['id']; ?>">
        
        <label><input type="radio" name="answer" value="A" required> Choice A</label><br><br>
        <label><input type="radio" name="answer" value="B" required> Choice B</label><br><br>
        <label><input type="radio" name="answer" value="C" required> Choice C</label><br><br>
        <label><input type="radio" name="answer" value="D" required> Choice D</label><br><br>
        
        <button type="submit" class="btn">Submit Answer</button>
    </form>
</div>

</body>
</html>