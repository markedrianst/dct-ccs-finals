<?php
$Pagetitle = "Edit";
include("../partials/header.php");
include("../partials/side-bar.php");
?>

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
                <?php if ($studentToDelete): ?>
                    <p>Are you sure you want to delete the following student record?</p>
                    <ul>
                        <li><strong>Student ID:</strong> <?php echo $studentToDelete['id']; ?></li>
                        <li><strong>First Name:</strong> <?php echo $studentToDelete['first_name']; ?></li>
                        <li><strong>Last Name:</strong> <?php echo $studentToDelete['last_name']; ?></li>
                    </ul>
                    <form action="delete.php" method="POST" class="d-inline">
                        <input type="hidden" name="deleteId" value="<?php echo $studentToDelete['id']; ?>">
                        <button type="submit" class="btn btn-danger">Delete Student Record</button>
                    </form>
                    <a href="register.php" class="btn btn-secondary">Cancel</a>
                <?php else: ?>
                    <p class="alert alert-danger">Student not found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </main>
    <?php
include('./partials/footer.php');
?>
