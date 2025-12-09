<?php
// GreenLife - Smart Environmental Impact Dashboard
//
// FILE: index.php
//
// This is the main landing page of the application.
// It displays the input form for the user to enter their daily usage data.
// The form submits data to `calculate.php` for processing.

// Start a session to handle potential messages or data persistence if needed.
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenLife - Carbon Footprint Calculator</title>
    
    <!-- Link to the external stylesheet for consistent styling -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="container">
        <header>
            <h1>🌿 GreenLife</h1>
            <p>Your Smart Environmental Impact Dashboard</p>
        </header>

        <main>
            <!-- 
                The form collects user data and sends it to `calculate.php` using the HTTP POST method.
                Using POST is more secure than GET for sending form data as it doesn't expose the values in the URL.
            -->
            <form action="calculate.php" method="POST">
                
                <div class="form-group">
                    <label for="car_km">Daily Car Travel (in KM)</label>
                    <input type="number" id="car_km" name="car_km" step="0.1" min="0" required placeholder="e.g., 25.5">
                </div>

                <div class="form-group">
                    <label for="elect_hours">Daily Electricity Use (in Hours)</label>
                    <input type="number" id="elect_hours" name="elect_hours" step="0.1" min="0" required placeholder="e.g., 8">
                </div>

                <button type="submit" class="btn">Calculate My Footprint</button>

            </form>
        </main>
        
        <!-- A simple footer -->
        <footer>
            <p style="text-align: center; margin-top: 20px; font-size: 0.9em; color: #888;">
                &copy; <?php echo date("Y"); ?> GreenLife Project
            </p>
        </footer>
    </div>

</body>
</html>
