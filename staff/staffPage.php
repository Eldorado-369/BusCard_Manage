<?php
session_start();
error_reporting(E_ALL);

// Initialize database connection
$connection = new mysqli("localhost", "root", "", "buscardmanagementsystem");
if ($connection->connect_error) {
  die("Connection failed: " . $connection->connect_error);
}

// Check if the staff is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: http://localhost/Project/loginPage/loginPage.php");
  exit;
}

// Get the logged-in staff ID from the session
$staff_id = $_SESSION['staff_id'];

// Fetch staff details using the staff ID
$sql = "SELECT * FROM staffDetails WHERE ID='$staff_id'";
$result = mysqli_query($connection, $sql);

$staff = mysqli_fetch_assoc($result);
$staffName = $staff['NAME'];
$staffMail = $staff['MAIL'];
$staffId = $staff['ID'];
$staffPassword = $staff['PASSWORD'];
$staffBusNumber = $staff['busNumber'];

// Image paths
$jpegImagePath = "$staffMail.jpeg";
$jpgImagePath = "$staffMail.jpg";
$pngImagePath = "$staffMail.png";

$imageSrc = "";
if (file_exists($jpegImagePath)) {
  $imageSrc = $jpegImagePath;
} elseif (file_exists($jpgImagePath)) {
  $imageSrc = $jpgImagePath;
} else {
  $imageSrc = $pngImagePath;
}

// Fetch count of inbox
$sql = "SELECT COUNT(*) AS inboxCount FROM inboxTable WHERE toMail='$staffMail'";
$result = mysqli_query($connection, $sql);
$staff = mysqli_fetch_assoc($result);
$inboxCount = $staff['inboxCount'];

// Fetch admin mail
$sql = "SELECT mailId FROM adminidandpassword";
$result = mysqli_query($connection, $sql);
if ($result) {
  $row = mysqli_fetch_assoc($result);
  $adminMailId = $row['mailId'];
} else {
  echo "Error: " . mysqli_error($connection);
}

echo "<script>
  document.addEventListener('DOMContentLoaded', () => {
    if ($inboxCount == 0) {
      document.getElementById('noNotifications').style.display = 'inline';
      document.getElementById('envelopeNumber').style.display = 'none';
    }
  });
</script>";
?>

<!DOCTYPE html>
<html>

<head>
  <title> STAFF </title>
  <link rel="stylesheet" href="staffPage1.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="allContainer">
    <div id="sideNavigationBar" class="sideNavigationBar">
      <div class="firstDivision">
        <div class="toggleIcon">
          <i id="toggleButton" class="fa-solid fa-minimize hover-effect"></i>
        </div>
      </div>
      <div class="secondDivision">
        <div class="profile">
          <img id="staffProfile" src="http://localhost/Project/staff/staffImages/<?php echo $imageSrc; ?>">
        </div>
        <div class="adminNameAndMail">
          <div class="adminName">
            <p class="hover-effect hiText"> Hi, </p>
            <p class="hover-effect adminNameText"> <?php echo "$staffName"; ?> </p>
          </div>
          <div class="adminMail">
            <p class="font-color hover-effect adminMailText"> <?php echo "$staffMail"; ?> </p>
          </div>
        </div>
      </div>
      <div class="sideNavigationBarList">
        <ul>
          <li>
            <a class="sideNavbarContents hover-effect"
              onclick="showBusCardPane()">

              <script>
                function showBusCardPane() {
                  const busCardPane = document.getElementById('loader');
                  const studentDet = document.getElementById('studentDet');
                  const locationPane = document.getElementById('locationPane');
                  const feedbackPane = document.getElementById('feedback');
                  const editProfilePane = document.getElementById('editProfile');
                  const inbox = document.getElementById('inbox');
                  busCardPane.style.display = 'flex';
                  studentDet.style.display = 'none';
                  locationPane.style.display = 'none';
                  feedbackPane.style.display = 'none';
                  editProfilePane.style.display = 'none';
                  inbox.style.display = 'none';
                }
              </script>

              <div class="item1">

                <div class="item1Top">
                  <div class="item1TopLeft">
                    <i class="fa-regular fa-address-card"></i>
                  </div>
                  <div class="item1TopRight">
                    <p class="addTopMargin"> Bus Card </p>
                  </div>
                </div>
              </div>
            </a>
          </li>

          <li>
            <a class="sideNavbarContents hover-effect"
              onclick="showStudentPane()">

              <script>
                function showStudentPane() {
                  const busCardPane = document.getElementById('loader');
                  const studentDet = document.getElementById('studentDet');
                  const locationPane = document.getElementById('locationPane');
                  const feedbackPane = document.getElementById('feedback');
                  const editProfilePane = document.getElementById('editProfile');
                  const inbox = document.getElementById('inbox');
                  busCardPane.style.display = 'none';
                  studentDet.style.display = 'flex';
                  locationPane.style.display = 'none';
                  feedbackPane.style.display = 'none';
                  editProfilePane.style.display = 'none';
                  inbox.style.display = 'none';
                }
              </script>

              <div class="item1">

                <div class="item1Top">
                  <div class="item1TopLeft">
                    <i class="fa-regular fa-address-card"></i>
                  </div>
                  <div class="item1TopRight">
                    <p class="addTopMargin"> Student Details</p>
                  </div>
                </div>
              </div>
            </a>
          </li>





          <li>
            <a class="sideNavbarContents hover-effect"
              onclick="showLocationPane()">

              <script>
                function showLocationPane() {
                  const busCardPane = document.getElementById('loader');
                  const studentDet = document.getElementById('studentDet');
                  const locationPane = document.getElementById('locationPane');
                  const feedbackPane = document.getElementById('feedback');
                  const editProfilePane = document.getElementById('editProfile');
                  const inbox = document.getElementById('inbox');
                  inbox.style.display = 'none';
                  busCardPane.style.display = 'none';
                  studentDet.style.display = 'none';
                  locationPane.style.display = 'block';
                  feedbackPane.style.display = 'none';
                  editProfilePane.style.display = 'none';
                }
              </script>
              <div class="item3">
                <div class="item3Top">
                  <div class="item3TopLeft">
                    <i class="fa-solid fa-location-dot"></i>
                  </div>
                  <div class="item3TopRight">
                    <p class="addTopMargin"> Location </p>
                  </div>
                </div>
              </div>
            </a>
          </li>
          <li>
            <a class="sideNavbarContents hover-effect"
              onclick="showFeedbackPane()">

              <script>
                function showFeedbackPane() {
                  const busCardPane = document.getElementById('loader');
                  const studentDet = document.getElementById('studentDet');
                  const locationPane = document.getElementById('locationPane');
                  const feedbackPane = document.getElementById('feedback');
                  const editProfilePane = document.getElementById('editProfile');
                  const inbox = document.getElementById('inbox');
                  inbox.style.display = 'none';
                  busCardPane.style.display = 'none';
                  studentDet.style.display = 'none';
                  locationPane.style.display = 'none';
                  feedbackPane.style.display = 'flex';
                  editProfilePane.style.display = 'none';
                }
              </script>
              <div class="item5">
                <div class="item5Top">
                  <div class="item5TopLeft">
                    <i class="fa-solid fa-comment"></i>
                  </div>
                  <div class="item5TopRight">
                    <p class="addTopMargin"> Feedback </p>
                  </div>
                </div>
              </div>
            </a>
          </li>
          <li>

            <a class="sideNavbarContents hover-effect"
              onclick="showEditProfilePane()">

              <script>
                function showEditProfilePane() {
                  const busCardPane = document.getElementById('loader');
                  const studentDet = document.getElementById('studentDet');
                  const locationPane = document.getElementById('locationPane');
                  const feedbackPane = document.getElementById('feedback');
                  const editProfilePane = document.getElementById('editProfile');
                  const inbox = document.getElementById('inbox');
                  inbox.style.display = 'none';
                  busCardPane.style.display = 'none';
                  studentDet.style.display = 'none';
                  locationPane.style.display = 'none';
                  feedbackPane.style.display = 'none';
                  editProfilePane.style.display = 'flex';
                }
              </script>
              <div class="item6">
                <div class="item5Top">
                  <div class="item5TopLeft">
                    <i class="fa-solid fa-user-pen"></i>
                  </div>
                  <div class="item5TopRight">
                    <p class="addTopMargin"> Edit Profile </p>
                  </div>
                </div>
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="rightPane">
      <div class="dashboardTextAndIcons">
        <div class="dashboardText hover-effect">
          Dashboard
        </div>
        <div class="topIcons">
          <a style="color: black;" onclick="showInbox()">
            <div class="envelopeAndNumber">
              <div class="envelopeNumber" id="envelopeNumber">
                <?php echo "$inboxCount"; ?>
              </div>
              <i class="fa-solid fa-envelope hover-effect" id="topIconsInbox"></i>
            </div>
          </a>
          <a href="logout.php" style="color: black;"> <i class="fa-solid fa-right-from-bracket hover-effect"></i> </a>
        </div>
      </div>
      <div class="busBodyContainer hover-effect">
        <div class="loader" id="loader">
          <div class="busBody">
            <div class="upperBody">
              <div class="peopleWindows">
                <div class="window1">
                  <div class="showCardStudentNameBusNumber">
                    <p id="showCardStudentName"> Name : <?php echo "$staffName" ?> </p>
                    <p id="showCardBusNumber"> Bus Number : <?php echo "$staffBusNumber" ?> </p>
                  </div>
                </div>
              </div>
              <div class="driverWindow">
                <img id="staffProfile" src="http://localhost/Project/staff/staffImages/<?php echo $imageSrc; ?>">
              </div>
            </div>
            <div class="collegeName">RCMAS</div>
            <div class="lowerBody">
              <div class="backLight"></div>
              <div class="headlight"></div>
            </div>
            <div class="backTyre">
              <div class="innerBackTyre"></div>
            </div>
            <div class="frontTyre">
              <div class="innerFrontTyre"></div>
            </div>
          </div>
        </div>

        <div class="inbox" id="inbox">
          <div id="notificationsContainer">
            <!-- Notifications will be dynamically added here -->
            <span class="noNotifications" id="noNotifications"> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> No messages...come back later </span>
          </div>
        </div>

        <!-- Hidden form to move notifications -->
        <form id="moveNotificationForm" method="post" action="moveNotification.php" style="display: none;">
          <input type="hidden" name="inboxId" id="inboxId">
          <input type="hidden" name="staffMail" value="<?php echo $staffMail; ?>">
        </form>

        <script>
          let inboxShown = false;

          function showInbox() {
            const busCardPane = document.getElementById('loader');
            const studentDet = document.getElementById('studentDet');
            const feedbackPane = document.getElementById('feedback');
            const editProfilePane = document.getElementById('editProfile');
            const inboxPane = document.getElementById('inbox');
            const locationPane = document.getElementById('locationPane');

            // Show or hide inbox
            if (inboxShown) {
              busCardPane.style.display = 'none';
              studentDet.style.display = 'none';
              feedbackPane.style.display = 'none';
              editProfilePane.style.display = 'none';
              locationPane.style.display = 'none';
              inboxPane.style.display = 'flex';
              return;
            }

            busCardPane.style.display = 'none';
            studentDet.style.display = 'none';
            feedbackPane.style.display = 'none';
            editProfilePane.style.display = 'none';
            locationPane.style.display = 'none';
            inboxPane.style.display = 'flex';

            // Define notifications array using PHP
            const notifications = [
              <?php
              $result = mysqli_query($connection, "SELECT inboxId, fromMail, content, dateAndTime FROM inboxTable WHERE toMail='$staffMail'");
              while ($row = mysqli_fetch_assoc($result)) {
                $inboxId = $row['inboxId'];
                $fromMail = addslashes($row['fromMail']); // Escape special characters
                $content = addslashes($row['content']); // Escape special characters
                $dateAndTime = addslashes($row['dateAndTime']); // Escape special characters
                echo "{ inboxId: '{$inboxId}', fromMail: '{$fromMail}', dateAndTime: '{$dateAndTime}', content: '{$content}' },";
              }
              ?>
            ];



            // Dynamically create notification elements
            notifications.forEach((notification, index) => {
              const notificationDiv = document.createElement('div');
              notificationDiv.classList.add('notification');
              notificationDiv.id = `notification-${index + 1}`; // Assign unique ID

              notificationDiv.innerHTML = `
                <p> Message from: ${notification.fromMail}</p>
                <p>${notification.dateAndTime}</p>
                <div class="notification-content">
                    <p>${notification.content}</p>
                    <span class="close-btn" onclick="closeNotification(this, ${notification.inboxId})">Dismiss</span>
                    
                </div>
            `;

              // Add event listener for expanding/collapsing notifications
              notificationDiv.addEventListener('click', function(event) {
                if (!event.target.classList.contains('close-btn')) {
                  notificationDiv.classList.toggle('expanded');
                }
              });

              const notificationsContainer = document.getElementById('notificationsContainer');
              notificationsContainer.appendChild(notificationDiv);
            });

            inboxShown = true;
          }

          let inboxCount = <?php echo $inboxCount; ?>; // Initialize inboxCount from PHP

          function closeNotification(element, inboxId) {
            const notification = element.closest('.notification');
            notification.remove();

            // Decrement the inboxCount variable in JavaScript
            inboxCount--;

            if (inboxCount == 0) {
              const noNotification = document.getElementById('noNotifications');
              noNotification.style.display = 'inline';
              const envelopeNumber = document.getElementById('envelopeNumber');
              envelopeNumber.style.display = 'none';
            }

            // Update the display element
            document.querySelector('.envelopeNumber').textContent = inboxCount;

            // Set inbox ID in form and submit
            document.getElementById('inboxId').value = inboxId;
            document.getElementById('moveNotificationForm').submit();
          }
        </script>


        <!-- <div class="studentDet" id="studentDet"> -->
        <?php
        // Database connection
        $connection = new mysqli("localhost", "root", "", "buscardmanagementsystem");
        if ($connection->connect_error) {
          die("Connection failed: " . $connection->connect_error);
        }

        // Get the logged-in staff ID from the session
        $staff_id = $_SESSION['staff_id'];

        // Fetch staff details including busNumber
        $sql = "SELECT busNumber FROM staffDetails WHERE ID='$staff_id'";
        $result = mysqli_query($connection, $sql);
        $staff = mysqli_fetch_assoc($result);
        $staffBusNumber = $staff['busNumber'];

        // SQL query to fetch students traveling in the same bus as the staff
        $sql = "SELECT 
            sc.studentId, 
            sp.name, 
            sp.mail, 
            sc.busNumber, 
            sc.cardNumber, 
            sc.card_type AS type, 
            sc.status
        FROM 
            studentcard sc
        JOIN 
            studentidandpassword sp ON sc.studentId = sp.id
        WHERE 
            sp.is_active = 1 AND sc.busNumber = '$staffBusNumber'";

        $result = mysqli_query($connection, $sql);
        ?>

        <!-- View Student Details Table -->
        <div class="studentDet" id="studentDet">
          <div class="container-fluid my-4">
            <div class="card">
              <div class="card-header" style="background-color:rgb(232, 234, 236); display:flex; justify-content:space-between;">
                <h3 class="card-title">View Students</h3>
                <div class="card-tools" style="height: 1em;">
                  <input type="text" class="form-control" id="searchBar"
                    placeholder="Search here..." style="height: 2.5em;" onkeyup="filterTable()">
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                  <table class="table table-striped table-bordered" id="busTable">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Card Number</th>
                        <th>Type</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                          echo "<tr>
                                        <td>" . htmlspecialchars($row['name']) . "</td>
                                        <td>" . htmlspecialchars($row['mail']) . "</td>
                                        <td>" . htmlspecialchars($row['cardNumber']) . "</td>
                                        <td>" . htmlspecialchars($row['type']) . "</td>
                                        <td>" . htmlspecialchars($row['status'] == 1 ? 'paid' : 'not paid') . "</td>
                                      </tr>";
                        }
                      } else {
                        echo "<tr><td colspan='6' class='text-center'>No students found for this bus.</td></tr>";
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <script>
          function filterTable() {
            const input = document.getElementById('searchBar').value.toLowerCase();
            const table = document.getElementById('busTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
              const cells = rows[i].getElementsByTagName('td');
              let match = false;

              for (let j = 0; j < cells.length; j++) {
                if (cells[j].innerText.toLowerCase().indexOf(input) > -1) {
                  match = true;
                  break;
                }
              }
              rows[i].style.display = match ? '' : 'none';
            }
          }
        </script>






        <!-- </div> -->


        <!-- Student Details  -->
        <form action="" method="POST">
          <div class="feedback" id="feedback">
            <textarea class="staffFeedback" id="staffFeedback" name="staffFeedback" placeholder="Write your feedbacks here..." required></textarea>

            <input type="submit" name="submitFeedback" id="submitFeedback">

            <span class="feedbackSubmitMessage" id="feedbackSubmitMessage"> Thank you for sharing your feedback :) </span>
          </div>
        </form>



        <div class="locationPane" id="locationPane">
          <div class="container" style="text-align: center; padding: 2rem; margin-top: 5em">
            <h1>Driver Location Sharing</h1>
            <button id="shareButton" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; font-size: 16px; cursor: pointer; border-radius: 4px;">Start Sharing Location</button>
            <div id="status" style="margin-top: 1rem;">Location sharing is off</div>
          </div>

          <script>
            let isSharing = false;
            let watchId = null;
            const shareButton = document.getElementById('shareButton');
            const statusDiv = document.getElementById('status');
            const updateInterval = 60 * 1000; // 1 minute
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

            function sendLocationToServer(latitude, longitude, istTimestamp) {
              console.log("Sending data:", latitude, longitude, istTimestamp); // Debugging log
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
              xhr.send(`latitude=${latitude}&longitude=${longitude}&timestamp=${istTimestamp}`);
            }
          </script>
        </div>



        <form action="" method="POST">
          <div class="feedback" id="feedback">
            <textarea class="staffFeedback" id="staffFeedback" name="staffFeedback" placeholder="Write your feedbacks here..." required></textarea>

            <input type="submit" name="submitFeedback" id="submitFeedback">

            <span class="feedbackSubmitMessage" id="feedbackSubmitMessage"> Thank you for sharing your feedback :) </span>
          </div>
        </form>

        <form action="" method="POST">
          <div class="editProfile" id="editProfile">
            <!-- <label class="editProfileText"> Current Name: <span> <?php echo "$staffName"; ?> </span> </label> -->
            <input type="text" name="staffNewName" id="staffNewName" placeholder=<?php echo "$staffName"; ?> maxlength="20">
            <br>

            <!-- <label class="editProfileText"> Current ID: <span> <?php echo "$staffId"; ?> </span> </label> -->
            <input type="text" name="staffNewId" id="staffNewId" placeholder=<?php echo "$staffId"; ?> maxlength="20">
            <br>

            <!-- <label class="editProfileText"> Current Password: <span> <?php echo "$staffPassword"; ?> </span> </label> -->
            <input type="text" name="staffNewPassword" id="staffNewPassword" placeholder=<?php echo "$staffPassword"; ?>
              maxlength="20">

            <input type="submit" name="submitStaffNewDetails" id="submitStaffNewDetails">

            <span class="submitMessageOne" id="submitMessageOne"> Note: You will need to sign back in after making the changes </span>
            <p class="submitMessageTwo" id="submitMessageTwo"> Profile Updated...redirecting in <span class="submitMessageTwoSpan" id="submitMessageTwoSpan"> 3 </span> s </p>
            <span class="submitMessageThree" id="submitMessageThree"> Error Updating Profile </span>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php

  //feedback submit
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['submitFeedback'])) {
      if (!empty(trim(($_POST['staffFeedback'])))) {
        $staffFeedback = $_POST['staffFeedback'];
        $currentDateAndTime = date("Y-m-d H:i:s");
        $sql = "INSERT INTO inboxtable (fromMail, toMail, content, dateAndTime) VALUES ('$staffMail', '$adminMailId', '$staffFeedback', '$currentDateAndTime');";
        $result = mysqli_query($connection, $sql);


        if ($result) {
          echo "<script>     
              const message = document.getElementById('feedbackSubmitMessage');     
              message.style.display = 'block'; 
              const busCardPane = document.getElementById('loader');
             const studentDet = document.getElementById('studentDet');
                  const locationPane = document.getElementById('locationPane');
                  const feedbackPane = document.getElementById('feedback');
                  const editProfilePane = document.getElementById('editProfile');
                  const inbox = document.getElementById('inbox');
                  inbox.style.display = 'none';
                  busCardPane.style.display = 'none';
                  studentDet.style.display = 'none';
                  locationPane.style.display = 'none';
                  feedbackPane.style.display = 'flex';
                  editProfilePane.style.display = 'none';
              </script>";
        }
      }
    }

    // If edit profile submit button was clicked
    if (isset($_POST['submitStaffNewDetails'])) {
      if (!empty(trim(($_POST['staffNewName'])))) {
        $staffNewName = $_POST['staffNewName'];
      } else {
        $staffNewName = $staffName;
      }

      if (!empty(trim(($_POST['staffNewId'])))) {
        $staffNewId = $_POST['staffNewId'];
      } else {
        $staffNewId = $staffId;
      }

      if (!empty(trim(($_POST['staffNewPassword'])))) {
        $staffNewPassword = $_POST['staffNewPassword'];
      } else {
        $staffNewPassword = $staffPassword;
      }

      $sql = "UPDATE staffDetails SET NAME = '$staffNewName', ID = '$staffNewId', PASSWORD = '$staffNewPassword' WHERE MAIL = '$staffMail'";
      $result = mysqli_query($connection, $sql);
      if ($result) {
        echo "<script> 
                                const messageOne = document.getElementById('submitMessageOne');                    
                                const message = document.getElementById('submitMessageTwo');
                                const span = document.getElementById('submitMessageTwoSpan');                                
                                
                                messageOne.style.display = 'none';  

                                // Ensure the elements are shown
                                message.style.display = 'block'; // Show the message
                                span.style.display = 'inline'; // Show the countdown span
                
                                // Start countdown
                                let countdownValue = 3;
                                const countdownInterval = setInterval(() => {
                                    span.textContent = countdownValue; // Update countdown value
                                    countdownValue--;
                
                                    if (countdownValue < 0) {
                                        clearInterval(countdownInterval);
                                        window.location.href = 'http://localhost/Project/staff/logout.php';
                                    }
                                }, 1000);
                            </script>";
      } else {
        echo "<script>     
            const messageOne = document.getElementById('submitMessageOne');     
            const messageThree = document.getElementById('submitMessageThree');
            messageOne.style.display = 'none';  
            messageThree.style.display = 'block'; 
            </script>";
      }
    }


    // Close the connection
    mysqli_close($connection);
  }

  ?>





  </div>
  </div>
  <script src="staffPage1.js"></script>
</body>

</html>