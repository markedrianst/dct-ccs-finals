<?php
include '../../functions.php'; 
guard();

$result = ['success' => false, 'errors' => []];
$student = null; 


if (isset($_GET['id'])) {
    $student_id = $_GET['id'];


    $student = getStudentId($student_id);  


    if (!$student) {
        echo "<div class='alert alert-danger'>Student not found!</div>";
        exit;
    }
    $student_subjects = getSubjects_StudentId($student_id); 


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($student_id)) {
        $selected_subjects = isset($_POST['subjects']) ? $_POST['subjects'] : [];

        if (empty($selected_subjects)) {
            $result['errors'][] = "At least one subject should be selected.";
        } else {

            $result = attachSubjectsToStudent($student_id, $selected_subjects);
        }

            $student_subjects = getSubjects_StudentId($student_id);
  
    }

    $available_subjects = getAllSubjectsdetailes();

    $available_subjects = array_filter($available_subjects, function ($subject) use ($student_subjects) {
        return !in_array($subject['subject_code'], array_column($student_subjects, 'subject_code'));
    });
    $available_subjects = array_values($available_subjects); 
}
$Pagetitle = 'Attach Subject';
include('../partials/header.php');
include '../partials/side-bar.php';
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <div class="container">
        <h1>Attach Subject to a Student</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Attach Subject to Student</li>
            </ol>
        </nav>

        <?php
        if (!empty($result['errors'])) {
            echo generateError(implode('<br>', $result['errors'])); 
        }
        ?>

        <div class="card p-4">
            <div class="card-body">
                <h4 class="mb-4">Selected Student Information</h4>
                <ul>
                    <?php if ($student): ?>
                        <li class="ml-4"><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></li>
                        <li><strong>Name:</strong> <?php echo htmlspecialchars($student['first_name']) . ' ' . htmlspecialchars($student['last_name']); ?></li>
                    <?php else: ?>
                        <li><strong>No student information available.</strong></li>
                    <?php endif; ?>
                </ul>
                <hr>
                <form action="attach-subject.php?id=<?php echo urlencode($student_id); ?>" method="POST">
                    <?php
                    if (!empty($available_subjects)) {
                        foreach ($available_subjects as $subject) {
                            echo "<div class='form-check'>
                                    <input class='form-check-input' type='checkbox' name='subjects[]' value='" . $subject['subject_code'] . "' id='subject" . $subject['subject_code'] . "'>
                                    <label class='form-check-label' for='subject" . $subject['subject_code'] . "'>" . htmlspecialchars($subject['subject_name']) . "</label>
                                  </div>";
                        }
                       echo '<button type="submit" class="btn btn-primary mt-3">Attach Subjects</button>';

                    } else {
                        echo "<p>No available subjects to select.</p>";
                    }
                    ?>
                    
                </form>
            </div>
        </div>
        <div class="card p-5 mt-4">
            <h4>Subject List</h4>
            <div class="table-responsive"> 
            <table class="table table-striped ">
                <thead class="table-white">
                    <tr> 
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Grade</th>
                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    Option</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($student_subjects)) {
                        foreach ($student_subjects as $subject) {
                            echo "<tr>";
                            echo "<td>". htmlspecialchars($subject['subject_code']) . "</td>";
                            echo "<td>" . htmlspecialchars($subject['subject_name']) . "</td>";
                            echo "<td>" . ($subject['grade'] == 0.00 ? '-,-' : htmlspecialchars($subject['grade'])) . "</td>";

                            echo "<td>
                                <a href='dettach-subject.php?id=" . htmlspecialchars($student['id']) . "&subject_code=" . htmlspecialchars($subject['subject_code']) . "' class='btn btn-danger btn-sm'>Detach Subject</a>
                                <a href='assign-grade.php?id=" . htmlspecialchars($student['id']) . "&subject_code=" . htmlspecialchars($subject['subject_code']) . "' class='btn btn-success btn-sm'>Assign Grades</a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No subjects attached to this Attach.</td></tr>";
                    }
                    ?>

                    </tbody>
            </table>
            </div>
        </div>
    </div>
</main>

<?php
include '../partials/footer.php'; 
?>
