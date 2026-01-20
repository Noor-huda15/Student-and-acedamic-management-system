<?php
require "config.php";

$dept = $_GET['dept'] ?? '';
$semester = $_GET['semester'] ?? '';
$type = $_GET['type'] ?? 'student';
$selected_json = $_GET['selected'] ?? '[]';
$unassigned = $_GET['unassigned'] ?? false;

$selected_courses = json_decode($selected_json, true);
if(!is_array($selected_courses)) $selected_courses = [];

// Validate inputs
if(empty($dept)) {
    echo "<p class='text-sm text-gray-500'>Please select a department</p>";
    exit();
}

if($type == 'student' && empty($semester)) {
    echo "<p class='text-sm text-gray-500'>Please select a semester</p>";
    exit();
}

// Build query
if($type == 'student') {
    $query = "SELECT * FROM Course WHERE DepartmentID='$dept' AND Semester='$semester' ORDER BY CourseName";
} else {
    $query = "SELECT * FROM Course WHERE DepartmentID='$dept'";
    
    // If unassigned parameter is true, show only courses not assigned to any faculty
    if($unassigned == 'true') {
        $query .= " AND (FacultyID IS NULL OR FacultyID = '')";
    }
    
    $query .= " ORDER BY Semester, CourseName";
}

$result = mysqli_query($conn, $query);

if(!$result) {
    // Query failed
    echo "<p class='text-sm text-red-500'>Error loading courses: " . mysqli_error($conn) . "</p>";
    exit();
}

if(mysqli_num_rows($result) > 0) {
    $course_count = 0;
    while($row = mysqli_fetch_assoc($result)) {
        $course_id = $row['CourseID'];
        $course_name = htmlspecialchars($row['CourseName']);
        $course_semester = $row['Semester'] ?? 1;
        $faculty_id = $row['FacultyID'] ?? '';
        
        // Check if course is selected
        $is_selected = false;
        foreach($selected_courses as $selected) {
            if($selected == $course_name || $selected == $course_id) {
                $is_selected = true;
                break;
            }
        }
        $checked = $is_selected ? 'checked' : '';
        
        // Add indicator for assigned courses in faculty edit mode (when not showing unassigned only)
        $assigned_indicator = '';
        if($type == 'faculty' && $unassigned != 'true' && !empty($faculty_id)) {
            $assigned_indicator = " <span class='text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded ml-2'>Assigned</span>";
        }
        
        if($type == 'student') {
            echo "<label class='course-label'>
                    <input type='checkbox' class='course-checkbox' name='courses[]' value='$course_id' $checked>
                    <div>
                        <div class='font-medium'>$course_name$assigned_indicator</div>
                        <div class='text-xs text-gray-500'>$course_id • Semester $course_semester</div>
                    </div>
                  </label>";
        } else {
            echo "<label class='course-label'>
                    <input type='checkbox' class='course-checkbox' name='faculty_courses[]' value='$course_id' $checked>
                    <div>
                        <div class='font-medium'>$course_name$assigned_indicator</div>
                        <div class='text-xs text-gray-500'>$course_id • Semester $course_semester</div>
                    </div>
                  </label>";
        }
        $course_count++;
    }
    
    // Show summary message for unassigned courses
    if($type == 'faculty' && $unassigned == 'true') {
        echo "<div class='mt-4 p-3 bg-blue-50 rounded-lg'>
                <p class='text-sm text-blue-700'>Showing $course_count unassigned course(s) available for assignment.</p>
              </div>";
    }
} else {
    if($type == 'student') {
        echo "<p class='text-sm text-gray-500'>No courses available for Semester $semester in this department</p>";
    } else {
        if($unassigned == 'true') {
            echo "<div class='p-4 bg-yellow-50 rounded-lg'>
                    <p class='text-sm text-yellow-700'>No unassigned courses available in this department.</p>
                    <p class='text-xs text-yellow-600 mt-1'>All courses are already assigned to faculty members.</p>
                  </div>";
        } else {
            echo "<p class='text-sm text-gray-500'>No courses available for this department</p>";
        }
    }
}
?>