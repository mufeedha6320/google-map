
<!doctype html>
<!--
 @license
 Copyright 2019 Google LLC. All Rights Reserved.
 SPDX-License-Identifier: Apache-2.0
-->
<html>
  <head>
    <title>Add Map</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        #map {
          height: 80%;
          width: 80%;
          margin-left: 10px;
        }
        html, body {
          height: 100%;
          margin: 0;
          padding: 0;
        }
        h3{
            padding: 9px;
        }
      </style>
  </head>
  <body>

        <h3>My Google Maps Demo</h3>
        <h4>Current Location : <div id="current_location"> </div></h4>
        <form action="" method="POST">
            @csrf
            @method('PUT')

            <label for="delivery_status">Delivery Status:</label>
            <select id="delivery_status" name="delivery_status" required>
                <option value="pending">Pending</option>
                <option value="in_transit">In Transit</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
            </select>

            <button type="submit">Update Delivery</button>
        </form>

        <div id="map"></div>




    <!-- prettier-ignore -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let map;
        let marker;

        async function initMap() {
            // Ensure delivery coordinates exist
            @if ($delivery && $delivery->lat && $delivery->lng)
                const position = { lat: {{ $delivery->lat }}, lng: {{ $delivery->lng }} };

                //@ts-ignore
                const { Map } = await google.maps.importLibrary("maps");
                const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

                map = new Map(document.getElementById("map"), {
                    zoom: 12,
                    center: position,
                    mapId: "DEMO_MAP_ID",
                });

                marker = new AdvancedMarkerElement({
                    map: map,
                    position: position,
                    title: "Location",
                });

                const geocoder = new google.maps.Geocoder();

                // Perform reverse geocoding
                geocoder.geocode({ location: position }, (results, status) => {
                    if (status === "OK") {
                        if (results[0]) {
                            let city = null;

                            // Loop through the address components to find the city name
                            results[0].address_components.forEach(component => {
                                if (component.types.includes("locality")) {
                                    city = component.long_name;
                                }
                            });

                            if (city) {
                                console.log("City: " + city);
                                // You can do something with the city name here, e.g., update a field
                            }
                        } else {
                            console.log("No results found");
                        }
                    } else {
                        console.log("Geocoder failed due to: " + status);
                    }
                });

                // Add click event listener to the map
                map.addListener('click', (event) => {
                    const lat = event.latLng.lat();
                    const lng = event.latLng.lng();
                    console.log(lng ," ; ", lat);

                    const isConfirmed = window.confirm(`Do you want to update the location to (${lat}, ${lng})?`);

                    if (isConfirmed) {
                        updateLocation(lat, lng);
                    }
                });

            @else
                console.error("Delivery location not found or missing coordinates.");
            @endif
        }

        async function updateLocation(lat, lng) {
            try {
                console.log(" Inside updateLocation ");
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const response = await fetch('/api/update-location', { // Adjust the URL as necessary
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        // 'X-CSRF-TOKEN': token, // Include CSRF token for Laravel
                    },
                    body: JSON.stringify({
                        lat, lng,  _token: token // CSRF token included in the body
                    }),
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                console.log('Location updated:', data);
                window.location.reload();
            } catch (error) {
                console.error('Error updating location:', error);
            }
        }

    </script>

    <!-- Load the Google Maps JavaScript API with callback -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key=Your_Api_KeyC_4&callback=initMap"
        async
    ></script>

  </body>
</html>
