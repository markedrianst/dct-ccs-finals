<?php
$Pagetitle = "Delete Subject";
include("../partials/header.php");
include("../partials/side-bar.php");
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">

<div class="container">
        <h1 class="mt-5">Delete Subject</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delete Subject</li>
            </ol>
        </nav>
        
        
        <div class="card">
            <div class="card-body">
                    <p>Are you sure you want to delete the following subject record?</p>
                    <ul>
                        <li><strong>Subject Code:</strong></li>
                        <li><strong>Subject Name:</strong></li>
                    </ul>
                    <form method="POST">
                        <input type="hidden" name="code">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='add.php';">Cancel</button>

                        <button type="submit" class="btn btn-primary">Delete Subject Record</button>
                    </form>
            </div>
        </div>
    </div>
</main>

<?php
include('../partials/footer.php');
?>