<?php
include("../../functions.php");

guard();
$studentId = null;
$selectedSubjects = null;

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure 'student_id' is set in POST data
    if (isset($_POST['student_id'])) {  // Corrected 'id' to 'student_id'
        $studentId = $_POST['student_id'];  // Corrected
    }

    // Handle form submission for attaching subjects
    if (isset($_POST['subjects']) && !empty($_POST['subjects']) && $studentId !== null) {
        $selectedSubjects = $_POST['subjects'];  // Corrected
        $success = attachSubjectsToStudent($studentId, $selectedSubjects);
    }
}

// Fetch the student details if an ID is provided via GET
if (isset($_GET['id'])) {
    $studentId = $_GET['id'];  
    $student = fetchStudentById($studentId);  // Assuming this function fetches student info
    $attachedSubjects = fetchAttachedSubjects($studentId);  // Fetch attached subjects for the student
    $subjectsToAttach = fetchAvailableSubjects();  // Fetch subjects available for attachment
} else {
    echo $selectedSubjects, $studentId;
}

$Pagetitle = "Attach Subject to Student";
include("../partials/header.php");
include("../partials/side-bar.php");
?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
        <div class="container">
            <h1>Attach Subject to a Student</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="../dashboard.php">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="register.php">Register Student</a>
                    </li>
                    <li class="breadcrumb-item Active" aria-current="page">
                        Attach Subject to a Student
                    </li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-body">
                    <p style="font-size:20px;">Select Student Information</p>
                    <ul>
                        <li>
                            <strong>Student ID: <?php echo htmlspecialchars($student['student_id'] ?? ''); ?> </strong>
                        </li>
                        <li>
                            <strong>Name: <?php echo htmlspecialchars($student['first_name'] ?? '') . ' ' . htmlspecialchars($student['last_name'] ?? ''); ?> </strong>
                        </li>
                    </ul>
                    <hr>
                    <form method="POST">
                        <h5>Select Subjects:</h5>
                        <?php if (!empty($subjectsToAttach)): ?>
                            <?php foreach ($subjectsToAttach as $subject): ?>
                                <?php
                            // Check if the current subject is already attached to the student
                            $isAttached = false;
                            foreach ($attachedSubjects as $attachedSubject) {
                                if ($subject['subject_code'] === $attachedSubject['subject_code'] && $subject['subject_name'] === $attachedSubject['subject_name']) {
                                    $isAttached = true;
                                    break;
                                }
                            }
                            ?>
                                    <?php if (!$isAttached): // Only show subjects that are not attached ?>
                                        <div class="form-check">
                                            <input type="hidden" name="student_id" value="
								<?php echo htmlspecialchars($studentId); ?>">
                                            <input class="form-check-input" type="checkbox" name="subjects[]" value="
									<?php echo htmlspecialchars($subject['id']); ?>">
                                            <label class="form-check-label">
                                                <?php echo htmlspecialchars($subject['subject_code'] . " - " . $subject['subject_name']); ?>
                                            </label>
                                        </div>
                                        <?php else: // If attached, mark as checked and disabled ?>
                                            <?php endif; ?>
                                                <?php endforeach; ?>
                                                    <button type="submit" class="btn btn-primary mt-3">Attach Selected Subjects</button>
                                                    <?php else: ?>
                                                        <p>No subjects available to attach.</p>
                                                        <?php endif; ?>
                    </form>
                    <div class="card mt-4">
                        <div class="card-header">Attached Subjects</div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Subject Code</th>
                                        <th scope="col">Subject Name</th>
                                        <th scope="col">Grade</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($attachedSubjects)): ?>
                                        <?php foreach ($attachedSubjects as $subject): ?>
                                            <tr>
                                                <td>
                                                    <?php echo htmlspecialchars(string: $subject['subject_code']); ?>
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($subject['subject_name']); ?>
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($subject['grade'] === '0.00' || $subject['grade'] == 0 ? '-,-' : ($subject['grade'] ?: 'N/A')); ?>
                                                </td>
                                                <td>
                                                    <a href="../student/dettach-subject.php" type="button" class="btn btn-danger">Detach Subject</a>
                                                    <a href="../student/assign-grade.php" type="button" class="btn btn-success">Assign Grade</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="4">No subjects attached to this student.</td>
                                                    </tr>
                                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include('../partials/footer.php'); ?>