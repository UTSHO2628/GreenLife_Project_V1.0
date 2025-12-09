<?php
// GreenLife - Smart Environmental Impact Dashboard
//
// FILE: calculate.php
//
// This script acts as the central controller for the calculation process.
//
// HOW IT WORKS:
// 1. Starts a session to store results for the next page.
// 2. Includes the database connection configuration.
// 3. Validates that the request method is POST.
// 4. Retrieves and sanitizes user inputs (`car_km`, `elect_hours`).
// 5. Constructs and executes a shell command to run the Python script (`calculate.py`).
// 6. Captures and decodes the JSON output from the Python script.
// 7. Inserts the inputs and results into the MySQL database.
// 8. Stores the results in the $_SESSION superglobal.
// 9. Redirects the user to `result.php` to display the results.

// Start a session to pass the calculation results to the result page.
session_start();

// Include the database configuration file. This establishes the $mysqli connection.
require_once 'config/db.php';

// --- 1. Input Validation ---
// Ensure the script is accessed via a POST request and that data is set.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['car_km']) || !isset($_POST['elect_hours'])) {
    // If not, redirect back to the form with an error message (optional).
    $_SESSION['error'] = "Invalid request."; // You could display this on index.php
    header("Location: index.php");
    exit();
}

// --- 2. Retrieve and Sanitize Inputs ---
// Use filter_input to get and sanitize variables. FILTER_SANITIZE_NUMBER_FLOAT
// removes all characters except digits, dot, and +- signs.
$car_km = filter_input(INPUT_POST, 'car_km', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$elect_hours = filter_input(INPUT_POST, 'elect_hours', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

// Basic validation to ensure values are non-negative.
if ($car_km < 0 || $elect_hours < 0) {
    $_SESSION['error'] = "Inputs cannot be negative.";
    header("Location: index.php");
    exit();
}

// --- 3. Execute Python Script ---
// Construct the full path to the Python script.
$python_script_path = __DIR__ . '/python/calculate.py';

// Use escapeshellarg() to securely pass arguments to the command line.
// This is crucial for preventing command injection vulnerabilities.
$escaped_car_km = escapeshellarg($car_km);
$escaped_elect_hours = escapeshellarg($elect_hours);

// The command to be executed. 'python' can be replaced with 'python3' if needed.
// You might need to use the full path to your python executable on some systems.
// e.g., "C:\\Python39\\python.exe"
$command = "python " . $python_script_path . " " . $escaped_car_km . " " . $escaped_elect_hours;

// Execute the command and capture the output.
// The output from the Python script is expected to be a JSON string.
$json_output = shell_exec($command);

// --- 4. Process Python Script Output ---
// Decode the JSON string into a PHP associative array.
$result = json_decode($json_output, true);

// Check if JSON decoding was successful and the expected keys exist.
if (json_last_error() !== JSON_ERROR_NONE || !isset($result['footprint']) || !isset($result['suggestion'])) {
    // Handle potential errors from the Python script (e.g., wrong arguments, calculation error).
    $_SESSION['error'] = "Could not calculate the footprint. The Python script may have an error.";
    // For debugging, you might want to log the actual output: error_log("Python output: ".$json_output);
    header("Location: index.php");
    exit();
}

$footprint = $result['footprint'];
$suggestion = $result['suggestion'];

// --- 5. Store Results in Database ---
// Prepare an SQL statement to prevent SQL injection.
$sql = "INSERT INTO footprints (car_km, elect_hours, footprint, suggestion) VALUES (?, ?, ?, ?)";

if ($stmt = $mysqli->prepare($sql)) {
    // Bind variables to the prepared statement as parameters.
    // 'ddds' specifies the types of the variables: double, double, double, string.
    $stmt->bind_param("ddds", $car_km, $elect_hours, $footprint, $suggestion);
    
    // Execute the statement.
    // We can add error handling here if needed.
    if(!$stmt->execute()){
        // Log error, but don't necessarily stop the user from seeing their result.
        error_log("Database insert failed: " . $stmt->error);
    }
    
    // Close the statement.
    $stmt->close();
} else {
    // Log error if statement preparation failed.
    error_log("Database prepare failed: " . $mysqli->error);
}

// Close the database connection.
$mysqli->close();

// --- 6. Pass Results to Session and Redirect ---
// Store the results in the session to be displayed on the next page.
$_SESSION['result'] = [
    'car_km' => $car_km,
    'elect_hours' => $elect_hours,
    'footprint' => $footprint,
    'suggestion' => $suggestion
];

// Redirect the user to the result page.
header("Location: result.php");
exit(); // Ensure no further code is executed after the redirect.

?>
