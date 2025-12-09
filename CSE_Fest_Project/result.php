<?php
// GreenLife - Smart Environmental Impact Dashboard
//
// FILE: result.php
//
// This page displays the results of the carbon footprint calculation.
//
// HOW IT WORKS:
// 1. Starts a session to retrieve the calculated data.
// 2. Checks if the result data exists in the session. If not, it redirects to the homepage.
// 3. Extracts the data into variables.
// 4. Renders the HTML, displaying the footprint value and the suggestion.
// 5. Includes the Chart.js library from a CDN.
// 6. Injects the user's footprint value into a JavaScript variable for the chart.
// 7. Includes the local `script.js` to render the chart.
// 8. Unsets the session data to prevent old results from showing up on a refresh.

session_start();

// Check if the result data is available in the session.
// If someone navigates to this page directly without a calculation,
// they will be redirected back to the homepage.
if (!isset($_SESSION['result'])) {
    header("Location: index.php");
    exit();
}

// Extract the result data from the session into a variable.
$result = $_SESSION['result'];

// Unset the session variable to clean up.
// This means if the user refreshes the page, the data will be gone,
// and they will be redirected back to the form. This is good practice.
unset($_SESSION['result']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Carbon Footprint Result - GreenLife</title>
    
    <!-- Link to the external stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Include Chart.js from a CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <div class="container">
        <header>
            <h1>🌿 Your Result</h1>
            <p>Here is your calculated daily carbon footprint.</p>
        </header>

        <main>
            <!-- Display the calculated footprint -->
            <div class="result-card">
                <h2>Your Daily Footprint</h2>
                <p>
                    <span id="footprint-value"><?php echo htmlspecialchars($result['footprint']); ?></span> kg CO2e
                </p>
            </div>

            <!-- Display the AI-generated suggestion -->
            <div class="result-card">
                <h2>Our Suggestion</h2>
                <p id="suggestion-text">
                    "<?php echo htmlspecialchars($result['suggestion']); ?>"
                </p>
            </div>

            <!-- The container for our Chart.js graph -->
            <div class="chart-container">
                <canvas id="footprintChart" height="300"></canvas>
            </div>
            
            <!-- A button to go back to the calculator form -->
            <a href="index.php" class="btn" style="margin-top: 30px;">Calculate Again</a>
        </main>
        
        <footer>
            <p style="text-align: center; margin-top: 20px; font-size: 0.9em; color: #888;">
                &copy; <?php echo date("Y"); ?> GreenLife Project
            </p>
        </footer>
    </div>

    <script>
        // Pass the PHP footprint value to our JavaScript file.
        // This is a common and simple way to bridge server-side and client-side data.
        const userFootprint = <?php echo json_encode($result['footprint']); ?>;
    </script>
    
    <!-- Link to our local JavaScript file which handles the chart rendering -->
    <script src="assets/js/script.js"></script>

</body>
</html>
