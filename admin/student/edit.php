<?php
$Pagetitle = "Edit";
include("../partials/header.php");
include("../partials/side-bar.php");
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">   
<div class="container mt-5">
        <h1>Edit Student</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
            </ol>
        </nav>

        <!-- Alert Message -->
        <?php if (!empty($message)): ?>
            <div class="alert <?php echo (strpos($message, 'successfully') !== false) ? 'alert-info' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="form-container mt-3">
            <?php if ($student): ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="studentId" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="studentId" value="<?php echo htmlspecialchars($student['id']); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($student['first_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($student['last_name']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Student</button>
                </form>
            <?php else: ?>
                <div class="alert alert-danger">Student not found.</div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php
include('./partials/footer.php');
?>