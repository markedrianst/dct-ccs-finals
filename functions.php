

<?php    
    session_start(); // Start the session at the beginning
    //Database Connection
    function connectDB() {
        $servername = "localhost";
        $email = "root";
        $password = "";
        $dbname = "dct-ccs-finals"; 
        $conn = new mysqli($servername, $email, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

// Function to generate error and successmessages (dismissable)
    function generateError($message) {
        return '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>System Error!</strong> ' . $message . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
    }

    function generateError1($message) {
        return '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>System Error!</strong> ' . $message . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
    }
    function generateSuccess($message) {
        return '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> ' . $message . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
    }
    
//Guard Functions
    function guard() {  
        if (empty($_SESSION["email"])){
            header("Location:/index.php");
        }
    }
//Login Page Property Function 
    function returnPage(){
        if (!empty($_SESSION["email"])) {
            if (!empty($_SESSION['page'])) { 
                header("Location:". $_SESSION['page']);
                exit();
            } else {
                header("Location: /admin/dashboard.php");
                exit();
            }
        }
    }
// Function to check and validate email and password
    function loginUser($email, $password) {
        // Validate the input
        if (empty($email) || empty($password)) {
            return generateError("<li>Email is required </li><li>Password are required.</li>");
        } elseif (!str_ends_with($email, '@gmail.com')) {
            return generateError("<li>Invalid Email format </li>");
        }
        $conn = connectDB();
        $hashedPassword = md5($password); // Hash the password
        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $hashedPassword);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['email'] = $email;
            return true;
        } else {
            return generateError("<li>Invalid email or password.</li>");
        }
    }

//Logout function 
    function logoutUser() {
        session_destroy();
        header("Location:/index.php");
    }



// Function to insert a new subject into the database
    function insertSubject($subjectCode, $subjectName) {
    $conn = connectDB();
    // Validate input
    if (empty($subjectCode) || empty($subjectName)) {
        return generateError("<li>Subject Code is required</li><li>Subject Name is required.</li>");
    }
    $query = "SELECT COUNT(*) as count FROM subjects WHERE subject_code = ? OR subject_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $subjectCode, $subjectName);  
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        return generateError("<li>Duplicate Subject Code or  Subject Name</li>");
    } else {
        $insertQuery = "INSERT INTO subjects (subject_code, subject_name) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ss", $subjectCode, $subjectName); // Bind parameters
        if ($insertStmt->execute()) {
            return generateSuccess("<li>Subject Added Successfully!</li>");
        } else {
            return generateError("<li>Error adding subject: " . $insertStmt->error . "</li>");
        }
    }
}

//functions for fetch subjects from the database

    function fetchAndDisplaySubjects() {
        $conn = connectDB();
        // Query to fetch subjects from the database
        $result = $conn->query("SELECT * FROM subjects");
    
        if ($result->num_rows > 0) {
            while ($subject = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($subject['subject_code']) . '</td>';
                echo '<td>' . htmlspecialchars($subject['subject_name']) . '</td>';
                echo '<td>';
                echo '<a href="edit.php?code=' . urlencode($subject['subject_code']) . '"><button class="btn btn-info ">Edit</button></a>';
                echo ' ';
                echo '<a href="delete.php?code=' . urlencode($subject['subject_code']) . '"><button class="btn btn-danger ">Delete</button></a>';
                echo '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr>';
            echo '<td colspan="3" class="text-center">No subjects found.</td>';
            echo '</tr>';
        }
    }

// Update Subject Function
function updateSubject($subjectName, $originalCode) {
    // Validate the input
    if (empty($subjectName)) {
        return generateError1("<li>Subject Name is required.</li>");
    }
    $conn = connectDB();

    // Check if the new subject name already exists for another subject code
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE subject_name = ? AND subject_code != ?");
    $stmt->bind_param("ss", $subjectName, $originalCode);
    $stmt->execute();
    $result = $stmt->get_result();
    // If a duplicate subject name is found, return an error
    if ($result->num_rows > 0) {
        $stmt->close();
        $conn->close();
        return generateError1("<li>Duplicate entry: Subject Name already exists for another Subject Code.</li>");
    }

    // Update the subject name in the database (subject_code remains the same)
    $stmt = $conn->prepare("UPDATE subjects SET subject_name = ? WHERE subject_code = ?");
    $stmt->bind_param("ss", $subjectName, $originalCode);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: /admin/subject/add.php?success=1");
        exit; 
    } else {
        $stmt->close();
        $conn->close();
        return generateError1("<li>Error updating subject name.</li>");
    }
}

// Fetch Subject Details Function (for editing)
function fetchSubjectDetails($subjectCode) {
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE subject_code = ?");
    $stmt->bind_param("s", $subjectCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $subject = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $subject;
    } else {
        $stmt->close();
        $conn->close();
        return null;
    }
}

// Delete Subject Function
function deleteSubject($subjectCode, $subjectName) {
    $conn = connectDB();

    // Prepare the DELETE query
    $stmt = $conn->prepare("DELETE FROM subjects WHERE subject_code = ? AND subject_name = ?");
    if (!$stmt) {
        error_log("Error preparing statement: " . $conn->error);
        return false;
    }
    $stmt->bind_param("ss", $subjectCode, $subjectName);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return true; 
    } else {
        error_log("Error executing delete query: " . $stmt->error);
        $stmt->close();
        $conn->close();
        return false; 
    }
}

// Function to count the number of subjects
function countSubjects() {
    $conn = connectDB();
    $query = "SELECT COUNT(*) as count FROM subjects";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['count']; 
    } else {
        return generateError("<li>Error fetching subject count: " . $conn->error . "</li>");
    }

    $conn->close();
}


?>

