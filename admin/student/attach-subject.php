<?php
    include("../../functions.php");

    guard();

    $studentId = null;
    $selectedSubjects = null;
    $errorMessage = '';

    // Handle POST request to attach subjects
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['student_id'])) {
            $studentId = $_POST['student_id'];
        }

        if (isset($_POST['subjects']) && !empty($_POST['subjects']) && $studentId !== null) {
            $selectedSubjects = $_POST['subjects'];
            $success = attachSubjectsToStudent($studentId, $selectedSubjects);
        } else {
            $errorMessage = generateError("Please select at least one subject to attach.");
        }
    }

    // Fetch student data, attached subjects, and available subjects
    if (isset($_GET['id'])) {
        $studentId = $_GET['id'];
        $student = fetchStudentById($studentId);
        $attachedSubjects = fetchAttachedSubjects($studentId);
        $subjectsToAttach = fetchAvailableSubjects();
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
                <li class="breadcrumb-item active" aria-current="page">
                    Attach Subject to a Student
                </li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <p style="font-size: 20px;">Selected Student Information</p>
                <ul>
                    <li>
                        <strong>Student ID: <?php echo htmlspecialchars($student['student_id'] ?? ''); ?></strong>
                    </li>
                    <li>
                        <strong>Name: <?php echo htmlspecialchars($student['first_name'] ?? '') . ' ' . htmlspecialchars($student['last_name'] ?? ''); ?></strong>
                    </li>
                </ul>
                <hr>

                <!-- Display Error Message if No Subjects Selected -->
                <?php if (!empty($errorMessage)): ?>
                    <?php echo $errorMessage; ?>
                <?php endif; ?>

                <form method="POST">
                    <?php 
                    $showButton = false;
                    $subjectsDisplayed = false;
                    
                    if (!empty($subjectsToAttach)): 
                        foreach ($subjectsToAttach as $subject):
                            $isAttached = false;

                            // Check if the subject is already attached to the student
                            foreach ($attachedSubjects as $attachedSubject) {
                                if ($subject['subject_code'] === $attachedSubject['subject_code'] && $subject['subject_name'] === $attachedSubject['subject_name']) {
                                    $isAttached = true;
                                    break;
                                }
                            }

                            // If not attached, show checkbox
                            if (!$isAttached): 
                                $subjectsDisplayed = true;
                                $showButton = true;  
                    ?>
                        <div class="form-check">
                            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($studentId); ?>">
                            <input class="form-check-input" type="checkbox" name="subjects[]" value="<?php echo htmlspecialchars($subject['id']); ?>">
                            <label class="form-check-label">
                                <?php echo htmlspecialchars($subject['subject_code'] . " - " . $subject['subject_name']); ?>
                            </label>
                        </div>
                    <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if ($subjectsDisplayed && $showButton): ?>
                        <button type="submit" class="btn btn-primary mt-3">Attach Selected Subjects</button>
                    <?php elseif (!$subjectsDisplayed): ?>
                        <p>No subjects available to attach.</p>
                    <?php endif; ?>
                    <?php else: ?>
                        <p>No subjects available to attach.</p>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="card p-5 mt-4">
            <h4>Attached Subjects</h4>
            <div class="table-responsive">
                <table class="table table-striped">
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
                                    <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                                    <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                                    <td>
                                        <?php 
                                        echo htmlspecialchars(
                                            $subject['grade'] === '0.00' || $subject['grade'] == 0 ? '-,-' : ($subject['grade'] ?: 'N/A')
                                        ); 
                                        ?>
                                    </td>
                                    <td>
                                    <a href="dettach-subject.php?student_id=<?php echo urlencode($studentId); ?>&subject_code=<?php echo urlencode($subject['subject_code']); ?>" class="btn btn-danger">Detach Subject</a>
                                    <a href="assign-grade.php?student_id=<?php echo urlencode($studentId); ?>&subject_code=<?php echo urlencode($subject['subject_code']); ?>" class="btn btn-success">Assign Grade</a>
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
</main>

<?php include('../partials/footer.php'); ?>
