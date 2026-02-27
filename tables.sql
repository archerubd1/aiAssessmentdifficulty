CREATE DATABASE ai_assessment;
USE ai_assessment;

-- Stores questions with a difficulty rating (0.0 to 1.0)
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT,
    correct_option CHAR(1),
    difficulty FLOAT -- 0.1 (Easy) to 0.9 (Hard)
);

-- Tracks user sessions and their current estimated ability
CREATE TABLE user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(50),
    current_ability FLOAT DEFAULT 0.5,
    questions_answered INT DEFAULT 0
);

-- Log of responses for the "AI" to learn from
CREATE TABLE response_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT,
    question_id INT,
    is_correct BOOLEAN
);


