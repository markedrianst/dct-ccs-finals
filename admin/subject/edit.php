<?php
$Pagetitle = "Edit Subject";
include("../partials/header.php");
include("../partials/side-bar.php");
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">

<div class="container">
        <h1 class="mt-5">Edit Subject</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Subject</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="">

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="subjectCode" name="subjectCode" value="" placeholder="Subject ID" >
                        <label for="1001">Subject ID</label>
                        <input type="hidden" name="editSubject"> <!-- Hidden input to track the subject index -->
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="subjectCode" name="subjectCode" value="" placeholder="Subject Name" >
                        <label for="1001">Subject Name</label>
                        <input type="hidden" name="editSubject"> <!-- Hidden input to track the subject index -->
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Subject</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
include('../partials/footer.php');
?>