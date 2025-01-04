-- Step 1: Create the Database
CREATE DATABASE IF NOT EXISTS rgpv_results;

-- Step 2: Use the Database
USE rgpv_results;

-- Step 3: Create the Students Table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_no VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    program VARCHAR(50) NOT NULL,
    semester INT NOT NULL
);

-- Step 4: Create the Results Table
CREATE TABLE IF NOT EXISTS results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_no VARCHAR(20) NOT NULL,
    semester INT NOT NULL,
    subject VARCHAR(100) NOT NULL,
    grade VARCHAR(10) NOT NULL,
    grading_option ENUM('grading', 'nonGrading') NOT NULL,
    FOREIGN KEY (enrollment_no) REFERENCES students(enrollment_no)
);

-- Step 5: Insert Sample Data into Students Table
INSERT INTO students (enrollment_no, name, program, semester) VALUES
('RGPV2023001', 'John Doe', 'B.E.', 5),
('RGPV2023002', 'Jane Smith', 'B.Tech.', 3),
('RGPV2023003', 'Alice Brown', 'Diploma', 2);

-- Step 6: Insert Sample Data into Results Table
INSERT INTO results (enrollment_no, semester, subject, grade, grading_option) VALUES
('RGPV2023001', 5, 'Mathematics', 'A', 'grading'),
('RGPV2023001', 5, 'Physics', 'B+', 'grading'),
('RGPV2023002', 3, 'Electronics', 'A-', 'grading'),
('RGPV2023003', 2, 'Mechanics', 'B', 'nonGrading');
