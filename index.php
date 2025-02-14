<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">

    <!--This script tag loads the Google Maps JavaScript API with the "Places" library-->
    <script src="https://maps.googleapis.com/maps/api/js?key=
        Replace with your actual API key
        &libraries=places">
    </script>
    <title>Form</title>
  </head>
  <style>
    .container{
        max-width: 50%;
        height: 100% ;
        margin: 0 auto;
        padding: 50px;
        box-shadow: rgba(100,100,111,0.2) 0px 7px 29px 0px;
        margin-top: 75px;
    }
  </style>
    <body>
        <div id="Form" class="container">
            <lable> Exact Address </lable><br>
            <input type="text" id="address" class="form-control" name="address" required><br>
            <lable> Province </lable><br>
            <input type="text" id="province" class="form-control" name="province" required><br>
            <lable> District </lable><br>
            <input type="text" id="district" class="form-control" name="district" required><br>
            <lable> Post Code </lable><br>
            <input type="text" id="postcode" class="form-control" name="postcode" required><br>
        </div>
            <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
    
    document.addEventListener('DOMContentLoaded', function() {
    let autocomplete;

    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('address'),
            { types: ['geocode'] }
        );

        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
        const place = autocomplete.getPlace();
        let province = '';
        let district = '';

        for (const component of place.address_components) {
            const componentType = component.types[0];

            switch (componentType) {
                case 'administrative_area_level_1':
                    province = component.long_name;
                    document.getElementById('province').value = province;
                    break;
                case 'administrative_area_level_2':
                    district = component.long_name;
                    document.getElementById('district').value = district;
                    break;
            }
        }

        // Trigger AJAX request to get latitude, longitude, and postcode
        getAddressDetails();
    }

    function getAddressDetails() {
        const proaddress = document.getElementById('address').value;
        const proprovince = document.getElementById('province').value;
        const prodistrict = document.getElementById('district').value;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'fetch_code.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.postcode) {
                    document.getElementById('postcode').value = response.postcode;
                }
                // Optionally handle latitude and longitude if needed
                console.log('Latitude:', response.latitude);
                console.log('Longitude:', response.longitude);
            }
        };
        xhr.send('address=' + encodeURIComponent(proaddress) +
                 '&province=' + encodeURIComponent(proprovince) +
                 '&district=' + encodeURIComponent(prodistrict));
    }
    initAutocomplete();
});
    
      //the PRG pattern, can avoid the "resubmission" warning
      if ( window.history.replaceState ) {
          window.history.replaceState( null, null, window.location.href );
      }
    </script>

    </body>
</html>
