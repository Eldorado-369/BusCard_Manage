<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Driver Location Sharing</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background-color: #f0f0f0;
    }
    .container {
      text-align: center;
      background-color: white;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    #shareButton {
      background-color: #4CAF50;
      border: none;
      color: white;
      padding: 15px 32px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 4px;
    }
    #status {
      margin-top: 1rem;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Driver Location Sharing</h1>
    <button id="shareButton">Start Sharing Location</button>
    <div id="status">Location sharing is off</div>
  </div>

  <script>
    let isSharing = false;
    let watchId = null;
    const shareButton = document.getElementById('shareButton');
    const statusDiv = document.getElementById('status');
    const updateInterval =  60 * 1000; // 1 minute
    let lastUpdateTime = 0;

    shareButton.addEventListener('click', toggleLocationSharing);

    function toggleLocationSharing() {
      if (isSharing) {
        stopSharing();
      } else {
        startSharing();
      }
    }

    function startSharing() {
      if ("geolocation" in navigator) {
        watchId = navigator.geolocation.watchPosition(handlePosition, handleError, {
          enableHighAccuracy: true,
          timeout: 5000,
          maximumAge: 0
        });
        isSharing = true;
        shareButton.textContent = 'Stop Sharing Location';
        statusDiv.textContent = 'Location sharing is on';
      } else {
        statusDiv.textContent = "Geolocation is not supported by this browser.";
      }
    }

    function stopSharing() {
      if (watchId !== null) {
        navigator.geolocation.clearWatch(watchId);
        watchId = null;
      }
      isSharing = false;
      shareButton.textContent = 'Start Sharing Location';
      statusDiv.textContent = 'Location sharing is off';
    }

    function handlePosition(position) {
      const currentTime = Date.now();
      if (currentTime - lastUpdateTime >= updateInterval) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        const timestamp = new Date();

        // Convert timestamp to IST
        const istOffset = 5.5 * 60 * 60 * 1000; // IST is UTC+5:30
        const istTimestamp = new Date(timestamp.getTime() + istOffset);
        const istFormattedTime = istTimestamp.toISOString().slice(0, 19).replace("T", " "); 

        console.log('Latitude:', latitude);
        console.log('Longitude:', longitude);

        sendLocationToServer(latitude, longitude, istFormattedTime);
        lastUpdateTime = currentTime;
      }
    }

    function handleError(error) {
      console.error('Geolocation error:', error);
      statusDiv.textContent = `Error: ${error.message}`;
      stopSharing();
    }

//     function sendLocationToServer(latitude, longitude, istTimestamp) {
//     console.log("Sending data:", latitude, longitude, istTimestamp); // Debugging log
//     const xhr = new XMLHttpRequest();
//     xhr.open("POST", "update_location.php", true);
//     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//     xhr.onreadystatechange = function() {
//         if (this.readyState === XMLHttpRequest.DONE) {
//             if (this.status === 200) {
//                 console.log("Location sent to server:", this.responseText);
//                 document.getElementById('status').textContent = 'Location updated successfully';
//             } else {
//                 console.error("Error sending location to server");
//                 document.getElementById('status').textContent = 'Error updating location';
//             }
//         }
//     };
//     xhr.send(`latitude=${latitude}&longitude=${longitude}&timestamp=${istTimestamp}`);
// }

function sendLocationToServer(latitude, longitude, istTimestamp) {
    const busNumber = "1"; // Replace with dynamic value if needed
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_location.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (this.readyState === XMLHttpRequest.DONE) {
            if (this.status === 200) {
                console.log("Location sent to server:", this.responseText);
                document.getElementById('status').textContent = 'Location updated successfully';
            } else {
                console.error("Error sending location to server");
                document.getElementById('status').textContent = 'Error updating location';
            }
        }
    };
    xhr.send(`latitude=${latitude}&longitude=${longitude}&timestamp=${istTimestamp}&busNumber=${busNumber}`);
}


  </script>
</body>
</html>
