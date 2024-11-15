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
                <li class="breadcrumb-item Active"><a href="register.php">Attach Subject to a Student</a></li>
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
                                    <th scope="col">Subject Code</th>
                                    <th scope="col">Subject Name</th>
                                    <th scope="col">Grade</th>
                                    <th scope="col">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1001</td>
                                    <td>English</td>
                                    <td>99.00</td>
                                    <td>
                                        <a href="../student/dettach-subject.php" type="button" class="btn btn-danger">Detach Subject</a>
                                        <a href="../student/assign-grade.php" type="button" class="btn btn-success">Assign Grade</a>
                                    </td>
                                </tr>
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