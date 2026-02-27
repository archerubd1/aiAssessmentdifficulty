<?php
include 'engine.php';
session_start();
$sessionId = $_SESSION['user_id'];

$user = $conn->query("SELECT * FROM user_sessions WHERE id = $sessionId")->fetch_assoc();
$responses = $conn->query("
    SELECT q.difficulty, r.is_correct 
    FROM response_log r 
    JOIN questions q ON r.question_id = q.id 
    WHERE r.session_id = $sessionId
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assessment Insights</title>
    <style>
        body { font-family: sans-serif; padding: 50px; background: #fff; text-align: center; }
        .graph-container { display: flex; align-items: flex-end; height: 150px; gap: 10px; justify-content: center; margin: 40px 0; border-bottom: 2px solid #333; }
        .bar { width: 30px; background: #6c5ce7; transition: height 1s; position: relative; }
        .bar.wrong { background: #fab1a0; }
        .final-score { font-size: 48px; color: #6c5ce7; margin: 0; }
    </style>
</head>
<body>
    <h1>AI Skill Certificate</h1>
    <p>Estimated Mastery Level:</p>
    <h2 class="final-score"><?php echo round($user['current_ability'] * 100); ?>%</h2>
    
    <div class="graph-container">
        <?php while($row = $responses->fetch_assoc()): ?>
            <div class="bar <?php echo $row['is_correct'] ? '' : 'wrong'; ?>" 
                 style="height: <?php echo ($row['difficulty'] * 150); ?>px;"
                 title="Diff: <?php echo $row['difficulty']; ?>">
            </div>
        <?php endwhile; ?>
    </div>
    <p>The graph above shows the AI's <strong>Difficulty Calibration Path</strong> during your session.</p>
    <a href="quiz.php?reset=1">Start New Adaptive Session</a>
</body>
</html>