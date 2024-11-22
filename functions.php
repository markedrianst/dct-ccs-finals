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
        return '<div id="autoDismissAlert1" class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>System Error!</strong> ' . $message . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div><script>
            // Auto-dismiss the alert after 5 seconds
            setTimeout(function () {
                const alertElement = document.getElementById("autoDismissAlert1");
                if (alertElement) {
                    const alert = bootstrap.Alert.getOrCreateInstance(alertElement);
                    alert.close();
                }
            }, 7000);
        </script>';
    }
    function generateError1($message) {
        return '<div id="autoDismissAlert1"  class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>System Error!</strong> ' . $message . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div> ';
    }
    function generateSuccess($message) {
        return ['
        <div id="autoDismissAlert" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> '. $message . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            // Auto-dismiss the alert after 5 seconds
            setTimeout(function () {
                const alertElement = document.getElementById("autoDismissAlert");
                if (alertElement) {
                    const alert = bootstrap.Alert.getOrCreateInstance(alertElement);
                    alert.close();
                }
            }, 5000);
        </script>',header("Location: " . $_SERVER['PHP_SELF'])];
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
    if(strlen($subjectCode) >4) {
        return generateError("<li>Subject code must not exceed 4 characters.</li>");
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
                echo '<a href="edit.php?code=' . urlencode($subject['id']) . '"><button class="btn btn-info ">Edit</button></a>';
                echo ' ';
                echo '<a href="delete.php?code=' . urlencode($subject['id']) . '"><button class="btn btn-danger ">Delete</button></a>';
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
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE subject_name = ? AND id != ?");
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
    $stmt = $conn->prepare("UPDATE subjects SET subject_name = ? WHERE id = ?");
    $stmt->bind_param("si", $subjectName, $originalCode);

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
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE id = ?");
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

    
    // Prepare the DELETE query for the students_subjects table
    $query = "DELETE FROM students_subjects WHERE subject_id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Error preparing statement for students_subjects: " . $conn->error);
        $conn->close();
        return false;
    }

    // Bind studentId to delete associated subjects first
    $stmt->bind_param('i', $subjectCode);
    if (!$stmt->execute()) {
        error_log("Error executing delete query for students_subjects: " . $stmt->error);
        $stmt->close();
        $conn->close();
        return false;
    }

    // Prepare the DELETE query
    $stmt = $conn->prepare("DELETE FROM subjects WHERE id = ? AND subject_name = ?");
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
    }
// Function to insert a new student into the database
    function insertStudent($studentId, $firstName, $lastName) {
    $conn = connectDB();

    // Validate inputs
    if (empty($studentId) || empty($firstName) || empty($lastName)) {
        return generateError("<li>Student id is required.</li><li>First Name  is required.</li><li>Last Name is required.</li>");
    }    
    if (strlen($studentId) > 4) {
        return generateError("<li>Student ID must not exceed 4 characters.</li>");
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
            echo '<a href="edit.php?id=' . urlencode($student['id']) . '" class="btn btn-info btn-sm">Edit</a> ';
            echo '<a href="delete.php?id=' . urlencode($student['id']) . '" class="btn btn-danger btn-sm">Delete</a> ';
            echo "<a href='attach-subject.php?id=" . htmlspecialchars($student['id']) . "&class_id=" . htmlspecialchars($student['id']) . "' class='btn btn-warning btn-sm'>Attach Subject</a>";
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

    }
// Function to fetch student details using MySQLi
    function fetchStudentDetails($studentId) {

    $conn = connectDB();
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
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

    // Prepare the DELETE query for the students_subjects table
    $query = "DELETE FROM students_subjects WHERE student_id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Error preparing statement for students_subjects: " . $conn->error);
        $conn->close();
        return false;
    }

    // Bind studentId to delete associated subjects first
    $stmt->bind_param('i', $studentId);
    if (!$stmt->execute()) {
        error_log("Error executing delete query for students_subjects: " . $stmt->error);
        $stmt->close();
        $conn->close();
        return false;
    }

    // Prepare the DELETE query for the students table
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ? AND first_name = ? AND last_name = ?");

    if (!$stmt) {
        error_log("Error preparing statement for students: " . $conn->error);
        $conn->close();
        return false;
    }

    // Bind parameters for deleting the student
    $stmt->bind_param("sss", $studentId, $studentFirstName, $studentLastName);

    // Execute the query to delete the student
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return true;  // Student and associated subjects deleted successfully
    } else {
        error_log("Error executing delete query for students: " . $stmt->error);
        $stmt->close();
        $conn->close();
        return false;  // Failed to delete student
    }
    }

//Functions for attach dettach
    function getStudentId($student_id) {
    $conn = connectDB();  // Ensure you use the database connection within the function
    $sql = "SELECT id, student_id, first_name, last_name FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Return the result if found
    return $result->fetch_assoc(); 
    }
    function getSubjects_StudentId($student_id) {
    $conn = connectDB();  // Ensure the connection is initialized

    $subjects = [];

    // Correct the query: changed 'g.grade' to an appropriate alias or removed it
    $sql = "SELECT s.subject_code, s.subject_name, ss.grade
            FROM subjects s
            INNER JOIN students_subjects ss ON s.id = ss.subject_id
            WHERE ss.student_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id); 
    $stmt->execute();
    $result = $stmt->get_result();

    while ($subject = $result->fetch_assoc()) {
        $subjects[] = $subject;
    }

    $stmt->close();
    $conn->close();  // Close the connection

    return $subjects;
    }
// Fetch all subjects from the database
    function getAllSubjectsdetailes() {
    $conn = connectDB();  // Ensure the database connection function is available
    $sql = "SELECT subject_code, subject_name FROM subjects";  // Adjust query to match your database schema
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];  // Return an empty array if no subjects found
    }
    }
    function attachSubjectsToStudent($student_id, $subjects) {
        // Explicitly call connectDB to ensure the connection is established
        $conn = connectDB();
        $success = true;
        $errors = [];

        if (empty($student_id) || empty($subjects)) {
            $errors[] = "Student ID or subjects cannot be empty.";
            return ['success' => false, 'errors' => $errors];
        }

        // Loop through each subject code to attach
        foreach ($subjects as $subject_code) {
            // Check if the subject is already attached to the student
            $check_query = "SELECT * FROM students_subjects WHERE student_id = ? AND subject_id = (SELECT id FROM subjects WHERE subject_code = ?)";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("is", $student_id, $subject_code);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $errors[] = "Subject with code $subject_code is already attached to this student.";
            } else {
                // Fetch the subject_id based on the subject_code
                $subject_id_query = "SELECT id FROM subjects WHERE subject_code = ?";
                $stmt = $conn->prepare($subject_id_query);
                $stmt->bind_param("s", $subject_code);
                $stmt->execute();
                $subject_result = $stmt->get_result();

                if ($subject_result->num_rows > 0) {
                    // Retrieve the subject_id from the result
                    $subject_id = $subject_result->fetch_assoc()['id'];

                    // Attach the subject to the student with a default grade (0.00 or as needed)
                    $insert_query = "INSERT INTO students_subjects (student_id, subject_id, grade) VALUES (?, ?, 0.00)";
                    $stmt = $conn->prepare($insert_query);
                    $stmt->bind_param("ii", $student_id, $subject_id);

                    if (!$stmt->execute()) {
                        $errors[] = "Failed to attach subject with code $subject_code.";
                        $success = false;
                    }
                } else {
                    $errors[] = "Subject with code $subject_code does not exist.";
                    $success = false;
                }
            }
        }

        // Return success status and any errors
        return ['success' => $success, 'errors' => $errors];
    }
    function getSubjectByCode($subjectCode) {
    $conn = connectDB();
    if ($conn === null) {
        die("Database connection failed.");
    }

    $sql = "SELECT * FROM subjects WHERE subject_code = ?";
    $stmt = $conn->prepare($sql);  // Check for valid connection before this line
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("s", $subjectCode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
    }
    function detachSubjectFromStudent($student_id, $subject_code) {
    $conn = connectDB();    
    if ($conn === null) {
        return ['success' => false, 'errors' => ['Database connection is not established.']];
    }

    // Start by querying the subject to get the subject ID
    $query = "SELECT id FROM subjects WHERE subject_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $subject_code);
    $stmt->execute();

    // Check for SQL errors after execution
    if ($stmt->error) {
        return ['success' => false, 'errors' => ['Query error: ' . $stmt->error]];
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $subject = $result->fetch_assoc();
        $subject_id = $subject['id'];
    } else {
        return ['success' => false, 'errors' => ['Subject not found!']];
    }

    // Now, delete the relationship between student and subject
    $query = "DELETE FROM students_subjects WHERE student_id = ? AND subject_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $student_id, $subject_id);
    $stmt->execute();

    // Check if any rows were deleted
    if ($stmt->affected_rows > 0) {
        return ['success' => true, 'message' => 'Subject successfully detached from student.'];
    } else {
        return ['success' => false, 'errors' => ['No rows were affected. Subject might not be assigned to this student.']];
    }
    }
    function updateGradeForSubject($student_id, $subject_code, $grade) {
    $conn = connectDB();    
    if ($conn === null) {
        return ['success' => false, 'errors' => ['Database connection is not established.']];
    }

    // Start by querying the subject to get the subject ID
    $query = "SELECT id FROM subjects WHERE subject_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $subject_code);
    $stmt->execute();

    // Check for SQL errors after execution
    if ($stmt->error) {
        return ['success' => false, 'errors' => ['Query error: ' . $stmt->error]];
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $subject = $result->fetch_assoc();
        $subject_id = $subject['id'];
    } else {
        return ['success' => false, 'errors' => ['Subject not found!']];
    }

    // Update the grade for the student and subject
    $query = "UPDATE students_subjects SET grade = ? WHERE student_id = ? AND subject_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('dii', $grade, $student_id, $subject_id); // `d` for decimal, `i` for integer
    $stmt->execute();

    // Check if the row was updated
    if ($stmt->affected_rows > 0) {
        return ['success' => true, 'message' => 'Grade successfully updated.'];
    } else {
        return ['success' => false, 'errors' => ['No rows were affected. Grade might already be the same or record not found.']];
    }
    }
// Function to get student grade counts and overall pass/fail counts
    function getStudentGradeCounts() {
    // Connect to the database
    $conn = connectDB();

    // SQL query to calculate average grade and pass/fail status, and count the passed/failed students
    $query = "
           SELECT 
            `student_id`,
            AVG(`grade`) AS `average_grade`,
            CASE 
                WHEN AVG(`grade`) >= 75 THEN 'Passed'
                ELSE 'Failed'
            END AS `status`
        FROM 
            `students_subjects`
        WHERE 
            `grade` >= 65 -- Only consider grades 65 and above
        GROUP BY 
            `student_id`;
    ";

    // Execute the query
    $result = $conn->query($query);

    // Initialize counters for passed and failed students
    $passedCount = 0;
    $failedCount = 0;
    $counts = [];

    // Check if the query was successful
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Add each student's result to the array
            $counts[] = [
                'student_id' => $row['student_id'],
                'average_grade' => $row['average_grade'],
                'status' => $row['status']
            ];

            // Increment pass/fail counters based on the status
            if ($row['status'] == 'Passed') {
                $passedCount++;
            } else {
                $failedCount++;
            }
        }
    }

    // Add the overall pass/fail counts to a separate return array
    return [
        'counts' => $counts,
        'passed_count' => $passedCount,
        'failed_count' => $failedCount,
    ];
    }
?>