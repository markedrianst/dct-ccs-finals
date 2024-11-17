<?php
include("../../functions.php");
$error_message = '';
guard();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $subjectCode = $_POST['subjectCode'] ?? '';
    $subjectName = $_POST['subjectName'] ?? '';

    // Validate input
    if (empty($subjectCode) || empty($subjectName)) {
        $error_message = generateError('Subject Code and Subject Name are required.');
    } else {
        // Call the insert function
        if (insertSubject($subjectCode, $subjectName)) {
            $success_message = '<div class="alert alert-success">Subject added successfully.</div>';
        } else {
            $error_message = generateError('Failed to add subject.');
        }
    }
}
$Pagetitle = "Add Subject";

include("../partials/header.php");
include("../partials/side-bar.php");
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    

<div class="container ">
        <h2 class="text-left">Add a New Subject</h2>
        <nav aria-label="breadcrumb" >
            <ol class="breadcrumb" >
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
            </ol>
        </nav>
        <?php if ($error_message): ?>
                <?php echo $error_message; ?>
            <?php endif; ?>
        <div class="form-container mb-4">
            <form method="POST">
                <div class="mb-3">
                    <label for="subjectCode" class="form-label">Subject Code</label>
                    <input type="text" class="form-control" id="subjectCode" name="subjectCode" placeholder="Enter Subject Code">
                </div>
                <div class="mb-3">
                    <label for="subjectName" class="form-label">Subject Name</label>
                    <input type="text" class="form-control" id="subjectName" name="subjectName" placeholder="Enter Subject Name">
                </div>
                <button type="submit" class="btn btn-primary">Add Subject</button>
            </form>
        </div>

        <div class="table-container">
            <h4>Subject List</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // Fetch subjects from database to display
                    $conn = connectDB();
                    $result = $conn->query("SELECT * FROM subjects");

                    if ($result->num_rows > 0):
                        while ($subject = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                                <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                                <td>
                                    <a href="edit.php?code=<?php echo urlencode($subject['subject_code']); ?>"><button class="btn btn-success">Edit</button></a>   
                                    <a href="delete.php?code=<?php echo urlencode($subject['subject_code']); ?>"><button class="btn btn-danger">Delete</button></a>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No subjects found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php
include('./partials/footer.php');
?>