

<?php    
    session_start(); // Start the session at the beginning

    // Function to connect to the database
    function connectDB() {
        $servername = "localhost";
        $email = "root";
        $password = "";
        $dbname = "dct-ccs-finals"; // Your database name

        $conn = new mysqli($servername, $email, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    function guard() {  
        if (empty($_SESSION["email"])){
            header("Location:/index.php");
        }
    }
    function returPage(){
        if (!empty($_SESSION["email"])) {
            if (!empty($_SESSION['page'])) {  // Check if the 'page' session variable is set
                header("Location:". $_SESSION['page']);
                exit();
            } else {
                // If 'page' is not set, redirect to a default page (e.g., dashboard or home)
                header("Location: /admin/dashboard.php"); // Change to your default redirect page
                exit();
            }
        }
    }

    // Function to generate error messages (dismissable)
    function generateError($message) {
        return '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>System Error!</strong> ' . $message . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
    }

    // Function to check and validate email and password
    function loginUser($email, $password) {
        // Validate the input
        if (empty($email) || empty($password)) {
            return generateError("<li>Email is required </li><li>Password are required.</li>");
        } elseif (!str_ends_with($email, '@gmail.com')) {
            return generateError("<li>Invalid Email format </li>");
        }
        // Connect to the database
        $conn = connectDB();
        $hashedPassword = md5($password); // Hash the password

        // SQL query to check the email and password
        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $hashedPassword);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If email and password are correct, start the session
            $_SESSION['email'] = $email;
            return true;
        } else {
            // Invalid credentials
            return generateError("<li>Invalid email or password.</li>");
        }
    }

    function logoutUser() {
        session_destroy();
        header("Location:/index.php");
    }



// Function to insert a new subject into the database
function insertSubject($subjectCode, $subjectName) {
    // Connect to the database
    $conn = connectDB();

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO subjects (subject_code, subject_name) VALUES (?, ?)");
    $stmt->bind_param("ss", $subjectCode, $subjectName); // "ss" means two strings

    // Execute the statement and check if successful
    if ($stmt->execute()) {
        // If successful, return true
        $stmt->close();
        $conn->close();
        return true;
    } else {
        // If there was an error, return false
        $stmt->close();
        $conn->close();
        return false;
    }
}
    ?>

