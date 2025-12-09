# GreenLife - Smart Environmental Impact Dashboard
#
# FILE: python/calculate.py
#
# This script calculates the carbon footprint based on user inputs
# and generates a simple suggestion.
#
# It's designed to be called from a PHP script via a system command.
#
# HOW IT WORKS:
# 1. It receives two command-line arguments: car_km and elect_hours.
# 2. It calculates the footprint using a predefined formula.
# 3. It generates a suggestion based on the footprint's value.
# 4. It prints the results (footprint and suggestion) as a JSON formatted string
#    to standard output (stdout), so the calling PHP script can capture it.

import sys
import json

def calculate_footprint(car_km, elect_hours):
    """
    Calculates the carbon footprint using a simple formula.
    Formula: footprint = (car_km * 0.21) + (elect_hours * 0.5)
    Factors are simplified estimates for kg CO2e.
    - Car: ~0.21 kg CO2e per km (average passenger car)
    - Electricity: ~0.5 kg CO2e per kWh (example grid, can vary widely)
    """
    try:
        # Convert inputs to float for calculation
        car_km_float = float(car_km)
        elect_hours_float = float(elect_hours)

        # Apply the formula
        footprint = (car_km_float * 0.21) + (elect_hours_float * 0.5)
        return footprint
    except (ValueError, TypeError):
        # Handle cases where conversion to float fails
        return None

def generate_suggestion(footprint):
    """
    Generates a simple, text-based suggestion based on the footprint value.
    """
    if footprint is None:
        return "Invalid input provided. Could not calculate footprint."
    
    if footprint < 5:
        return "Excellent! Your carbon footprint is quite low. Keep up the great work! To improve further, try replacing old incandescent bulbs with LEDs."
    elif 5 <= footprint < 10:
        return "Your carbon footprint is moderate. Consider carpooling or using public transport once a week to see a significant reduction."
    elif 10 <= footprint < 20:
        return "Your carbon footprint is high. A major contributor is your daily commute. Exploring electric vehicle options or reducing travel days could cut your emissions drastically."
    else:
        return "Your carbon footprint is very high. It's crucial to take action. Focus on major changes like improving home insulation, adopting renewable energy sources, or significantly reducing vehicle usage."

def main():
    """
    Main function to orchestrate the script's execution.
    """
    # Expecting two arguments: car_km and elect_hours
    if len(sys.argv) != 3:
        # Output an error in JSON format if arguments are incorrect
        error_response = {
            "error": "Invalid number of arguments. Expected car_km and elect_hours."
        }
        print(json.dumps(error_response))
        sys.exit(1)

    car_km = sys.argv[1]
    elect_hours = sys.argv[2]

    # Calculate footprint
    footprint = calculate_footprint(car_km, elect_hours)
    
    # Generate suggestion
    suggestion = generate_suggestion(footprint)

    # Prepare the results in a dictionary
    # We round the footprint for cleaner presentation.
    response = {
        "footprint": round(footprint, 2) if footprint is not None else None,
        "suggestion": suggestion
    }

    # Print the dictionary as a JSON string to stdout
    # This is how the data is passed back to the PHP script
    print(json.dumps(response))

if __name__ == "__main__":
    # This block ensures the main function is called only when the script is executed directly
    main()
