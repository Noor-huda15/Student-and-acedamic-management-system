-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2026 at 12:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_and acedamic management system`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `AttendanceID` int(11) NOT NULL,
  `StudentID` varchar(10) NOT NULL,
  `CourseID` varchar(10) NOT NULL,
  `AttendanceDate` date NOT NULL,
  `Status` enum('Present','Absent','Late') NOT NULL DEFAULT 'Present'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`AttendanceID`, `StudentID`, `CourseID`, `AttendanceDate`, `Status`) VALUES
(4189, 'S017', 'CS115', '2025-12-18', 'Present'),
(4190, 'S029', 'CS115', '2025-12-18', 'Present'),
(4191, 'S018', 'CS115', '2025-12-18', 'Present'),
(4192, 'S035', 'CS115', '2025-12-18', 'Present'),
(4193, 'S017', 'CS115', '2025-12-17', 'Absent'),
(4195, 'S029', 'CS115', '2025-12-17', 'Present'),
(4196, 'S018', 'CS115', '2025-12-17', 'Present'),
(4197, 'S035', 'CS115', '2025-12-17', 'Present'),
(4198, 'S027', 'ME111', '2025-12-23', 'Present'),
(4200, 'S034', 'ME111', '2025-12-23', 'Present'),
(4201, 'S028', 'ME111', '2025-12-23', 'Present'),
(4202, 'S027', 'ME111', '2025-12-22', 'Absent'),
(4204, 'S034', 'ME111', '2025-12-22', 'Late'),
(4205, 'S028', 'ME111', '2025-12-22', 'Present'),
(4206, 'S027', 'ME111', '2025-12-24', 'Present'),
(4207, 'S034', 'ME111', '2025-12-24', 'Present'),
(4208, 'S028', 'ME111', '2025-12-24', 'Absent'),
(4212, 'S001', 'CS616', '2025-12-24', 'Present'),
(4213, 'S002', 'CS616', '2025-12-24', 'Present'),
(4214, 'S005', 'CS616', '2025-12-24', 'Present'),
(4215, 'S013', 'CS616', '2025-12-24', 'Present'),
(4216, 'S001', 'CS616', '2025-12-23', 'Absent'),
(4218, 'S002', 'CS616', '2025-12-23', 'Present'),
(4219, 'S005', 'CS616', '2025-12-23', 'Present'),
(4220, 'S013', 'CS616', '2025-12-23', 'Present'),
(4221, 'S001', 'CS616', '2025-12-22', 'Present'),
(4223, 'S002', 'CS616', '2025-12-22', 'Absent'),
(4224, 'S005', 'CS616', '2025-12-22', 'Absent'),
(4225, 'S013', 'CS616', '2025-12-22', 'Absent'),
(4230, 'S001', 'CS615', '2025-12-24', 'Present'),
(4231, 'S002', 'CS615', '2025-12-24', 'Present'),
(4232, 'S005', 'CS615', '2025-12-24', 'Present'),
(4233, 'S013', 'CS615', '2025-12-24', 'Present'),
(4234, 'S001', 'CS615', '2025-12-23', 'Present'),
(4235, 'S001', 'CS615', '2025-12-22', 'Present'),
(4237, 'S002', 'CS615', '2025-12-22', 'Present'),
(4238, 'S005', 'CS615', '2025-12-22', 'Present'),
(4239, 'S013', 'CS615', '2025-12-22', 'Present'),
(4241, 'S002', 'CS615', '2025-12-23', 'Present'),
(4242, 'S005', 'CS615', '2025-12-23', 'Present'),
(4243, 'S013', 'CS615', '2025-12-23', 'Present'),
(4244, 'S027', 'ME111', '2025-12-25', 'Present'),
(4246, 'S034', 'ME111', '2025-12-25', 'Absent'),
(4247, 'S028', 'ME111', '2025-12-25', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `CourseID` varchar(10) NOT NULL,
  `DepartmentID` varchar(10) NOT NULL,
  `CourseName` varchar(150) NOT NULL,
  `Credits` int(11) NOT NULL CHECK (`Credits` between 1 and 6),
  `FacultyID` varchar(20) DEFAULT NULL,
  `Semester` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`CourseID`, `DepartmentID`, `CourseName`, `Credits`, `FacultyID`, `Semester`) VALUES
('BBA111', 'BBA', 'Freshman English', 3, 'F003', 1),
('BBA112', 'BBA', 'IT in Business', 3, 'F025', 1),
('BBA113', 'BBA', 'Business Mathematics', 3, 'F026', 1),
('BBA114', 'BBA', 'Microeconomics', 3, 'F027', 1),
('BBA115', 'BBA', 'Pakistan Studies', 2, 'F028', 1),
('BBA116', 'BBA', 'Islamic Studies', 2, NULL, 1),
('BBA211', 'BBA', 'Financial Accounting', 3, 'F025', 2),
('BBA212', 'BBA', 'Macroeconomics', 3, 'F026', 2),
('BBA213', 'BBA', 'Business Statistics', 3, 'F027', 2),
('BBA214', 'BBA', 'Oral Communication', 3, 'F028', 2),
('BBA215', 'BBA', 'Introduction to Psychology', 3, 'F003', 2),
('BBA216', 'BBA', 'Principles of Management', 3, 'F010', 2),
('BBA311', 'BBA', 'Cost Accounting', 3, 'F026', 3),
('BBA312', 'BBA', 'Business Law', 3, 'F027', 3),
('BBA313', 'BBA', 'Marketing Management', 3, 'F028', 3),
('BBA314', 'BBA', 'Organizational Behavior', 3, 'F003', 3),
('BBA315', 'BBA', 'Business Finance', 3, 'F025', 3),
('BBA316', 'BBA', 'Human Resource Management', 3, 'F026', 3),
('BBA411', 'BBA', 'Managerial Accounting', 3, 'F027', 4),
('BBA412', 'BBA', 'Consumer Behavior', 3, 'F028', 4),
('BBA413', 'BBA', 'Financial Management', 3, 'F003', 4),
('BBA414', 'BBA', 'Research Methods', 3, 'F025', 4),
('BBA415', 'BBA', 'Supply Chain Management', 3, 'F026', 4),
('BBA416', 'BBA', 'Entrepreneurship', 3, 'F027', 4),
('BBA511', 'BBA', 'Corporate Finance', 3, 'F028', 5),
('BBA512', 'BBA', 'International Business', 3, 'F003', 5),
('BBA513', 'BBA', 'Strategic Marketing', 3, 'F025', 5),
('BBA514', 'BBA', 'Leadership & Change Management', 3, 'F026', 5),
('BBA515', 'BBA', 'Operations Management', 3, 'F027', 5),
('BBA516', 'BBA', 'Business Ethics', 3, 'F028', 5),
('BBA611', 'BBA', 'Investment Analysis', 3, NULL, 6),
('BBA612', 'BBA', 'Brand Management', 3, 'F025', 6),
('BBA613', 'BBA', 'Project Management', 3, 'F026', 6),
('BBA614', 'BBA', 'Strategic Management', 3, 'F027', 6),
('BBA615', 'BBA', 'E-Commerce', 3, 'F028', 6),
('BBA616', 'BBA', 'Risk Management', 3, NULL, 6),
('BBA711', 'BBA', 'Final Year Project I', 3, 'F025', 7),
('BBA712', 'BBA', 'Auditing', 3, 'F026', 7),
('BBA713', 'BBA', 'Digital Marketing', 3, 'F027', 7),
('BBA714', 'BBA', 'Corporate Governance', 3, 'F028', 7),
('BBA715', 'BBA', 'Taxation', 3, NULL, 7),
('BBA716', 'BBA', 'Global Business Strategy', 3, 'F025', 7),
('BBA811', 'BBA', 'Final Year Project II', 3, 'F026', 8),
('BBA812', 'BBA', 'Advanced Financial Management', 3, 'F027', 8),
('BBA813', 'BBA', 'Services Marketing', 3, 'F028', 8),
('BBA814', 'BBA', 'Innovation Management', 3, 'F003', 8),
('BBA815', 'BBA', 'Business Analytics', 3, 'F025', 8),
('BBA816', 'BBA', 'Sustainable Business Practices', 3, 'F026', 8),
('CE111', 'CE', 'Engineering Drawing', 3, NULL, 1),
('CE112', 'CE', 'Surveying I', 4, 'F029', 1),
('CE113', 'CE', 'Applied Mathematics I', 3, 'F030', 1),
('CE114', 'CE', 'Applied Physics', 3, 'F031', 1),
('CE115', 'CE', 'Functional English', 3, 'F032', 1),
('CE116', 'CE', 'Pakistan Studies', 2, 'F005', 1),
('CE211', 'CE', 'Engineering Mechanics', 4, 'F029', 2),
('CE212', 'CE', 'Surveying II', 4, 'F030', 2),
('CE213', 'CE', 'Applied Mathematics II', 3, 'F031', 2),
('CE214', 'CE', 'Communication Skills', 3, 'F032', 2),
('CE215', 'CE', 'Islamic Studies/Ethics', 2, NULL, 2),
('CE216', 'CE', 'Civil Engineering Materials', 3, 'F029', 2),
('CE311', 'CE', 'Structural Analysis I', 4, 'F030', 3),
('CE312', 'CE', 'Fluid Mechanics I', 4, 'F031', 3),
('CE313', 'CE', 'Geotechnical Engineering I', 4, 'F032', 3),
('CE314', 'CE', 'Construction Planning', 3, NULL, 3),
('CE315', 'CE', 'Probability in Engineering', 3, 'F029', 3),
('CE316', 'CE', 'Transportation Engineering I', 3, 'F030', 3),
('CE411', 'CE', 'Structural Analysis II', 4, 'F031', 4),
('CE412', 'CE', 'Fluid Mechanics II', 4, 'F032', 4),
('CE413', 'CE', 'Geotechnical Engineering II', 4, NULL, 4),
('CE414', 'CE', 'Reinforced Concrete Design I', 4, 'F029', 4),
('CE415', 'CE', 'Hydrology', 3, 'F030', 4),
('CE416', 'CE', 'Transportation Engineering II', 3, 'F031', 4),
('CE511', 'CE', 'Steel Structures', 4, 'F032', 5),
('CE512', 'CE', 'Reinforced Concrete Design II', 4, 'F005', 5),
('CE513', 'CE', 'Environmental Engineering I', 3, 'F029', 5),
('CE514', 'CE', 'Earthquake Engineering', 3, 'F030', 5),
('CE515', 'CE', 'Hydraulic Engineering', 3, 'F031', 5),
('CE516', 'CE', 'Construction Management', 3, 'F032', 5),
('CE611', 'CE', 'Foundation Engineering', 4, 'F005', 6),
('CE612', 'CE', 'Environmental Engineering II', 3, 'F029', 6),
('CE613', 'CE', 'Highway Engineering', 3, 'F030', 6),
('CE614', 'CE', 'Irrigation Engineering', 3, 'F031', 6),
('CE615', 'CE', 'Pre-stressed Concrete', 3, 'F032', 6),
('CE616', 'CE', 'Project Management', 3, 'F005', 6),
('CE711', 'CE', 'Final Year Project I', 3, 'F029', 7),
('CE712', 'CE', 'Bridge Engineering', 3, 'F030', 7),
('CE713', 'CE', 'Traffic Engineering', 3, 'F031', 7),
('CE714', 'CE', 'Geotechnical Design', 3, 'F032', 7),
('CE715', 'CE', 'Water Resources Management', 3, 'F005', 7),
('CE716', 'CE', 'Sustainable Construction', 3, 'F029', 7),
('CE811', 'CE', 'Final Year Project II', 3, 'F030', 8),
('CE812', 'CE', 'Structural Dynamics', 3, 'F031', 8),
('CE813', 'CE', 'Pavement Design', 3, 'F032', 8),
('CE814', 'CE', 'Coastal Engineering', 3, 'F005', 8),
('CE815', 'CE', 'GIS in Civil Engineering', 3, 'F029', 8),
('CE816', 'CE', 'Professional Ethics', 3, 'F030', 8),
('CS111', 'CS', 'Programming Fundamentals', 4, NULL, 1),
('CS112', 'CS', 'Discrete Structures', 3, 'F011', 1),
('CS113', 'CS', 'Calculus I', 3, 'F014', 1),
('CS114', 'CS', 'Applied Physics', 3, 'F015', 1),
('CS115', 'CS', 'Functional English', 3, 'F016', 1),
('CS116', 'CS', 'Pakistan Studies', 2, 'F013', 1),
('CS211', 'CS', 'Object Oriented Programming', 4, 'F014', 2),
('CS212', 'CS', 'Digital Logic Design', 4, 'F015', 2),
('CS213', 'CS', 'Linear Algebra', 3, 'F016', 2),
('CS214', 'CS', 'Communication Skills', 3, NULL, 2),
('CS215', 'CS', 'Islamic Studies/Ethics', 2, 'F013', 2),
('CS216', 'CS', 'Data Structures & Algorithms', 4, 'F014', 2),
('CS311', 'CS', 'Database Systems', 4, 'F015', 3),
('CS312', 'CS', 'Operating Systems', 4, 'F016', 3),
('CS313', 'CS', 'Software Engineering Fundamentals', 3, NULL, 3),
('CS314', 'CS', 'Computer Organization & Assembly', 3, 'F013', 3),
('CS315', 'CS', 'Probability & Statistics', 3, 'F014', 3),
('CS316', 'CS', 'Web Programming', 3, 'F015', 3),
('CS411', 'CS', 'Design & Analysis of Algorithms', 3, 'F016', 4),
('CS412', 'CS', 'Theory of Automata', 3, 'F001', 4),
('CS413', 'CS', 'Computer Networks', 4, NULL, 4),
('CS414', 'CS', 'Artificial Intelligence', 3, 'F014', 4),
('CS415', 'CS', 'Numerical Computing', 3, 'F015', 4),
('CS416', 'CS', 'Mobile Application Development', 3, 'F016', 4),
('CS511', 'CS', 'Compiler Construction', 3, NULL, 5),
('CS512', 'CS', 'Distributed Computing', 3, NULL, 5),
('CS513', 'CS', 'Machine Learning', 3, 'F014', 5),
('CS514', 'CS', 'Cloud Computing', 3, 'F015', 5),
('CS515', 'CS', 'Information Security', 3, 'F016', 5),
('CS516', 'CS', 'Human Computer Interaction', 3, NULL, 5),
('CS611', 'CS', 'Parallel Computing', 3, 'F013', 6),
('CS612', 'CS', 'Data Warehousing & Mining', 3, 'F014', 6),
('CS613', 'CS', 'Deep Learning', 3, 'F015', 6),
('CS614', 'CS', 'Software Project Management', 3, 'F016', 6),
('CS615', 'CS', 'Cyber Security', 3, 'F001', 6),
('CS616', 'CS', 'Big Data Analytics', 3, 'F013', 6),
('CS711', 'CS', 'Final Year Project I', 3, 'F014', 7),
('CS712', 'CS', 'Blockchain Technology', 3, 'F015', 7),
('CS713', 'CS', 'Internet of Things', 3, 'F016', 7),
('CS714', 'CS', 'DevOps Practices', 3, NULL, 7),
('CS715', 'CS', 'Computer Vision', 3, 'F013', 7),
('CS716', 'CS', 'Natural Language Processing', 3, 'F014', 7),
('CS811', 'CS', 'Final Year Project II', 3, 'F015', 8),
('CS812', 'CS', 'Software Re-Engineering', 3, 'F007', 8),
('CS813', 'CS', 'Quantum Computing', 3, 'F001', 8),
('CS814', 'CS', 'Ethical Hacking', 3, 'F013', 8),
('CS815', 'CS', 'Game Development', 3, 'F014', 8),
('CS816', 'CS', 'Research Methodology', 3, 'F015', 8),
('EE111', 'EE', 'Circuit Analysis I', 4, 'F009', 1),
('EE112', 'EE', 'Applied Physics', 3, 'F021', 1),
('EE113', 'EE', 'Calculus I', 3, 'F022', 1),
('EE114', 'EE', 'Functional English', 3, 'F023', 1),
('EE115', 'EE', 'Pakistan Studies', 2, 'F024', 1),
('EE116', 'EE', 'Workshop Practice', 1, 'F002', 1),
('EE211', 'EE', 'Circuit Analysis II', 4, 'F021', 2),
('EE212', 'EE', 'Digital Logic Design', 4, 'F022', 2),
('EE213', 'EE', 'Differential Equations', 3, 'F023', 2),
('EE214', 'EE', 'Communication Skills', 3, 'F024', 2),
('EE215', 'EE', 'Islamic Studies/Ethics', 2, NULL, 2),
('EE216', 'EE', 'Electronic Devices & Circuits', 4, 'F021', 2),
('EE311', 'EE', 'Signals & Systems', 4, 'F022', 3),
('EE312', 'EE', 'Electrical Machines I', 4, 'F023', 3),
('EE313', 'EE', 'Electromagnetic Field Theory', 3, 'F024', 3),
('EE314', 'EE', 'Microprocessor Systems', 4, 'F002', 3),
('EE315', 'EE', 'Probability Methods in Engineering', 3, 'F021', 3),
('EE316', 'EE', 'Power Generation', 3, 'F022', 3),
('EE411', 'EE', 'Electrical Machines II', 4, 'F023', 4),
('EE412', 'EE', 'Power Electronics', 4, 'F024', 4),
('EE413', 'EE', 'Communication Systems', 4, NULL, 4),
('EE414', 'EE', 'Control Systems', 4, 'F021', 4),
('EE415', 'EE', 'Instrumentation & Measurements', 3, 'F022', 4),
('EE416', 'EE', 'Linear Algebra', 3, 'F023', 4),
('EE511', 'EE', 'Power System Analysis', 4, 'F024', 5),
('EE512', 'EE', 'Digital Signal Processing', 4, NULL, 5),
('EE513', 'EE', 'Embedded Systems', 4, 'F021', 5),
('EE514', 'EE', 'Renewable Energy Systems', 3, 'F022', 5),
('EE515', 'EE', 'VLSI Design', 4, 'F023', 5),
('EE516', 'EE', 'Antenna & Wave Propagation', 3, 'F024', 5),
('EE611', 'EE', 'Power Transmission & Distribution', 4, 'F002', 6),
('EE612', 'EE', 'Digital Communication', 4, 'F021', 6),
('EE613', 'EE', 'Power System Protection', 3, 'F022', 6),
('EE614', 'EE', 'Control System Design', 3, 'F023', 6),
('EE615', 'EE', 'Optical Fiber Communication', 3, 'F024', 6),
('EE616', 'EE', 'High Voltage Engineering', 3, 'F002', 6),
('EE711', 'EE', 'Final Year Project I', 3, NULL, 7),
('EE712', 'EE', 'Power System Operation & Control', 3, 'F022', 7),
('EE713', 'EE', 'Wireless Communication', 3, 'F023', 7),
('EE714', 'EE', 'RF & Microwave Engineering', 3, 'F024', 7),
('EE715', 'EE', 'Smart Grid Technology', 3, 'F002', 7),
('EE716', 'EE', 'Industrial Automation', 3, NULL, 7),
('EE811', 'EE', 'Final Year Project II', 3, 'F022', 8),
('EE812', 'EE', 'Power System Stability', 3, 'F023', 8),
('EE813', 'EE', 'Satellite Communication', 3, 'F024', 8),
('EE814', 'EE', 'Radar Systems', 3, 'F002', 8),
('EE815', 'EE', 'Electric Drives', 3, NULL, 8),
('EE816', 'EE', 'Professional Practices', 3, 'F022', 8),
('ME111', 'ME', 'Engineering Drawing', 3, 'F006', 1),
('ME112', 'ME', 'Applied Thermodynamics', 4, 'F033', 1),
('ME113', 'ME', 'Calculus I', 3, 'F034', 1),
('ME114', 'ME', 'Applied Physics', 3, 'F035', 1),
('ME115', 'ME', 'Functional English', 3, 'F036', 1),
('ME116', 'ME', 'Pakistan Studies', 2, 'F006', 1),
('ME211', 'ME', 'Engineering Statics', 3, 'F033', 2),
('ME212', 'ME', 'Fluid Mechanics I', 4, 'F034', 2),
('ME213', 'ME', 'Differential Equations', 3, 'F035', 2),
('ME214', 'ME', 'Communication Skills', 3, 'F036', 2),
('ME215', 'ME', 'Islamic Studies/Ethics', 2, 'F006', 2),
('ME216', 'ME', 'Workshop Practice', 2, 'F033', 2),
('ME311', 'ME', 'Engineering Dynamics', 3, 'F034', 3),
('ME312', 'ME', 'Fluid Mechanics II', 4, 'F035', 3),
('ME313', 'ME', 'Mechanics of Materials I', 4, 'F036', 3),
('ME314', 'ME', 'Manufacturing Processes', 3, 'F006', 3),
('ME315', 'ME', 'Probability & Statistics', 3, 'F033', 3),
('ME316', 'ME', 'Machine Design I', 3, 'F034', 3),
('ME411', 'ME', 'Mechanics of Materials II', 4, 'F035', 4),
('ME412', 'ME', 'Heat Transfer', 4, 'F036', 4),
('ME413', 'ME', 'Machine Design II', 4, 'F006', 4),
('ME414', 'ME', 'Instrumentation & Measurement', 3, 'F033', 4),
('ME415', 'ME', 'Control Systems', 4, 'F034', 4),
('ME416', 'ME', 'Numerical Methods', 3, 'F035', 4),
('ME511', 'ME', 'Refrigeration & Air Conditioning', 4, 'F036', 5),
('ME512', 'ME', 'Internal Combustion Engines', 4, 'F006', 5),
('ME513', 'ME', 'Power Plants', 3, 'F033', 5),
('ME514', 'ME', 'Renewable Energy Technology', 3, 'F034', 5),
('ME515', 'ME', 'Finite Element Analysis', 3, 'F035', 5),
('ME516', 'ME', 'Vibration Analysis', 3, 'F036', 5),
('ME611', 'ME', 'Automobile Engineering', 3, 'F006', 6),
('ME612', 'ME', 'Robotics', 3, 'F033', 6),
('ME613', 'ME', 'Aerodynamics', 3, 'F034', 6),
('ME614', 'ME', 'Tribology', 3, 'F035', 6),
('ME615', 'ME', 'CAD/CAM', 3, 'F036', 6),
('ME616', 'ME', 'Project Management', 3, 'F006', 6),
('ME711', 'ME', 'Final Year Project I', 3, 'F033', 7),
('ME712', 'ME', 'Mechatronics', 3, 'F034', 7),
('ME713', 'ME', 'Energy Management', 3, 'F035', 7),
('ME714', 'ME', 'Maintenance Engineering', 3, 'F036', 7),
('ME715', 'ME', 'Industrial Safety', 3, 'F006', 7),
('ME716', 'ME', 'Quality Control', 3, 'F033', 7),
('ME811', 'ME', 'Final Year Project II', 3, 'F034', 8),
('ME812', 'ME', 'Computational Fluid Dynamics', 3, 'F035', 8),
('ME813', 'ME', 'Advanced Manufacturing', 3, 'F036', 8),
('ME814', 'ME', 'Sustainable Engineering', 3, 'F006', 8),
('ME815', 'ME', 'Nano Technology', 3, 'F033', 8),
('ME816', 'ME', 'Professional Ethics', 3, 'F034', 8),
('SE111', 'SE', 'Programming Fundamentals', 4, 'F004', 1),
('SE112', 'SE', 'Discrete Structures', 3, 'F017', 1),
('SE113', 'SE', 'Calculus I', 3, 'F018', 1),
('SE114', 'SE', 'Applied Physics', 3, 'F019', 1),
('SE115', 'SE', 'Functional English', 3, 'F020', 1),
('SE116', 'SE', 'Pakistan Studies', 2, 'F004', 1),
('SE211', 'SE', 'Object Oriented Programming', 4, 'F017', 2),
('SE212', 'SE', 'Digital Logic Design', 4, 'F018', 2),
('SE213', 'SE', 'Linear Algebra', 3, 'F019', 2),
('SE214', 'SE', 'Communication Skills', 3, 'F020', 2),
('SE215', 'SE', 'Islamic Studies/Ethics', 2, 'F004', 2),
('SE216', 'SE', 'Introduction to Software Engineering', 3, 'F017', 2),
('SE311', 'SE', 'Data Structures & Algorithms', 4, 'F018', 3),
('SE312', 'SE', 'Software Requirements Engineering', 3, 'F019', 3),
('SE313', 'SE', 'Database Systems', 4, 'F020', 3),
('SE314', 'SE', 'Operating Systems', 4, 'F004', 3),
('SE315', 'SE', 'Probability & Statistics', 3, 'F017', 3),
('SE316', 'SE', 'Human Computer Interaction', 3, 'F018', 3),
('SE411', 'SE', 'Software Design & Architecture', 3, 'F019', 4),
('SE412', 'SE', 'Web Engineering', 3, 'F020', 4),
('SE413', 'SE', 'Computer Networks', 4, 'F004', 4),
('SE414', 'SE', 'Software Construction', 3, 'F017', 4),
('SE415', 'SE', 'Formal Methods', 3, 'F018', 4),
('SE416', 'SE', 'Mobile Application Development', 3, 'F019', 4),
('SE511', 'SE', 'Software Quality Engineering', 3, 'F020', 5),
('SE512', 'SE', 'Software Testing', 3, 'F004', 5),
('SE513', 'SE', 'Cloud Computing', 3, NULL, 5),
('SE514', 'SE', 'Information Security', 3, 'F018', 5),
('SE515', 'SE', 'Artificial Intelligence', 3, 'F019', 5),
('SE516', 'SE', 'DevOps Practices', 3, 'F020', 5),
('SE611', 'SE', 'Software Project Management', 3, NULL, 6),
('SE612', 'SE', 'Machine Learning for Software Engineers', 3, NULL, 6),
('SE613', 'SE', 'Big Data Analytics', 3, 'F018', 6),
('SE614', 'SE', 'Cyber Security', 3, 'F019', 6),
('SE615', 'SE', 'Blockchain Technology', 3, 'F020', 6),
('SE616', 'SE', 'Internet of Things', 3, 'F004', 6),
('SE711', 'SE', 'Final Year Project I', 3, NULL, 7),
('SE712', 'SE', 'Software Re-Engineering', 3, 'F018', 7),
('SE713', 'SE', 'Game Development', 3, 'F019', 7),
('SE714', 'SE', 'Enterprise Systems', 3, 'F020', 7),
('SE715', 'SE', 'Natural Language Processing', 3, NULL, 7),
('SE716', 'SE', 'Research Methodology', 3, NULL, 7),
('SE811', 'SE', 'Final Year Project II', 3, 'F018', 8),
('SE812', 'SE', 'Advanced Web Engineering', 3, 'F019', 8),
('SE813', 'SE', 'Distributed Systems', 3, 'F020', 8),
('SE814', 'SE', 'Ethical Hacking', 3, 'F012', 8),
('SE815', 'SE', 'Deep Learning', 3, NULL, 8),
('SE816', 'SE', 'Professional Practices in IT', 3, 'F008', 8);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `DepartmentID` varchar(10) NOT NULL,
  `DepartmentName` varchar(100) NOT NULL,
  `HOD` varchar(100) DEFAULT NULL,
  `OfficeLocation` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`DepartmentID`, `DepartmentName`, `HOD`, `OfficeLocation`) VALUES
('BBA', 'Business Administration', 'Prof. Salman Butt', 'Admin Block'),
('CE', 'Civil Engineering', 'Eng. Bilal Hussain', 'Eng Block'),
('CS', 'Computer Science', 'Dr. Ahmed Khan', 'A-301'),
('EE', 'Electrical Engineering', 'Dr. Fatima Sheikh', 'B-101'),
('ME', 'Mechanical Engineering', 'Dr. Imran Ali', 'Workshop'),
('SE', 'Software Engineering', 'Dr. Sana Malik', 'A-401');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE `enrollment` (
  `StudentID` varchar(10) NOT NULL,
  `CourseID` varchar(10) NOT NULL,
  `Semester` int(11) NOT NULL CHECK (`Semester` between 1 and 8),
  `DepartmentID` varchar(50) DEFAULT NULL,
  `EnrollDate` date NOT NULL,
  `EnrollmentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`StudentID`, `CourseID`, `Semester`, `DepartmentID`, `EnrollDate`, `EnrollmentID`) VALUES
('S001', 'CS611', 6, 'CS', '2025-12-18', 175),
('S001', 'CS612', 6, 'CS', '2025-12-18', 176),
('S001', 'CS613', 6, 'CS', '2025-12-18', 177),
('S001', 'CS614', 6, 'CS', '2025-12-18', 178),
('S001', 'CS615', 6, 'CS', '2025-12-18', 179),
('S001', 'CS616', 6, 'CS', '2025-12-18', 180),
('S002', 'CS611', 6, 'CS', '2025-12-18', 181),
('S002', 'CS612', 6, 'CS', '2025-12-18', 182),
('S002', 'CS613', 6, 'CS', '2025-12-18', 183),
('S002', 'CS614', 6, 'CS', '2025-12-18', 184),
('S002', 'CS615', 6, 'CS', '2025-12-18', 185),
('S002', 'CS616', 6, 'CS', '2025-12-18', 186),
('S003', 'SE711', 7, 'SE', '2025-12-18', 187),
('S003', 'SE712', 7, 'SE', '2025-12-18', 188),
('S003', 'SE713', 7, 'SE', '2025-12-18', 189),
('S003', 'SE714', 7, 'SE', '2025-12-18', 190),
('S003', 'SE715', 7, 'SE', '2025-12-18', 191),
('S003', 'SE716', 7, 'SE', '2025-12-18', 192),
('S004', 'EE711', 7, 'EE', '2025-12-18', 193),
('S004', 'EE712', 7, 'EE', '2025-12-18', 194),
('S004', 'EE713', 7, 'EE', '2025-12-18', 195),
('S004', 'EE714', 7, 'EE', '2025-12-18', 196),
('S004', 'EE715', 7, 'EE', '2025-12-18', 197),
('S004', 'EE716', 7, 'EE', '2025-12-18', 198),
('S005', 'CS611', 6, 'CS', '2025-12-18', 199),
('S005', 'CS612', 6, 'CS', '2025-12-18', 200),
('S005', 'CS613', 6, 'CS', '2025-12-18', 201),
('S005', 'CS614', 6, 'CS', '2025-12-18', 202),
('S005', 'CS615', 6, 'CS', '2025-12-18', 203),
('S005', 'CS616', 6, 'CS', '2025-12-18', 204),
('S006', 'BBA411', 4, 'BBA', '2025-12-18', 205),
('S006', 'BBA412', 4, 'BBA', '2025-12-18', 206),
('S006', 'BBA413', 4, 'BBA', '2025-12-18', 207),
('S006', 'BBA414', 4, 'BBA', '2025-12-18', 208),
('S006', 'BBA415', 4, 'BBA', '2025-12-18', 209),
('S006', 'BBA416', 4, 'BBA', '2025-12-18', 210),
('S007', 'SE611', 6, 'SE', '2025-12-18', 211),
('S007', 'SE612', 6, 'SE', '2025-12-18', 212),
('S007', 'SE613', 6, 'SE', '2025-12-18', 213),
('S007', 'SE614', 6, 'SE', '2025-12-18', 214),
('S007', 'SE615', 6, 'SE', '2025-12-18', 215),
('S007', 'SE616', 6, 'SE', '2025-12-18', 216),
('S008', 'CS811', 8, 'CS', '2025-12-18', 217),
('S008', 'CS812', 8, 'CS', '2025-12-18', 218),
('S008', 'CS813', 8, 'CS', '2025-12-18', 219),
('S008', 'CS814', 8, 'CS', '2025-12-18', 220),
('S008', 'CS815', 8, 'CS', '2025-12-18', 221),
('S008', 'CS816', 8, 'CS', '2025-12-18', 222),
('S009', 'CS311', 3, 'CS', '2025-12-18', 223),
('S009', 'CS312', 3, 'CS', '2025-12-18', 224),
('S009', 'CS313', 3, 'CS', '2025-12-18', 225),
('S009', 'CS314', 3, 'CS', '2025-12-18', 226),
('S009', 'CS315', 3, 'CS', '2025-12-18', 227),
('S009', 'CS316', 3, 'CS', '2025-12-18', 228),
('S010', 'BBA411', 4, 'BBA', '2025-12-18', 229),
('S010', 'BBA412', 4, 'BBA', '2025-12-18', 230),
('S010', 'BBA413', 4, 'BBA', '2025-12-18', 231),
('S010', 'BBA414', 4, 'BBA', '2025-12-18', 232),
('S010', 'BBA415', 4, 'BBA', '2025-12-18', 233),
('S010', 'BBA416', 4, 'BBA', '2025-12-18', 234),
('S011', 'EE511', 5, 'EE', '2025-12-18', 235),
('S011', 'EE512', 5, 'EE', '2025-12-18', 236),
('S011', 'EE513', 5, 'EE', '2025-12-18', 237),
('S011', 'EE514', 5, 'EE', '2025-12-18', 238),
('S011', 'EE515', 5, 'EE', '2025-12-18', 239),
('S011', 'EE516', 5, 'EE', '2025-12-18', 240),
('S012', 'SE511', 5, 'SE', '2025-12-18', 241),
('S012', 'SE512', 5, 'SE', '2025-12-18', 242),
('S012', 'SE513', 5, 'SE', '2025-12-18', 243),
('S012', 'SE514', 5, 'SE', '2025-12-18', 244),
('S012', 'SE515', 5, 'SE', '2025-12-18', 245),
('S012', 'SE516', 5, 'SE', '2025-12-18', 246),
('S013', 'CS611', 6, 'CS', '2025-12-18', 247),
('S013', 'CS612', 6, 'CS', '2025-12-18', 248),
('S013', 'CS613', 6, 'CS', '2025-12-18', 249),
('S013', 'CS614', 6, 'CS', '2025-12-18', 250),
('S013', 'CS615', 6, 'CS', '2025-12-18', 251),
('S013', 'CS616', 6, 'CS', '2025-12-18', 252),
('S014', 'CE811', 8, 'CE', '2025-12-18', 253),
('S014', 'CE812', 8, 'CE', '2025-12-18', 254),
('S014', 'CE813', 8, 'CE', '2025-12-18', 255),
('S014', 'CE814', 8, 'CE', '2025-12-18', 256),
('S014', 'CE815', 8, 'CE', '2025-12-18', 257),
('S014', 'CE816', 8, 'CE', '2025-12-18', 258),
('S015', 'ME411', 4, 'ME', '2025-12-18', 259),
('S015', 'ME412', 4, 'ME', '2025-12-18', 260),
('S015', 'ME413', 4, 'ME', '2025-12-18', 261),
('S015', 'ME414', 4, 'ME', '2025-12-18', 262),
('S015', 'ME415', 4, 'ME', '2025-12-18', 263),
('S015', 'ME416', 4, 'ME', '2025-12-18', 264),
('S016', 'CS211', 2, 'CS', '2025-12-18', 265),
('S016', 'CS212', 2, 'CS', '2025-12-18', 266),
('S016', 'CS213', 2, 'CS', '2025-12-18', 267),
('S016', 'CS214', 2, 'CS', '2025-12-18', 268),
('S016', 'CS215', 2, 'CS', '2025-12-18', 269),
('S016', 'CS216', 2, 'CS', '2025-12-18', 270),
('S017', 'CS111', 1, 'CS', '2025-12-18', 271),
('S017', 'CS112', 1, 'CS', '2025-12-18', 272),
('S017', 'CS113', 1, 'CS', '2025-12-18', 273),
('S017', 'CS114', 1, 'CS', '2025-12-18', 274),
('S017', 'CS115', 1, 'CS', '2025-12-18', 275),
('S017', 'CS116', 1, 'CS', '2025-12-18', 276),
('S018', 'CS111', 1, 'CS', '2025-12-18', 277),
('S018', 'CS112', 1, 'CS', '2025-12-18', 278),
('S018', 'CS113', 1, 'CS', '2025-12-18', 279),
('S018', 'CS114', 1, 'CS', '2025-12-18', 280),
('S018', 'CS115', 1, 'CS', '2025-12-18', 281),
('S018', 'CS116', 1, 'CS', '2025-12-18', 282),
('S019', 'SE111', 1, 'SE', '2025-12-18', 283),
('S019', 'SE112', 1, 'SE', '2025-12-18', 284),
('S019', 'SE113', 1, 'SE', '2025-12-18', 285),
('S019', 'SE114', 1, 'SE', '2025-12-18', 286),
('S019', 'SE115', 1, 'SE', '2025-12-18', 287),
('S019', 'SE116', 1, 'SE', '2025-12-18', 288),
('S020', 'SE111', 1, 'SE', '2025-12-18', 289),
('S020', 'SE112', 1, 'SE', '2025-12-18', 290),
('S020', 'SE113', 1, 'SE', '2025-12-18', 291),
('S020', 'SE114', 1, 'SE', '2025-12-18', 292),
('S020', 'SE115', 1, 'SE', '2025-12-18', 293),
('S020', 'SE116', 1, 'SE', '2025-12-18', 294),
('S021', 'EE111', 1, 'EE', '2025-12-18', 295),
('S021', 'EE112', 1, 'EE', '2025-12-18', 296),
('S021', 'EE113', 1, 'EE', '2025-12-18', 297),
('S021', 'EE114', 1, 'EE', '2025-12-18', 298),
('S021', 'EE115', 1, 'EE', '2025-12-18', 299),
('S021', 'EE116', 1, 'EE', '2025-12-18', 300),
('S022', 'EE111', 1, 'EE', '2025-12-18', 301),
('S022', 'EE112', 1, 'EE', '2025-12-18', 302),
('S022', 'EE113', 1, 'EE', '2025-12-18', 303),
('S022', 'EE114', 1, 'EE', '2025-12-18', 304),
('S022', 'EE115', 1, 'EE', '2025-12-18', 305),
('S022', 'EE116', 1, 'EE', '2025-12-18', 306),
('S023', 'BBA111', 1, 'BBA', '2025-12-18', 307),
('S023', 'BBA112', 1, 'BBA', '2025-12-18', 308),
('S023', 'BBA113', 1, 'BBA', '2025-12-18', 309),
('S023', 'BBA114', 1, 'BBA', '2025-12-18', 310),
('S023', 'BBA115', 1, 'BBA', '2025-12-18', 311),
('S023', 'BBA116', 1, 'BBA', '2025-12-18', 312),
('S024', 'BBA111', 1, 'BBA', '2025-12-18', 313),
('S024', 'BBA112', 1, 'BBA', '2025-12-18', 314),
('S024', 'BBA113', 1, 'BBA', '2025-12-18', 315),
('S024', 'BBA114', 1, 'BBA', '2025-12-18', 316),
('S024', 'BBA115', 1, 'BBA', '2025-12-18', 317),
('S024', 'BBA116', 1, 'BBA', '2025-12-18', 318),
('S025', 'CE111', 1, 'CE', '2025-12-18', 319),
('S025', 'CE112', 1, 'CE', '2025-12-18', 320),
('S025', 'CE113', 1, 'CE', '2025-12-18', 321),
('S025', 'CE114', 1, 'CE', '2025-12-18', 322),
('S025', 'CE115', 1, 'CE', '2025-12-18', 323),
('S025', 'CE116', 1, 'CE', '2025-12-18', 324),
('S026', 'CE111', 1, 'CE', '2025-12-18', 325),
('S026', 'CE112', 1, 'CE', '2025-12-18', 326),
('S026', 'CE113', 1, 'CE', '2025-12-18', 327),
('S026', 'CE114', 1, 'CE', '2025-12-18', 328),
('S026', 'CE115', 1, 'CE', '2025-12-18', 329),
('S026', 'CE116', 1, 'CE', '2025-12-18', 330),
('S027', 'ME111', 1, 'ME', '2025-12-18', 331),
('S027', 'ME112', 1, 'ME', '2025-12-18', 332),
('S027', 'ME113', 1, 'ME', '2025-12-18', 333),
('S027', 'ME114', 1, 'ME', '2025-12-18', 334),
('S027', 'ME115', 1, 'ME', '2025-12-18', 335),
('S027', 'ME116', 1, 'ME', '2025-12-18', 336),
('S028', 'ME111', 1, 'ME', '2025-12-18', 337),
('S028', 'ME112', 1, 'ME', '2025-12-18', 338),
('S028', 'ME113', 1, 'ME', '2025-12-18', 339),
('S028', 'ME114', 1, 'ME', '2025-12-18', 340),
('S028', 'ME115', 1, 'ME', '2025-12-18', 341),
('S028', 'ME116', 1, 'ME', '2025-12-18', 342),
('S029', 'CS111', 1, 'CS', '2025-12-18', 343),
('S029', 'CS112', 1, 'CS', '2025-12-18', 344),
('S029', 'CS113', 1, 'CS', '2025-12-18', 345),
('S029', 'CS114', 1, 'CS', '2025-12-18', 346),
('S029', 'CS115', 1, 'CS', '2025-12-18', 347),
('S029', 'CS116', 1, 'CS', '2025-12-18', 348),
('S030', 'SE111', 1, 'SE', '2025-12-18', 349),
('S030', 'SE112', 1, 'SE', '2025-12-18', 350),
('S030', 'SE113', 1, 'SE', '2025-12-18', 351),
('S030', 'SE114', 1, 'SE', '2025-12-18', 352),
('S030', 'SE115', 1, 'SE', '2025-12-18', 353),
('S030', 'SE116', 1, 'SE', '2025-12-18', 354),
('S031', 'EE111', 1, 'EE', '2025-12-18', 355),
('S031', 'EE112', 1, 'EE', '2025-12-18', 356),
('S031', 'EE113', 1, 'EE', '2025-12-18', 357),
('S031', 'EE114', 1, 'EE', '2025-12-18', 358),
('S031', 'EE115', 1, 'EE', '2025-12-18', 359),
('S031', 'EE116', 1, 'EE', '2025-12-18', 360),
('S032', 'BBA111', 1, 'BBA', '2025-12-18', 361),
('S032', 'BBA112', 1, 'BBA', '2025-12-18', 362),
('S032', 'BBA113', 1, 'BBA', '2025-12-18', 363),
('S032', 'BBA114', 1, 'BBA', '2025-12-18', 364),
('S032', 'BBA115', 1, 'BBA', '2025-12-18', 365),
('S032', 'BBA116', 1, 'BBA', '2025-12-18', 366),
('S033', 'CE111', 1, 'CE', '2025-12-18', 367),
('S033', 'CE112', 1, 'CE', '2025-12-18', 368),
('S033', 'CE113', 1, 'CE', '2025-12-18', 369),
('S033', 'CE114', 1, 'CE', '2025-12-18', 370),
('S033', 'CE115', 1, 'CE', '2025-12-18', 371),
('S033', 'CE116', 1, 'CE', '2025-12-18', 372),
('S034', 'ME111', 1, 'ME', '2025-12-18', 373),
('S034', 'ME112', 1, 'ME', '2025-12-18', 374),
('S034', 'ME113', 1, 'ME', '2025-12-18', 375),
('S034', 'ME114', 1, 'ME', '2025-12-18', 376),
('S034', 'ME115', 1, 'ME', '2025-12-18', 377),
('S034', 'ME116', 1, 'ME', '2025-12-18', 378),
('S035', 'CS111', 1, 'CS', '2025-12-18', 379),
('S035', 'CS112', 1, 'CS', '2025-12-18', 380),
('S035', 'CS113', 1, 'CS', '2025-12-18', 381),
('S035', 'CS114', 1, 'CS', '2025-12-18', 382),
('S035', 'CS115', 1, 'CS', '2025-12-18', 383),
('S035', 'CS116', 1, 'CS', '2025-12-18', 384),
('S036', 'SE111', 1, 'SE', '2025-12-18', 385),
('S036', 'SE112', 1, 'SE', '2025-12-18', 386),
('S036', 'SE113', 1, 'SE', '2025-12-18', 387),
('S036', 'SE114', 1, 'SE', '2025-12-18', 388),
('S036', 'SE115', 1, 'SE', '2025-12-18', 389),
('S036', 'SE116', 1, 'SE', '2025-12-18', 390),
('S038', 'CS311', 3, NULL, '2025-12-24', 449),
('S039', 'CS311', 3, NULL, '2025-12-24', 451),
('S039', 'CS312', 3, NULL, '2025-12-24', 452),
('S039', 'CS313', 3, NULL, '2025-12-24', 454),
('S039', 'CS314', 3, NULL, '2025-12-24', 450),
('S039', 'CS315', 3, NULL, '2025-12-24', 453),
('S039', 'CS316', 3, NULL, '2025-12-24', 455);

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `FacultyID` varchar(20) NOT NULL,
  `UserID` varchar(20) NOT NULL,
  `DepartmentID` varchar(10) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Department` varchar(100) NOT NULL,
  `ContactInfo` varchar(15) DEFAULT NULL,
  `YearsOfService` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`FacultyID`, `UserID`, `DepartmentID`, `Name`, `Department`, `ContactInfo`, `YearsOfService`) VALUES
('F001', 'F001', 'CS', 'Dr. Ahmed Khan', 'Computer Science', '03001234567', 5),
('F002', 'F002', 'EE', 'Dr. Fatima Sheikh', 'Electrical Engineering', '03339876543', 4),
('F003', 'F003', 'BBA', 'Prof. Salman Butt', 'Business Administration', '03215550198', 6),
('F004', 'F004', 'SE', 'Dr. Sana Malik', 'Software Engineering', '03014445678', 7),
('F005', 'F005', 'CE', 'Eng. Bilal Hussain', 'Civil Engineering', '03457778899', 5),
('F006', 'F006', 'ME', 'Dr. Imran Ali', 'Mechanical Engineering', '03158887766', 2),
('F007', 'F007', 'CS', 'Ms. Aisha Rehman', 'Computer Science', '03312223344', 3),
('F008', 'F008', 'SE', 'Mr. Kashif Ahmed', 'Software Engineering', '03085556677', 5),
('F009', 'F009', 'EE', 'Dr. Nadeem Akhtar', 'Electrical Engineering', '03229990011', 6),
('F010', 'F010', 'BBA', 'Ms. Saba Khan', 'Business Administration', '03351112233', 4),
('F011', 'F011', 'CS', 'Mr. Farhan Abbas', 'Computer Science', '03004445566', 6),
('F012', 'F012', 'SE', 'Ms. Hina Aslam', 'Software Engineering', '03217778899', 6),
('F013', 'F013', 'CS', 'Dr. Rizwan Ahmed', 'Computer Science', '03001234567', 8),
('F014', 'F014', 'CS', 'Prof. Sana Khan', 'Computer Science', '03339876543', 6),
('F015', 'F015', 'CS', 'Dr. Farhan Ali', 'Computer Science', '03155678901', 10),
('F016', 'F016', 'CS', 'Prof. Ayesha Malik', 'Computer Science', '03445551234', 5),
('F017', 'F017', 'SE', 'Dr. Usman Ahmed', 'Software Engineering', '03217894561', 7),
('F018', 'F018', 'SE', 'Prof. Zainab Hassan', 'Software Engineering', '03331237890', 4),
('F019', 'F019', 'SE', 'Dr. Bilal Khan', 'Software Engineering', '03149876543', 9),
('F020', 'F020', 'SE', 'Prof. Hina Farooq', 'Software Engineering', '03456789012', 3),
('F021', 'F021', 'EE', 'Dr. Raheel Abbas', 'Electrical Engineering', '03008765432', 12),
('F022', 'F022', 'EE', 'Prof. Saba Rizvi', 'Electrical Engineering', '03334567890', 5),
('F023', 'F023', 'EE', 'Dr. Kashif Javed', 'Electrical Engineering', '03111223344', 11),
('F024', 'F024', 'EE', 'Prof. Nida Akram', 'Electrical Engineering', '03449887766', 4),
('F025', 'F025', 'BBA', 'Dr. Imran Chaudhry', 'Business Administration', '03215550198', 13),
('F026', 'F026', 'BBA', 'Prof. Mehwish Ansari', 'Business Administration', '03336661234', 6),
('F027', 'F027', 'BBA', 'Dr. Waqas Ahmed', 'Business Administration', '03147778899', 8),
('F028', 'F028', 'BBA', 'Prof. Rubina Khan', 'Business Administration', '03458889900', 5),
('F029', 'F029', 'CE', 'Eng. Asif Rehman', 'Civil Engineering', '03009998877', 10),
('F030', 'F030', 'CE', 'Prof. Sumaira Bibi', 'Civil Engineering', '03331112233', 7),
('F031', 'F031', 'CE', 'Dr. Nadeem Akhtar', 'Civil Engineering', '03142223344', 9),
('F032', 'F032', 'CE', 'Prof. Saima Rafique', 'Civil Engineering', '03453334455', 4),
('F033', 'F033', 'ME', 'Dr. Faisal Jamil', 'Mechanical Engineering', '03216667788', 11),
('F034', 'F034', 'ME', 'Prof. Bushra Saeed', 'Mechanical Engineering', '03337778899', 6),
('F035', 'F035', 'ME', 'Dr. Talha Qadir', 'Mechanical Engineering', '03148889900', 8),
('F036', 'F036', 'ME', 'Prof. Humaira Nasir', 'Mechanical Engineering', '03459990011', 5);

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `GradeID` int(11) NOT NULL,
  `StudentID` varchar(20) NOT NULL,
  `CourseID` varchar(20) NOT NULL,
  `Semester` int(11) NOT NULL,
  `Quiz` decimal(5,2) DEFAULT 0.00,
  `Assignment` decimal(5,2) DEFAULT 0.00,
  `Midterm` decimal(5,2) DEFAULT 0.00,
  `FinalExam` decimal(5,2) DEFAULT 0.00,
  `TotalPercentage` decimal(5,2) DEFAULT 0.00,
  `LetterGrade` varchar(5) DEFAULT NULL,
  `GPA` decimal(3,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`GradeID`, `StudentID`, `CourseID`, `Semester`, `Quiz`, `Assignment`, `Midterm`, `FinalExam`, `TotalPercentage`, `LetterGrade`, `GPA`) VALUES
(1, 'S001', 'CS611', 6, 1.10, 0.40, 23.90, 33.20, 58.60, 'C', 1.70),
(2, 'S001', 'CS612', 6, 6.80, 2.80, 1.20, 19.80, 30.60, 'F', 0.00),
(3, 'S001', 'CS613', 6, 12.40, 9.60, 8.20, 37.90, 68.10, 'B-', 2.30),
(4, 'S001', 'CS614', 6, 12.00, 7.30, 6.40, 3.80, 29.50, 'F', 0.00),
(5, 'S001', 'CS615', 6, 9.30, 8.60, 11.60, 36.40, 65.90, 'B-', 2.30),
(6, 'S001', 'CS616', 6, 3.70, 0.60, 14.30, 33.50, 52.10, 'C-', 1.30),
(7, 'S002', 'CS611', 6, 9.50, 1.60, 22.60, 1.40, 35.10, 'F', 0.00),
(8, 'S002', 'CS612', 6, 6.50, 0.80, 2.30, 11.00, 20.60, 'F', 0.00),
(9, 'S002', 'CS613', 6, 12.40, 4.90, 23.50, 12.50, 53.30, 'C-', 1.30),
(10, 'S002', 'CS614', 6, 6.30, 3.60, 13.50, 31.00, 54.40, 'C-', 1.30),
(11, 'S002', 'CS615', 6, 7.20, 5.40, 6.80, 36.20, 55.60, 'C', 1.70),
(12, 'S002', 'CS616', 6, 12.10, 8.70, 23.30, 2.00, 46.10, 'D', 1.00),
(13, 'S003', 'SE711', 7, 6.10, 9.20, 9.40, 6.10, 30.80, 'F', 0.00),
(14, 'S003', 'SE712', 7, 7.20, 0.40, 18.70, 31.10, 57.40, 'C', 1.70),
(15, 'S003', 'SE713', 7, 13.00, 4.80, 19.50, 23.20, 60.50, 'C+', 2.00),
(16, 'S003', 'SE714', 7, 14.80, 5.30, 17.40, 44.20, 81.70, 'A-', 3.30),
(17, 'S003', 'SE715', 7, 5.00, 0.20, 2.60, 23.10, 30.90, 'F', 0.00),
(18, 'S003', 'SE716', 7, 14.90, 5.70, 21.60, 31.10, 73.30, 'B', 2.70),
(19, 'S004', 'EE711', 7, 7.80, 7.20, 1.10, 3.50, 19.60, 'F', 0.00),
(20, 'S004', 'EE712', 7, 3.20, 8.50, 15.50, 27.60, 54.80, 'C-', 1.30),
(21, 'S004', 'EE713', 7, 13.40, 8.30, 11.10, 36.90, 69.70, 'B-', 2.30),
(22, 'S004', 'EE714', 7, 5.40, 5.90, 21.10, 23.60, 56.00, 'C', 1.70),
(23, 'S004', 'EE715', 7, 12.40, 7.10, 1.50, 8.90, 29.90, 'F', 0.00),
(24, 'S004', 'EE716', 7, 10.60, 0.00, 22.50, 24.80, 57.90, 'C', 1.70),
(25, 'S005', 'CS611', 6, 11.60, 3.80, 14.10, 34.50, 64.00, 'C+', 2.00),
(26, 'S005', 'CS612', 6, 11.30, 7.10, 7.10, 14.50, 40.00, 'F', 0.00),
(27, 'S005', 'CS613', 6, 9.00, 1.30, 21.00, 40.80, 72.10, 'B', 2.70),
(28, 'S005', 'CS614', 6, 8.40, 3.40, 0.60, 5.00, 17.40, 'F', 0.00),
(29, 'S005', 'CS615', 6, 6.40, 8.40, 22.90, 3.30, 41.00, 'F', 0.00),
(30, 'S005', 'CS616', 6, 8.70, 6.90, 18.10, 27.50, 61.20, 'C+', 2.00),
(31, 'S006', 'BBA411', 4, 8.60, 2.20, 9.10, 8.40, 28.30, 'F', 0.00),
(32, 'S006', 'BBA412', 4, 11.20, 2.30, 22.60, 41.80, 77.90, 'B+', 3.00),
(33, 'S006', 'BBA413', 4, 7.00, 8.30, 18.70, 12.90, 46.90, 'D', 1.00),
(34, 'S006', 'BBA414', 4, 0.50, 4.10, 23.30, 21.90, 49.80, 'D', 1.00),
(35, 'S006', 'BBA415', 4, 5.90, 6.50, 2.00, 22.30, 36.70, 'F', 0.00),
(36, 'S006', 'BBA416', 4, 14.80, 5.90, 24.70, 9.10, 54.50, 'C-', 1.30),
(37, 'S007', 'SE611', 6, 14.10, 1.60, 24.80, 23.00, 63.50, 'C+', 2.00),
(38, 'S007', 'SE612', 6, 5.00, 2.80, 10.30, 10.70, 28.80, 'F', 0.00),
(39, 'S007', 'SE613', 6, 12.50, 5.20, 2.50, 47.10, 67.30, 'B-', 2.30),
(40, 'S007', 'SE614', 6, 6.10, 2.20, 22.20, 38.90, 69.40, 'B-', 2.30),
(41, 'S007', 'SE615', 6, 3.20, 7.50, 2.30, 11.00, 24.00, 'F', 0.00),
(42, 'S007', 'SE616', 6, 12.30, 4.40, 18.70, 20.70, 56.10, 'C', 1.70),
(43, 'S008', 'CS811', 8, 12.40, 9.10, 0.90, 23.40, 45.80, 'D', 1.00),
(44, 'S008', 'CS812', 8, 3.40, 7.40, 24.90, 38.90, 74.60, 'B', 2.70),
(45, 'S008', 'CS813', 8, 13.50, 1.80, 4.20, 16.10, 35.60, 'F', 0.00),
(46, 'S008', 'CS814', 8, 1.50, 5.40, 9.90, 18.00, 34.80, 'F', 0.00),
(47, 'S008', 'CS815', 8, 9.20, 9.80, 1.40, 17.00, 37.40, 'F', 0.00),
(48, 'S008', 'CS816', 8, 8.00, 6.60, 17.30, 24.30, 56.20, 'C', 1.70),
(49, 'S009', 'CS311', 3, 5.30, 2.90, 10.50, 11.00, 29.70, 'F', 0.00),
(50, 'S009', 'CS312', 3, 12.60, 5.40, 4.40, 12.70, 35.10, 'F', 0.00),
(51, 'S009', 'CS313', 3, 11.20, 9.70, 15.20, 6.20, 42.30, 'F', 0.00),
(52, 'S009', 'CS314', 3, 12.00, 6.40, 19.80, 1.80, 40.00, 'F', 0.00),
(53, 'S009', 'CS315', 3, 12.10, 9.40, 6.30, 22.60, 50.40, 'C-', 1.30),
(54, 'S009', 'CS316', 3, 7.50, 1.50, 6.20, 39.40, 54.60, 'C-', 1.30),
(55, 'S010', 'BBA411', 4, 3.00, 6.20, 13.20, 38.70, 61.10, 'C+', 2.00),
(56, 'S010', 'BBA412', 4, 4.20, 0.70, 12.80, 17.50, 35.20, 'F', 0.00),
(57, 'S010', 'BBA413', 4, 3.30, 0.30, 12.90, 24.40, 40.90, 'F', 0.00),
(58, 'S010', 'BBA414', 4, 13.30, 9.70, 5.10, 5.30, 33.40, 'F', 0.00),
(59, 'S010', 'BBA415', 4, 13.70, 2.60, 13.50, 46.80, 76.60, 'B+', 3.00),
(60, 'S010', 'BBA416', 4, 0.80, 4.70, 5.00, 28.80, 39.30, 'F', 0.00),
(61, 'S011', 'EE511', 5, 4.20, 6.60, 12.00, 20.80, 43.60, 'F', 0.00),
(62, 'S011', 'EE512', 5, 9.60, 9.40, 19.90, 7.40, 46.30, 'D', 1.00),
(63, 'S011', 'EE513', 5, 5.30, 3.30, 15.10, 0.50, 24.20, 'F', 0.00),
(64, 'S011', 'EE514', 5, 3.70, 1.90, 5.40, 25.90, 36.90, 'F', 0.00),
(65, 'S011', 'EE515', 5, 14.00, 1.30, 20.70, 37.90, 73.90, 'B', 2.70),
(66, 'S011', 'EE516', 5, 4.50, 2.40, 7.80, 41.50, 56.20, 'C', 1.70),
(67, 'S012', 'SE511', 5, 3.20, 5.80, 6.60, 28.50, 44.10, 'F', 0.00),
(68, 'S012', 'SE512', 5, 1.00, 6.10, 21.70, 25.10, 53.90, 'C-', 1.30),
(69, 'S012', 'SE513', 5, 13.60, 0.40, 11.70, 11.10, 36.80, 'F', 0.00),
(70, 'S012', 'SE514', 5, 10.60, 8.70, 5.50, 24.60, 49.40, 'D', 1.00),
(71, 'S012', 'SE515', 5, 12.00, 5.30, 6.40, 34.20, 57.90, 'C', 1.70),
(72, 'S012', 'SE516', 5, 9.70, 1.90, 0.40, 25.50, 37.50, 'F', 0.00),
(73, 'S013', 'CS611', 6, 7.40, 9.50, 6.10, 19.30, 42.30, 'F', 0.00),
(74, 'S013', 'CS612', 6, 3.00, 8.30, 14.30, 17.80, 43.40, 'F', 0.00),
(75, 'S013', 'CS613', 6, 1.00, 2.70, 3.70, 46.80, 54.20, 'C-', 1.30),
(76, 'S013', 'CS614', 6, 3.50, 3.60, 2.80, 23.20, 33.10, 'F', 0.00),
(77, 'S013', 'CS615', 6, 14.80, 5.30, 17.80, 48.20, 86.10, 'A', 3.70),
(78, 'S013', 'CS616', 6, 10.20, 5.20, 14.10, 12.70, 42.20, 'F', 0.00),
(79, 'S014', 'CE811', 8, 8.60, 1.20, 21.90, 0.40, 32.10, 'F', 0.00),
(80, 'S014', 'CE812', 8, 6.30, 0.60, 1.80, 7.80, 16.50, 'F', 0.00),
(81, 'S014', 'CE813', 8, 8.60, 4.00, 7.00, 9.60, 29.20, 'F', 0.00),
(82, 'S014', 'CE814', 8, 1.90, 0.60, 22.90, 20.10, 45.50, 'D', 1.00),
(83, 'S014', 'CE815', 8, 4.00, 1.10, 18.90, 22.60, 46.60, 'D', 1.00),
(84, 'S014', 'CE816', 8, 14.80, 5.90, 24.60, 7.80, 53.10, 'C-', 1.30),
(85, 'S015', 'ME411', 4, 12.40, 6.70, 21.20, 11.70, 52.00, 'C-', 1.30),
(86, 'S015', 'ME412', 4, 9.50, 4.70, 11.20, 41.80, 67.20, 'B-', 2.30),
(87, 'S015', 'ME413', 4, 12.40, 6.40, 17.80, 31.60, 68.20, 'B-', 2.30),
(88, 'S015', 'ME414', 4, 0.50, 2.60, 5.40, 14.60, 23.10, 'F', 0.00),
(89, 'S015', 'ME415', 4, 12.20, 1.90, 12.80, 49.70, 76.60, 'B+', 3.00),
(90, 'S015', 'ME416', 4, 6.50, 1.80, 14.60, 19.90, 42.80, 'F', 0.00),
(91, 'S016', 'CS211', 2, 3.50, 9.60, 3.20, 37.70, 54.00, 'C-', 1.30),
(92, 'S016', 'CS212', 2, 5.80, 6.60, 3.40, 35.00, 50.80, 'C-', 1.30),
(93, 'S016', 'CS213', 2, 1.40, 3.80, 15.20, 45.50, 65.90, 'B-', 2.30),
(94, 'S016', 'CS214', 2, 10.80, 8.60, 3.90, 9.50, 32.80, 'F', 0.00),
(95, 'S016', 'CS215', 2, 7.30, 8.60, 21.20, 32.80, 69.90, 'B-', 2.30),
(96, 'S016', 'CS216', 2, 11.10, 7.30, 10.50, 45.60, 74.50, 'B', 2.70),
(97, 'S017', 'CS111', 1, 4.50, 7.80, 24.60, 28.90, 65.80, 'B-', 2.30),
(98, 'S017', 'CS112', 1, 14.10, 9.70, 0.70, 11.70, 36.20, 'F', 0.00),
(99, 'S017', 'CS113', 1, 1.20, 7.10, 7.80, 21.20, 37.30, 'F', 0.00),
(100, 'S017', 'CS114', 1, 2.80, 6.60, 18.90, 39.60, 67.90, 'B-', 2.30),
(101, 'S017', 'CS115', 1, 15.00, 7.00, 23.00, 41.00, 86.00, 'A-', 3.70),
(102, 'S017', 'CS116', 1, 11.10, 9.90, 18.80, 38.50, 78.30, 'B+', 3.00),
(103, 'S018', 'CS111', 1, 9.00, 6.90, 16.10, 7.50, 39.50, 'F', 0.00),
(104, 'S018', 'CS112', 1, 12.30, 6.40, 19.10, 44.50, 82.30, 'A-', 3.30),
(105, 'S018', 'CS113', 1, 2.40, 1.30, 4.00, 21.00, 28.70, 'F', 0.00),
(106, 'S018', 'CS114', 1, 9.20, 8.20, 6.60, 43.00, 67.00, 'B-', 2.30),
(107, 'S018', 'CS115', 1, 7.50, 9.10, 1.90, 32.20, 50.70, 'D', 1.00),
(108, 'S018', 'CS116', 1, 14.80, 0.10, 2.10, 19.50, 36.50, 'F', 0.00),
(109, 'S019', 'SE111', 1, 10.50, 3.30, 14.20, 41.60, 69.60, 'B-', 2.30),
(110, 'S019', 'SE112', 1, 6.90, 8.00, 15.70, 37.10, 67.70, 'B-', 2.30),
(111, 'S019', 'SE113', 1, 12.40, 8.90, 24.50, 11.50, 57.30, 'C', 1.70),
(112, 'S019', 'SE114', 1, 3.10, 3.40, 2.20, 21.10, 29.80, 'F', 0.00),
(113, 'S019', 'SE115', 1, 12.60, 9.40, 4.90, 7.00, 33.90, 'F', 0.00),
(114, 'S019', 'SE116', 1, 1.70, 1.50, 10.30, 30.10, 43.60, 'F', 0.00),
(115, 'S020', 'SE111', 1, 11.60, 0.70, 0.50, 45.00, 57.80, 'C', 1.70),
(116, 'S020', 'SE112', 1, 6.50, 4.70, 1.50, 43.90, 56.60, 'C', 1.70),
(117, 'S020', 'SE113', 1, 3.20, 4.20, 12.20, 8.10, 27.70, 'F', 0.00),
(118, 'S020', 'SE114', 1, 5.30, 2.70, 7.20, 32.20, 47.40, 'D', 1.00),
(119, 'S020', 'SE115', 1, 5.20, 8.10, 0.40, 32.50, 46.20, 'D', 1.00),
(120, 'S020', 'SE116', 1, 3.00, 0.50, 15.70, 0.30, 19.50, 'F', 0.00),
(121, 'S021', 'EE111', 1, 2.20, 7.10, 2.60, 20.20, 32.10, 'F', 0.00),
(122, 'S021', 'EE112', 1, 10.50, 3.00, 9.50, 0.50, 23.50, 'F', 0.00),
(123, 'S021', 'EE113', 1, 13.60, 5.20, 21.40, 36.70, 76.90, 'B+', 3.00),
(124, 'S021', 'EE114', 1, 1.50, 2.80, 3.10, 38.70, 46.10, 'D', 1.00),
(125, 'S021', 'EE115', 1, 7.50, 1.80, 9.70, 20.10, 39.10, 'F', 0.00),
(126, 'S021', 'EE116', 1, 12.80, 0.40, 16.70, 10.30, 40.20, 'F', 0.00),
(127, 'S022', 'EE111', 1, 0.50, 5.30, 14.40, 13.60, 33.80, 'F', 0.00),
(128, 'S022', 'EE112', 1, 9.50, 3.60, 22.60, 21.70, 57.40, 'C', 1.70),
(129, 'S022', 'EE113', 1, 6.90, 0.00, 15.60, 5.60, 28.10, 'F', 0.00),
(130, 'S022', 'EE114', 1, 10.40, 1.30, 13.70, 18.30, 43.70, 'F', 0.00),
(131, 'S022', 'EE115', 1, 2.80, 8.30, 15.10, 25.70, 51.90, 'C-', 1.30),
(132, 'S022', 'EE116', 1, 11.50, 2.80, 2.30, 31.10, 47.70, 'D', 1.00),
(133, 'S023', 'BBA111', 1, 12.60, 3.40, 4.10, 40.80, 60.90, 'C+', 2.00),
(134, 'S023', 'BBA112', 1, 8.70, 4.50, 13.00, 12.30, 38.50, 'F', 0.00),
(135, 'S023', 'BBA113', 1, 10.00, 6.10, 0.90, 17.90, 34.90, 'F', 0.00),
(136, 'S023', 'BBA114', 1, 10.10, 3.10, 12.60, 30.40, 56.20, 'C', 1.70),
(137, 'S023', 'BBA115', 1, 7.90, 8.00, 10.10, 31.60, 57.60, 'C', 1.70),
(138, 'S023', 'BBA116', 1, 14.30, 8.70, 12.00, 40.10, 75.10, 'B+', 3.00),
(139, 'S024', 'BBA111', 1, 8.50, 4.20, 9.70, 34.30, 56.70, 'C', 1.70),
(140, 'S024', 'BBA112', 1, 4.00, 2.90, 15.70, 14.30, 36.90, 'F', 0.00),
(141, 'S024', 'BBA113', 1, 8.20, 8.60, 17.10, 41.50, 75.40, 'B+', 3.00),
(142, 'S024', 'BBA114', 1, 1.40, 9.80, 16.00, 12.20, 39.40, 'F', 0.00),
(143, 'S024', 'BBA115', 1, 4.60, 7.80, 0.20, 34.10, 46.70, 'D', 1.00),
(144, 'S024', 'BBA116', 1, 5.80, 8.90, 7.70, 42.60, 65.00, 'B-', 2.30),
(145, 'S025', 'CE111', 1, 5.10, 1.50, 18.10, 8.90, 33.60, 'F', 0.00),
(146, 'S025', 'CE112', 1, 10.70, 0.30, 25.00, 45.60, 81.60, 'A-', 3.30),
(147, 'S025', 'CE113', 1, 8.50, 1.00, 19.60, 31.50, 60.60, 'C+', 2.00),
(148, 'S025', 'CE114', 1, 12.00, 1.00, 2.40, 9.40, 24.80, 'F', 0.00),
(149, 'S025', 'CE115', 1, 9.70, 6.80, 11.00, 8.30, 35.80, 'F', 0.00),
(150, 'S025', 'CE116', 1, 7.70, 0.70, 19.80, 37.80, 66.00, 'B-', 2.30),
(151, 'S026', 'CE111', 1, 6.10, 7.80, 16.50, 48.50, 78.90, 'B+', 3.00),
(152, 'S026', 'CE112', 1, 13.00, 4.30, 14.10, 26.20, 57.60, 'C', 1.70),
(153, 'S026', 'CE113', 1, 13.90, 0.50, 11.90, 11.30, 37.60, 'F', 0.00),
(154, 'S026', 'CE114', 1, 10.50, 8.40, 2.10, 44.90, 65.90, 'B-', 2.30),
(155, 'S026', 'CE115', 1, 3.70, 5.40, 23.50, 4.20, 36.80, 'F', 0.00),
(156, 'S026', 'CE116', 1, 9.10, 7.70, 0.80, 42.90, 60.50, 'C+', 2.00),
(157, 'S027', 'ME111', 1, 14.00, 6.00, 15.00, 40.00, 75.00, 'B', 3.00),
(158, 'S027', 'ME112', 1, 7.10, 2.10, 15.10, 20.20, 44.50, 'F', 0.00),
(159, 'S027', 'ME113', 1, 3.10, 8.30, 12.80, 3.70, 27.90, 'F', 0.00),
(160, 'S027', 'ME114', 1, 12.50, 9.60, 7.10, 27.40, 56.60, 'C', 1.70),
(161, 'S027', 'ME115', 1, 13.30, 7.80, 5.90, 42.60, 69.60, 'B-', 2.30),
(162, 'S027', 'ME116', 1, 8.20, 1.70, 5.40, 28.40, 43.70, 'F', 0.00),
(163, 'S028', 'ME111', 1, 2.80, 2.50, 16.60, 29.20, 51.10, 'D', 1.00),
(164, 'S028', 'ME112', 1, 13.80, 8.60, 13.40, 4.90, 40.70, 'F', 0.00),
(165, 'S028', 'ME113', 1, 13.20, 1.10, 22.60, 9.50, 46.40, 'D', 1.00),
(166, 'S028', 'ME114', 1, 3.60, 6.20, 9.70, 3.60, 23.10, 'F', 0.00),
(167, 'S028', 'ME115', 1, 3.00, 7.90, 9.20, 22.50, 42.60, 'F', 0.00),
(168, 'S028', 'ME116', 1, 2.30, 4.20, 15.40, 41.80, 63.70, 'C+', 2.00),
(169, 'S029', 'CS111', 1, 4.90, 1.40, 17.60, 5.30, 29.20, 'F', 0.00),
(170, 'S029', 'CS112', 1, 6.30, 7.80, 15.60, 39.50, 69.20, 'B-', 2.30),
(171, 'S029', 'CS113', 1, 1.20, 0.40, 23.90, 32.60, 58.10, 'C', 1.70),
(172, 'S029', 'CS114', 1, 5.90, 0.30, 23.50, 31.20, 60.90, 'C+', 2.00),
(173, 'S029', 'CS115', 1, 4.50, 6.20, 4.60, 3.80, 19.10, 'F', 0.00),
(174, 'S029', 'CS116', 1, 12.40, 9.00, 0.40, 19.50, 41.30, 'F', 0.00),
(175, 'S030', 'SE111', 1, 13.50, 3.40, 25.00, 48.70, 90.60, 'A+', 4.00),
(176, 'S030', 'SE112', 1, 13.10, 4.40, 14.60, 30.20, 62.30, 'C+', 2.00),
(177, 'S030', 'SE113', 1, 4.00, 5.20, 19.70, 19.10, 48.00, 'D', 1.00),
(178, 'S030', 'SE114', 1, 8.30, 6.10, 9.50, 3.80, 27.70, 'F', 0.00),
(179, 'S030', 'SE115', 1, 3.60, 9.80, 4.10, 44.50, 62.00, 'C+', 2.00),
(180, 'S030', 'SE116', 1, 14.40, 1.20, 18.00, 12.40, 46.00, 'D', 1.00),
(181, 'S031', 'EE111', 1, 1.20, 6.50, 0.50, 7.00, 15.20, 'F', 0.00),
(182, 'S031', 'EE112', 1, 9.60, 8.00, 1.30, 43.60, 62.50, 'C+', 2.00),
(183, 'S031', 'EE113', 1, 3.00, 3.90, 8.70, 28.90, 44.50, 'F', 0.00),
(184, 'S031', 'EE114', 1, 12.60, 4.50, 19.00, 21.50, 57.60, 'C', 1.70),
(185, 'S031', 'EE115', 1, 13.10, 0.80, 19.60, 34.10, 67.60, 'B-', 2.30),
(186, 'S031', 'EE116', 1, 0.80, 2.10, 22.10, 40.30, 65.30, 'B-', 2.30),
(187, 'S032', 'BBA111', 1, 5.60, 4.50, 3.00, 13.10, 26.20, 'F', 0.00),
(188, 'S032', 'BBA112', 1, 14.20, 9.50, 22.30, 31.40, 77.40, 'B+', 3.00),
(189, 'S032', 'BBA113', 1, 6.90, 4.10, 16.60, 5.00, 32.60, 'F', 0.00),
(190, 'S032', 'BBA114', 1, 7.60, 2.20, 14.70, 14.20, 38.70, 'F', 0.00),
(191, 'S032', 'BBA115', 1, 9.80, 4.30, 4.20, 28.10, 46.40, 'D', 1.00),
(192, 'S032', 'BBA116', 1, 4.60, 8.40, 7.00, 44.20, 64.20, 'C+', 2.00),
(193, 'S033', 'CE111', 1, 8.70, 2.60, 13.40, 45.90, 70.60, 'B', 2.70),
(194, 'S033', 'CE112', 1, 14.70, 1.40, 19.10, 20.30, 55.50, 'C', 1.70),
(195, 'S033', 'CE113', 1, 11.00, 4.60, 2.30, 4.10, 22.00, 'F', 0.00),
(196, 'S033', 'CE114', 1, 2.00, 4.10, 16.50, 3.00, 25.60, 'F', 0.00),
(197, 'S033', 'CE115', 1, 4.90, 4.50, 6.50, 48.20, 64.10, 'C+', 2.00),
(198, 'S033', 'CE116', 1, 0.50, 2.90, 8.30, 39.40, 51.10, 'C-', 1.30),
(199, 'S034', 'ME111', 1, 14.20, 3.80, 1.20, 5.40, 24.60, 'F', 0.00),
(200, 'S034', 'ME112', 1, 5.90, 6.30, 24.80, 3.20, 40.20, 'F', 0.00),
(201, 'S034', 'ME113', 1, 5.10, 5.10, 12.80, 2.10, 25.10, 'F', 0.00),
(202, 'S034', 'ME114', 1, 10.10, 2.30, 3.90, 3.90, 20.20, 'F', 0.00),
(203, 'S034', 'ME115', 1, 13.80, 3.60, 1.10, 6.80, 25.30, 'F', 0.00),
(204, 'S034', 'ME116', 1, 8.30, 3.50, 2.10, 18.80, 32.70, 'F', 0.00),
(205, 'S035', 'CS111', 1, 9.40, 0.10, 3.70, 35.90, 49.10, 'D', 1.00),
(206, 'S035', 'CS112', 1, 2.30, 6.10, 14.80, 6.10, 29.30, 'F', 0.00),
(207, 'S035', 'CS113', 1, 12.50, 8.10, 13.90, 17.50, 52.00, 'C-', 1.30),
(208, 'S035', 'CS114', 1, 1.20, 3.40, 11.80, 16.50, 32.90, 'F', 0.00),
(209, 'S035', 'CS115', 1, 3.60, 2.10, 8.40, 2.70, 16.80, 'F', 0.00),
(210, 'S035', 'CS116', 1, 3.80, 1.10, 19.60, 29.60, 54.10, 'C-', 1.30),
(211, 'S036', 'SE111', 1, 9.10, 2.60, 12.00, 31.10, 54.80, 'C-', 1.30),
(212, 'S036', 'SE112', 1, 10.00, 4.80, 9.30, 21.90, 46.00, 'D', 1.00),
(213, 'S036', 'SE113', 1, 1.00, 0.40, 24.30, 37.60, 63.30, 'C+', 2.00),
(214, 'S036', 'SE114', 1, 12.70, 9.60, 7.20, 27.00, 56.50, 'C', 1.70),
(215, 'S036', 'SE115', 1, 12.60, 5.90, 10.90, 20.30, 49.70, 'D', 1.00),
(216, 'S036', 'SE116', 1, 10.80, 3.90, 19.80, 39.50, 74.00, 'B', 2.70);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `StudentID` varchar(20) NOT NULL,
  `UserID` varchar(20) NOT NULL,
  `DepartmentID` varchar(10) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `FatherName` varchar(100) DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `Gender` enum('Male','Female','Other') DEFAULT NULL,
  `ContactInfo` varchar(15) DEFAULT NULL,
  `AdmissionDate` date DEFAULT curdate(),
  `Semester` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`StudentID`, `UserID`, `DepartmentID`, `Name`, `FatherName`, `DOB`, `Gender`, `ContactInfo`, `AdmissionDate`, `Semester`) VALUES
('S001', 'S001', 'CS', 'Ali Ahmed', 'Ahmed hussain', '2003-05-15', 'Male', '03331112223', '2021-09-01', 6),
('S002', 'S002', 'CS', 'Ayesha Khan', 'Sarfraz ali', '2004-02-28', 'Female', '03213334445', '2021-09-01', 6),
('S003', 'S003', 'SE', 'Muhammad Usman', 'Abid ali', '2003-08-10', 'Male', '03005556667', '2021-09-01', 7),
('S004', 'S004', 'EE', 'Fatima Zahra', 'Mirza Maroof', '2004-11-20', 'Female', '03347778889', '2022-02-15', 7),
('S005', 'S005', 'CS', 'Hassan Raza', 'Faisal Raza', '2003-07-05', 'Male', '03129990001', '2021-09-01', 6),
('S006', 'S006', 'BBA', 'Zainab Ali', 'Tayyab Akram', '2004-03-18', 'Female', '03352223334', '2022-09-01', 4),
('S007', 'S007', 'SE', 'Abdullah Khalid', 'Khalid Ali', '2003-12-01', 'Male', '03084445556', '2021-09-01', 6),
('S008', 'S008', 'CS', 'Sara Ahmed', '', '0000-00-00', '', '03226667778', '0000-00-00', 8),
('S009', 'S009', 'CS', 'Omer Farooq', 'Nazam Farooq', '2003-09-12', 'Male', '03118889990', '2021-09-01', 3),
('S010', 'S010', 'BBA', 'Laiba Bashir', 'Bashir Ahmed', '2004-01-30', 'Female', '03365556677', '2022-09-01', 4),
('S011', 'S011', 'EE', 'Bilal Siddiqui', 'Hassan Siddiqui', '2003-04-22', 'Male', '03091112233', '2021-09-01', 5),
('S012', 'S012', 'SE', 'Mahnoor Akram', 'Akram Raza', '2004-10-05', 'Female', '03234445566', '2022-02-15', 5),
('S013', 'S013', 'CS', 'Talha Malik', 'Malik hussian', '2003-11-18', 'Male', '03147778899', '2021-09-01', 6),
('S014', 'S014', 'CE', 'Rimsha Nadeem', 'Nadeem Shiekh', '2004-07-08', 'Female', '03329990011', '2022-09-01', 8),
('S015', 'S015', 'ME', 'Danish Iqbal', 'Iqbal shah', '2003-03-25', 'Male', '03072223344', '2021-09-01', 4),
('S016', 'S016', 'CS', 'Atiqa sultana', 'Mudassar Ali', NULL, NULL, NULL, '2025-12-07', 2),
('S017', 'S017', 'CS', 'Ahmed Raza', '', '0000-00-00', '', '03341234567', '0000-00-00', 1),
('S018', 'S018', 'CS', 'Sana Fatima', '', '0000-00-00', '', '03151234567', '0000-00-00', 1),
('S019', 'S019', 'SE', 'Usama Khan', '', '0000-00-00', '', '03061234567', '0000-00-00', 1),
('S020', 'S020', 'SE', 'Hira Noor', '', '0000-00-00', '', '03271234567', '0000-00-00', 1),
('S021', 'S021', 'EE', 'Farhan Ali', '', '0000-00-00', '', '03481234567', '0000-00-00', 1),
('S022', 'S022', 'EE', 'Amina Siddiqui', '', '0000-00-00', '', '03391234567', '0000-00-00', 1),
('S023', 'S023', 'BBA', 'Zeeshan Akhtar', '', '0000-00-00', '', '03101234567', '0000-00-00', 1),
('S024', 'S024', 'BBA', 'Maryam Iqbal', '', '0000-00-00', '', '03011234567', '0000-00-00', 1),
('S025', 'S025', 'CE', 'Hamza Sheikh', 'Sheikh Javed', '2004-09-25', 'Male', '03221234567', '2022-09-01', 1),
('S026', 'S026', 'CE', 'Nida Aslam', '', '0000-00-00', '', '03431234567', '0000-00-00', 1),
('S027', 'S027', 'ME', 'Bilal Mehmood', '', '0000-00-00', '', '03351234567', '0000-00-00', 1),
('S028', 'S028', 'ME', 'Saba Yousaf', '', '0000-00-00', '', '03161234567', '0000-00-00', 1),
('S029', 'S029', 'CS', 'Ibrahim Malik', '', '0000-00-00', '', '03071234567', '0000-00-00', 1),
('S030', 'S030', 'SE', 'Fiza Khan', '', '0000-00-00', '', '03281234567', '0000-00-00', 1),
('S031', 'S031', 'EE', 'Omar Farooq', '', '0000-00-00', '', '03491234567', '0000-00-00', 1),
('S032', 'S032', 'BBA', 'Rida Hussain', '', '0000-00-00', '', '03301234567', '0000-00-00', 1),
('S033', 'S033', 'CE', 'Taimur Javed', '', '0000-00-00', '', '03111234567', '0000-00-00', 1),
('S034', 'S034', 'ME', 'Kainat Shah', '', '0000-00-00', '', '03021234567', '0000-00-00', 1),
('S035', 'S035', 'CS', 'Yusra Butt', '', '0000-00-00', '', '03231234567', '0000-00-00', 1),
('S036', 'S036', 'SE', 'Saad Ahmed', '', '0000-00-00', '', '03441234567', '0000-00-00', 1),
('S038', 'S038', 'CS', 'Amna baig', 'Mirza', '0000-00-00', '', '', '0000-00-00', 3),
('S039', 'S039', 'CS', 'Arooj', 'HANIF', '2005-03-12', 'Female', '089768909', '2025-12-24', 3);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` varchar(20) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `Role` enum('Student','Faculty','Admin') NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`, `Role`, `Email`, `Status`) VALUES
('ADMIN001', 'admin', 'Admin@2025', 'Admin', 'admin@pakuni.edu.pk', 'Active'),
('F001', 'ahmed.khan', 'Pass123', 'Faculty', 'ahmed.khan@pakuni.edu.pk', 'Active'),
('F002', 'fatima.sheikh', 'Pass123', 'Faculty', 'fatima.sheikh@pakuni.edu.pk', 'Active'),
('F003', 'salman.butt', 'Pass123', 'Faculty', 'salman.butt@pakuni.edu.pk', 'Active'),
('F004', 'sana.malik', 'Pass123', 'Faculty', 'sana.malik@pakuni.edu.pk', 'Active'),
('F005', 'bilal.hussain', 'Pass123', 'Faculty', 'bilal.hussain@pakuni.edu.pk', 'Active'),
('F006', 'imran.ali', 'Pass123', 'Faculty', 'imran.ali@pakuni.edu.pk', 'Active'),
('F007', 'aisha.rehman', 'Pass123', 'Faculty', 'aisha.rehman@pakuni.edu.pk', 'Active'),
('F008', 'kashif.ahmed', 'Pass123', 'Faculty', 'kashif.ahmed@pakuni.edu.pk', 'Active'),
('F009', 'nadeem.akhtar', 'Pass123', 'Faculty', 'nadeem.akhtar@pakuni.edu.pk', 'Active'),
('F010', 'saba.khan', 'Pass123', 'Faculty', 'saba.khan@pakuni.edu.pk', 'Active'),
('F011', 'farhan.abbas', 'Pass123', 'Faculty', 'farhan.abbas@pakuni.edu.pk', 'Active'),
('F012', 'hina.aslam', 'Pass123', 'Faculty', 'hina.aslam@pakuni.edu.pk', 'Active'),
('F013', 'F013', 'faculty123', 'Faculty', 'rizwan.ahmed@cs.university.edu', 'Active'),
('F014', 'F014', 'faculty123', 'Faculty', 'sana.khan@cs.university.edu', 'Active'),
('F015', 'F015', 'faculty123', 'Faculty', 'farhan.ali@cs.university.edu', 'Active'),
('F016', 'F016', 'faculty123', 'Faculty', 'ayesha.malik@cs.university.edu', 'Active'),
('F017', 'F017', 'faculty123', 'Faculty', 'usman.ahmed@se.university.edu', 'Active'),
('F018', 'F018', 'faculty123', 'Faculty', 'zainab.hassan@se.university.edu', 'Active'),
('F019', 'F019', 'faculty123', 'Faculty', 'bilal.khan@se.university.edu', 'Active'),
('F020', 'F020', 'faculty123', 'Faculty', 'hina.farooq@se.university.edu', 'Active'),
('F021', 'F021', 'faculty123', 'Faculty', 'raheel.abbas@ee.university.edu', 'Active'),
('F022', 'F022', 'faculty123', 'Faculty', 'saba.rizvi@ee.university.edu', 'Active'),
('F023', 'F023', 'faculty123', 'Faculty', 'kashif.javed@ee.university.edu', 'Active'),
('F024', 'F024', 'faculty123', 'Faculty', 'nida.akram@ee.university.edu', 'Active'),
('F025', 'F025', 'faculty123', 'Faculty', 'imran.chaudhry@bba.university.edu', 'Active'),
('F026', 'F026', 'faculty123', 'Faculty', 'mehwish.ansari@bba.university.edu', 'Active'),
('F027', 'F027', 'faculty123', 'Faculty', 'waqas.ahmed@bba.university.edu', 'Active'),
('F028', 'F028', 'faculty123', 'Faculty', 'rubina.khan@bba.university.edu', 'Active'),
('F029', 'F029', 'faculty123', 'Faculty', 'asif.rehman@ce.university.edu', 'Active'),
('F030', 'F030', 'faculty123', 'Faculty', 'sumaira.bibi@ce.university.edu', 'Active'),
('F031', 'F031', 'faculty123', 'Faculty', 'nadeem.akhtar@ce.university.edu', 'Active'),
('F032', 'F032', 'faculty123', 'Faculty', 'saima.rafique@ce.university.edu', 'Active'),
('F033', 'F033', 'faculty123', 'Faculty', 'faisal.jamil@me.university.edu', 'Active'),
('F034', 'F034', 'faculty123', 'Faculty', 'bushra.saeed@me.university.edu', 'Active'),
('F035', 'F035', 'faculty123', 'Faculty', 'talha.qadir@me.university.edu', 'Active'),
('F036', 'F036', 'faculty123', 'Faculty', 'humaira.nasir@me.university.edu', 'Active'),
('S001', 'ali.ahmed', 'Pass123', 'Student', 'ali.ahmed@stu.pakuni.edu.pk', 'Active'),
('S002', 'ayesha.khan', 'Pass123', 'Student', 'ayesha.khan@stu.pakuni.edu.pk', 'Active'),
('S003', 'usman.ali', 'Pass123', 'Student', 'usman.ali@stu.pakuni.edu.pk', 'Active'),
('S004', 'fatima.zahra', 'Pass123', 'Student', 'fatima.zahra@stu.pakuni.edu.pk', 'Active'),
('S005', 'hassan.raza', 'Pass123', 'Student', 'hassan.raza@stu.pakuni.edu.pk', 'Active'),
('S006', 'zainab.ali', 'Pass123', 'Student', 'zainab.ali@stu.pakuni.edu.pk', 'Active'),
('S007', 'abdullah.khalid', 'Pass123', 'Student', 'abdullah.khalid@stu.pakuni.edu.pk', 'Active'),
('S008', 'sara.ahmed', 'Pass123', 'Student', 'sara.ahmed@stu.pakuni.edu.pk', 'Active'),
('S009', 'omer.farooq', 'Pass123', 'Student', 'omer.farooq@stu.pakuni.edu.pk', 'Active'),
('S010', 'laiba.bashir', 'Pass123', 'Student', 'laiba.bashir@stu.pakuni.edu.pk', 'Active'),
('S011', 'bilal.siddiqui', 'Pass123', 'Student', 'bilal.siddiqui@stu.pakuni.edu.pk', 'Active'),
('S012', 'mahnoor.akram', 'Pass123', 'Student', 'mahnoor.akram@stu.pakuni.edu.pk', 'Active'),
('S013', 'talha.malik', 'Pass123', 'Student', 'talha.malik@stu.pakuni.edu.pk', 'Active'),
('S014', 'rimsha.nadeem', 'Pass123', 'Student', 'rimsha.nadeem@stu.pakuni.edu.pk', 'Active'),
('S015', 'danish.iqbal', 'Pass123', 'Student', 'danish.iqbal@stu.pakuni.edu.pk', 'Active'),
('S016', 'S016', 'Atiqa sultana', 'Student', 'Atiqa@gmail.com', 'Active'),
('S017', 'S017', 'password123', 'Student', 'ahmed@studentuniversity.edu.pk', 'Active'),
('S018', 'S018', 'password123', 'Student', 'sana@studentuniversity.edu.pk', 'Active'),
('S019', 'S019', 'password123', 'Student', 'usama@studentuniversity.edu.pk', 'Active'),
('S020', 'S020', 'password123', 'Student', 'hira@studentuniversity.edu.pk', 'Active'),
('S021', 'S021', 'password123', 'Student', 'farhan@studentuniversity.edu.pk', 'Active'),
('S022', 'S022', 'password123', 'Student', 'amna@studentuniversity.edu.pk', 'Active'),
('S023', 'S023', 'password123', 'Student', 'zeeshan@studentuniversity.edu.pk', 'Active'),
('S024', 'S024', 'password123', 'Student', 'maryam@studentuniversity.edu.pk', 'Active'),
('S025', 'S025', 'password123', 'Student', 's025@studentuniversity.edu.pk', 'Active'),
('S026', 'S026', 'password123', 'Student', 'lamoa@studentuniversity.edu.pk', 'Active'),
('S027', 'S027', 'password123', 'Student', 'bilal@studentuniversity.edu.pk', 'Active'),
('S028', 'S028', 'password123', 'Student', 'saba@studentuniversity.edu.pk', 'Active'),
('S029', 'S029', 'password123', 'Student', 'ibrahim@studentuniversity.edu.pk', 'Active'),
('S030', 'S030', 'password123', 'Student', 'hah@studentuniversity.edu.pk', 'Active'),
('S031', 'S031', 'password123', 'Student', 'omar@studentuniversity.edu.pk', 'Active'),
('S032', 'S032', 'password123', 'Student', 'rida@studentuniversity.edu.pk', 'Active'),
('S033', 'S033', 'password123', 'Student', 'taimur@studentuniversity.edu.pk', 'Active'),
('S034', 'S034', 'password123', 'Student', 'kainat@studentuniversity.edu.pk', 'Active'),
('S035', 'S035', 'password123', 'Student', 'yusra@studentuniversity.edu.pk', 'Active'),
('S036', 'S036', 'password123', 'Student', 'saad@studentuniversity.edu.pk', 'Active'),
('S038', 'S038', '$2y$10$Jvh7Lg0kiggHeZTc2.aLx.CCFmDs8Npfd9Qz/VQN3okPYzNzSnwHi', 'Student', 'amna@gmail.com', 'Active'),
('S039', 'S039', '$2y$10$eAhmpXw4NZb7x8qWAz5SN.iAbjiRb3ET2nnlVed3Qbspxv5zQfaC.', 'Student', 'shamaim@gmail', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`AttendanceID`),
  ADD UNIQUE KEY `unique_student_course_date` (`StudentID`,`CourseID`,`AttendanceDate`),
  ADD KEY `CourseID` (`CourseID`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`CourseID`),
  ADD KEY `DepartmentID` (`DepartmentID`),
  ADD KEY `FacultyID` (`FacultyID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`DepartmentID`),
  ADD UNIQUE KEY `DepartmentName` (`DepartmentName`);

--
-- Indexes for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD PRIMARY KEY (`StudentID`,`CourseID`),
  ADD UNIQUE KEY `EnrollmentID` (`EnrollmentID`),
  ADD UNIQUE KEY `unique_enrollment` (`StudentID`,`CourseID`,`Semester`,`DepartmentID`),
  ADD KEY `CourseID` (`CourseID`),
  ADD KEY `fk_enrollment_department` (`DepartmentID`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`FacultyID`),
  ADD UNIQUE KEY `UserID` (`UserID`),
  ADD KEY `DepartmentID` (`DepartmentID`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`GradeID`),
  ADD UNIQUE KEY `unique_student_course` (`StudentID`,`CourseID`),
  ADD KEY `CourseID` (`CourseID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`StudentID`),
  ADD UNIQUE KEY `UserID` (`UserID`),
  ADD KEY `DepartmentID` (`DepartmentID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `AttendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4248;

--
-- AUTO_INCREMENT for table `enrollment`
--
ALTER TABLE `enrollment`
  MODIFY `EnrollmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=456;

--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `GradeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=371;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `student` (`StudentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`CourseID`) REFERENCES `course` (`CourseID`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_3` FOREIGN KEY (`StudentID`,`CourseID`) REFERENCES `enrollment` (`StudentID`, `CourseID`) ON DELETE CASCADE;

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`DepartmentID`) REFERENCES `department` (`DepartmentID`),
  ADD CONSTRAINT `course_ibfk_2` FOREIGN KEY (`FacultyID`) REFERENCES `faculty` (`FacultyID`) ON DELETE SET NULL;

--
-- Constraints for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD CONSTRAINT `enrollment_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `student` (`StudentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollment_ibfk_2` FOREIGN KEY (`CourseID`) REFERENCES `course` (`CourseID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_enrollment_department` FOREIGN KEY (`DepartmentID`) REFERENCES `department` (`DepartmentID`);

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `faculty_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `faculty_ibfk_2` FOREIGN KEY (`DepartmentID`) REFERENCES `department` (`DepartmentID`);

--
-- Constraints for table `grade`
--
ALTER TABLE `grade`
  ADD CONSTRAINT `grade_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `student` (`StudentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `grade_ibfk_2` FOREIGN KEY (`CourseID`) REFERENCES `course` (`CourseID`) ON DELETE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`DepartmentID`) REFERENCES `department` (`DepartmentID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
