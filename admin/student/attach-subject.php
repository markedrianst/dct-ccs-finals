<?php
$Pagetitle = "Attach Subject to Student";
include("../partials/header.php");
include("../partials/side-bar.php");
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5"> 

<div class="container">
        <h1>Attach Subject to a Student</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delete Student</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-body">
                 <p style="font-size:20px;">Select Student Information</p>

                    <ul>
                        <li><strong>Student ID:</strong></li>
                        <li><strong>Name:</strong></li>
                    </ul>

                    <hr>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            1001 - English
                        </label>      
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            1002 - Mathematics
                        </label>      
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            1003 - Science
                        </label>      
                    </div>

                    <form action="delete.php" method="POST" class="d-inline">
                        <input type="hidden" name="deleteId" value="">
                        <button type="submit" class="btn btn-primary mt-3">Attach Subject</button>
                    </form>

                <div class="card mt-4">
                    <div class="card-header">Student List</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Student ID</th>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($_SESSION['students'])): ?>
                                    <?php foreach ($_SESSION['students'] as $student): ?>
                                        <tr>
                                            <td><?php echo $student['id']; ?></td>
                                            <td><?php echo $student['first_name']; ?></td>
                                            <td><?php echo $student['last_name']; ?></td>
                                            <td>
                                                <!-- Edit and Delete Buttons Inline -->
                                                <div class="d-flex gap-2">
                                                    <!-- Edit Button -->
                                                    <form action="edit.php" method="GET" class="d-inline">
                                                        <button type="submit" class="btn btn-success btn-sm" name="studentId" value="<?php echo $student['id']; ?>">Edit</button>
                                                    </form>
                                                    <!-- Delete Button -->
                                                    <form action="delete.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="studentId" value="<?php echo $student['id']; ?>">   
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No student records found.</td>
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

<?php
include('../partials/footer.php');
?>