CREATE DATABASE ai_assessment;
USE ai_assessment;

-- 1. Stores the questions and their 'fixed' difficulty weight
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT,
    correct_option CHAR(1),
    difficulty FLOAT -- 0.1 to 0.9
);

-- 2. Stores the 'dynamic' state of the user's progress
CREATE TABLE user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(50),
    current_ability FLOAT DEFAULT 0.5, -- This moves up/down
    questions_answered INT DEFAULT 0
);

-- 3. Stores the history for the results graph
CREATE TABLE response_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT,
    question_id INT,
    is_correct BOOLEAN
);