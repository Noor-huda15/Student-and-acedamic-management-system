<?php
require "config.php";

// Check if student is logged in
if(!isset($_SESSION['id']) || $_SESSION['role'] != 'Student') {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['id'];

// Get student info - FIXED: ContactInfo for phone number
$student_query = mysqli_query($conn, "SELECT * FROM student WHERE StudentID='$student_id'");
$student_data = mysqli_fetch_assoc($student_query);
$student_name = $student_data['Name'];
$student_dept = $student_data['Department'] ?? $student_data['department'] ?? 'Computer Science';
$current_semester = $student_data['Semester'] ?? '6';

// FIXED: Phone number from ContactInfo column
$contact_info = $student_data['ContactInfo'] ?? '+1 (555) 987-6543';

// Get semester number
$semester_number = (int) filter_var($current_semester, FILTER_SANITIZE_NUMBER_INT);
if($semester_number == 0) {
    $semester_number = 6;
}

// Get enrolled courses for current semester from enrollment table
$courses_query = mysqli_query($conn, 
    "SELECT 
        e.CourseID,
        e.Semester as EnrolledSemester,
        c.CourseName,
        c.Credits,
        f.Name as FacultyName,
        g.Quiz,
        g.Assignment,
        g.Midterm,
        g.FinalExam,
        g.TotalPercentage,
        g.LetterGrade,
        g.GPA as CourseGPA
     FROM enrollment e
     JOIN course c ON e.CourseID = c.CourseID
     LEFT JOIN faculty f ON c.FacultyID = f.FacultyID
     LEFT JOIN grade g ON g.StudentID = e.StudentID AND g.CourseID = e.CourseID
     WHERE e.StudentID = '$student_id'
     AND e.Semester = '$semester_number'
     ORDER BY c.CourseName");

// Count enrolled courses for current semester
$courses_count_query = mysqli_query($conn,
    "SELECT COUNT(*) as count 
     FROM enrollment 
     WHERE StudentID = '$student_id' 
     AND Semester = '$semester_number'");
$courses_count = mysqli_fetch_assoc($courses_count_query)['count'];

// Calculate SGPA from enrolled courses in current semester
$sgpa_query = mysqli_query($conn,
    "SELECT 
        SUM(COALESCE(g.GPA, 0)) as total_gpa,
        COUNT(*) as enrolled_count
     FROM enrollment e
     LEFT JOIN grade g ON e.StudentID = g.StudentID AND e.CourseID = g.CourseID
     WHERE e.StudentID = '$student_id'
     AND e.Semester = '$semester_number'");
     
$sgpa_data = mysqli_fetch_assoc($sgpa_query);
$total_gpa = $sgpa_data['total_gpa'] ?? 0;
$enrolled_count = $sgpa_data['enrolled_count'] ?? 0;

// SGPA = (sum of GPAs for enrolled courses + 0 for missing courses) / 6
$sgpa = round(($total_gpa + (0 * (6 - $enrolled_count))) / 6, 2);

// Calculate academic standing
$academic_standing = $sgpa > 2.5 ? 'Good Standing' : 'Needs Improvement';

// Calculate CGPA (all grades from all semesters) - FIXED VERSION
$cgpa_query = mysqli_query($conn, 
    "SELECT AVG(GPA) as cgpa FROM grade WHERE StudentID='$student_id' AND GPA > 0");
$cgpa_data = mysqli_fetch_assoc($cgpa_query);
$cgpa = $cgpa_data['cgpa'] ? round($cgpa_data['cgpa'], 2) : 0.00;

// If CGPA is showing incorrectly, use weighted calculation or set default
if($cgpa == 0.93 || $cgpa < 1.0) {
    // Use weighted CGPA calculation (GPA × Credits)
    $weighted_cgpa = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT 
            SUM(g.GPA * COALESCE(c.Credits, 3)) / SUM(COALESCE(c.Credits, 3)) as weighted_cgpa
         FROM grade g
         LEFT JOIN course c ON g.CourseID = c.CourseID
         WHERE g.StudentID='$student_id' AND g.GPA > 0"))['weighted_cgpa'];
    
    $cgpa = $weighted_cgpa ? round($weighted_cgpa, 2) : 0.00;
    
    // If still very low or 0, show N/A
    if($cgpa < 1.0 && $cgpa > 0) {
        // Keep as is - might be actual grade
    } elseif($cgpa == 0) {
        $cgpa = "N/A"; // No grades recorded yet
    }
}

$active_tab = $_GET['tab'] ?? 'profile';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8f5fd; letter-spacing: -0.01em; }
        .sidebar { background: linear-gradient(180deg, #735da5 0%, #5a4a84 100%); }
        .stat-card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .grade-card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; }
        .course-header { background: linear-gradient(135deg, #735da5 0%, #a991d4 100%); }
        .profile-value { font-size: 15px; color: #374151; font-weight: 500; }
        .profile-label { font-size: 13px; color: #6b7280; font-weight: 400; }
        .grade-a { color: #735da5; font-weight: 700; }
        .grade-b { color: #8b7cb3; font-weight: 700; }
        .grade-c { color: #a991d4; font-weight: 700; }
        .grade-d { color: #c7b5e5; font-weight: 700; }
        .grade-f { color: #e5d9f2; font-weight: 700; }
        .attendance-high { color: #735da5; }
        .attendance-medium { color: #a991d4; }
        .attendance-low { color: #e5d9f2; }
        .progress-bar { height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 4px; background: linear-gradient(90deg, #735da5 0%, #a991d4 100%); transition: width 1s ease-in-out; }
        .gpa-badge { background: linear-gradient(135deg, #735da5 0%, #a991d4 100%); }
        .table-header { background: #735da5; color: white; }
        .table-header th { background: #735da5; }
        .dept-badge { background: linear-gradient(135deg, #8b7cb3 0%, #a991d4 100%); }
    </style>
</head>
<body class="min-h-screen text-gray-800">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="sidebar w-64 min-h-screen p-8 text-white flex flex-col">
        <div class="flex-1">
            <div class="flex items-center space-x-4 mb-12">
                <i class="fas fa-graduation-cap text-3xl"></i>
                <div>
                    <h1 class="text-xl font-bold">Student Portal</h1>
                    <p class="text-sm opacity-90"><?= $student_id ?></p>
                </div>
            </div>
            
            <div class="mb-12">
                <div class="text-2xl font-bold mb-2"><?= htmlspecialchars($student_name) ?></div>
                <p class="text-sm opacity-80"><?= htmlspecialchars($student_dept) ?> • Semester <?= $semester_number ?></p>
            </div>

            <nav class="space-y-2">
                <a href="student.php?tab=profile" class="flex items-center space-x-3 p-3 rounded-lg <?= $active_tab=='profile' ? 'bg-white bg-opacity-10' : 'hover:bg-white hover:bg-opacity-5' ?>">
                    <i class="fas fa-user w-6"></i>
                    <span>Profile</span>
                </a>
                <a href="student.php?tab=courses" class="flex items-center space-x-3 p-3 rounded-lg <?= $active_tab=='courses' ? 'bg-white bg-opacity-10' : 'hover:bg-white hover:bg-opacity-5' ?>">
                    <i class="fas fa-book w-6"></i>
                    <span>My Courses</span>
                </a>
                <a href="student.php?tab=grades" class="flex items-center space-x-3 p-3 rounded-lg <?= $active_tab=='grades' ? 'bg-white bg-opacity-10' : 'hover:bg-white hover:bg-opacity-5' ?>">
                    <i class="fas fa-chart-bar w-6"></i>
                    <span>Grades</span>
                </a>
                <a href="student.php?tab=attendance" class="flex items-center space-x-3 p-3 rounded-lg <?= $active_tab=='attendance' ? 'bg-white bg-opacity-10' : 'hover:bg-white hover:bg-opacity-5' ?>">
                    <i class="fas fa-calendar-alt w-6"></i>
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
    <div class="flex-1 p-8">
        <div class="max-w-7xl mx-auto">
            <!-- Profile Tab -->
            <?php if($active_tab == 'profile'): ?>
            <div class="space-y-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Student Profile</h1>
                        <p class="text-gray-600 mt-1">Academic overview and personal information</p>
                    </div>
                    <div class="flex items-center space-x-2 text-sm">
                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full">Student</span>
                        <span class="dept-badge text-white px-3 py-1 rounded-full"><?= htmlspecialchars($student_dept) ?></span>
                        <span class="text-gray-500">Semester <?= $semester_number ?></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <div class="stat-card">
                            <div class="flex items-start space-x-6">
                                <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-lilac rounded-xl flex items-center justify-center">
                                    <i class="fas fa-user-graduate text-4xl text-purple-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h2 class="text-xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($student_name) ?></h2>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="profile-label">Student ID</p>
                                            <p class="profile-value mt-1"><?= $student_id ?></p>
                                        </div>
                                        <div>
                                            <p class="profile-label">Department</p>
                                            <p class="profile-value mt-1"><?= htmlspecialchars($student_dept) ?></p>
                                        </div>
                                        <div>
                                            <p class="profile-label">Program</p>
                                            <p class="profile-value mt-1"><?= htmlspecialchars($student_data['Program'] ?? 'Undergraduate') ?></p>
                                        </div>
                                        <div>
                                            <p class="profile-label">Current Semester</p>
                                            <p class="profile-value mt-1">Semester <?= $semester_number ?></p>
                                        </div>
                                        <div>
                                            <p class="profile-label">Email</p>
                                            <p class="profile-value mt-1"><?= htmlspecialchars($student_data['Email'] ?? $student_id . '@student.edu') ?></p>
                                        </div>
                                        <div>
                                            <p class="profile-label">Phone</p>
                                            <p class="profile-value mt-1"><?= htmlspecialchars($contact_info) ?></p>
                                        </div>
                                        <div class="col-span-2">
                                            <p class="profile-label">Address</p>
                                            <p class="profile-value mt-1"><?= htmlspecialchars($student_data['Address'] ?? '123 University Ave, City, State 12345') ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Stats - UPDATED: Removed CGPA card -->
                        <div class="grid grid-cols-2 gap-6 mt-8">
                            <div class="stat-card">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="profile-label">Courses Enrolled</p>
                                        <p class="text-3xl font-bold text-gray-900 mt-2"><?= $courses_count ?></p>
                                        <p class="text-xs text-gray-500 mt-1">Semester <?= $semester_number ?></p>
                                    </div>
                                    <i class="fas fa-book text-purple-600 text-2xl"></i>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="profile-label">Semester GPA (SGPA)</p>
                                        <p class="text-3xl font-bold text-gray-900 mt-2"><?= $sgpa ?></p>
                                        <p class="text-xs text-gray-500 mt-1">Out of 4.0</p>
                                    </div>
                                    <i class="fas fa-star text-purple-600 text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Academic Standing -->
                    <div class="space-y-6">
                        <div class="stat-card">
                            <h3 class="font-semibold text-gray-900 mb-4">Academic Standing</h3>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">SGPA Progress</span>
                                        <span class="font-medium"><?= $sgpa ?>/4.0</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?= min(100, ($sgpa/4)*100) ?>%"></div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Academic Status</span>
                                    <span class="font-medium <?= $sgpa > 2.5 ? 'text-green-600' : 'text-yellow-600' ?>">
                                        <?= $academic_standing ?>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Department</span>
                                    <span class="font-medium text-purple-600"><?= htmlspecialchars($student_dept) ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Current Semester</span>
                                    <span class="font-medium">Semester <?= $semester_number ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Enrolled/Total Courses</span>
                                    <span class="font-medium"><?= $courses_count ?>/6</span>
                                </div>
                            </div>
                        </div>

                        <div class="stat-card">
                            <h3 class="font-semibold text-gray-900 mb-4">SGPA Calculation</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Courses with Grades</span>
                                    <span class="font-medium"><?= $enrolled_count ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Missing Courses (0 GPA)</span>
                                    <span class="font-medium"><?= max(0, 6 - $courses_count) ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Total Courses for SGPA</span>
                                    <span class="font-medium">6</span>
                                </div>
                                <div class="pt-2 border-t border-gray-100">
                                    <p class="text-xs text-gray-500">SGPA = (Sum of GPAs + (0 × missing courses)) ÷ 6</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Courses Tab -->
            <?php if($active_tab == 'courses'): ?>
            <div class="space-y-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">My Courses - Semester <?= $semester_number ?></h1>
                        <p class="text-gray-600 mt-1">Enrolled courses from enrollment table</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">Total Enrolled: <?= $courses_count ?> courses</span>
                        <span class="dept-badge text-white px-3 py-1 rounded-full text-sm"><?= htmlspecialchars($student_dept) ?></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php 
                    mysqli_data_seek($courses_query, 0);
                    $has_courses = false;
                    while($course = mysqli_fetch_assoc($courses_query)): 
                        $has_courses = true;
                        $course_gpa = $course['CourseGPA'] ? number_format($course['CourseGPA'], 2) : 'N/A';
                        $grade_class = $course_gpa !== 'N/A' && $course_gpa >= 3.7 ? 'grade-a' : 
                                      ($course_gpa !== 'N/A' && $course_gpa >= 3.0 ? 'grade-b' : 
                                      ($course_gpa !== 'N/A' && $course_gpa >= 2.0 ? 'grade-c' : 
                                      ($course_gpa !== 'N/A' && $course_gpa >= 1.0 ? 'grade-d' : 'grade-f')));
                    ?>
                    <div class="grade-card hover:shadow-lg transition-shadow">
                        <div class="course-header rounded-lg -m-6 mb-6 p-6 text-white">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-xl font-bold"><?= htmlspecialchars($course['CourseName']) ?></h3>
                                    <p class="text-sm opacity-90 mt-1"><?= $course['CourseID'] ?> • <?= $course['Credits'] ?? '3' ?> Credits</p>
                                </div>
                                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-medium">
                                    Semester <?= $course['EnrolledSemester'] ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="profile-label">Instructor</p>
                                <p class="profile-value mt-1"><?= htmlspecialchars($course['FacultyName']) ?></p>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="profile-label">Course GPA</p>
                                    <p class="text-2xl font-bold mt-1 <?= $grade_class ?>">
                                        <?= $course_gpa ?>
                                    </p>
                                </div>
                                
                                <div class="text-right">
                                    <p class="profile-label">Assessment</p>
                                    <div class="flex space-x-3 mt-1">
                                        <?php 
                                        // Define component totals
                                        $components = [
                                            'Quiz' => ['score' => $course['Quiz'], 'total' => 15],
                                            'Assignment' => ['score' => $course['Assignment'], 'total' => 10],
                                            'Midterm' => ['score' => $course['Midterm'], 'total' => 25],
                                            'Final' => ['score' => $course['FinalExam'], 'total' => 50]
                                        ];
                                        
                                        $has_grades = false;
                                        foreach($components as $type => $data):
                                            $score = $data['score'];
                                            $total = $data['total'];
                                            
                                            if($score !== null && $score > 0):
                                                $has_grades = true;
                                                
                                                // Calculate percentage based on actual totals
                                                $perc = ($score / $total) * 100;
                                                
                                                // Calculate letter grade based on percentage
                                                if($perc >= 90) $lgrade = 'A';
                                                elseif($perc >= 85) $lgrade = 'A-';
                                                elseif($perc >= 80) $lgrade = 'B+';
                                                elseif($perc >= 75) $lgrade = 'B';
                                                elseif($perc >= 70) $lgrade = 'B-';
                                                elseif($perc >= 65) $lgrade = 'C+';
                                                elseif($perc >= 60) $lgrade = 'C';
                                                elseif($perc >= 55) $lgrade = 'C-';
                                                elseif($perc >= 50) $lgrade = 'D';
                                                else $lgrade = 'F';
                                                
                                                // Determine grade class for coloring
                                                $gclass = 'grade-f';
                                                if(strpos($lgrade, 'A') === 0) $gclass = 'grade-a';
                                                elseif(strpos($lgrade, 'B') === 0) $gclass = 'grade-b';
                                                elseif(strpos($lgrade, 'C') === 0) $gclass = 'grade-c';
                                                elseif(strpos($lgrade, 'D') === 0) $gclass = 'grade-d';
                                        ?>
                                        <div class="text-center">
                                            <div class="text-xs text-gray-500"><?= $type ?></div>
                                            <div class="font-bold <?= $gclass ?>"><?= $lgrade ?></div>
                                        </div>
                                        <?php endif; endforeach; ?>
                                        <?php if(!$has_grades): ?>
                                        <span class="text-sm text-gray-400">No grades yet</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Attendance Summary -->
                            <?php 
                            $attendance_query = mysqli_query($conn, 
                                "SELECT Status, COUNT(*) as count 
                                 FROM attendance 
                                 WHERE StudentID='$student_id' AND CourseID='{$course['CourseID']}'
                                 GROUP BY Status");
                            $total_classes = 0;
                            $present_classes = 0;
                            while($att = mysqli_fetch_assoc($attendance_query)) {
                                $total_classes += $att['count'];
                                if($att['Status'] == 'Present') $present_classes += $att['count'];
                            }
                            $attendance_percentage = $total_classes > 0 ? round(($present_classes/$total_classes)*100) : 0;
                            $attendance_class = $attendance_percentage >= 75 ? 'attendance-high' : 
                                               ($attendance_percentage >= 50 ? 'attendance-medium' : 'attendance-low');
                            ?>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Attendance</span>
                                    <span class="font-medium <?= $attendance_class ?>"><?= $attendance_percentage ?>%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?= $attendance_percentage ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    
                    <?php if(!$has_courses): ?>
                    <div class="col-span-2">
                        <div class="text-center py-12">
                            <i class="fas fa-book-open text-5xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900">No Courses Enrolled for Semester <?= $semester_number ?></h3>
                            <p class="text-gray-600 mt-1">You are not enrolled in any courses for this semester.</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Grades Tab -->
            <?php if($active_tab == 'grades'): ?>
            <div class="space-y-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Academic Grades - Semester <?= $semester_number ?></h1>
                        <p class="text-gray-600 mt-1">Detailed grade report for enrolled courses</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="gpa-badge text-white px-4 py-2 rounded-lg">
                            <div class="text-sm">Semester GPA (SGPA)</div>
                            <div class="text-2xl font-bold"><?= $sgpa ?></div>
                        </div>
                    </div>
                </div>

                <!-- Grades Overview -->
                <div class="grid grid-cols-4 gap-6 mb-8">
                    <div class="stat-card">
                        <div class="text-center">
                            <div class="text-3xl font-bold grade-a mb-2">A</div>
                            <div class="text-sm text-gray-600">Excellent</div>
                            <div class="text-xs text-gray-500">≥ 90%</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="text-center">
                            <div class="text-3xl font-bold grade-b mb-2">B</div>
                            <div class="text-sm text-gray-600">Good</div>
                            <div class="text-xs text-gray-500">80-89%</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="text-center">
                            <div class="text-3xl font-bold grade-c mb-2">C</div>
                            <div class="text-sm text-gray-600">Average</div>
                            <div class="text-xs text-gray-500">70-79%</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="text-center">
                            <div class="text-3xl font-bold grade-d mb-2">D</div>
                            <div class="text-sm text-gray-600">Passing</div>
                            <div class="text-xs text-gray-500">60-69%</div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Grades Table -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="table-header">
                                <tr>
                                    <th class="p-4 text-left">Course</th>
                                    <th class="p-4 text-left">Instructor</th>
                                    <th class="p-4 text-center">Quiz<br><span class="text-xs opacity-90">/15</span></th>
                                    <th class="p-4 text-center">Assignment<br><span class="text-xs opacity-90">/10</span></th>
                                    <th class="p-4 text-center">Midterm<br><span class="text-xs opacity-90">/25</span></th>
                                    <th class="p-4 text-center">Final<br><span class="text-xs opacity-90">/50</span></th>
                                    <th class="p-4 text-center">Total<br><span class="text-xs opacity-90">%</span></th>
                                    <th class="p-4 text-center">Grade</th>
                                    <th class="p-4 text-center">GPA</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php 
                                mysqli_data_seek($courses_query, 0);
                                $has_grades = false;
                                while($course = mysqli_fetch_assoc($courses_query)): 
                                    $has_grades = true;
                                    $final_grade = $course['LetterGrade'] ?: '-';
                                    $grade_class = '';
                                    if ($final_grade != '-') {
                                        if (strpos($final_grade, 'A') === 0) $grade_class = 'grade-a';
                                        elseif (strpos($final_grade, 'B') === 0) $grade_class = 'grade-b';
                                        elseif (strpos($final_grade, 'C') === 0) $grade_class = 'grade-c';
                                        elseif (strpos($final_grade, 'D') === 0) $grade_class = 'grade-d';
                                        elseif ($final_grade == 'F') $grade_class = 'grade-f';
                                    }
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="p-4">
                                        <div>
                                            <div class="font-medium text-gray-900"><?= htmlspecialchars($course['CourseName']) ?></div>
                                            <div class="text-sm text-gray-500"><?= $course['CourseID'] ?> (Sem <?= $course['EnrolledSemester'] ?>)</div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="text-sm"><?= htmlspecialchars($course['FacultyName']) ?></div>
                                    </td>
                                    
                                    <!-- Quiz -->
                                    <td class="p-4 text-center">
                                        <?= $course['Quiz'] !== null && $course['Quiz'] > 0 ? number_format($course['Quiz'], 2) : '-' ?>
                                    </td>
                                    
                                    <!-- Assignment -->
                                    <td class="p-4 text-center">
                                        <?= $course['Assignment'] !== null && $course['Assignment'] > 0 ? number_format($course['Assignment'], 2) : '-' ?>
                                    </td>
                                    
                                    <!-- Midterm -->
                                    <td class="p-4 text-center">
                                        <?= $course['Midterm'] !== null && $course['Midterm'] > 0 ? number_format($course['Midterm'], 2) : '-' ?>
                                    </td>
                                    
                                    <!-- Final -->
                                    <td class="p-4 text-center">
                                        <?= $course['FinalExam'] !== null && $course['FinalExam'] > 0 ? number_format($course['FinalExam'], 2) : '-' ?>
                                    </td>
                                    
                                    <!-- Total Percentage -->
                                    <td class="p-4 text-center">
                                        <div class="font-medium text-purple-600"><?= $course['TotalPercentage'] ? number_format($course['TotalPercentage'], 2) : '-' ?></div>
                                    </td>
                                    
                                    <!-- Final Grade -->
                                    <td class="p-4 text-center">
                                        <div class="<?= $grade_class ?> font-bold text-lg"><?= $final_grade ?></div>
                                    </td>
                                    
                                    <!-- GPA -->
                                    <td class="p-4 text-center">
                                        <div class="font-bold <?= $course['CourseGPA'] !== null ? 
                                            ($course['CourseGPA'] >= 3.7 ? 'grade-a' : 
                                            ($course['CourseGPA'] >= 3.0 ? 'grade-b' : 
                                            ($course['CourseGPA'] >= 2.0 ? 'grade-c' : 
                                            ($course['CourseGPA'] >= 1.0 ? 'grade-d' : 'grade-f')))) : '' ?>">
                                            <?= $course['CourseGPA'] !== null ? number_format($course['CourseGPA'], 2) : '-' ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                
                                <?php if(!$has_grades): ?>
                                <tr>
                                    <td colspan="9" class="p-8 text-center">
                                        <i class="fas fa-chart-bar text-4xl text-gray-300 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900">No Grades Available</h3>
                                        <p class="text-gray-600 mt-1">You don't have any grades recorded yet for your enrolled courses.</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Attendance Tab -->
            <?php if($active_tab == 'attendance'): ?>
            <div class="space-y-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Attendance Records - Semester <?= $semester_number ?></h1>
                        <p class="text-gray-600 mt-1">Class attendance history for enrolled courses</p>
                    </div>
                </div>

                <!-- Attendance Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <?php 
                    // Overall attendance for current semester courses
                    $overall_attendance = mysqli_query($conn, 
                        "SELECT a.Status, COUNT(*) as count 
                         FROM attendance a
                         JOIN enrollment e ON a.StudentID = e.StudentID AND a.CourseID = e.CourseID
                         WHERE a.StudentID='$student_id'
                         AND e.Semester = '$semester_number'
                         GROUP BY a.Status");
                    
                    $total_classes = 0;
                    $present_classes = 0;
                    while($att = mysqli_fetch_assoc($overall_attendance)) {
                        $total_classes += $att['count'];
                        if($att['Status'] == 'Present') $present_classes += $att['count'];
                    }
                    $overall_percentage = $total_classes > 0 ? round(($present_classes / $total_classes) * 100) : 0;
                    $overall_class = $overall_percentage >= 75 ? 'attendance-high' : 
                                   ($overall_percentage >= 50 ? 'attendance-medium' : 'attendance-low');
                    ?>
                    <div class="stat-card">
                        <div class="text-center">
                            <div class="text-3xl font-bold <?= $overall_class ?> mb-2"><?= $overall_percentage ?>%</div>
                            <div class="text-sm text-gray-600">Semester Attendance</div>
                            <div class="text-xs text-gray-500"><?= $present_classes ?>/<?= $total_classes ?> classes</div>
                        </div>
                    </div>
                    
                    <?php 
                    // Month attendance
                    $month_attendance = mysqli_query($conn, 
                        "SELECT a.Status, COUNT(*) as count 
                         FROM attendance a
                         JOIN enrollment e ON a.StudentID = e.StudentID AND a.CourseID = e.CourseID
                         WHERE a.StudentID='$student_id'
                         AND e.Semester = '$semester_number'
                         AND YEAR(a.AttendanceDate) = YEAR(CURDATE()) 
                         AND MONTH(a.AttendanceDate) = MONTH(CURDATE())
                         GROUP BY a.Status");
                    
                    $month_total = 0;
                    $month_present = 0;
                    while($att = mysqli_fetch_assoc($month_attendance)) {
                        $month_total += $att['count'];
                        if($att['Status'] == 'Present') $month_present += $att['count'];
                    }
                    $month_percentage = $month_total > 0 ? round(($month_present / $month_total) * 100) : 0;
                    $month_class = $month_percentage >= 75 ? 'attendance-high' : 
                                 ($month_percentage >= 50 ? 'attendance-medium' : 'attendance-low');
                    ?>
                    <div class="stat-card">
                        <div class="text-center">
                            <div class="text-3xl font-bold <?= $month_class ?> mb-2"><?= $month_percentage ?>%</div>
                            <div class="text-sm text-gray-600">This Month</div>
                            <div class="text-xs text-gray-500"><?= date('F Y') ?></div>
                        </div>
                    </div>
                    
                    <?php 
                    // Perfect attendance courses
                    $perfect_courses = mysqli_query($conn, 
                        "SELECT c.CourseName, 
                                SUM(CASE WHEN a.Status = 'Present' THEN 1 ELSE 0 END) as present,
                                COUNT(*) as total
                         FROM attendance a
                         JOIN course c ON a.CourseID = c.CourseID
                         JOIN enrollment e ON a.StudentID = e.StudentID AND a.CourseID = e.CourseID
                         WHERE a.StudentID='$student_id'
                         AND e.Semester = '$semester_number'
                         GROUP BY a.CourseID, c.CourseName
                         HAVING present = total AND total > 0");
                    
                    $perfect_count = mysqli_num_rows($perfect_courses);
                    ?>
                    <div class="stat-card">
                        <div class="text-center">
                            <div class="text-3xl font-bold grade-a mb-2"><?= $perfect_count ?></div>
                            <div class="text-sm text-gray-600">Perfect Attendance</div>
                            <div class="text-xs text-gray-500">100% courses</div>
                        </div>
                    </div>
                </div>

                <!-- Course-wise Attendance -->
                <div class="space-y-6">
                    <h2 class="text-lg font-semibold text-gray-900">Course-wise Attendance</h2>
                    
                    <?php 
                    mysqli_data_seek($courses_query, 0);
                    $has_courses = false;
                    while($course = mysqli_fetch_assoc($courses_query)): 
                        $has_courses = true;
                        $attendance_details = mysqli_query($conn, 
                            "SELECT AttendanceDate, Status
                             FROM attendance
                             WHERE StudentID='$student_id' AND CourseID='{$course['CourseID']}'
                             ORDER BY AttendanceDate DESC");
                        
                        $total_classes = 0;
                        $present_classes = 0;
                        $attendance_dates = [];
                        while($att = mysqli_fetch_assoc($attendance_details)) {
                            $attendance_dates[] = $att;
                            $total_classes++;
                            if($att['Status'] == 'Present') $present_classes++;
                        }
                        $attendance_percentage = $total_classes > 0 ? round(($present_classes/$total_classes)*100) : 0;
                        $attendance_class = $attendance_percentage >= 75 ? 'attendance-high' : 
                                           ($attendance_percentage >= 50 ? 'attendance-medium' : 'attendance-low');
                    ?>
                    <div class="grade-card">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($course['CourseName']) ?></h3>
                                <p class="text-sm text-gray-600 mt-1"><?= $course['CourseID'] ?> • <?= htmlspecialchars($course['FacultyName']) ?> (Sem <?= $course['EnrolledSemester'] ?>)</p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold <?= $attendance_class ?>"><?= $attendance_percentage ?>%</div>
                                <div class="text-sm text-gray-600"><?= $present_classes ?>/<?= $total_classes ?> classes</div>
                            </div>
                        </div>
                        
                        <?php if(!empty($attendance_dates)): ?>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Recent Classes</span>
                                <span class="text-gray-500">Status</span>
                            </div>
                            
                            <?php 
                            $recent_dates = array_slice($attendance_dates, 0, 5);
                            foreach($recent_dates as $att):
                                $status_color = $att['Status'] == 'Present' ? 'text-purple-600 bg-purple-50' : 
                                              ($att['Status'] == 'Absent' ? 'text-red-400 bg-red-50' : 
                                              ($att['Status'] == 'Late' ? 'text-yellow-500 bg-yellow-50' : 'text-blue-400 bg-blue-50'));
                            ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center <?= $status_color ?>">
                                        <?= substr($att['Status'], 0, 1) ?>
                                    </div>
                                    <div>
                                        <div class="font-medium"><?= date('l, M d', strtotime($att['AttendanceDate'])) ?></div>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-sm font-medium <?= $status_color ?>">
                                    <?= $att['Status'] ?>
                                </span>
                            </div>
                            <?php endforeach; ?>
                            
                            <?php if(count($attendance_dates) > 5): ?>
                            <div class="text-center pt-2">
                                <span class="text-sm text-gray-500">+ <?= count($attendance_dates) - 5 ?> more classes</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-times text-3xl text-gray-300 mb-3"></i>
                            <p class="text-gray-600">No attendance records found for this course</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endwhile; ?>
                    
                    <?php if(!$has_courses): ?>
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-alt text-5xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900">No Attendance Records</h3>
                        <p class="text-gray-600 mt-1">You are not enrolled in any courses with attendance records.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.progress-fill').forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.width = width;
        }, 100);
    });
});
</script>
</body>
</html>