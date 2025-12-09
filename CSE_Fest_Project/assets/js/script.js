/*
 * GreenLife - Smart Environmental Impact Dashboard
 *
 * FILE: assets/js/script.js
 *
 * This script handles the rendering of the Chart.js bar chart on the result page.
 * It's included in `result.php`.
 */

// The `DOMContentLoaded` event fires when the initial HTML document has been
// completely loaded and parsed, without waiting for stylesheets, images, and
// subframes to finish loading.
document.addEventListener('DOMContentLoaded', function() {
    
    // Check if the chart canvas element exists on the page.
    // This prevents errors if the script is accidentally loaded on a page without the chart.
    const ctx = document.getElementById('footprintChart');

    if (ctx) {
        // --- CHART.JS IMPLEMENTATION ---

        // This is the data for the bar chart.
        // As per requirements, we are using STATIC SAMPLE DATA for demonstration.
        // A more advanced implementation could fetch this data from the database via an API.
        const chartData = {
            labels: ['Your Footprint', 'Average Person', 'Eco-Warrior'],
            datasets: [{
                label: 'Carbon Footprint (kg CO2e per day)',
                // The 'userFootprint' variable is passed from `result.php` via a script tag.
                // We provide a fallback value of 0 if it's not defined.
                data: [userFootprint || 0, 15.5, 4.0],
                backgroundColor: [
                    '#4CAF50', // --secondary-color (Your bar)
                    '#FFC107', // An orange color for average
                    '#2E7D32'  // --primary-color (Eco-warrior bar)
                ],
                borderColor: [
                    '#388E3C',
                    '#FFA000',
                    '#1B5E20'
                ],
                borderWidth: 1
            }]
        };

        // Configuration options for the chart.
        // We can customize animations, axes, titles, etc. here.
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'kg CO2e / day'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false // We hide the legend as the labels are self-explanatory
                },
                title: {
                    display: true,
                    text: 'Daily Carbon Footprint Comparison',
                    font: {
                        size: 18
                    }
                }
            }
        };

        // Create a new Chart instance.
        // This command tells Chart.js to draw the chart on our <canvas> element.
        new Chart(ctx, {
            type: 'bar', // Specify the chart type
            data: chartData,
            options: chartOptions
        });
    }

});
