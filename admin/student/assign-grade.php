<?php
include '../../functions.php';
guard();
$error_message = '';
$success_message = '';

if (isset($_GET['id']) && isset($_GET['subject_code']) && isset($_GET['grade'])) {
    $student_id = $_GET['id']; 
    $subject_code = $_GET['subject_code']; 
    $grade = $_GET['grade'];
    $student = getStudentId($student_id); 
    $subject = getSubjectByCode($subject_code);

    if (!$student || !$subject) {
        echo "<div class='alert alert-danger'>Invalid student or subject!</div>";
        exit;
    }
    $student_name = $student['first_name'] . ' ' . $student['last_name'];

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the grade from the form input
        if (isset($_POST['numberInput']) && is_numeric($_POST['numberInput'])) {
            $grade = $_POST['numberInput'];
            if ($grade >= 65 && $grade <= 100) {
                // Call the function to update the grade
                $result = updateGradeForSubject($student_id, $subject_code, $grade);
                if ($result) {
                    // Redirect with success message
                    header("Location: attach-subject.php?id=" . urlencode($student_id) . "&success=1"); 
                    exit;
                } else {  
                    $error_message = generateError("Failed to assign grade.");
                }
            } else {
                $error_message = generateError("Grade must be between 65 and 100.");
            }
        } else {
            $error_message = generateError("Please enter a valid grade.");
        }
    }
} 

// Fetch the success message if the query parameter is set
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success_message = "Grade assigned successfully!";
}

$Pagetitle = 'Assign Grade';
include '../partials/header.php';
include '../partials/side-bar.php';
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
    <div class="container">
        <h2 class="mb-1">Assign Grade</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
                <li class="breadcrumb-item"><a href="attach-subject.php?id=<?php echo urlencode($student_id); ?>">Attach Subject to Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Assign Grade</li>
            </ol>
        </nav>

        <!-- Display success or error message -->
        <?php if ($error_message): ?>
            <?php echo $error_message; ?>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <div class="card p-2">
            <div class="card-body">
                <p style="font-size: 20px;">Selected Student and Subject Information</p>
                <ul>
                    <li><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></li>
                    <li><strong>First Name:</strong> <?php echo htmlspecialchars($student['first_name']); ?></li>
                    <li><strong>Last Name:</strong> <?php echo htmlspecialchars($student['last_name']); ?></li>
                    <li><strong>Subject Code:</strong> <?php echo htmlspecialchars($subject['subject_code']); ?></li>
                    <li><strong>Subject Name:</strong> <?php echo htmlspecialchars($subject['subject_name']); ?></li>
                </ul><br><hr>

                <!-- Grade assignment form -->
                <form method="POST">
                    <div class="form-floating mb-3 ">
                        <input type="number" id="numberInput" class="form-control w-100" 
                               value="<?php echo htmlspecialchars($grade); ?>" 
                               name="numberInput" placeholder="65-100" >
                        <label for="numberInput">Grade</label>
                    </div>
                    <button type="button" class="btn btn-secondary" onclick="window.history.back();">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Grade To Subject</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
include '../partials/footer.php';
?>
