    <?php
        // dettach-subject.php

    include("../../functions.php");
    guard();

    if (isset($_GET['student_id']) && isset($_GET['subject_code'])) {
        $studentId = $_GET['student_id'];
        $subjectCode = $_GET['subject_code'];
        
        $conn = connectDB();
        $stmt = $conn->prepare("DELETE FROM students_subjects WHERE student_id = ? AND subject_id = (SELECT id FROM subjects WHERE subject_code = ?)");
        $stmt->bind_param("is", $studentId, $subjectCode);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            header("Location: attach-subject.php?id=" . urlencode($studentId));
            exit();
        } else {
            echo "Error detaching subject.";
        }
        
        $stmt->close();
        $conn->close();
    } else {
        echo "Invalid request.";
    }

        // Fetch subject details if the 'code' parameter is set
        if (isset($_GET['code'])) {
            $subjectCode = $_GET['code'];
            $subject = fetchSubjectDetails($subjectCode);  // Fetch the subject details
            if (!$subject) {
                // If subject is not found, display an error message
                $subjectNotFound = true;
            }
        }

        // Handle form submission (deleting the subject)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subjectCodeToDelete = $_POST['code'] ?? '';
            $subjectNameToDelete = $_POST['subjectName'] ?? '';

            // Debug output
            error_log("Deleting subject with code: " . $subjectCodeToDelete);
            error_log("Deleting subject with name: " . $subjectNameToDelete);

            if (!empty($subjectCodeToDelete) && !empty($subjectNameToDelete)) {
                // Call deleteSubject to remove the subject from the database
                $deleteResult = deleteSubject($subjectCodeToDelete, $subjectNameToDelete);

                if ($deleteResult) {
                    header("Location: add.php?deleted=1");
                    exit();
                } else {
                    echo "Error deleting subject.";
                }
            } else {
                echo "Missing subject details.";
            }
        }

        include("../partials/header.php");
        include("../partials/side-bar.php");
        ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
            <div class="container">
                <h1 class="mt-1">Delete Subject</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="register.php">Add Subject</a></li>
                        <li class="breadcrumb-item"><a href="attach-subject.php">Attach Subject</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detach Subject</li>
                    </ol>
                </nav>

                <div class="card">
                    <div class="card-body">
                        <?php if (isset($subjectNotFound) && $subjectNotFound): ?>
                            <p class="text-danger">Subject not found.</p>
                        <?php else: ?>
                            <!-- Confirmation form for deleting the subject -->
                            <p>Are you sure you want to delete the following subject record?</p>
                            <ul>
                                <li><strong>Subject Code: <?php echo htmlspecialchars($subject['subject_code'] ?? ''); ?></strong></li>
                                <li><strong>Subject Name: <?php echo htmlspecialchars($subject['subject_name'] ?? ''); ?></strong></li>
                            </ul>

                            <!-- Form to confirm deletion -->
                            <form method="POST">
                                <input type="hidden" name="code" value="<?php echo htmlspecialchars($subject['subject_code'] ?? ''); ?>">
                                <input type="hidden" name="subjectName" value="<?php echo htmlspecialchars($subject['subject_name'] ?? ''); ?>">

                                <button type="button" class="btn btn-secondary" onclick="window.location.href='attach-subject.php';">Cancel</button>
                                <button type="submit" class="btn btn-danger">Delete Subject Record</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>

        <?php
        include('../partials/footer.php');
        ?>
