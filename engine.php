<?php
// Database connection
$conn = new mysqli("localhost", "root", "root", "ai_assessment");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getNextQuestion($sessionId) {
    global $conn;
    
    $res = $conn->query("SELECT current_ability FROM user_sessions WHERE id = $sessionId");
    $user = $res->fetch_assoc();
    $ability = $user['current_ability'];

    // AI Selection Logic: Find the question where difficulty is closest to user ability
    $sql = "SELECT * FROM questions 
            WHERE id NOT IN (SELECT question_id FROM response_log WHERE session_id = $sessionId)
            ORDER BY ABS(difficulty - $ability) ASC 
            LIMIT 1";
    
    return $conn->query($sql)->fetch_assoc();
}

function updateAbility($sessionId, $isCorrect) {
    global $conn;
    
    $res = $conn->query("SELECT current_ability, questions_answered FROM user_sessions WHERE id = $sessionId");
    $data = $res->fetch_assoc();
    
    // AI Step Factor: Larger adjustments early on, finer tuning later
    $step = 0.15 / ($data['questions_answered'] + 1); 
    
    $newAbility = $isCorrect 
        ? $data['current_ability'] + $step
        : $data['current_ability'] - $step;

    $newAbility = max(0.05, min(0.95, $newAbility));

    $conn->query("UPDATE user_sessions SET 
                  current_ability = $newAbility, 
                  questions_answered = questions_answered + 1 
                  WHERE id = $sessionId");
}

function getAILogicState($ability) {
    if ($ability > 0.7) return "High Proficiency Detected: Serving Challenge Tier Questions.";
    if ($ability > 0.4) return "Stable Performance: Calibrating Intermediate Concepts.";
    return "Identifying Knowledge Gaps: Providing Foundational Support.";
}
?>