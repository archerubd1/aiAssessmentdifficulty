INSERT INTO questions (question_text, correct_option, difficulty) VALUES
('What does PHP stand for?', 'A', 0.1), -- Hypertext Preprocessor
('Which symbol is used to declare a variable in PHP?', 'B', 0.1), -- $
('What is the default port for MySQL?', 'C', 0.2), -- 3306
('Which command is used to delete a table in SQL?', 'A', 0.3), -- DROP
('How do you start a session in PHP?', 'D', 0.3), -- session_start()
('What is the purpose of an INDEX in MySQL?', 'B', 0.4), -- Speed up retrieval
('Which PHP function merges two or more arrays?', 'C', 0.4), -- array_merge
('What is a JOIN used for in SQL?', 'A', 0.5), -- Combine rows from tables
('What is the difference between == and === in PHP?', 'B', 0.5), -- Value vs Value+Type
('What is "Referential Integrity" in a database?', 'D', 0.6), -- Foreign key consistency
('Which PHP magic method is called when an object is treated as a string?', 'A', 0.6), -- __toString
('What is a SQL Injection?', 'C', 0.7), -- Malicious code in input
('What does the "LIMIT" clause do in MySQL?', 'B', 0.7), -- Restricts returned rows
('How do you prevent SQL injection in PHP?', 'A', 0.8), -- Prepared Statements
('What is the purpose of the "HAVING" clause in SQL?', 'D', 0.8), -- Filter aggregated data
('What is a "Closure" in PHP?', 'C', 0.8), -- Anonymous function using external scope
('In IRT, what does the "Discrimination" parameter measure?', 'B', 0.9), -- Ability to differentiate students
('What is the O(n) complexity of a primary key lookup in MySQL?', 'A', 0.9), -- O(log n) usually B-Tree
('What is the purpose of the PHP "yield" keyword?', 'C', 0.9), -- Generators/Memory efficiency
('How does a B-Tree index differ from a Hash index?', 'D', 0.9); -- Range queries support