<?php
$Pagetitle = "Dettach Subject to Student";
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
                <li class="breadcrumb-item"><a href="">Attach Subject Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dettach Subject to Student</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-body">
                 <p style="font-size:20px;">Select Student Information</p>

                    <ul>
                        <li><strong>Student ID:</strong></li>
                        <li><strong>First Name:</strong></li>
                        <li><strong>Last Name:</strong></li>
                        <li><strong>Subject Code:</strong></li>
                        <li><strong>Subject Name:</strong></li>
                    </ul>

                    <a href="attach-subject.php" class="btn btn-secondary">Cancel</a>
                    <a href="register.php" class="btn btn-primary">Dettach Subject from Student</a>
                    
            </div>
        </div>
    </div>
    </main>

<?php
include('../partials/footer.php');
?>