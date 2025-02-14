Address Form with Province, District, and Postcode Auto-fill Functionality

Project Description
This project implements a web-based form for capturing and automatically populating 
address-related information, such as province, district, and postcode, based on an 
exact address input. The system integrates the Google Maps JavaScript API with the 
Places library for auto-complete functionality, and the Google Geocoding API to 
retrieve additional location data such as latitude, longitude, and postal code.

Additionally, the program includes server-side logic written in PHP to process 
the submitted address, calculate approximate location details, and fetch the 
postal code from a database using latitude and longitude.

Puspose of this program
#The Google API was provide a location address, state, province, district, and Geocoder. 
the they didn't provide a local postal relevant details. so, to provide the postal code 
and the relevant details.


Functionality Overview

Exact Address Auto-complete
#Users type their exact address into the form.
#The Google Maps Places Library provides real-time suggestions based on user input.

Auto-fill for Province and District
#Once a user selects an address, the system extracts the province and district from the address components and populates the respective fields.

Postcode Retrieval
#The system uses the Google Geocoding API to calculate the latitude and longitude of the provided address.
#These coordinates are compared against stored database records to find and return the appropriate postcode.

Database Approximation Matching
#Latitude and longitude values are compared against a pre-defined tolerance level to find the nearest matching postcode in the database. This helps to ensure robust handling of approximate location matches.


