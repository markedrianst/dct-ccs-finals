<?php
include("../../functions.php");
guard();
if (isset($_GET['id'])) {
    $studentId = $_GET['id'];
    $student = fetchStudentDetails($studentId);

    }

// Check if the form is submitted to delete the student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $studentIdToDelete = $_POST['id'] ?? '';
    $studentFirstNameToDelete = $_POST['firstname'] ?? '';
    $studentLastNameToDelete = $_POST['lastname'] ?? '';

    error_log("Deleting student with ID: " . $studentIdToDelete);
    error_log("Deleting student with First Name: " . $studentFirstNameToDelete);
    error_log("Deleting student with Last Name: " . $studentLastNameToDelete);

    if (!empty($studentIdToDelete) && !empty($studentFirstNameToDelete) && !empty($studentLastNameToDelete)) {
        // Call deleteStudent to remove the student from the database
        $deleteResult1 = deleteStudent($studentIdToDelete, $studentFirstNameToDelete, $studentLastNameToDelete);
            
    }if ($deleteResult1) {
        header("Location:register.php?deleted=1");
        exit();
    } else {
        echo "Error deleting subject.";
    }
    } else {
    echo "Missing subject details.";

}
$Pagetitle = "Delete Students";
include("../partials/header.php");
include("../partials/side-bar.php");

?>

<!-- Form for deleting the student -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <div class="container">
        <h1>Delete a Student</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delete Student</li>
            </ol>
        </nav>
        
        <div class="card">
            <div class="card-body">
                <?php if ($student): ?>
                    <form method="POST">
                        <p>Are you sure you want to delete the following student record?</p>
                        <ul>
                            <li><strong>Student ID: <?php echo htmlspecialchars($student['student_id'] ?? ''); ?></strong></li>
                            <li><strong>Student First Name: <?php echo htmlspecialchars($student['first_name'] ?? ''); ?></strong></li>
                            <li><strong>Student Last Name: <?php echo htmlspecialchars($student['last_name'] ?? ''); ?></strong></li>
                        </ul>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($student['student_id'] ?? ''); ?>">
                        <input type="hidden" name="firstname" value="<?php echo htmlspecialchars($student['first_name'] ?? ''); ?>">
                        <input type="hidden" name="lastname" value="<?php echo htmlspecialchars($student['last_name'] ?? ''); ?>">

                        <button type="button" class="btn btn-secondary" onclick="window.location.href='register.php';">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="delete" value="1">Delete Student Record</button>
                    </form>
                <?php else: ?>
                    <p>Student not found!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include('../partials/footer.php'); ?>
