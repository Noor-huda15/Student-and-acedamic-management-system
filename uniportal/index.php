<?php require "config.php"; 

if(isset($_POST['login'])){
    $id   = trim($_POST['userid']);
    $name = trim($_POST['password']);

    // STUDENT
    $q = mysqli_query($conn,"SELECT Name FROM Student WHERE StudentID='$id'");
    if(mysqli_num_rows($q)==1 && strcasecmp(mysqli_fetch_assoc($q)['Name'], $name)==0){
        $_SESSION['id'] = $id; $_SESSION['role'] = 'Student'; $_SESSION['name'] = $name;
        header("Location: student.php"); exit();
    }

    // FACULTY
    $q = mysqli_query($conn,"SELECT Name FROM Faculty WHERE FacultyID='$id'");
    if(mysqli_num_rows($q)==1 && strcasecmp(mysqli_fetch_assoc($q)['Name'], $name)==0){
        $_SESSION['id'] = $id; $_SESSION['role'] = 'Faculty'; $_SESSION['name'] = $name;
        header("Location: faculty.php"); exit();
    }

    // ADMIN
    if($id=="ADMIN001" && strtoupper($name)=="ADMIN"){
        $_SESSION['id'] = $id; $_SESSION['role'] = 'Admin';
        header("Location: admin.php"); exit();
    }

    $error = "Invalid ID or Name";
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>University Portal</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { font-family: 'Inter', sans-serif; }
    .bg-peri { background-color: #735DA5; }
    .bg-peri-hover:hover { background-color: #5a4a8a; }
    .bg-lilac { background-color: #D3C5E5; }
    .bg-lilac-light { background-color: #f8f5fd; }
    .text-peri { color: #735DA5; }
    .active-link { background-color: #D3C5E5; color: #735DA5 !important; font-weight: 600; }
  </style>
</head>
<body class="bg-lilac-light min-h-screen">

  <div class="min-h-screen flex items-center justify-center p-5 bg-lilac-light">
    <div class="w-full max-w-md">
      <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-peri rounded-2xl shadow-xl mb-6">
          <i class="fas fa-university text-white text-4xl"></i>
        </div>
        <h1 class="text-4xl font-bold text-peri">University Portal</h1>
        <p class="mt-2 text-gray-600">Academic Management System</p>
      </div>

      <div class="bg-white rounded-2xl shadow-xl p-8 border border-lilac">
        <h2 class="text-2xl font-semibold text-center mb-8 text-gray-800">Sign In</h2>

        <?php if(isset($error)): ?>
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 text-center">
            <?= $error ?>
          </div>
        <?php endif; ?>

        <form method="post" class="space-y-6">
          <div class="grid grid-cols-3 gap-2 bg-gray-100 p-1 rounded-xl">
            <label class="py-3 text-center rounded-lg cursor-pointer hover:bg-white transition">
              <input type="radio" name="role" value="admin" class="sr-only"><span class="text-sm font-medium">Admin</span>
            </label>
            <label class="py-3 text-center rounded-lg cursor-pointer hover:bg-white transition">
              <input type="radio" name="role" value="faculty" class="sr-only"><span class="text-sm font-medium">Faculty</span>
            </label>
            <label class="py-3 text-center rounded-lg cursor-pointer hover:bg-white transition bg-white">
              <input type="radio" name="role" value="student" class="sr-only" checked><span class="text-sm font-medium">Student</span>
            </label>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">University ID</label>
            <div class="relative">
              <i class="fas fa-user absolute left-4 top-4 text-peri"></i>
              <input type="text" name="userid" required class="w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-peri" placeholder="">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password = Your Full Name</label>
            <div class="relative">
              <i class="fas fa-lock absolute left-4 top-4 text-peri"></i>
              <input type="text" name="password" required class="w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-peri" placeholder="">
            </div>
          </div>

          <button type="submit" name="login" class="w-full bg-peri hover:bg-peri-hover text-white font-bold py-4 rounded-xl shadow-lg transition transform hover:-translate-y-1">
            Sign In
          </button>
        </form>
      </div>
    </div>
  </div>

</body>
</html>