<?php
include("../../functions.php");
guard();
$error_message = '';
$Pagetitle = "Edit Subject";

if (isset($_GET['code'])) {
    $subjectCode = $_GET['code'];
    $subject = fetchSubjectDetails($subjectCode);  // Fetch the subject details

    if (!$subject) {
        $error_message = generateError1("<li>Subject not found.</li>");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newSubjectName = $_POST['subjectName'] ?? '';  
    $result = updateSubject($newSubjectName, $subjectCode);  // Get the new subject name from the form
    
    $error_message=$result;
}

include("../partials/header.php");
include("../partials/side-bar.php");
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <div class="container">
        <h2>Edit Subject</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Subject</li>
            </ol>
        </nav>

        <?php if ($error_message): ?>
            <?php echo $error_message; ?>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="subjectCode" class="form-label">Subject Code</label>
                <input type="text" disabled class="form-control" id="subjectCode" name="subjectCode" value="<?php echo htmlspecialchars($subject['subject_code'] ?? ''); ?>" >
            </div>

            <div class="mb-3">
                <label for="subjectName" class="form-label">Subject Name</label>
                <input type="text" class="form-control" id="subjectName" name="subjectName" value="<?php echo htmlspecialchars($subject['subject_name'] ?? ''); ?>" >
            </div>
          
             <button type="submit" class="btn btn-primary">Update Subject</button>      

        </form>
    </div>
</main>

<?php
include('../partials/footer.php');
?>
