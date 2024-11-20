<?php
include '../../functions.php';
guard();
$error_message = '';
if (isset($_GET['id']) && isset($_GET['subject_code'])) {
    $student_id = $_GET['id']; 
    $subject_code = $_GET['subject_code']; 

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
            
            // Call the function to update the grade
            $result = updateGradeForSubject($student_id, $subject_code, $grade);
            if ($result) {
                $successMessage = "Grade assigned successfully!";
                header("Location: attach-subject.php?id=" . urlencode($student_id) . "&success=1"); 
                exit;
            } else {
                
                $error_message = generateError("Failed to assign grade.");
            }
        } else {
            $error_message = generateError  ("Please enter a valid grade.");
        }
    }
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
        <?php if ($error_message): ?>
                <?php echo $error_message; ?>
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
                <form method="POST">
                        <div class="form-floating mb-3 ">
                        <input type="number" id="numberInput" class="form-control w-100" name="numberInput" placeholder="Grade">
                        <label for="99.00">Grade</label>
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
