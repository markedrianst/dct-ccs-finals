<?php
    include("../../functions.php");
    guard();
$Pagetitle = "Assign Grade to Subject";
include("../partials/header.php");
include("../partials/side-bar.php");
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5"> 

<div class="container">
        <h1>Assign Grade to Subject</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
                <li class="breadcrumb-item"><a href="attach-subject.php">Attach Subject Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Assign Grade to Subject</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-body">
                 <p style="font-size:20px;">Select Student and Subject Information</p>

                    <ul>
                        <li><strong>Student ID:</strong></li>
                        <li><strong>Name:</strong></li>
                        <li><strong>Subject Code:</strong></li>     
                        <li><strong>Subject Name:</strong></li>
                    </ul>

                    <hr>

                    <div class="form-floating ">
                        <input type="number" id="numberInput" class="form-control w-100" name="numberInput" placeholder="Grade">
                        <label for="99.00">Grade</label>
                    </div>

                    <div class="mt-3">
                        <a href="attach-subject.php" class="btn btn-secondary">Cancel</a>
                        <a href="register.php" class="btn btn-primary">Assign Grade to Subject</a>
                    </div>
                    
            </div>
        </div>
    </div>
    </main>

<?php
include('../partials/footer.php');
?>