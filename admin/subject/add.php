<?php
include("../../functions.php");
$error_message = '';
guard();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $subjectCode = $_POST['subjectCode'] ?? '';
    $subjectName = $_POST['subjectName'] ?? '';
    $result =insertSubject($subjectCode, $subjectName);
    $error_message=$result;
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
        <div class="form-container mb-4" >
        <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="subjectCode" name="subjectCode" placeholder="" maxlength="4">
                    <label for="subjectCode">Subject Code</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="subjectName" name="subjectName"placeholder="" >                   <label for="subjectName">Subject Name</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-4" >Add Subject</button>
            </form>
            </div>
            </div>  
        </div>

        <div class="card p-5 mt-4">
            <h4>Subject List</h4>
            <div class="table-responsive"> 
            <table class="table table-striped ">
                <thead class="table-white">
                    <tr>
                    <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Options</th>
                    </tr>
                </thead>
            <?php fetchAndDisplaySubjects(); ?>
            </table>
            </div>
        </div>
    </div>
</main>


<?php
include('../partials/footer.php');
?>