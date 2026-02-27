<?php
include 'engine.php';
session_start();

$sessionId = $_SESSION['user_id'];

// 1. Fetch User's Final Ability
$res = $conn->query("SELECT current_ability, questions_answered FROM user_sessions WHERE id = $sessionId");
$user = $res->fetch_assoc();

// 2. Fetch Performance Breakdown
$stats = $conn->query("
    SELECT 
        CASE 
            WHEN q.difficulty < 0.4 THEN 'Beginner'
            WHEN q.difficulty < 0.7 THEN 'Intermediate'
            ELSE 'Advanced'
        END as tier,
        COUNT(*) as total,
        SUM(r.is_correct) as correct
    FROM response_log r
    JOIN questions q ON r.question_id = q.id
    WHERE r.session_id = $sessionId
    GROUP BY tier
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assessment Results</title>
    <style>
        .bar { height: 20px; background: #4CAF50; margin-bottom: 10px; }
        .container { width: 50%; margin: auto; font-family: sans-serif; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Assessment Complete</h2>
        <p>Your Estimated Skill Level: <strong><?php echo number_ those_format($user['current_ability'] * 100, 1); ?>%</strong></p>
        <p>Questions Answered: <?php echo $user['questions_answered']; ?></p>
        
        <hr>
        <h3>Performance by Tier:</h3>
        <?php while($row = $stats->fetch_assoc()): 
            $perc = ($row['correct'] / $row['total']) * 100; ?>
            <p><?php echo $row['tier']; ?>: <?php echo $row['correct']; ?>/<?php echo $row['total']; ?></p>
            <div class="bar" style="width: <?php echo $perc; ?>%;"></div>
        <?php endwhile; ?>
        
        <br>
        <a href="quiz.php?reset=1">Restart Assessment</a>
    </div>
</body>
</html>