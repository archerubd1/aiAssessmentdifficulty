<?php
include 'engine.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    $conn->query("INSERT INTO user_sessions (user_name) VALUES ('Uday_Student')");
    $_SESSION['user_id'] = $conn->insert_id;
}

$sessionId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qId = (int)$_POST['q_id'];
    $userAns = $_POST['answer'];
    $q = $conn->query("SELECT correct_option FROM questions WHERE id = $qId")->fetch_assoc();
    $isCorrect = ($userAns == $q['correct_option']) ? 1 : 0;
    
    updateAbility($sessionId, $isCorrect);
    $conn->query("INSERT INTO response_log (session_id, question_id, is_correct) VALUES ($sessionId, $qId, $isCorrect)");
    header("Location: quiz.php");
    exit();
}

$res = $conn->query("SELECT * FROM user_sessions WHERE id = $sessionId");
$userData = $res->fetch_assoc();

if ($userData['questions_answered'] >= 10) {
    header("Location: results.php");
    exit();
}

$nextQ = getNextQuestion($sessionId);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Astraal AI Assessment</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; display: flex; justify-content: center; padding: 40px; }
        .card { background: white; width: 500px; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .ai-badge { background: #6c5ce7; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .ai-status-box { background: #f8f9ff; border: 1px solid #e0e7ff; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .meter { background: #eee; height: 6px; border-radius: 3px; margin-top: 10px; position: relative; }
        .meter-fill { background: #6c5ce7; height: 100%; transition: 0.8s; width: <?php echo ($nextQ['difficulty'] * 100); ?>%; }
        .option { display: block; padding: 10px; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 10px; cursor: pointer; }
        .option:hover { background: #f0f7ff; }
        .submit-btn { background: #2d3436; color: white; border: none; width: 100%; padding: 12px; border-radius: 6px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <div class="ai-status-box">
            <span class="ai-badge">AI ADAPTIVE ENGINE ACTIVE</span>
            <p style="font-size: 13px; color: #4a4a4a; margin: 8px 0;"><?php echo getAILogicState($userData['current_ability']); ?></p>
            <div class="meter"><div class="meter-fill"></div></div>
            <small style="color: #888;">Target Difficulty: <?php echo number_format($nextQ['difficulty'], 2); ?></small>
        </div>

        <form method="POST">
            <h3>Question <?php echo $userData['questions_answered'] + 1; ?></h3>
            <p><?php echo $nextQ['question_text']; ?></p>
            <input type="hidden" name="q_id" value="<?php echo $nextQ['id']; ?>">
            
            <?php foreach(['A', 'B', 'C', 'D'] as $opt): ?>
                <label class="option">
                    <input type="radio" name="answer" value="<?php echo $opt; ?>" required> <?php echo $opt; ?>
                </label>
            <?php endforeach; ?>
            
            <button type="submit" class="submit-btn">Evaluate & Proceed</button>
        </form>
    </div>
</body>
</html>