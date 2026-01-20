<?php
require "config.php";
if($_SESSION['role']!='Admin') header("Location: index.php");
// Initialize active tab first
$active_tab = $_GET['tab'] ?? 'dashboard';
// Search functionality
$search_student = $_GET['q_student'] ?? '';
$search_faculty = $_GET['q_faculty'] ?? '';
// Add Student (WITH ALL MISSING FIELDS FROM TABLE)
if(isset($_POST['add_student'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $dept = $_POST['dept'];
    $father_name = $_POST['father_name'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $admission_date = $_POST['admission_date'] ?? date('Y-m-d');
    $semester = $_POST['semester'] ?? 1;
    $courses = $_POST['courses'] ?? [];
  
    // Create user account
    mysqli_query($conn,"INSERT INTO `User` (UserID, Username, Password, Role, Email, Status)
                        VALUES ('$id', '$id', '".password_hash($id, PASSWORD_DEFAULT)."', 'Student', '$email', 'Active')");
  
    // Create student record with all fields
    mysqli_query($conn,"INSERT INTO Student (StudentID, UserID, Name, DepartmentID, FatherName, DOB, Gender, ContactInfo, AdmissionDate, Semester)
                        VALUES ('$id', '$id', '$name', '$dept', '$father_name', '$dob', '$gender', '$phone', '$admission_date', '$semester')");
  
    // Enroll in selected courses - FIXED: lowercase enrollment
    foreach($courses as $course_id) {
        mysqli_query($conn,"INSERT INTO enrollment (StudentID, CourseID, EnrollDate, Semester)
                            VALUES ('$id', '$course_id', NOW(), '$semester')");
    }
  
    header("Location: admin.php?tab=students");
    exit();
}
// Add Faculty (WITH Years of Service)
if(isset($_POST['add_faculty'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $dept = $_POST['dept'];
    $phone = $_POST['phone'] ?? '';
    $years_of_service = $_POST['years_of_service'] ?? 0;
    $faculty_courses = $_POST['faculty_courses'] ?? [];
  
    // Create user account
    mysqli_query($conn,"INSERT INTO `User` (UserID, Username, Password, Role, Email, Status)
                        VALUES ('$id', '$id', '".password_hash($id, PASSWORD_DEFAULT)."', 'Faculty', '$email', 'Active')");
  
    // Get department name
    $dept_name_result = mysqli_query($conn, "SELECT DepartmentName FROM Department WHERE DepartmentID='$dept'");
    $dept_name = mysqli_fetch_assoc($dept_name_result)['DepartmentName'];
  
    // Create faculty record with YearsOfService
    mysqli_query($conn,"INSERT INTO Faculty (FacultyID, UserID, DepartmentID, Name, Department, ContactInfo, YearsOfService)
                        VALUES ('$id', '$id', '$dept', '$name', '$dept_name', '$phone', '$years_of_service')");
  
    // Assign courses to faculty
    foreach($faculty_courses as $course_id) {
        mysqli_query($conn,"UPDATE Course SET FacultyID='$id' WHERE CourseID='$course_id'");
    }
  
    header("Location: admin.php?tab=faculty");
    exit();
}
// Update Student (with all fields)
if(isset($_POST['update_student'])){
    $id = $_POST['id'];
    $email = $_POST['email'];
    $dept = $_POST['dept'];
    $father_name = $_POST['father_name'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $phone = $_POST['phone'];
    $admission_date = $_POST['admission_date'] ?? '';
    $semester = $_POST['semester'] ?? 1;
  
    mysqli_query($conn,"UPDATE `User` SET Email='$email' WHERE UserID='$id'");
    mysqli_query($conn,"UPDATE Student SET
                        DepartmentID='$dept',
                        FatherName='$father_name',
                        DOB='$dob',
                        Gender='$gender',
                        ContactInfo='$phone',
                        AdmissionDate='$admission_date',
                        Semester='$semester'
                        WHERE StudentID='$id'");
  
    mysqli_query($conn,"UPDATE enrollment SET Semester='$semester' WHERE StudentID='$id'");
  
    header("Location: admin.php?tab=students");
    exit();
}
// Update Faculty (with YearsOfService)
if(isset($_POST['update_faculty'])){
    $id = $_POST['id'];
    $email = $_POST['email'];
    $dept = $_POST['dept'];
    $phone = $_POST['phone'];
    $years_of_service = $_POST['years_of_service'] ?? 0;
    $faculty_courses = $_POST['faculty_courses'] ?? [];
  
    mysqli_query($conn,"UPDATE `User` SET Email='$email' WHERE UserID='$id'");
    mysqli_query($conn,"UPDATE Faculty SET
                        DepartmentID='$dept',
                        ContactInfo='$phone',
                        YearsOfService='$years_of_service'
                        WHERE FacultyID='$id'");
  
    mysqli_query($conn,"UPDATE Course SET FacultyID = NULL WHERE FacultyID='$id'");
  
    foreach($faculty_courses as $course_id) {
        mysqli_query($conn,"UPDATE Course SET FacultyID='$id' WHERE CourseID='$course_id'");
    }
  
    header("Location: admin.php?tab=faculty");
    exit();
}
// Delete functions - FIXED: lowercase enrollment and grade
if(isset($_GET['del_student'])){
    $id = $_GET['del_student'];
    mysqli_query($conn,"DELETE FROM enrollment WHERE StudentID='$id'");
    mysqli_query($conn,"DELETE FROM grade WHERE StudentID='$id'");
    mysqli_query($conn,"DELETE FROM Student WHERE StudentID='$id'");
    mysqli_query($conn,"DELETE FROM `User` WHERE UserID='$id'");
    header("Location: admin.php?tab=students");
    exit();
}
if(isset($_GET['del_faculty'])){
    $id = $_GET['del_faculty'];
    mysqli_query($conn,"UPDATE Course SET FacultyID = NULL WHERE FacultyID='$id'");
    mysqli_query($conn,"DELETE FROM Faculty WHERE FacultyID='$id'");
    mysqli_query($conn,"DELETE FROM `User` WHERE UserID='$id'");
    header("Location: admin.php?tab=faculty");
    exit();
}
// Function to calculate GPA from percentage - FINAL CORRECT VERSION
function calculateGPA($percentage) {
    if ($percentage >= 85) return 4.0;
    elseif ($percentage >= 80) return 3.7;
    elseif ($percentage >= 75) return 3.3;
    elseif ($percentage >= 70) return 3.0;
    elseif ($percentage >= 65) return 2.7;
    elseif ($percentage >= 60) return 2.3;
    elseif ($percentage >= 55) return 2.0;
    elseif ($percentage >= 50) return 1.7; // Minimum passing
    elseif ($percentage >= 45) return 1.3; // Failed but has GPA
    elseif ($percentage >= 40) return 1.0; // Failed but has GPA
    elseif ($percentage >= 35) return 0.7; // Failed but has GPA
    elseif ($percentage >= 30) return 0.3; // Failed but has GPA
    else return 0.0; // Below 30%
}
// Excel Export for Student Report - UPDATED: Relegated if SGPA < 1.5
if(isset($_GET['export_excel']) && $active_tab == 'student_report') {
    $report_department = $_GET['report_dept'] ?? '';
    $report_semester = $_GET['report_sem'] ?? '';
  
    if($report_department && $report_semester) {
        // Get department name
        $dept_result = mysqli_query($conn, "SELECT DepartmentName FROM Department WHERE DepartmentID='$report_department'");
        $dept_name = mysqli_fetch_assoc($dept_result)['DepartmentName'];
      
        // Get student report data - FIXED: lowercase grade table
        $report_query = mysqli_query($conn,
            "SELECT s.StudentID, s.Name, s.Semester, d.DepartmentName,
                   
                    -- Count enrolled courses for this semester
                    COALESCE((SELECT COUNT(*) FROM enrollment e
                              WHERE e.StudentID = s.StudentID
                              AND e.Semester = '$report_semester'), 0) as total_enrolled_courses,
                  
                    -- Count attempted courses (have grades)
                    COALESCE((SELECT COUNT(DISTINCT g.CourseID)
                              FROM grade g
                              WHERE g.StudentID = s.StudentID
                              AND g.Semester = '$report_semester'), 0) as attempted_courses,
                  
                    -- Count passed courses: percentage >= 50
                    COALESCE((SELECT COUNT(DISTINCT g.CourseID)
                              FROM grade g
                              WHERE g.StudentID = s.StudentID
                              AND g.Semester = '$report_semester'
                              AND g.TotalPercentage >= 50), 0) as passed_courses,
                  
                    -- Count failed courses: percentage < 50
                    COALESCE((SELECT COUNT(DISTINCT g.CourseID)
                              FROM grade g
                              WHERE g.StudentID = s.StudentID
                              AND g.Semester = '$report_semester'
                              AND g.TotalPercentage < 50), 0) as failed_courses,
                  
                    -- SGPA CALCULATION: Failed courses contribute their actual GPA
                    CASE
                        WHEN (SELECT COUNT(DISTINCT CourseID) FROM grade
                              WHERE StudentID = s.StudentID AND Semester = '$report_semester') = 0
                        THEN 0.00
                        ELSE COALESCE(ROUND(
                            (SELECT SUM(GPA) FROM grade
                             WHERE StudentID = s.StudentID AND Semester = '$report_semester') /
                            (SELECT COUNT(DISTINCT CourseID) FROM grade
                             WHERE StudentID = s.StudentID AND Semester = '$report_semester')
                        , 2), 0.00)
                    END as sgpa
           
             FROM Student s
             JOIN Department d ON s.DepartmentID = d.DepartmentID
             WHERE s.DepartmentID = '$report_department'
             AND s.Semester = '$report_semester'
             ORDER BY sgpa DESC");
      
        // Set headers for Excel file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Student_Report_Semester_' . $report_semester . '_' . $dept_name . '.xls"');
        header('Cache-Control: max-age=0');
      
        // Create Excel content
        echo "<table border='1'>";
        echo "<tr><th colspan='9' style='background-color: #735da5; color: white; font-size: 16px; padding: 10px;'>Student Report - Semester $report_semester - $dept_name</th></tr>";
        echo "<tr style='background-color: #f8f5fd; font-weight: bold;'>";
        echo "<th>Student ID</th>";
        echo "<th>Name</th>";
        echo "<th>Department</th>";
        echo "<th>Semester</th>";
        echo "<th>Courses</th>";
        echo "<th>Passed</th>";
        echo "<th>Failed</th>";
        echo "<th>SGPA</th>";
        echo "<th>Status</th>";
        echo "</tr>";
      
        while($row = mysqli_fetch_assoc($report_query)) {
            $sgpa = $row['sgpa'];
            $passed = $row['passed_courses'];
            $failed = $row['failed_courses'];
            $attempted = $row['attempted_courses'];
            $total_enrolled = $row['total_enrolled_courses'];
          
            // Courses display: passed/total_enrolled
            $courses_display = $passed . "/" . $total_enrolled;
          
            // Determine status - Relegated if SGPA < 1.5
            if($total_enrolled == 0) {
                $status = 'Not Enrolled';
            } elseif($attempted == 0) {
                $status = 'No Attempts';
            } elseif($passed == 0 && $attempted > 0) {
                $status = 'Failed All';
            } elseif($sgpa < 1.5) {
                $status = 'Relegated';
            } elseif($sgpa < 1.0) {
                $status = 'Probation';
            } elseif($sgpa < 1.5) {
                $status = 'At Risk';
            } elseif($sgpa < 2.0) {
                $status = 'Warning';
            } elseif($sgpa < 2.5) {
                $status = 'Satisfactory';
            } else {
                $status = 'Good Standing';
            }
          
            echo "<tr>";
            echo "<td>" . $row['StudentID'] . "</td>";
            echo "<td>" . $row['Name'] . "</td>";
            echo "<td>" . $row['DepartmentName'] . "</td>";
            echo "<td>Semester " . $row['Semester'] . "</td>";
            echo "<td>" . $courses_display . "</td>";
            echo "<td>" . $passed . "</td>";
            echo "<td>" . $failed . "</td>";
            echo "<td>" . number_format($sgpa, 2) . "</td>";
            echo "<td>" . $status . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        exit();
    }
}
// Get stats for dashboard
$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM Student"))['count'];
$total_faculty = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM Faculty"))['count'];
$total_courses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM Course"))['count'];
$active_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM `User` WHERE Status='Active'"))['count'];
// Get total users (students + faculty)
$total_users = $total_students + $total_faculty;
// Get departments for dropdowns
$departments = mysqli_query($conn, "SELECT * FROM Department");
// Get courses for dropdowns (grouped by semester)
$courses_by_semester = [];
$courses_result = mysqli_query($conn, "SELECT * FROM Course ORDER BY Semester, CourseName");
while($course = mysqli_fetch_assoc($courses_result)) {
    $semester = $course['Semester'] ?? 1;
    if(!isset($courses_by_semester[$semester])) {
        $courses_by_semester[$semester] = [];
    }
    $courses_by_semester[$semester][] = $course;
}
// For Student Report Tab
$report_department = $_GET['report_dept'] ?? '';
$report_semester = $_GET['report_sem'] ?? '';
$student_report = [];
if($active_tab == 'student_report' && $report_department && $report_semester) {
    // Get students in the selected department and semester - CORRECTED & FIXED: lowercase grade
    $report_query = mysqli_query($conn,
        "SELECT s.StudentID, s.Name, s.Semester, d.DepartmentName,
               
                -- Count enrolled courses for this semester
                COALESCE((SELECT COUNT(*) FROM enrollment e
                          WHERE e.StudentID = s.StudentID
                          AND e.Semester = '$report_semester'), 0) as total_enrolled_courses,
              
                -- Count attempted courses (have grades)
                COALESCE((SELECT COUNT(DISTINCT g.CourseID)
                          FROM grade g
                          WHERE g.StudentID = s.StudentID
                          AND g.Semester = '$report_semester'), 0) as attempted_courses,
              
                -- Count passed courses: percentage >= 50
                COALESCE((SELECT COUNT(DISTINCT g.CourseID)
                          FROM grade g
                          WHERE g.StudentID = s.StudentID
                          AND g.Semester = '$report_semester'
                          AND g.TotalPercentage >= 50), 0) as passed_courses,
              
                -- Count failed courses: percentage < 50
                COALESCE((SELECT COUNT(DISTINCT g.CourseID)
                          FROM grade g
                          WHERE g.StudentID = s.StudentID
                          AND g.Semester = '$report_semester'
                          AND g.TotalPercentage < 50), 0) as failed_courses,
              
                -- SGPA CALCULATION: Failed courses contribute their actual GPA
                CASE
                    WHEN (SELECT COUNT(DISTINCT CourseID) FROM grade
                          WHERE StudentID = s.StudentID AND Semester = '$report_semester') = 0
                    THEN 0.00
                    ELSE COALESCE(ROUND(
                        (SELECT SUM(GPA) FROM grade
                         WHERE StudentID = s.StudentID AND Semester = '$report_semester') /
                        (SELECT COUNT(DISTINCT CourseID) FROM grade
                         WHERE StudentID = s.StudentID AND Semester = '$report_semester')
                    , 2), 0.00)
                END as sgpa
       
         FROM Student s
         JOIN Department d ON s.DepartmentID = d.DepartmentID
         WHERE s.DepartmentID = '$report_department'
         AND s.Semester = '$report_semester'
         ORDER BY sgpa DESC");
  
    while($row = mysqli_fetch_assoc($report_query)) {
        $student_report[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>University Admin Portal</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { font-family: 'Inter', sans-serif; background: #f8f5fd; letter-spacing: -0.01em; }
    .sidebar { background: linear-gradient(180deg, #735da5 0%, #5a4a84 100%); }
    .stat-card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; }
    .table-container { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .table-header { background: #f8f5fd; border-bottom: 2px solid #e5e7eb; font-size: 14px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em; }
    .btn-primary { background: #735da5; color: white; font-weight: 600; padding: 10px 24px; border-radius: 8px; }
    .btn-primary:hover { background: #5a4a84; }
    .btn-secondary { background: #d3c5e5; color: #735da5; font-weight: 600; padding: 10px 24px; border-radius: 8px; }
    .btn-secondary:hover { background: #c5b4e0; }
    .btn-success { background: #10b981; color: white; font-weight: 600; padding: 10px 24px; border-radius: 8px; }
    .btn-success:hover { background: #059669; }
    .nav-link { padding: 12px 20px; border-radius: 8px; transition: all 0.2s; }
    .nav-link:hover { background: rgba(255,255,255,0.1); }
    .nav-link.active { background: white; color: #735da5; font-weight: 600; }
    .input-field { border: 1px solid #d1d5db; border-radius: 6px; padding: 10px 14px; width: 100%; }
    .input-field:focus { outline: none; border-color: #735da5; box-shadow: 0 0 0 3px rgba(115, 93, 165, 0.1); }
    .modal-overlay { background: rgba(0,0,0,0.5); }
    .modal-content { background: white; border-radius: 16px; max-width: 500px; width: 90%; }
    .action-btn { padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: 500; margin: 0 2px; }
    .edit-btn { background: #735da5; color: white; }
    .edit-btn:hover { background: #5a4a84; }
    .delete-btn { background: #ef4444; color: white; }
    .delete-btn:hover { background: #dc2626; }
    .table-heading { font-weight: 700; color: #374151; }
    .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .status-good { background: #d1fae5; color: #065f46; }
    .status-warning { background: #fef3c7; color: #92400e; }
    .status-danger { background: #fee2e2; color: #991b1b; }
    .status-info { background: #dbeafe; color: #1e40af; }
    .course-checkbox { margin-right: 8px; }
    .course-label { display: flex; align-items: center; margin-bottom: 8px; padding: 8px; border-radius: 6px; background: #f9fafb; }
    .course-label:hover { background: #f3f4f6; }
    /* Compact table styles */
    .compact-table td, .compact-table th { padding: 12px 8px; font-size: 13px; }
    .compact-table .action-btn { padding: 4px 8px; font-size: 12px; }
    .ellipsis { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px; }
  </style>
</head>
<body class="min-h-screen text-gray-800">
<div class="flex min-h-screen">
  <!-- Sidebar -->
  <div class="sidebar w-64 min-h-screen p-6 text-white flex flex-col">
    <div class="flex-1">
      <div class="flex items-center space-x-3 mb-10">
        <i class="fas fa-shield-alt text-2xl"></i>
        <div>
          <h1 class="text-xl font-bold">Admin Portal</h1>
          <p class="text-xs opacity-80 mt-1">University Management</p>
        </div>
      </div>
    
      <nav class="space-y-1">
        <a href="admin.php?tab=dashboard" class="nav-link flex items-center space-x-3 <?= $active_tab=='dashboard' ? 'active' : '' ?>">
          <i class="fas fa-chart-bar w-5"></i>
          <span>Dashboard</span>
        </a>
        <a href="admin.php?tab=students" class="nav-link flex items-center space-x-3 <?= $active_tab=='students' ? 'active' : '' ?>">
          <i class="fas fa-user-graduate w-5"></i>
          <span>Students</span>
        </a>
        <a href="admin.php?tab=add_student" class="nav-link flex items-center space-x-3 <?= $active_tab=='add_student' ? 'active' : '' ?>">
          <i class="fas fa-user-plus w-5"></i>
          <span>Add Student</span>
        </a>
        <a href="admin.php?tab=faculty" class="nav-link flex items-center space-x-3 <?= $active_tab=='faculty' ? 'active' : '' ?>">
          <i class="fas fa-chalkboard-teacher w-5"></i>
          <span>Faculty</span>
        </a>
        <a href="admin.php?tab=add_faculty" class="nav-link flex items-center space-x-3 <?= $active_tab=='add_faculty' ? 'active' : '' ?>">
          <i class="fas fa-user-tie w-5"></i>
          <span>Add Faculty</span>
        </a>
        <a href="admin.php?tab=student_report" class="nav-link flex items-center space-x-3 <?= $active_tab=='student_report' ? 'active' : '' ?>">
          <i class="fas fa-file-alt w-5"></i>
          <span>Student Report</span>
        </a>
      </nav>
    </div>
    <!-- LOGOUT BUTTON -->
    <div class="mt-auto pt-8">
      <a href="logout.php" class="flex items-center justify-center space-x-2 bg-white bg-opacity-10 hover:bg-opacity-20 p-3 rounded-lg transition-colors">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
      </a>
    </div>
  </div>
  <!-- Main Content -->
  <div class="flex-1 p-8">
    <div class="max-w-7xl mx-auto">
      <!-- Dashboard Tab -->
      <?php if($active_tab == 'dashboard'): ?>
      <div class="space-y-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
            <p class="text-gray-600 mt-1">Welcome back, Admin. Here's what's happening today.</p>
          </div>
          <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-500 bg-white px-4 py-2 rounded-lg border">
              <i class="fas fa-calendar-alt mr-2"></i>
              <?= date('l, F j, Y') ?>
            </div>
            <div class="text-sm text-gray-500 bg-white px-4 py-2 rounded-lg border">
              <i class="fas fa-clock mr-2"></i>
              <span id="liveClock"><?= date('h:i A') ?></span>
            </div>
          </div>
        </div>
        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <!-- Student Card -->
          <div class="stat-card group hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
              <div class="w-14 h-14 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-user-graduate text-purple-600 text-2xl"></i>
              </div>
              <div class="text-right">
                <span class="text-xs font-semibold px-2 py-1 rounded-full <?= $total_students > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                  <i class="fas fa-arrow-up mr-1"></i> Total
                </span>
              </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-2"><?= number_format($total_students) ?></h3>
            <p class="text-gray-600 text-sm mb-3">Total Students</p>
            <div class="pt-3 border-t border-gray-100">
              <?php
              $new_students_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM Student");
              $new_students = mysqli_fetch_assoc($new_students_result)['count'];
              ?>
              <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500"><i class="fas fa-user-plus mr-1"></i> Total count</span>
                <span class="font-semibold text-purple-600"><?= $new_students ?></span>
              </div>
            </div>
          </div>
          <!-- Faculty Card -->
          <div class="stat-card group hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
              <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-chalkboard-teacher text-blue-600 text-2xl"></i>
              </div>
              <div class="text-right">
                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                  Active
                </span>
              </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-2"><?= number_format($total_faculty) ?></h3>
            <p class="text-gray-600 text-sm mb-3">Faculty Members</p>
            <div class="pt-3 border-t border-gray-100">
              <?php
              $professors_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM Faculty WHERE Name LIKE 'Dr.%' OR Name LIKE 'Prof.%'");
              $professors = mysqli_fetch_assoc($professors_result)['count'];
              ?>
              <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500"><i class="fas fa-user-tie mr-1"></i> Professors</span>
                <span class="font-semibold text-blue-600"><?= $professors ?></span>
              </div>
            </div>
          </div>
          <!-- Courses Card -->
          <div class="stat-card group hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
              <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-book text-green-600 text-2xl"></i>
              </div>
              <div class="text-right">
                <?php
                $active_courses_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM Course");
                $active_courses = mysqli_fetch_assoc($active_courses_result)['count'];
                ?>
                <span class="text-xs font-semibold px-2 py-1 rounded-full <?= $active_courses > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                  <?= $active_courses ?> active
                </span>
              </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-2"><?= number_format($total_courses) ?></h3>
            <p class="text-gray-600 text-sm mb-3">Available Courses</p>
            <div class="pt-3 border-t border-gray-100">
              <?php
              $departments_with_courses_result = mysqli_query($conn, "SELECT COUNT(DISTINCT DepartmentID) as count FROM Course");
              $departments_with_courses = mysqli_fetch_assoc($departments_with_courses_result)['count'];
              ?>
              <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500"><i class="fas fa-layer-group mr-1"></i> Departments</span>
                <span class="font-semibold text-green-600"><?= $departments_with_courses ?></span>
              </div>
            </div>
          </div>
          <!-- Users Card -->
          <div class="stat-card group hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
              <div class="w-14 h-14 bg-gradient-to-br from-amber-100 to-amber-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-users text-amber-600 text-2xl"></i>
              </div>
              <div class="text-right">
                <?php
                $active_percentage = $total_users > 0 ? round(($active_users / $total_users) * 100) : 0;
                ?>
                <span class="text-xs font-semibold px-2 py-1 rounded-full <?= $active_percentage >= 80 ? 'bg-green-100 text-green-800' : ($active_percentage >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                  <?= $active_percentage ?>% active
                </span>
              </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-2"><?= number_format($active_users) ?></h3>
            <p class="text-gray-600 text-sm mb-3">Active Users</p>
            <div class="pt-3 border-t border-gray-200">
              <?php
              $inactive_users_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM `User` WHERE Status='Inactive'");
              $inactive_users = mysqli_fetch_assoc($inactive_users_result)['count'];
              ?>
              <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500"><i class="fas fa-user-slash mr-1"></i> Inactive</span>
                <span class="font-semibold text-amber-600"><?= $inactive_users ?></span>
              </div>
            </div>
          </div>
        </div>
        <!-- Additional Metrics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Department Distribution -->
          <div class="stat-card lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-lg font-bold text-gray-900">Department Distribution</h3>
              <span class="text-sm text-gray-500">By student count</span>
            </div>
            <div class="space-y-4">
              <?php
              $dept_distribution = mysqli_query($conn,
                "SELECT d.DepartmentName, COUNT(s.StudentID) as student_count
                 FROM Department d
                 LEFT JOIN Student s ON d.DepartmentID = s.DepartmentID
                 GROUP BY d.DepartmentID, d.DepartmentName
                 ORDER BY student_count DESC
                 LIMIT 5");
            
              $max_count = 0;
              $results = [];
              while($row = mysqli_fetch_assoc($dept_distribution)) {
                $results[] = $row;
                if($row['student_count'] > $max_count) $max_count = $row['student_count'];
              }
            
              foreach($results as $dept):
                $percentage = $max_count > 0 ? ($dept['student_count'] / $max_count) * 100 : 0;
              ?>
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="font-medium"><?= htmlspecialchars($dept['DepartmentName']) ?></span>
                  <span class="text-gray-600"><?= $dept['student_count'] ?> students</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                  <div class="h-full bg-gradient-to-r from-purple-500 to-purple-300 rounded-full"
                       style="width: <?= $percentage ?>%"></div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-200">
              <a href="admin.php?tab=students" class="text-sm text-purple-600 hover:text-purple-800 font-medium inline-flex items-center">
                View all departments
                <i class="fas fa-arrow-right ml-2"></i>
              </a>
            </div>
          </div>
          <!-- System Status -->
          <div class="stat-card">
            <h3 class="text-lg font-bold text-gray-900 mb-6">System Status</h3>
            <div class="space-y-4">
              <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                <div class="flex items-center">
                  <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-database text-green-600"></i>
                  </div>
                  <div>
                    <p class="font-medium text-gray-900">Database</p>
                    <p class="text-xs text-gray-500">Connected</p>
                  </div>
                </div>
                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
              </div>
            
              <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                <div class="flex items-center">
                  <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-server text-blue-600"></i>
                  </div>
                  <div>
                    <p class="font-medium text-gray-900">Server</p>
                    <p class="text-xs text-gray-500">Operational</p>
                  </div>
                </div>
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
              </div>
            
              <div class="flex items-center justify-between p-3 bg-amber-50 rounded-lg">
                <div class="flex items-center">
                  <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-shield-alt text-amber-600"></i>
                  </div>
                  <div>
                    <p class="font-medium text-gray-900">Security</p>
                    <p class="text-xs text-gray-500">Protected</p>
                  </div>
                </div>
                <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
              </div>
            </div>
          
            <div class="mt-6 pt-4 border-t border-gray-200">
              <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">Last updated</span>
                <span class="font-medium">Just now</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script>
      function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
          hour: '2-digit',
          minute: '2-digit',
          hour12: true
        });
        document.getElementById('liveClock').textContent = timeString;
      }
      setInterval(updateClock, 1000);
      updateClock();
      </script>
      <?php endif; ?>
      <!-- Add Student Tab -->
      <?php if($active_tab == 'add_student'): ?>
      <div class="space-y-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Register New Student</h1>
            <p class="text-gray-600 mt-1">Add student to university database with course enrollment</p>
          </div>
        </div>
        <div class="stat-card">
          <form method="post" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Student ID</label>
                <input type="text" name="id" placeholder="S001" class="input-field" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" placeholder="John Doe" class="input-field" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Father's Name</label>
                <input type="text" name="father_name" placeholder="Ahmed Hussain" class="input-field">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                <input type="date" name="dob" class="input-field">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                <select name="gender" class="input-field">
                  <option value="">Select Gender</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Other">Other</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" placeholder="john@student.edu" class="input-field" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="tel" name="phone" placeholder="+1 (555) 123-4567" class="input-field">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Admission Date</label>
                <input type="date" name="admission_date" value="<?= date('Y-m-d') ?>" class="input-field">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select name="dept" id="studentDept" class="input-field" required onchange="loadCoursesForStudent()">
                  <option value="">Select Department</option>
                  <?php
                  mysqli_data_seek($departments, 0);
                  while($dept = mysqli_fetch_assoc($departments)):
                  ?>
                  <option value="<?= $dept['DepartmentID'] ?>"><?= htmlspecialchars($dept['DepartmentName']) ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                <select name="semester" id="studentSemester" class="input-field" required onchange="loadCoursesForStudent()">
                  <option value="1">Semester 1</option>
                  <option value="2">Semester 2</option>
                  <option value="3">Semester 3</option>
                  <option value="4">Semester 4</option>
                  <option value="5">Semester 5</option>
                  <option value="6">Semester 6</option>
                  <option value="7">Semester 7</option>
                  <option value="8">Semester 8</option>
                </select>
              </div>
            </div>
          
            <!-- Course Selection -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-3">Select Courses</label>
              <div id="courseSelection" class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-4">Select department and semester to see available courses</p>
              </div>
            </div>
          
            <div class="pt-4 border-t border-gray-200">
              <div class="flex justify-end space-x-4">
                <a href="admin.php?tab=students" class="btn-secondary px-8">Cancel</a>
                <button type="submit" name="add_student" class="btn-primary px-8">
                  <i class="fas fa-user-plus mr-2"></i>
                  Register Student
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <script>
      function loadCoursesForStudent() {
        const dept = document.getElementById('studentDept').value;
        const semester = document.getElementById('studentSemester').value;
        const courseSelection = document.getElementById('courseSelection');
      
        if(!dept || !semester) {
          courseSelection.innerHTML = '<p class="text-sm text-gray-500">Select department and semester to see available courses</p>';
          return;
        }
      
        fetch(`get_courses.php?dept=${dept}&semester=${semester}`)
          .then(response => response.text())
          .then(data => {
            courseSelection.innerHTML = data;
          })
          .catch(error => {
            courseSelection.innerHTML = '<p class="text-sm text-red-500">Error loading courses</p>';
          });
      }
      </script>
      <?php endif; ?>
      <!-- Add Faculty Tab -->
      <?php if($active_tab == 'add_faculty'): ?>
      <div class="space-y-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Register New Faculty</h1>
            <p class="text-gray-600 mt-1">Add faculty member with course assignments</p>
          </div>
        </div>
        <div class="stat-card">
          <form method="post" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Faculty ID</label>
                <input type="text" name="id" placeholder="F001" class="input-field" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" placeholder="Dr. Jane Smith" class="input-field" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" placeholder="jane@faculty.edu" class="input-field" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="tel" name="phone" placeholder="+1 (555) 987-6543" class="input-field">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Years of Service</label>
                <input type="number" name="years_of_service" min="0" value="0" class="input-field">
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select name="dept" id="facultyDept" class="input-field" required onchange="loadCoursesForFaculty()">
                  <option value="">Select Department</option>
                  <?php
                  mysqli_data_seek($departments, 0);
                  while($dept = mysqli_fetch_assoc($departments)):
                  ?>
                  <option value="<?= $dept['DepartmentID'] ?>"><?= htmlspecialchars($dept['DepartmentName']) ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>
          
            <!-- Course Assignment -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-3">Assign Courses to Teach</label>
              <div id="facultyCourseSelection" class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-4">Select department to see available courses</p>
              </div>
            </div>
          
            <div class="pt-4 border-t border-gray-200">
              <div class="flex justify-end space-x-4">
                <a href="admin.php?tab=faculty" class="btn-secondary px-8">Cancel</a>
                <button type="submit" name="add_faculty" class="btn-primary px-8">
                  <i class="fas fa-user-tie mr-2"></i>
                  Register Faculty
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <script>
      function loadCoursesForFaculty() {
        const dept = document.getElementById('facultyDept').value;
        const courseSelection = document.getElementById('facultyCourseSelection');
      
        if(!dept) {
          courseSelection.innerHTML = '<p class="text-sm text-gray-500">Select department to see available courses</p>';
          return;
        }
      
        fetch(`get_courses.php?dept=${dept}&type=faculty&unassigned=true`)
          .then(response => response.text())
          .then(data => {
            courseSelection.innerHTML = data;
          })
          .catch(error => {
            courseSelection.innerHTML = '<p class="text-sm text-red-500">Error loading courses</p>';
          });
      }
      </script>
      <?php endif; ?>
      <!-- Students List Tab - COMPACT VERSION -->
      <?php if($active_tab == 'students'): ?>
      <div class="space-y-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Student Management</h1>
            <p class="text-gray-600 mt-1">Manage student records and information</p>
          </div>
          <a href="admin.php?tab=add_student" class="btn-primary">
            <i class="fas fa-user-plus mr-2"></i>
            Add Student
          </a>
        </div>
        <!-- Search Bar -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <form method="get" class="flex items-center space-x-4">
            <input type="hidden" name="tab" value="students">
            <div class="flex-1">
              <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="q_student" value="<?= htmlspecialchars($search_student) ?>"
                       placeholder="Search by Student ID, Name, or Email"
                       class="input-field pl-12">
              </div>
            </div>
            <button type="submit" class="btn-primary px-6">Search</button>
            <?php if($search_student): ?>
            <a href="admin.php?tab=students" class="btn-secondary px-6">Clear</a>
            <?php endif; ?>
          </form>
        </div>
        <!-- Students Table - COMPACT -->
        <div class="table-container overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full compact-table">
              <thead class="table-header">
                <tr>
                  <th class="p-3 text-left font-bold text-gray-700">ID</th>
                  <th class="p-3 text-left font-bold text-gray-700">Name</th>
                  <th class="p-3 text-left font-bold text-gray-700">Email</th>
                  <th class="p-3 text-left font-bold text-gray-700">Dept</th>
                  <th class="p-3 text-left font-bold text-gray-700">Sem</th>
                  <th class="p-3 text-left font-bold text-gray-700">Phone</th>
                  <th class="p-3 text-left font-bold text-gray-700">SGPA</th>
                  <th class="p-3 text-left font-bold text-gray-700">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <?php
                // SGPA CALCULATION for students list - FIXED: lowercase grade table
                $sql = "SELECT s.StudentID, s.Name, u.Email, d.DepartmentName,
                        s.DepartmentID, s.ContactInfo, s.Semester,
                      
                        -- SGPA CALCULATION for current semester
                        CASE
                            WHEN (SELECT COUNT(DISTINCT CourseID) FROM grade
                                  WHERE StudentID = s.StudentID AND Semester = s.Semester) = 0
                            THEN 0.00
                            ELSE COALESCE(ROUND(
                                (SELECT SUM(GPA) FROM grade
                                 WHERE StudentID = s.StudentID AND Semester = s.Semester) /
                                (SELECT COUNT(DISTINCT CourseID) FROM grade
                                 WHERE StudentID = s.StudentID AND Semester = s.Semester)
                            , 2), 0.00)
                        END AS sgpa
                      
                        FROM Student s
                        JOIN `User` u ON s.UserID = u.UserID
                        JOIN Department d ON s.DepartmentID = d.DepartmentID
                        WHERE u.Role = 'Student'";
              
                if($search_student) {
                  $sql .= " AND (s.StudentID LIKE '%$search_student%' OR s.Name LIKE '%$search_student%' OR u.Email LIKE '%$search_student%')";
                }
              
                $sql .= " ORDER BY s.StudentID ASC";
                $res = mysqli_query($conn, $sql);
              
                if(mysqli_num_rows($res) > 0):
                  while($r = mysqli_fetch_assoc($res)):
                    $sgpa = $r['sgpa'];
                    if ($sgpa >= 3.5) {
                      $color_class = 'text-green-600';
                      $label_class = 'bg-green-100 text-green-800';
                      $status = 'Exc';
                    } elseif ($sgpa >= 3.0) {
                      $color_class = 'text-blue-600';
                      $label_class = 'bg-blue-100 text-blue-800';
                      $status = 'Good';
                    } elseif ($sgpa >= 2.0) {
                      $color_class = 'text-yellow-600';
                      $label_class = 'bg-yellow-100 text-yellow-800';
                      $status = 'Avg';
                    } elseif ($sgpa >= 1.0) {
                      $color_class = 'text-orange-600';
                      $label_class = 'bg-orange-100 text-orange-800';
                      $status = 'Low';
                    } elseif ($sgpa > 0) {
                      $color_class = 'text-red-600';
                      $label_class = 'bg-red-100 text-red-800';
                      $status = 'Poor';
                    } else {
                      $color_class = 'text-gray-500';
                      $label_class = 'bg-gray-100 text-gray-800';
                      $status = 'N/A';
                    }
                ?>
                <tr class="hover:bg-gray-50">
                  <td class="p-3">
                    <div class="font-medium text-gray-900 text-sm"><?= $r['StudentID'] ?></div>
                  </td>
                  <td class="p-3">
                    <div class="font-medium ellipsis" title="<?= htmlspecialchars($r['Name']) ?>"><?= htmlspecialchars($r['Name']) ?></div>
                  </td>
                  <td class="p-3">
                    <div class="text-xs text-gray-600 ellipsis" title="<?= $r['Email'] ?>"><?= $r['Email'] ?></div>
                  </td>
                  <td class="p-3">
                    <div class="text-xs"><?= $r['DepartmentName'] ?></div>
                  </td>
                  <td class="p-3">
                    <div class="text-xs font-medium bg-purple-100 text-purple-800 px-2 py-1 rounded-full w-fit">
                      Sem <?= $r['Semester'] ?>
                    </div>
                  </td>
                  <td class="p-3">
                    <div class="text-xs text-gray-600"><?= $r['ContactInfo'] ?: '-' ?></div>
                  </td>
                  <td class="p-3">
                    <div class="flex items-center space-x-1">
                      <div class="font-bold <?= $color_class ?>"><?= number_format($sgpa, 2) ?></div>
                      <span class="text-xs font-semibold px-1.5 py-0.5 rounded-full <?= $label_class ?>">
                        <?= $status ?>
                      </span>
                    </div>
                  </td>
                  <td class="p-3">
                    <div class="flex space-x-1">
                      <button onclick="openEditModal('student',
                              '<?= $r['StudentID'] ?>',
                              '<?= htmlspecialchars($r['Email']) ?>',
                              '<?= $r['DepartmentID'] ?>',
                              '<?= htmlspecialchars($r['ContactInfo'] ?? '') ?>',
                              '<?= $r['Semester'] ?>')"
                              class="action-btn edit-btn" title="Edit">
                        <i class="fas fa-edit"></i>
                      </button>
                      <a href="?tab=students&del_student=<?= $r['StudentID'] ?>"
                         onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars($r['Name']) ?>? This action cannot be undone.')"
                         class="action-btn delete-btn" title="Delete">
                        <i class="fas fa-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                  <td colspan="8" class="p-8 text-center text-gray-500">
                    <i class="fas fa-user-graduate text-3xl mb-3 opacity-30"></i>
                    <p>No students found</p>
                  </td>
                </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <?php endif; ?>
      <!-- Faculty List Tab - COMPACT -->
      <?php if($active_tab == 'faculty'): ?>
      <div class="space-y-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Faculty Management</h1>
            <p class="text-gray-600 mt-1">Manage faculty records and information</p>
          </div>
          <a href="admin.php?tab=add_faculty" class="btn-primary">
            <i class="fas fa-user-tie mr-2"></i>
            Add Faculty
          </a>
        </div>
        <!-- Search Bar -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <form method="get" class="flex items-center space-x-4">
            <input type="hidden" name="tab" value="faculty">
            <div class="flex-1">
              <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="q_faculty" value="<?= htmlspecialchars($search_faculty) ?>"
                       placeholder="Search by Faculty ID, Name, or Email"
                       class="input-field pl-12">
              </div>
            </div>
            <button type="submit" class="btn-primary px-6">Search</button>
            <?php if($search_faculty): ?>
            <a href="admin.php?tab=faculty" class="btn-secondary px-6">Clear</a>
            <?php endif; ?>
          </form>
        </div>
        <!-- Faculty Table - COMPACT -->
        <div class="table-container overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full compact-table">
              <thead class="table-header">
                <tr>
                  <th class="p-3 text-left font-bold text-gray-700">ID</th>
                  <th class="p-3 text-left font-bold text-gray-700">Name</th>
                  <th class="p-3 text-left font-bold text-gray-700">Email</th>
                  <th class="p-3 text-left font-bold text-gray-700">Dept</th>
                  <th class="p-3 text-left font-bold text-gray-700">Phone</th>
                  <th class="p-3 text-left font-bold text-gray-700">Years of Service</th>
                  <th class="p-3 text-left font-bold text-gray-700">Courses</th>
                  <th class="p-3 text-left font-bold text-gray-700">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <?php
                $sql = "SELECT f.FacultyID, f.Name, u.Email, d.DepartmentName,
                        f.ContactInfo, f.DepartmentID, f.YearsOfService,
                        GROUP_CONCAT(DISTINCT CONCAT(c.CourseID, ' - ', c.CourseName) ORDER BY c.CourseID SEPARATOR '|') as assigned_courses_data
                        FROM Faculty f
                        JOIN `User` u ON f.UserID = u.UserID
                        JOIN Department d ON f.DepartmentID = d.DepartmentID
                        LEFT JOIN Course c ON f.FacultyID = c.FacultyID
                        WHERE u.Role = 'Faculty'";
              
                if($search_faculty) {
                  $sql .= " AND (f.FacultyID LIKE '%$search_faculty%' OR f.Name LIKE '%$search_faculty%' OR u.Email LIKE '%$search_faculty%')";
                }
              
                $sql .= " GROUP BY f.FacultyID ORDER BY f.FacultyID ASC";
                $res = mysqli_query($conn, $sql);
              
                if(mysqli_num_rows($res) > 0):
                  while($r = mysqli_fetch_assoc($res)):
                    $courses = $r['assigned_courses_data'] ? explode('|', $r['assigned_courses_data']) : [];
                    $courses_display = [];
                    foreach($courses as $course) {
                      if(!empty($course)) {
                        $parts = explode(' - ', $course, 2);
                        $courses_display[] = [
                          'code' => $parts[0] ?? '',
                          'name' => $parts[1] ?? $course
                        ];
                      }
                    }
                ?>
                <tr class="hover:bg-gray-50">
                  <td class="p-3">
                    <div class="font-medium text-gray-900 text-sm"><?= $r['FacultyID'] ?></div>
                  </td>
                  <td class="p-3">
                    <div class="font-medium ellipsis" title="<?= htmlspecialchars($r['Name']) ?>"><?= htmlspecialchars($r['Name']) ?></div>
                  </td>
                  <td class="p-3">
                    <div class="text-xs text-gray-600 ellipsis" title="<?= $r['Email'] ?>"><?= $r['Email'] ?></div>
                  </td>
                  <td class="p-3">
                    <div class="text-xs"><?= $r['DepartmentName'] ?></div>
                  </td>
                  <td class="p-3">
                    <div class="text-xs text-gray-600"><?= $r['ContactInfo'] ?: '-' ?></div>
                  </td>
                  <td class="p-3">
                    <div class="font-bold text-purple-600 text-sm"><?= $r['YearsOfService'] ?></div>
                  </td>
                  <td class="p-3">
                    <div class="text-xs">
                      <?php if(!empty($courses_display)): ?>
                        <div class="flex flex-col gap-1">
                          <?php foreach(array_slice($courses_display, 0, 2) as $course): ?>
                            <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs ellipsis"
                                  title="<?= htmlspecialchars($course['code'] . ' - ' . $course['name']) ?>">
                              <?= htmlspecialchars($course['code']) ?>
                            </span>
                          <?php endforeach; ?>
                          <?php if(count($courses_display) > 2): ?>
                            <button onclick="viewAllCourses('<?= htmlspecialchars(json_encode($courses_display)) ?>', '<?= htmlspecialchars($r['Name']) ?>')"
                                    class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded text-xs hover:bg-gray-200 transition-colors">
                              +<?= count($courses_display) - 2 ?> more
                            </button>
                          <?php endif; ?>
                          <?php if(count($courses_display) <= 2 && !empty($courses_display)): ?>
                            <button onclick="viewAllCourses('<?= htmlspecialchars(json_encode($courses_display)) ?>', '<?= htmlspecialchars($r['Name']) ?>')"
                                    class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded text-xs hover:bg-blue-100 transition-colors text-xs mt-1">
                              View All
                            </button>
                          <?php endif; ?>
                        </div>
                      <?php else: ?>
                        <span class="text-gray-400 text-xs">No courses</span>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td class="p-3">
                    <div class="flex space-x-1">
                      <button onclick="openEditModal('faculty',
                              '<?= $r['FacultyID'] ?>',
                              '<?= htmlspecialchars($r['Email']) ?>',
                              '<?= $r['DepartmentID'] ?>',
                              '<?= htmlspecialchars($r['ContactInfo'] ?? '') ?>',
                              '<?= htmlspecialchars(json_encode(array_column($courses_display, 'code'))) ?>',
                              '<?= $r['YearsOfService'] ?>')"
                              class="action-btn edit-btn" title="Edit">
                        <i class="fas fa-edit"></i>
                      </button>
                      <a href="?tab=faculty&del_faculty=<?= $r['FacultyID'] ?>"
                         onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars($r['Name']) ?>? This will unassign them from all courses.')"
                         class="action-btn delete-btn" title="Delete">
                        <i class="fas fa-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                  <td colspan="8" class="p-8 text-center text-gray-500">
                    <i class="fas fa-chalkboard-teacher text-3xl mb-3 opacity-30"></i>
                    <p>No faculty members found</p>
                  </td>
                </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <?php endif; ?>
      <!-- Student Report Tab - CORRECTED WITH RELEGATED -->
      <?php if($active_tab == 'student_report'): ?>
      <div class="space-y-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Student Semester Report</h1>
            <p class="text-gray-600 mt-1">Generate semester-wise student performance reports</p>
          </div>
        </div>
        <!-- Report Filter Form -->
        <div class="stat-card">
          <form method="get" class="space-y-6">
            <input type="hidden" name="tab" value="student_report">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select name="report_dept" class="input-field" required>
                  <option value="">Select Department</option>
                  <?php
                  mysqli_data_seek($departments, 0);
                  while($dept = mysqli_fetch_assoc($departments)):
                    $selected = ($report_department == $dept['DepartmentID']) ? 'selected' : '';
                  ?>
                  <option value="<?= $dept['DepartmentID'] ?>" <?= $selected ?>>
                    <?= htmlspecialchars($dept['DepartmentName']) ?>
                  </option>
                  <?php endwhile; ?>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                <select name="report_sem" class="input-field" required>
                  <option value="">Select Semester</option>
                  <?php for($i = 1; $i <= 8; $i++): ?>
                    <option value="<?= $i ?>" <?= ($report_semester == $i) ? 'selected' : '' ?>>Semester <?= $i ?></option>
                  <?php endfor; ?>
                </select>
              </div>
              <div class="flex items-end">
                <button type="submit" class="btn-primary w-full">
                  <i class="fas fa-chart-bar mr-2"></i>
                  Generate Report
                </button>
              </div>
            </div>
          </form>
        </div>
        <?php if($report_department && $report_semester): ?>
        <!-- Report Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <?php
          $total_students = count($student_report);
          $good_standing = 0;
          $satisfactory = 0;
          $warning = 0;
          $at_risk = 0;
          $probation = 0;
          $failed_all = 0;
          $not_enrolled = 0;
          $no_attempts = 0;
        
          foreach($student_report as $student) {
            $sgpa = $student['sgpa'];
            $passed = $student['passed_courses'];
            $attempted = $student['attempted_courses'];
            $total_enrolled = $student['total_enrolled_courses'];
          
            // Status determination
            if($total_enrolled == 0) {
                $not_enrolled++;
            } elseif($attempted == 0) {
                $no_attempts++;
            } elseif($passed == 0 && $attempted > 0) {
                $failed_all++;
            } elseif($sgpa < 1.0) {
                $probation++;
            } elseif($sgpa < 1.5) {
                $at_risk++;
            } elseif($sgpa < 2.0) {
                $warning++;
            } elseif($sgpa < 2.5) {
                $satisfactory++;
            } elseif($sgpa >= 2.5) {
                $good_standing++;
            }
          }
          ?>
          <div class="stat-card">
            <div class="text-center">
              <div class="text-3xl font-bold text-purple-600 mb-2"><?= $total_students ?></div>
              <div class="text-sm text-gray-600">Total Students</div>
              <div class="text-xs text-gray-500">Sem <?= $report_semester ?></div>
            </div>
          </div>
          <div class="stat-card">
            <div class="text-center">
              <div class="text-3xl font-bold text-green-600 mb-2"><?= $good_standing + $satisfactory ?></div>
              <div class="text-sm text-gray-600">Good Standing</div>
              <div class="text-xs text-gray-500">SGPA ≥ 2.0</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="text-center">
              <div class="text-3xl font-bold text-yellow-600 mb-2"><?= $warning + $at_risk + $probation ?></div>
              <div class="text-sm text-gray-600">At Risk</div>
              <div class="text-xs text-gray-500">SGPA < 2.0</div>
            </div>
          </div>
          <div class="stat-card">
            <div class="text-center">
              <div class="text-3xl font-bold text-red-600 mb-2"><?= $failed_all + $no_attempts + $not_enrolled ?></div>
              <div class="text-sm text-gray-600">Critical</div>
              <div class="text-xs text-gray-500">Failed/No Attempts</div>
            </div>
          </div>
        </div>
        <!-- Detailed Report Table - CORRECTED -->
        <div class="table-container overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full compact-table">
              <thead class="table-header">
                <tr>
                  <th class="p-3 text-left font-bold text-gray-700">Student ID</th>
                  <th class="p-3 text-left font-bold text-gray-700">Name</th>
                  <th class="p-3 text-left font-bold text-gray-700">Dept</th>
                  <th class="p-3 text-left font-bold text-gray-700">Sem</th>
                  <th class="p-3 text-left font-bold text-gray-700">Courses</th>
                  <th class="p-3 text-left font-bold text-gray-700">Passed</th>
                  <th class="p-3 text-left font-bold text-gray-700">Failed</th>
                  <th class="p-3 text-left font-bold text-gray-700">SGPA</th>
                  <th class="p-3 text-left font-bold text-gray-700">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <?php if(!empty($student_report)): ?>
                  <?php foreach($student_report as $student):
                    $sgpa = $student['sgpa'];
                    $failed = $student['failed_courses'];
                    $passed = $student['passed_courses'];
                    $attempted = $student['attempted_courses'];
                    $total_enrolled = $student['total_enrolled_courses'];
                  
                    // Courses display: passed/total_enrolled
                    $courses_display = $passed . "/" . $total_enrolled;
                  
                    // Determine status - Relegated if SGPA < 1.5
                    if($total_enrolled == 0) {
                      $status = 'Not Enrolled';
                      $status_class = 'status-warning';
                    } elseif($attempted == 0) {
                      $status = 'No Attempts';
                      $status_class = 'status-warning';
                    } elseif($passed == 0 && $attempted > 0) {
                      $status = 'Failed All';
                      $status_class = 'status-danger';
                    } elseif($sgpa < 1.5) {
                      $status = 'Relegated';
                      $status_class = 'status-danger';
                    } elseif($sgpa < 1.0) {
                      $status = 'Probation';
                      $status_class = 'status-danger';
                    } elseif($sgpa < 1.5) {
                      $status = 'At Risk';
                      $status_class = 'status-danger';
                    } elseif($sgpa < 2.0) {
                      $status = 'Warning';
                      $status_class = 'status-warning';
                    } elseif($sgpa < 2.5) {
                      $status = 'Satisfactory';
                      $status_class = 'status-good';
                    } elseif($sgpa >= 2.5) {
                      $status = 'Good Standing';
                      $status_class = 'status-good';
                    } else {
                      $status = 'N/A';
                      $status_class = 'status-info';
                    }
                  
                    // SGPA color
                    if($sgpa >= 2.5) $sgpa_class = 'text-green-600 font-bold';
                    elseif($sgpa >= 2.0) $sgpa_class = 'text-blue-600 font-bold';
                    elseif($sgpa >= 1.5) $sgpa_class = 'text-yellow-600 font-bold';
                    elseif($sgpa >= 1.0) $sgpa_class = 'text-orange-600 font-bold';
                    elseif($sgpa > 0) $sgpa_class = 'text-red-600 font-bold';
                    else $sgpa_class = 'text-gray-500 font-bold';
                  ?>
                  <tr class="hover:bg-gray-50">
                    <td class="p-3">
                      <div class="font-medium text-gray-900 text-sm"><?= $student['StudentID'] ?></div>
                    </td>
                    <td class="p-3">
                      <div class="font-medium ellipsis" title="<?= htmlspecialchars($student['Name']) ?>"><?= htmlspecialchars($student['Name']) ?></div>
                    </td>
                    <td class="p-3">
                      <div class="text-xs"><?= $student['DepartmentName'] ?></div>
                    </td>
                    <td class="p-3">
                      <div class="text-xs font-medium bg-purple-100 text-purple-800 px-2 py-1 rounded-full w-fit">
                        Sem <?= $student['Semester'] ?>
                      </div>
                    </td>
                    <td class="p-3 text-center">
                      <div class="font-medium text-sm"><?= $courses_display ?></div>
                    </td>
                    <td class="p-3 text-center">
                      <div class="font-medium text-green-600 text-sm"><?= $passed ?></div>
                    </td>
                    <td class="p-3 text-center">
                      <div class="font-medium text-red-600 text-sm"><?= $failed ?></div>
                    </td>
                    <td class="p-3">
                      <div class="<?= $sgpa_class ?> text-sm"><?= number_format($sgpa, 2) ?></div>
                    </td>
                    <td class="p-3">
                      <span class="status-badge text-xs <?= $status_class ?>"><?= $status ?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="9" class="p-8 text-center text-gray-500">
                      <i class="fas fa-file-alt text-3xl mb-3 opacity-30"></i>
                      <p>No students found for selected criteria</p>
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        
          <?php if(!empty($student_report)): ?>
          <div class="p-4 border-t border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
              <div class="text-sm text-gray-600">
                Showing <?= count($student_report) ?> student(s) in Semester <?= $report_semester ?>
              </div>
              <div class="flex space-x-2">
                <button onclick="window.print()" class="btn-secondary">
                  <i class="fas fa-print mr-2"></i>
                  Print Report
                </button>
                <a href="admin.php?tab=student_report&report_dept=<?= $report_department ?>&report_sem=<?= $report_semester ?>&export_excel=1"
                   class="btn-success">
                  <i class="fas fa-file-excel mr-2"></i>
                  Export to Excel
                </a>
              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 modal-overlay hidden flex items-center justify-center z-50">
  <div class="modal-content p-8 max-w-2xl">
    <div class="flex items-center justify-between mb-6">
      <h3 class="text-xl font-bold text-gray-900">Edit <span id="modalType"></span></h3>
      <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
        <i class="fas fa-times"></i>
      </button>
    </div>
  
    <form id="editForm" method="post" class="space-y-4">
      <input type="hidden" name="id" id="modalID">
    
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
          <input type="email" name="email" id="modalEmail" class="input-field" required>
        </div>
      
        <div id="semesterField" style="display: none;">
          <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
          <select name="semester" id="modalSemester" class="input-field">
            <?php for($i = 1; $i <= 8; $i++): ?>
              <option value="<?= $i ?>">Semester <?= $i ?></option>
            <?php endfor; ?>
          </select>
        </div>
      
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
          <select name="dept" id="modalDept" class="input-field" required>
            <option value="">Select Department</option>
            <?php
            mysqli_data_seek($departments, 0);
            while($dept = mysqli_fetch_assoc($departments)):
            ?>
            <option value="<?= $dept['DepartmentID'] ?>"><?= htmlspecialchars($dept['DepartmentName']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
      
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
          <input type="tel" name="phone" id="modalPhone" class="input-field">
        </div>
      
        <div id="yearsField" style="display: none;">
          <label class="block text-sm font-medium text-gray-700 mb-2">Years of Service</label>
          <input type="number" name="years_of_service" id="modalYears" min="0" class="input-field">
        </div>
      </div>
    
      <!-- Student extra fields -->
      <div id="studentExtraFields" style="display: none;" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Father's Name</label>
          <input type="text" name="father_name" id="modalFatherName" class="input-field">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
          <input type="date" name="dob" id="modalDOB" class="input-field">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
          <select name="gender" id="modalGender" class="input-field">
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Admission Date</label>
          <input type="date" name="admission_date" id="modalAdmissionDate" class="input-field">
        </div>
      </div>
    
      <!-- Course Assignment Section (for faculty) -->
      <div id="courseAssignmentSection" style="display: none;">
        <label class="block text-sm font-medium text-gray-700 mb-2">Assign Courses</label>
        <div id="modalCourseSelection" class="bg-gray-50 p-4 rounded-lg max-h-60 overflow-y-auto">
          <!-- Courses will be loaded dynamically -->
        </div>
      </div>
    
      <div class="pt-6 border-t border-gray-200">
        <div class="flex justify-end space-x-4">
          <button type="button" onclick="closeModal()" class="btn-secondary px-6">Cancel</button>
          <button type="submit" id="modalSubmitBtn" class="btn-primary px-6">Save Changes</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Course View Modal -->
<div id="courseViewModal" class="fixed inset-0 modal-overlay hidden flex items-center justify-center z-50">
  <div class="modal-content p-6 max-w-md">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-bold text-gray-900" id="courseModalTitle"></h3>
      <button onclick="closeCourseModal()" class="text-gray-400 hover:text-gray-600">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div id="courseListContent" class="max-h-64 overflow-y-auto space-y-2"></div>
    <div class="mt-6 pt-4 border-t border-gray-200">
      <button onclick="closeCourseModal()" class="btn-secondary w-full">Close</button>
    </div>
  </div>
</div>
<script>
// Open edit modal with data
function openEditModal(type, id, email, dept, phone = '', additionalData = '', years = '', extraData = '') {
  const modal = document.getElementById('editModal');
  // Set modal title
  document.getElementById('modalType').textContent = type === 'student' ? 'Student' : 'Faculty';
  // Set form values
  document.getElementById('modalID').value = id;
  document.getElementById('modalEmail').value = email;
  document.getElementById('modalDept').value = dept;
  document.getElementById('modalPhone').value = phone;
  // Show/hide fields
  const semesterField = document.getElementById('semesterField');
  const yearsField = document.getElementById('yearsField');
  const courseAssignmentSection = document.getElementById('courseAssignmentSection');
  const studentExtraFields = document.getElementById('studentExtraFields');
  if(type === 'student') {
    semesterField.style.display = 'block';
    yearsField.style.display = 'none';
    courseAssignmentSection.style.display = 'none';
    studentExtraFields.style.display = 'grid';
  
    document.getElementById('modalSemester').value = additionalData || 1;
  
    // Fill extra fields if data is passed
    if(extraData) {
      try {
        const data = JSON.parse(extraData);
        document.getElementById('modalFatherName').value = data.father_name || '';
        document.getElementById('modalDOB').value = data.dob || '';
        document.getElementById('modalGender').value = data.gender || '';
        document.getElementById('modalAdmissionDate').value = data.admission_date || '';
      } catch(e) {
        console.log('No extra data');
      }
    }
  
    document.getElementById('modalSubmitBtn').name = 'update_student';
  } else {
    semesterField.style.display = 'none';
    yearsField.style.display = 'block';
    courseAssignmentSection.style.display = 'block';
    studentExtraFields.style.display = 'none';
  
    document.getElementById('modalYears').value = years || 0;
  
    loadFacultyCoursesForEdit(dept, additionalData);
  
    document.getElementById('modalSubmitBtn').name = 'update_faculty';
  }
  // Show modal
  modal.classList.remove('hidden');
}
// Load courses for faculty edit
function loadFacultyCoursesForEdit(dept, selectedCoursesJson = '') {
  const courseSelection = document.getElementById('modalCourseSelection');
  if(!dept) {
    courseSelection.innerHTML = '<p class="text-sm text-gray-500">Select department first</p>';
    return;
  }
  // Parse selected courses
  let selectedCourses = [];
  if(selectedCoursesJson) {
    try {
      selectedCourses = JSON.parse(selectedCoursesJson);
    } catch(e) {
      selectedCourses = selectedCoursesJson.split(', ');
    }
  }
  fetch(`get_courses.php?dept=${dept}&type=faculty&selected=${encodeURIComponent(JSON.stringify(selectedCourses))}`)
    .then(response => response.text())
    .then(data => {
      courseSelection.innerHTML = data;
    })
    .catch(error => {
      courseSelection.innerHTML = '<p class="text-sm text-red-500">Error loading courses</p>';
    });
}
// View all courses for a faculty
function viewAllCourses(coursesJson, facultyName) {
  try {
    const courses = JSON.parse(coursesJson);
    const modal = document.getElementById('courseViewModal');
    const title = document.getElementById('courseModalTitle');
    const content = document.getElementById('courseListContent');
  
    title.textContent = `Courses Assigned to ${facultyName}`;
  
    if (courses.length === 0) {
      content.innerHTML = '<p class="text-gray-500 text-center py-8">No courses assigned</p>';
    } else {
      let html = '';
      courses.forEach(course => {
        html += `
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <div>
              <div class="font-medium text-sm">${course.code || ''}</div>
              <div class="text-xs text-gray-600">${course.name || course}</div>
            </div>
          </div>
        `;
      });
      content.innerHTML = html;
    }
  
    modal.classList.remove('hidden');
  } catch (error) {
    console.error('Error parsing courses:', error);
    alert('Error loading courses');
  }
}
// Close course modal
function closeCourseModal() {
  document.getElementById('courseViewModal').classList.add('hidden');
}
// Close edit modal
function closeModal() {
  document.getElementById('editModal').classList.add('hidden');
}
// Close modal on escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeModal();
    closeCourseModal();
  }
});
// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeModal();
  }
});
document.getElementById('courseViewModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeCourseModal();
  }
});
</script>
</body>
</html>