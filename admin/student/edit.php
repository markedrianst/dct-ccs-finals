    <?php
    include("../../functions.php");
    guard();

    $error_message = '';  
    // Validate that the studentId is a valid number, only if it's coming from $_POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['Studentid'])) {
            $studentId = $_POST['Studentid'];  // Ensure it's cast to an integer
        }

        // Validate that the studentId is a number
        if (!empty($studentId) ) {
            // Handle form submission
            $firstName = $_POST['Firstname'];
            $lastName = $_POST['Lastname'];
            $result = updateStudent($studentId, $firstName, $lastName);
            $error_message = $result;
        } 
    }
   
    // Fetch the student details if an ID is provided
    if (isset($_GET['id'])) {
        $studentId = $_GET['id'];  
        $student = fetchStudentDetails($studentId);  
    }

    $Pagetitle = "Edit Student";
    include("../partials/header.php");
    include("../partials/side-bar.php");
    ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">   
        <div class="container mt-1">
            <h1>Edit Student</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
                </ol>
            </nav>
            <?php if ($error_message): ?>
            <?php echo $error_message; ?>
        <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <!-- Student ID (readonly) -->
                        <div class="form-floating mb-3">
                            <input type="text" readonly class="form-control readonly-input" id="Studentid" name="Studentid" value="<?php echo htmlspecialchars($student['student_id'] ?? ''); ?>">
                            <label for="Studentid">Student ID</label>
                        </div>

                        <!-- First Name -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="Firstname" name="Firstname" value="<?php echo htmlspecialchars($student['first_name'] ?? ''); ?>" >
                            <label for="Firstname">First Name</label>
                        </div>

                        <!-- Last Name -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="Lastname" name="Lastname" value="<?php echo htmlspecialchars($student['last_name'] ?? ''); ?>" >
                            <label for="Lastname">Last Name</label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100">Update Student</button>      
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php
    include('../partials/footer.php');
    ?>
