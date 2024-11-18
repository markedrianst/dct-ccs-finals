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
// Function to insert a new student into the database
function insertStudent($studentId, $firstName, $lastName) {
    $conn = connectDB();

    // Validate inputs
    if (empty($studentId) || empty($firstName) || empty($lastName)) {
        return generateError("<li>All fields are required.</li>");
    }

    // Check for duplicate student ID
    $query = "SELECT COUNT(*) as count FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        return generateError("<li>Student ID already exists.</li>");
    }

    // Insert the new student
    $query = "INSERT INTO students (student_id, first_name, last_name) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $studentId, $firstName, $lastName);

    if ($stmt->execute()) {
        return generateSuccess("<li>Student added successfully!</li>");
    } else {
        return generateError("<li>Error adding student: " . $stmt->error . "</li>");
    }
}
// Function to fetch and display the student list
function fetchStudents() {
    $conn = connectDB();
    $query = "SELECT id,student_id, first_name, last_name FROM students ORDER BY student_id ASC";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Loop through each student and generate table rows
        while ($student = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($student['student_id']) . '</td>';
            echo '<td>' . htmlspecialchars($student['first_name']) . '</td>';
            echo '<td>' . htmlspecialchars($student['last_name']) . '</td>';
            echo '<td>';
            echo '<a href="edit.php?id=' . urlencode($student['student_id']) . '" class="btn btn-info btn-sm">Edit</a> ';
            echo '<a href="delete.php?id=' . urlencode($student['student_id']) . '" class="btn btn-danger btn-sm">Delete</a> ';
            echo '<a href="attach-subject.php?id=' . urlencode($student['id']) . '" class="btn btn-warning btn-sm">Attach Subject</a>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr>';
        echo '<td colspan="4" class="text-center">No students found.</td>';
        echo '</tr>';
    }
    $conn->close();
}
// Function to count the number of Student
function countStudents() {
    $conn = connectDB();
    $query = "SELECT COUNT(*) as count FROM students";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['count']; 
    } else {
        return generateError("<li>Error fetching subject count: " . $conn->error . "</li>");
    }

    $conn->close();
}
// Function to fetch student details using MySQLi
function fetchStudentDetails($studentId) {

    $conn = connectDB();
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
   if ($result->num_rows > 0) {
        $student = $result->fetch_assoc(); 
        $stmt->close();
        $conn->close();
        return $student;
    } else {
        $stmt->close();
        $conn->close();
        return null; 
    }
}
// Function to update student details
function updateStudent($studentId, $firstName, $lastName) {
    // Ensure studentId is treated as a string
    $studentId = (string)$studentId;

    // Check if any of the fields are empty
    if (empty($studentId) || empty($firstName) || empty($lastName)) {
        return generateError1("All fields are required.") ;
    }

    // Call connectDB to establish a new connection within the function
    $conn = connectDB();

    $query = "UPDATE students SET first_name = ?, last_name = ? WHERE student_id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        return "Error preparing the statement: " . $conn->error;
    }
    $stmt->bind_param("sss", $firstName, $lastName, $studentId);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: /admin/student/register.php?success=1");
        exit; 
    } else {
        $stmt->close();
        $conn->close();
        return "Failed to update student: " . $stmt->error;
    }
}
//Function Delete Student
function deleteStudent($studentId, $studentFirstName, $studentLastName) {
        $conn = connectDB();

        // Prepare the DELETE query for the students table
        $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ? AND first_name = ? AND last_name = ?");
        if (!$stmt) {
            error_log("Error preparing statement: " . $conn->error);
            return false;
        }
        $stmt->bind_param("sss", $studentId, $studentFirstName,$studentLastName);

        // Execute the query
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

//Functions fo
function fetchAttachedSubjects($studentId) {
    $conn = connectDB();  // Connect to the database
    $stmt = $conn->prepare("
        SELECT 
            subjects.subject_code, 
            subjects.subject_name, 
            students_subjects.grade 
        FROM 
            students_subjects 
        JOIN 
            subjects 
        ON 
            subjects.id = students_subjects.subject_id 
        WHERE 
            students_subjects.student_id = ?
    ");
    $stmt->bind_param("i", $studentId); // 'i' indicates the parameter is an integer

    $stmt->execute();
    $result = $stmt->get_result();
    $subjects = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $subjects;
}
function attachSubjectsToStudent($studentId, $subjects) {
    // Ensure studentId and subjects are not empty
    if (empty($studentId) || empty($subjects)) {
        return false;
    }

    // Assuming you have a connection variable $conn
    $conn = connectDB(); 

    $query = "INSERT INTO students_subjects (student_id, subject_id, grade) VALUES (?, ?, ?)";

    // Prepare the query
    $stmt = $conn->prepare($query);

    // Bind parameters for each subject
    foreach ($subjects as $subjectId) {
        $grades = '0'; // Or any other default grade you want
        $stmt->bind_param("iis", $studentId, $subjectId, $grades);  // "iis" means integer, integer, string
        $stmt->execute();
    }

    return true;
}

    
    function fetchStudentById($studentId) {
        $conn = connectDB();  // Assuming connectDB() is your function to connect to the database
        $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->bind_param("i", $studentId);  // Binding the studentId parameter
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();  // Fetching the student record
            $stmt->close();
            $conn->close();
            return $student;  // Returning student details
        } else {
            $stmt->close();
            $conn->close();
            return null;  // Returning null if no student found
        }
    }
    function fetchsubjectById($subjectId) {
        $conn = connectDB();  // Assuming connectDB() is your function to connect to the database
        $stmt = $conn->prepare("SELECT * FROM subjects WHERE id = ?");
        $stmt->bind_param("i", $subjectId);  // Binding the studentId parameter
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $subjectid = $result->fetch_assoc();  // Fetching the student record
            $stmt->close();
            $conn->close();
            return $subjectid;  // Returning student details
        } else {
            $stmt->close();
            $conn->close();
            return null;  // Returning null if no student found
        }
    }
    function fetchAvailableSubjects() {
        $conn = connectDB();  // Assuming connectDB() is your function to connect to the database
        $stmt = $conn->prepare("SELECT * FROM subjects");
        $stmt->execute();
        $result = $stmt->get_result();
    
        $subjects = [];
        while ($subject = $result->fetch_assoc()) {
            $subjects[] = $subject;  // Storing each subject in an array
        }
    
        $stmt->close();
        $conn->close();
    
        return $subjects;  // Returning an array of subjects
    }

    function attachSubjectToStudent($studentId, $subjectId) {
        $conn = connectDB();
        $stmt = $conn->prepare("INSERT INTO students_subjects (student_id, subject_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $studentId, $subjectId);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
    
    
?>