<?php
// Database connection
$conn = new mysqli("localhost", "root", "root", "ai_assessment");

function getNextQuestion($sessionId) {
    global $conn;
    
    // 1. Get current user ability
    $res = $conn->query("SELECT current_ability FROM user_sessions WHERE id = $sessionId");
    $user = $res->fetch_assoc();
    $ability = $user['current_ability'];

    // 2. Find a question closest to their ability that they haven't answered yet
    $sql = "SELECT * FROM questions 
            WHERE id NOT IN (SELECT question_id FROM response_log WHERE session_id = $sessionId)
            ORDER BY ABS(difficulty - $ability) ASC 
            LIMIT 1";
    
    return $conn->query($sql)->fetch_assoc();
}

function updateAbility($sessionId, $isCorrect) {
    global $conn;
    
    // Step-size reduces over time to "lock in" the score
    $res = $conn->query("SELECT current_ability, questions_answered FROM user_sessions WHERE id = $sessionId");
    $data = $res->fetch_assoc();
    
    $k = 0.1; // Adjustment factor
    $newAbility = $isCorrect 
        ? $data['current_ability'] + ($k / ($data['questions_answered'] + 1))
        : $data['current_ability'] - ($k / ($data['questions_answered'] + 1));

    // Clamp between 0.1 and 0.9
    $newAbility = max(0.1, min(0.9, $newAbility));

    $conn->query("UPDATE user_sessions SET 
                  current_ability = $newAbility, 
                  questions_answered = questions_answered + 1 
                  WHERE id = $sessionId");
}
?>