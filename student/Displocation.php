<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Live Bus Location Tracking</title>
  <style>
    #map {
      height: 400px;
      width: 100%;
    }

#map {
  height: 100%;
  width: 100%;
  position: absolute;
  left: 0;
}

    #status {
      padding: 10px;
      background: #f1f1f1;
      font-family: Arial, sans-serif;
    }
  </style>
</head>

<body>
  <h1>Live Bus Location</h1>

  <div id="status">Fetching bus location...</div>

  <div id="map"></div>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASFAl719UkPSCvcQU3AuD4QE6gPWe-tg4&callback=initMap" async defer></script>

  <script>
    let map, marker;

    function initMap() {
      const defaultLocation = {
        lat: 9.9947,
        lng: 76.3579
      };

      map = new google.maps.Map(document.getElementById('map'), {
        center: defaultLocation,
        zoom: 15
      });

      marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        title: "Bus Location"
      });

      // Start fetching bus location
      fetchBusLocation();
    }

    function fetchBusLocation() {
      fetch('get_latest_location.php')
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            document.getElementById('status').textContent = data.error;
          } else {
            updateBusLocation(data);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          document.getElementById('status').textContent = "Error fetching bus location.";
        })
        .finally(() => {
          // Fetch location again after 2 minutes
          setTimeout(fetchBusLocation, 120000);
        });
    }

    function updateBusLocation(location) {
      const busLocation = {
        lat: parseFloat(location.latitude),
        lng: parseFloat(location.longitude)
      };

      map.setCenter(busLocation);
      marker.setPosition(busLocation);

      const timestamp = new Date(location.timestamp).toLocaleString();
      const localTime = new Date().toLocaleString('en-IN', {
        timeZone: 'Asia/Kolkata'
      });

      document.getElementById('status').textContent = `Bus location updated at ${localTime}`;
    }
  </script>
</body>

</html>




