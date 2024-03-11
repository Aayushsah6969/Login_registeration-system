<?php
// Database connection parameters
$servername = "localhost"; // Change this if your database is hosted on a different server
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$database = "crudphp"; // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['register_email'];
    $username = $_POST['register_username'];
    $password = $_POST['register_password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to insert data into users table
    $sql = "INSERT INTO users (email_id, username, password) VALUES (?, ?, ?)";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $username, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
        // Create a new table for the user
        $create_table_sql = "CREATE TABLE $username (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT
        )";

        if ($conn->query($create_table_sql) === TRUE) {
            // Redirect to todo.php
            header("Location: todo.php");
            exit;
        } else {
            echo "Error creating table: " . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
