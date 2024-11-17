<?php
include("../../functions.php");
guard();
$Pagetitle = "Register Student";
$error_message = '';// Variable to store messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $studentId = $_POST["studentId"] ?? '';
    $firstName = $_POST["firstName"] ?? '';
    $lastName = $_POST["lastName"] ?? '';
    
    // Call the insertStudent function and store the result
    $error_message = insertStudent($studentId, $firstName, $lastName);
}

include("../partials/header.php");
include("../partials/side-bar.php");
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
<div class="container">
    <h2>Register a New Student</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Register Student</li>
        </ol>
    </nav>

    <!-- Alert Message -->
    <?php if ($error_message): ?>
                <?php echo $error_message; ?>
            <?php endif; ?>  


    <!-- Registration Form -->
    <div class="card">
        <div class="card-body">
            <form action="" method="POST">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="studentId" name="studentId" placeholder="Enter Student ID" maxlength="4">
                    <label for="studentId" >Student ID</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter First Name" >
                    <label for="firstName" >First Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter Last Name" >
                    <label for="lastName" >Last Name</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Add Student</button>
            </form>
        </div>
    </div>


    <div class="card p-5 mt-4">
            <h4>Subject List</h4>
            <div class="table-responsive"> 
            <table class="table table-striped ">
                <thead class="table-white">
                    <tr>
                    <th>Student Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Option</th>
                    </tr>
                </thead>
            <?php fetchStudents(); ?>
            </table>
            </div>
        </div>
    </div>
</div>
</main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php 
include("../partials/footer.php");
?>
