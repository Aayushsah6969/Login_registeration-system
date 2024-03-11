<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "crudphp";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $login_username = $_POST['login_username'];
    $login_password = $_POST['login_password'];

    // Prepare SQL statement to fetch user data
    $sql = "SELECT * FROM users WHERE username = ?";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $login_username);

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($login_password, $user['password'])) {
            echo "Login successful!";
            // Start session and set session variables if needed
            // session_start();
            // $_SESSION['loggedin'] = true;
            // $_SESSION['username'] = $user['username'];
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
