<?php
require "config.php";

// Check if faculty is logged in
if(!isset($_SESSION['id']) || $_SESSION['role'] != 'Faculty') {
    header("Location: index.php");
    exit();
}

$faculty_id = $_SESSION['id'];

// Get faculty info - Email from User table, YearsOfService from Faculty table
$faculty_query = mysqli_query($conn, "SELECT f.*, u.Email FROM Faculty f JOIN `User` u ON f.UserID = u.UserID WHERE f.FacultyID='$faculty_id'");
$faculty_data = mysqli_fetch_assoc($faculty_query);
$faculty_name = $faculty_data['Name'] ?? 'Faculty Member';
$faculty_dept = $faculty_data['Department'] ?? 'Unknown Department';
$contact_info = $faculty_data['ContactInfo'] ?? 'Not provided';
$email = $faculty_data['Email'] ?? 'Not set';
$years_of_service = $faculty_data['YearsOfService'] ?? 0;

// Get faculty stats
$courses_count = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as count FROM Course WHERE FacultyID='$faculty_id'"))['count'];

$students_count = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(DISTINCT e.StudentID) as count
     FROM Enrollment e
     JOIN Course c ON e.CourseID = c.CourseID
     WHERE c.FacultyID='$faculty_id'"))['count'];

$active_tab = $_GET['tab'] ?? 'profile';

// Save Assessment/Grades - WITH GPA
if(isset($_POST['save_marks'])){
    $cid = mysqli_real_escape_string($conn, $_POST['cid']);

    foreach($_POST['quiz'] as $sid => $quiz_val){
        $sid = mysqli_real_escape_string($conn, $sid);
        $quiz = floatval($quiz_val ?? 0);
        $assignment = floatval($_POST['assignment'][$sid] ?? 0);
        $midterm = floatval($_POST['midterm'][$sid] ?? 0);
        $final = floatval($_POST['final'][$sid] ?? 0);

        $total = $quiz + $assignment + $midterm + $final;

        if ($total >= 90) {
            $grade = 'A';
            $gpa = 4.00;
        } elseif ($total >= 85) {
            $grade = 'A-';
            $gpa = 3.70;
        } elseif ($total >= 80) {
            $grade = 'B+';
            $gpa = 3.30;
        } elseif ($total >= 75) {
            $grade = 'B';
            $gpa = 3.00;
        } elseif ($total >= 70) {
            $grade = 'B-';
            $gpa = 2.70;
        } elseif ($total >= 65) {
            $grade = 'C+';
            $gpa = 2.30;
        } elseif ($total >= 60) {
            $grade = 'C';
            $gpa = 2.00;
        } elseif ($total >= 55) {
            $grade = 'C-';
            $gpa = 1.70;
        } elseif ($total >= 50) {
            $grade = 'D';
            $gpa = 1.00;
        } else {
            $grade = 'F';
            $gpa = 0.00;
        }

        $query = "INSERT INTO grade 
                  (StudentID, CourseID, Quiz, Assignment, Midterm, FinalExam, TotalPercentage, LetterGrade, GPA)
                  VALUES ('$sid', '$cid', '$quiz', '$assignment', '$midterm', '$final', '$total', '$grade', '$gpa')
                  ON DUPLICATE KEY UPDATE
                  Quiz='$quiz', Assignment='$assignment', Midterm='$midterm', FinalExam='$final',
                  TotalPercentage='$total', LetterGrade='$grade', GPA='$gpa'";

        mysqli_query($conn, $query);
    }
    header("Location: faculty.php?tab=grades");
    exit();
}

// Save Attendance
if(isset($_POST['save_attendance'])){
    $cid = mysqli_real_escape_string($conn, $_POST['cid']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    
    if(isset($_POST['attendance']) && is_array($_POST['attendance'])){
        foreach($_POST['attendance'] as $sid => $status){
            $sid = mysqli_real_escape_string($conn, $sid);
            $status = strtoupper(trim($status));
            if(in_array($status, ['P','A','L','E',''])){
                $full_status = '';
                switch($status) {
                    case 'P': $full_status = 'Present'; break;
                    case 'A': $full_status = 'Absent'; break;
                    case 'L': $full_status = 'Late'; break;
                    case 'E': $full_status = 'Excused'; break;
                    default: $full_status = 'Present';
                }
                mysqli_query($conn, "INSERT INTO Attendance (StudentID, CourseID, AttendanceDate, Status) 
                                    VALUES ('$sid', '$cid', '$date', '$full_status')
                                    ON DUPLICATE KEY UPDATE Status = '$full_status'");
            }
        }
    }
    header("Location: faculty.php?tab=attendance&course=$cid&date=$date");
    exit();
}

// Handle "Add Date" - Create dummy record
if(isset($_GET['add_date']) && isset($_GET['course']) && isset($_GET['date'])) {
    $cid = mysqli_real_escape_string($conn, $_GET['course']);
    $date = mysqli_real_escape_string($conn, $_GET['date']);
    
    $check = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM Attendance WHERE CourseID='$cid' AND AttendanceDate='$date'");
    $row = mysqli_fetch_assoc($check);
    
    if($row['cnt'] == 0) {
        $dummy = mysqli_query($conn, "SELECT StudentID FROM Enrollment WHERE CourseID='$cid' LIMIT 1");
        if(mysqli_num_rows($dummy) > 0) {
            $stu = mysqli_fetch_assoc($dummy);
            $sid = $stu['StudentID'];
            mysqli_query($conn, "INSERT INTO Attendance (StudentID, CourseID, AttendanceDate, Status) VALUES ('$sid', '$cid', '$date', 'Present')");
        }
    }
    
    header("Location: faculty.php?tab=attendance&course=$cid&date=$date");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f9fafb; }
        .sidebar { background: linear-gradient(180deg, #735da5 0%, #5a4a84 100%); }
        .date-badge {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 9999px;
            padding: 8px 16px;
            font-size: 13px;
            color: #4b5563;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .date-badge:hover { background: #e5e7eb; }
        .date-badge.active { background: #e9d5ff; border-color: #c4b5fd; color: #9333ea; }
        .status-p { color: #10b981; font-weight: 700; font-size: 1.1rem; }
        .status-a { color: #ef4444; font-weight: 700; font-size: 1.1rem; }
        .status-l { color: #f59e0b; font-weight: 700; font-size: 1.1rem; }
        .status-e { color: #3b82f6; font-weight: 700; font-size: 1.1rem; }
        .attendance-select {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 6px 10px;
            width: 80px;
            font-size: 14px;
            background: white;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%236b7280' viewBox='0 0 20 20'%3E%3Cpath d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 12px;
            padding-right: 30px;
        }
        .attendance-select:focus {
            outline: none;
            border-color: #735da5;
            box-shadow: 0 0 0 3px rgba(115, 93, 165, 0.1);
        }
        .mark-input {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 6px 10px;
            width: 70px;
            font-size: 14px;
            text-align: center;
        }
        .mark-input:focus {
            outline: none;
            border-color: #735da5;
            box-shadow: 0 0 0 3px rgba(115, 93, 165, 0.1);
        }
        .profile-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
        }
    </style>
</head>
<body class="min-h-screen text-gray-800">
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="sidebar w-64 min-h-screen p-8 text-white flex flex-col">
        <div class="flex-1">
            <div class="flex items-center space-x-4 mb-12">
                <i class="fas fa-chalkboard-teacher text-3xl"></i>
                <div>
                    <h1 class="text-xl font-bold">Faculty Portal</h1>
                    <p class="text-sm opacity-90"><?= $faculty_id ?></p>
                </div>
            </div>
            
            <div class="mb-12">
                <div class="text-2xl font-bold mb-2"><?= htmlspecialchars($faculty_name) ?></div>
                <p class="text-sm opacity-80">Welcome back</p>
            </div>

            <nav class="space-y-2">
                <a href="faculty.php?tab=profile" class="flex items-center space-x-3 p-3 rounded-lg <?= $active_tab=='profile' ? 'bg-white bg-opacity-10' : 'hover:bg-white hover:bg-opacity-5' ?>">
                    <i class="fas fa-user w-6"></i>
                    <span>Profile</span>
                </a>
                <a href="faculty.php?tab=grades" class="flex items-center space-x-3 p-3 rounded-lg <?= $active_tab=='grades' ? 'bg-white bg-opacity-10' : 'hover:bg-white hover:bg-opacity-5' ?>">
                    <i class="fas fa-chart-bar w-6"></i>
                    <span>Assessment</span>
                </a>
                <a href="faculty.php?tab=attendance" class="flex items-center space-x-3 p-3 rounded-lg <?= $active_tab=='attendance' ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-5' ?>">
                    <i class="fas fa-calendar-check w-6"></i>
                    <span>Attendance</span>
                </a>
            </nav>
        </div>

        <div class="mt-auto pt-8">
            <a href="logout.php" class="flex items-center justify-center space-x-2 bg-white bg-opacity-10 hover:bg-opacity-20 p-3 rounded-lg transition-colors">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-10">
        <div class="max-w-7xl mx-auto">
            <!-- Profile Tab -->
            <?php if($active_tab == 'profile'): ?>
            <div class="space-y-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Faculty Profile</h1>
                        <p class="text-gray-600 mt-1">Personal information and academic overview</p>
                    </div>
                    <div class="flex items-center space-x-2 text-sm">
                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full">Faculty</span>
                        <span class="text-gray-500">ID: <?= $faculty_id ?></span>
                    </div>
                </div>

                <div class="profile-card">
                    <div class="flex items-start space-x-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-4xl text-purple-600"></i>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6"><?= htmlspecialchars($faculty_name) ?></h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Faculty ID</p>
                                        <p class="text-lg text-gray-900 mt-1"><?= $faculty_id ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Department</p>
                                        <p class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($faculty_dept) ?></p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Email</p>
                                        <p class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($email) ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Phone</p>
                                        <p class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($contact_info) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <div class="stat-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Courses Assigned</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2"><?= $courses_count ?></p>
                            </div>
                            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Students</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2"><?= $students_count ?></p>
                            </div>
                            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Years of Service</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2"><?= $years_of_service ?></p>
                            </div>
                            <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-award text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-card mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Academic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Current Semester</p>
                            <p class="text-gray-900 mt-1">Fall 2024</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Office Hours</p>
                            <p class="text-gray-900 mt-1">Mon-Wed 10:00 AM - 12:00 PM</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Office Location</p>
                            <p class="text-gray-900 mt-1">Business Building, Room 305</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Specialization</p>
                            <p class="text-gray-900 mt-1">Strategic Management & Leadership</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Assessment Tab (Grades) -->
            <?php if($active_tab == 'grades'): ?>
                <?php
                $courses = mysqli_query($conn, "SELECT CourseID, CourseName FROM Course WHERE FacultyID='$faculty_id'");
                if(mysqli_num_rows($courses) > 0):
                    while($c = mysqli_fetch_assoc($courses)):
                        $cid = $c['CourseID'];
                        $students = mysqli_query($conn, "SELECT s.StudentID, s.Name FROM Enrollment e JOIN Student s ON e.StudentID=s.StudentID WHERE e.CourseID='$cid' ORDER BY s.StudentID");
                ?>
                <div class="mb-12">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($c['CourseName']) ?></h2>
                            <p class="text-gray-600 mt-1">Assessment Marks</p>
                        </div>
                    </div>

                    <form method="post">
                        <input type="hidden" name="cid" value="<?= $cid ?>">

                        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="p-4 text-left text-sm font-medium text-gray-700">Student ID</th>
                                            <th class="p-4 text-left text-sm font-medium text-gray-700">Name</th>
                                            <th class="p-4 text-center text-sm font-medium text-gray-700">Quiz<br><span class="text-xs font-normal">(Out of 15)</span></th>
                                            <th class="p-4 text-center text-sm font-medium text-gray-700">Assignment<br><span class="text-xs font-normal">(Out of 10)</span></th>
                                            <th class="p-4 text-center text-sm font-medium text-gray-700">Midterm<br><span class="text-xs font-normal">(Out of 25)</span></th>
                                            <th class="p-4 text-center text-sm font-medium text-gray-700">Final<br><span class="text-xs font-normal">(Out of 50)</span></th>
                                            <th class="p-4 text-center text-sm font-medium text-gray-700">Total %</th>
                                            <th class="p-4 text-center text-sm font-medium text-gray-700">Grade</th>
                                            <th class="p-4 text-center text-sm font-medium text-gray-700">GPA</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <?php if(mysqli_num_rows($students) > 0): ?>
                                            <?php while($s = mysqli_fetch_assoc($students)): ?>
                                                <?php
                                                $sid = $s['StudentID'];
                                                $g = mysqli_query($conn, "SELECT Quiz, Assignment, Midterm, FinalExam, TotalPercentage, LetterGrade, GPA FROM grade WHERE StudentID='$sid' AND CourseID='$cid' LIMIT 1");
                                                $grade_data = mysqli_fetch_assoc($g);

                                                $quiz = $grade_data ? $grade_data['Quiz'] : '';
                                                $assignment = $grade_data ? $grade_data['Assignment'] : '';
                                                $midterm = $grade_data ? $grade_data['Midterm'] : '';
                                                $final = $grade_data ? $grade_data['FinalExam'] : '';
                                                $total = $grade_data ? $grade_data['TotalPercentage'] : '0';
                                                $grade = $grade_data ? $grade_data['LetterGrade'] : '-';
                                                $gpa = $grade_data ? number_format($grade_data['GPA'], 2) : '0.00';
                                                ?>
                                                <tr class="hover:bg-gray-50">
                                                    <td class="p-4 text-sm font-medium text-gray-900"><?= $sid ?></td>
                                                    <td class="p-4 text-sm font-medium text-gray-900"><?= htmlspecialchars($s['Name']) ?></td>

                                                    <td class="p-4 text-center">
                                                        <input type="number" step="0.01" min="0" max="15" name="quiz[<?= $sid ?>]" value="<?= $quiz ?>" class="mark-input" placeholder="0-15" oninput="calculateRow(this)">
                                                    </td>
                                                    <td class="p-4 text-center">
                                                        <input type="number" step="0.01" min="0" max="10" name="assignment[<?= $sid ?>]" value="<?= $assignment ?>" class="mark-input" placeholder="0-10" oninput="calculateRow(this)">
                                                    </td>
                                                    <td class="p-4 text-center">
                                                        <input type="number" step="0.01" min="0" max="25" name="midterm[<?= $sid ?>]" value="<?= $midterm ?>" class="mark-input" placeholder="0-25" oninput="calculateRow(this)">
                                                    </td>
                                                    <td class="p-4 text-center">
                                                        <input type="number" step="0.01" min="0" max="50" name="final[<?= $sid ?>]" value="<?= $final ?>" class="mark-input" placeholder="0-50" oninput="calculateRow(this)">
                                                    </td>
                                                    <td class="p-4 text-center font-medium text-blue-600 text-lg">
                                                        <span class="total-display"><?= $total ?></span>
                                                    </td>
                                                    <td class="p-4 text-center font-medium text-purple-600 text-lg">
                                                        <span class="grade-display"><?= $grade ?></span>
                                                    </td>
                                                    <td class="p-4 text-center font-medium text-green-600 text-lg">
                                                        <span class="gpa-display"><?= $gpa ?></span>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="9" class="p-8 text-center text-gray-500">No students enrolled in this course</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="border-t border-gray-200 p-6 bg-gray-50">
                                <div class="flex justify-end">
                                    <button name="save_marks" class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg font-semibold">
                                        Save Assessment Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-book-open text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900">No Courses Assigned</h3>
                    <p class="text-gray-600 mt-1">You are not assigned to any courses this semester.</p>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Attendance Tab - FULLY RESTORED AND IDENTICAL TO YOUR ORIGINAL -->
            <?php if($active_tab == 'attendance'): ?>
                <?php
                $selected_course = $_GET['course'] ?? '';
                $selected_date = $_GET['date'] ?? date('Y-m-d');
                
                // Get all courses for this faculty
                $courses = mysqli_query($conn, "SELECT CourseID, CourseName FROM Course WHERE FacultyID='$faculty_id' ORDER BY CourseName");
                
                if(mysqli_num_rows($courses) > 0):
                    // If no course is selected, pick the first one
                    if(empty($selected_course)) {
                        $first_course = mysqli_fetch_assoc($courses);
                        $selected_course = $first_course['CourseID'];
                        mysqli_data_seek($courses, 0); // Reset pointer
                    }
                    
                    // Get selected course data
                    $selected_course_data = null;
                    mysqli_data_seek($courses, 0); // Reset pointer again
                    while($c = mysqli_fetch_assoc($courses)) {
                        if($c['CourseID'] == $selected_course) {
                            $selected_course_data = $c;
                            break;
                        }
                    }
                    
                    if($selected_course_data):
                        $cid = $selected_course_data['CourseID'];
                        
                        // Get students for this course
                        $students = mysqli_query($conn, 
                            "SELECT s.StudentID, s.Name 
                             FROM Enrollment e 
                             JOIN Student s ON e.StudentID = s.StudentID 
                             WHERE e.CourseID = '$cid' 
                             ORDER BY s.Name");
                        
                        // Get distinct attendance dates for this course (recent 10)
                        $dates_result = mysqli_query($conn, 
                            "SELECT DISTINCT AttendanceDate 
                             FROM Attendance 
                             WHERE CourseID = '$cid' 
                             ORDER BY AttendanceDate DESC 
                             LIMIT 10");
                        
                        $all_dates = [];
                        while($d = mysqli_fetch_assoc($dates_result)) {
                            $all_dates[] = $d['AttendanceDate'];
                        }
                        
                        // Add selected date if not in list
                        if(!in_array($selected_date, $all_dates)) {
                            array_unshift($all_dates, $selected_date);
                        }
                        
                        // Sort dates and get last 5 (most recent first)
                        rsort($all_dates);
                        $display_dates = array_slice($all_dates, 0, 5);
                        
                        // Make sure selected date is in display dates
                        if(!in_array($selected_date, $display_dates)) {
                            $display_dates[] = $selected_date;
                            rsort($display_dates);
                            $display_dates = array_slice($display_dates, 0, 5);
                        }
                        
                        // Sort for display (oldest to newest)
                        sort($display_dates);
                ?>
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="p-8 border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($selected_course_data['CourseName']) ?></h2>
                                <p class="text-gray-600 mt-1">Attendance Records</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <!-- Course Selector -->
                                <select id="courseSelector" onchange="changeCourse()" class="border border-gray-300 rounded-lg px-5 py-3 text-base bg-white">
                                    <?php 
                                    mysqli_data_seek($courses, 0);
                                    while($course = mysqli_fetch_assoc($courses)): 
                                    ?>
                                    <option value="<?= $course['CourseID'] ?>" <?= $cid == $course['CourseID'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($course['CourseName']) ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                                
                                <!-- Date Selector -->
                                <input type="date" id="attendanceDate" value="<?= $selected_date ?>" class="border border-gray-300 rounded-lg px-5 py-3 text-base">
                                
                                <!-- Add Date Button -->
                                <button onclick="addNewDate()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center space-x-2">
                                    <i class="fas fa-plus"></i>
                                    <span>Add Date</span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Date Badges -->
                        <div class="flex flex-wrap gap-3 mb-4">
                            <?php foreach($display_dates as $date): ?>
                            <a href="faculty.php?tab=attendance&course=<?= $cid ?>&date=<?= $date ?>" 
                               class="date-badge <?= $date == $selected_date ? 'active' : '' ?>">
                                <?= date('M d', strtotime($date)) ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <form method="post" id="attendanceForm_<?= $cid ?>">
                        <input type="hidden" name="cid" value="<?= $cid ?>">
                        <input type="hidden" name="date" value="<?= $selected_date ?>">
                        
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="p-4 text-left text-sm font-medium text-gray-700">Student ID</th>
                                        <th class="p-4 text-left text-sm font-medium text-gray-700">Name</th>
                                        <?php foreach($display_dates as $date): ?>
                                        <th class="p-4 text-center text-xs font-medium text-gray-600">
                                            <?= date('M d', strtotime($date)) ?>
                                            <?php if($date == $selected_date): ?>
                                            <br><span class="text-purple-600 text-xs">(Today)</span>
                                            <?php endif; ?>
                                        </th>
                                        <?php endforeach; ?>
                                        <th class="p-4 text-center text-sm font-medium text-gray-700">Attendance</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php if(mysqli_num_rows($students) > 0): ?>
                                        <?php mysqli_data_seek($students, 0); ?>
                                        <?php while($s = mysqli_fetch_assoc($students)): ?>
                                            <?php
                                            $sid = $s['StudentID'];
                                            $total_dates = count($display_dates);
                                            $present_count = 0;
                                            ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="p-4 text-sm font-medium text-gray-900"><?= $sid ?></td>
                                                <td class="p-4 text-sm font-medium text-gray-900"><?= htmlspecialchars($s['Name']) ?></td>
                                                
                                                <?php foreach($display_dates as $date):
                                                    // Get attendance status for each date
                                                    $status_query = mysqli_query($conn, 
                                                        "SELECT Status FROM Attendance 
                                                         WHERE StudentID='$sid' AND CourseID='$cid' AND AttendanceDate='$date'");
                                                    $status_row = mysqli_fetch_assoc($status_query);
                                                    $current_status = $status_row ? $status_row['Status'] : '';
                                                    
                                                    $status_letter = '';
                                                    if($current_status) {
                                                        switch($current_status) {
                                                            case 'Present': $status_letter = 'P'; $present_count++; break;
                                                            case 'Absent': $status_letter = 'A'; break;
                                                            case 'Late': $status_letter = 'L'; $present_count++; break;
                                                            case 'Excused': $status_letter = 'E'; break;
                                                            default: $status_letter = '-';
                                                        }
                                                    } else {
                                                        $status_letter = '-';
                                                    }
                                                    
                                                    // Only show dropdown for selected date
                                                    if($date == $selected_date):
                                                ?>
                                                <td class="p-4 text-center">
                                                    <select name="attendance[<?= $sid ?>]" class="attendance-select">
                                                        <option value="">-</option>
                                                        <option value="P" <?= $status_letter == 'P' ? 'selected' : '' ?>>P</option>
                                                        <option value="A" <?= $status_letter == 'A' ? 'selected' : '' ?>>A</option>
                                                        <option value="L" <?= $status_letter == 'L' ? 'selected' : '' ?>>L</option>
                                                        <option value="E" <?= $status_letter == 'E' ? 'selected' : '' ?>>E</option>
                                                    </select>
                                                </td>
                                                <?php else: ?>
                                                <td class="p-4 text-center">
                                                    <?php if($status_letter != '-'): ?>
                                                    <span class="<?= $status_letter == 'P' ? 'status-p' : ($status_letter == 'A' ? 'status-a' : ($status_letter == 'L' ? 'status-l' : 'status-e')) ?>">
                                                        <?= $status_letter ?>
                                                    </span>
                                                    <?php else: ?>
                                                    <span class="text-gray-400">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <?php endif; ?>
                                                <?php endforeach; ?>
                                                
                                                <td class="p-4 text-center">
                                                    <?php if($total_dates > 0):
                                                        $percentage = round(($present_count / $total_dates) * 100);
                                                        $percentage_color = $percentage >= 75 ? 'text-green-600' : ($percentage >= 50 ? 'text-amber-600' : 'text-red-600');
                                                    ?>
                                                    <span class="font-bold <?= $percentage_color ?> text-xl"><?= $percentage ?>%</span>
                                                    <?php else: ?>
                                                    <span class="text-gray-400">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="<?= count($display_dates) + 3 ?>" class="p-8 text-center text-gray-500">
                                                No students enrolled in this course
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Save Button -->
                        <div class="p-6 bg-gray-50 border-t border-gray-200">
                            <div class="flex justify-end">
                                <button type="submit" name="save_attendance" 
                                        class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg font-semibold">
                                    Save Attendance
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-book-open text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900">Course Not Found</h3>
                    <p class="text-gray-600 mt-1">The selected course is not assigned to you.</p>
                </div>
                <?php endif; ?>
                <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-book-open text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900">No Courses Assigned</h3>
                    <p class="text-gray-600 mt-1">You are not assigned to any courses this semester.</p>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function changeCourse() {
    const courseSelector = document.getElementById('courseSelector');
    const selectedCourse = courseSelector.value;
    const currentUrl = new URL(window.location.href);
    
    currentUrl.searchParams.set('course', selectedCourse);
    
    const dateInput = document.getElementById('attendanceDate');
    if (dateInput && dateInput.value) {
        currentUrl.searchParams.set('date', dateInput.value);
    }
    
    currentUrl.searchParams.set('tab', 'attendance');
    
    window.location.href = currentUrl.toString();
}

function addNewDate() {
    const dateInput = document.getElementById('attendanceDate');
    const selectedDate = dateInput.value;
    const courseSelector = document.getElementById('courseSelector');
    const courseId = courseSelector?.value;
    
    if(!selectedDate || !courseId) {
        alert('Please select a date and course');
        return;
    }
    
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('add_date', 'true');
    currentUrl.searchParams.set('course', courseId);
    currentUrl.searchParams.set('date', selectedDate);
    window.location.href = currentUrl.toString();
}

function calculateRow(input) {
    const row = input.closest('tr');
    const quiz = parseFloat(row.querySelector('input[name^="quiz"]').value) || 0;
    const assignment = parseFloat(row.querySelector('input[name^="assignment"]').value) || 0;
    const midterm = parseFloat(row.querySelector('input[name^="midterm"]').value) || 0;
    const final = parseFloat(row.querySelector('input[name^="final"]').value) || 0;

    const total = quiz + assignment + midterm + final;

    const totalDisplay = row.querySelector('.total-display');
    totalDisplay.textContent = total.toFixed(2).replace(/\.00$/, '');

    let grade = '-';
    let gpa = '0.00';
    if(total > 0) {
        if(total >= 90) { grade = 'A'; gpa = '4.00'; }
        else if(total >= 85) { grade = 'A-'; gpa = '3.70'; }
        else if(total >= 80) { grade = 'B+'; gpa = '3.30'; }
        else if(total >= 75) { grade = 'B'; gpa = '3.00'; }
        else if(total >= 70) { grade = 'B-'; gpa = '2.70'; }
        else if(total >= 65) { grade = 'C+'; gpa = '2.30'; }
        else if(total >= 60) { grade = 'C'; gpa = '2.00'; }
        else if(total >= 55) { grade = 'C-'; gpa = '1.70'; }
        else if(total >= 50) { grade = 'D'; gpa = '1.00'; }
        else { grade = 'F'; gpa = '0.00'; }
    }

    const gradeDisplay = row.querySelector('.grade-display');
    gradeDisplay.textContent = grade;

    const gpaDisplay = row.querySelector('.gpa-display');
    gpaDisplay.textContent = gpa;
}
</script>
</body>
</html>