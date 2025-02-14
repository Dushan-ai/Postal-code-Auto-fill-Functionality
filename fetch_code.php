<?php
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $proaddress = $_POST['address'];
    $proprovince = $_POST['province'];
    $prodistrict = $_POST['district'];

    // Combine the address components into a full address string
    $address = $proaddress;

    // Function to get latitude and longitude using Google Maps Geocoding API
    function getLatLong($address) {
        $address = urlencode($address);
        $apiKey = ''; // Replace with your actual API key
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$apiKey}";

        $response = file_get_contents($url);
        if ($response === FALSE) {
            echo json_encode(['error' => 'Failed to get response from API']);
            exit;
        }
        //echo '<pre>' . htmlspecialchars($response) . '</pre>'; // Debug output
        $json = json_decode($response, true);

        if ($json['status'] == 'OK') {
            $latitude = $json['results'][0]['geometry']['location']['lat'];
            $longitude = $json['results'][0]['geometry']['location']['lng'];
            return array('latitude' => $latitude, 'longitude' => $longitude);
        } else {
            // Handle case where address is not found
            $latitude = "8.58736380";
            $longitude = "81.21521210";
            return array('latitude' => $latitude, 'longitude' => $longitude);
        }
    }
    // Function to find approximate matches
    function findApproximateLocation($latitude, $longitude, $conn) {
        // Define a tolerance level (in degrees). This corresponds to a distance of about 1 km.
        $tolerance = 0.01;

        // Calculate the range for latitude and longitude
        $minLat = $latitude - $tolerance;
        $maxLat = $latitude + $tolerance;
        $minLng = $longitude - $tolerance;
        $maxLng = $longitude + $tolerance;

        // Construct SQL query with tolerance
        $sql = "SELECT postcode FROM location 
                WHERE latitude BETWEEN ? AND ?
                AND longitude BETWEEN ? AND ?";

        // Prepare and execute the query
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("dddd", $minLat, $maxLat, $minLng, $maxLng);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['postcode'];
            } else {
                return null; // No matches found
            }
        } else {
            return null; // Error preparing the query
        }
    }

    // Get the latitude and longitude from the full address
    $latLong = getLatLong($address);

    if ($latLong) {
        $latitude = $latLong['latitude'];
        $longitude = $latLong['longitude'];
        // Find approximate location
        $postcode = findApproximateLocation($latitude, $longitude, $conn);
        if ($postcode) {
            echo json_encode(['latitude' => $latitude, 'longitude' => $longitude, 'postcode' => $postcode]);
        } else {
            echo json_encode(['latitude' => $latitude, 'longitude' => $longitude]);
        }
        // Close the connection
        $conn->close();
    } else {
        // Handle address not found
        echo json_encode(['address' => $address, 'error' => 'Unable to retrieve latitude and longitude']);
    }
}
