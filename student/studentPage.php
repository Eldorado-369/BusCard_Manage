<?php
session_start();
error_reporting(E_ALL);
$connection = mysqli_connect("localhost", "root", "", "busCardManagementSystem");
if ($connection->connect_error) {
  die("<p> <br> Connection Failed: " . $connection->connect_error . "</p> <br>");
}
// Check if the staff is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location:http://localhost/Project/loginPage/loginPage.php");
  exit;
}
// Get the logged-in student ID from the session
$student_id = $_SESSION['student_id'];

$studentType = $_SESSION['student_type'];

if ($studentType == 'new') {
  echo "<script>
            document.addEventListener('DOMContentLoaded', () => {
                const dashboardTextAndIcons = document.getElementById('dashboardTextAndIcons');
                dashboardTextAndIcons.style.display = 'none'; 
                // dashboardTextAndIcons.remove();
                
                const secondDivision = document.getElementById('secondDivision');
                secondDivision.style.display = 'none'; 

                const busBodyContainer = document.getElementById('busBodyContainer');
                busBodyContainer.style.display = 'none';
                
                const applicationForm = document.getElementById('applicationForm');
                applicationForm.style.display = 'none';

                const item1 = document.getElementById('item1');
                item1.style.display = 'none';

                const item3 = document.getElementById('item3');
                item3.style.display = 'none';

                const item4 = document.getElementById('item4');
                item4.style.display = 'none';

                const item5 = document.getElementById('item5');
                item5.style.display = 'none';

                const item6 = document.getElementById('item6');
                item6.style.display = 'none';

                const newStudentUnderReviewText = document.getElementById('newStudentUnderReviewText');
                newStudentUnderReviewText.style.display = 'none';

                const newStudentUnderReviewRejectedText = document.getElementById('newStudentUnderReviewRejectedText');
                newStudentUnderReviewRejectedText.style.display = 'none';               
            });
        </script>";

  // Fetch student details using the student ID
  $sql = "SELECT * FROM newStudent WHERE studentId='$student_id'";
  $result = mysqli_query($connection, $sql);

  // Fetch the result as an associative array
  $student = mysqli_fetch_assoc($result);

  $studentMail = $student['mail'];
  $newStudentName = $student['name'];
}

if ($studentType == 'underReview') {
  echo "<script>
  document.addEventListener('DOMContentLoaded', () => {
             const dashboardTextAndIcons = document.getElementById('dashboardTextAndIcons');
             dashboardTextAndIcons.style.display = 'none';

               const secondDivision = document.getElementById('secondDivision');
                secondDivision.style.display = 'none'; 

                const busBodyContainer = document.getElementById('busBodyContainer');
                busBodyContainer.style.display = 'none';
                student/studentPage.php
                const applicationForm = document.getElementById('applicationForm');
                applicationForm.style.display = 'none';

                const sideNavigationBar = document.getElementById('sideNavigationBar');
                sideNavigationBar.style.display = 'none';
                sideNavigationBar.style.width = '0em';

                const applyCardMessage = document.getElementById('newStudentMessageText');
                applyCardMessage.style.display = 'none';

                 const rightPane = document.getElementById('rightPane');
                rightPane.style.width = '91em';
                rightPane.style.marginLeft = '2em';  
                });
        </script>";

  $sql = "SELECT * FROM newApplications WHERE studentId='$student_id'";
  // $sql = "SELECT studentcard.card_type, studentcard.busNumber, studentidandpassword.name, studentidandpassword.mail 
  //       FROM studentcard 
  //       INNER JOIN studentidandpassword ON studentcard.studentId = studentidandpassword.id 
  //       WHERE studentcard.studentId = '$student_id'";

  $result = mysqli_query($connection, $sql);
  $row = mysqli_fetch_assoc($result);
  $cardType = $row['cardType'];
  $route = $row['route'];
  $studentMail = $row['mailId'];
  $newStudentName = $row['name'];

  $sql7 = "SELECT * FROM buscardcount where busNumber = $route";
  $result7 = mysqli_query($connection, $sql7);
  $row7 = mysqli_fetch_assoc($result7);
  $increasePink = $row7['pinkCardCount'];
  $increaseYellow = $row7['yellowCardCount'];

  if ($cardType == 'Yellow') {
    $sql1 = "SELECT yellowCardCount FROM buscardcount WHERE busNumber='$route'";
    $result1 = mysqli_query($connection, $sql1);
    $row1 = mysqli_fetch_assoc($result1);
    $yellowCardCount = $row1['yellowCardCount'];

    if ($yellowCardCount >= 30) {
      echo "<script>
                document.addEventListener('DOMContentLoaded', () => {
                    const newStudentUnderReviewText = document.getElementById('newStudentUnderReviewText');
                    newStudentUnderReviewText.style.display = 'none';

                    const newStudentUnderReviewRejectedText = document.getElementById('newStudentUnderReviewRejectedText');
                    newStudentUnderReviewRejectedText.style.display = 'inline';                          
                });
            </script>";

      $sql2 = "INSERT INTO newStudent (studentId, mail, name) VALUES ('$student_id', '$studentMail', '$newStudentName')";
      $result2 = mysqli_query($connection, $sql2);

      $sql3 = "DELETE FROM newApplications WHERE studentId='$student_id'";
      $result3 = mysqli_query($connection, $sql3);

      $jpegImagePath = "studentImagesUnderReview/$studentMail.jpeg";
      $jpgImagePath = "studentImagesUnderReview/$studentMail.jpg";
      $pngImagePath = "studentImagesUnderReview/$studentMail.png";

      $imagePath = "";

      // Check if the .jpeg file exists first
      if (file_exists($jpegImagePath)) {
        $imagePath = $jpegImagePath;
      } else if (file_exists($jpgImagePath)) {
        $imagePath = $jpgImagePath;
      } else {
        $imagePath = $pngImagePath; // Replace with your placeholder image path
      }

      if (file_exists($imagePath)) {
        unlink($imagePath);
      }
    } else {
      do {
        // Generate a random 8-digit number for the card
        $ypCardNumber = random_int(10000000, 99999999);

        // Check if the generated card number already exists in the yellowstudent table
        $sql17 = "SELECT cardNumber FROM studentcard WHERE cardNumber = '$ypCardNumber'";
        $result17 = mysqli_query($connection, $sql17);
        $row17 = mysqli_fetch_assoc($result17);
      } while ($row17 != null); // Repeat if the card number already exists

      $sql3 = "INSERT INTO studentcard (studentId, card_type, stop, busNumber, cardNumber, status ) VALUES ('$student_id','yellow', NULL, '$route', '$ypCardNumber', 'not paid')";
      $result3 = mysqli_query($connection, $sql3);

      $increaseYellow++;
      // $sql8 = "UPDATE buscardcount SET yellowCardCount = '$increaseYellow' WHERE busNumber='$route'";
      // $result8 = mysqli_query($connection, $sql8);

      $sql9 = "INSERT INTO inboxtable (fromMail, toMail, content) VALUES ('mani@gmail.com', '$studentMail', 'Welcome to the bus card management system')";
      $result9 = mysqli_query($connection, $sql9);

      $sql5 = "DELETE FROM newApplications WHERE studentId='$student_id'";
      $result5 = mysqli_query($connection, $sql5);

      if ($result3) {
        $jpegImagePath = "studentImagesUnderReview/$studentMail.jpeg";
        $jpgImagePath = "studentImagesUnderReview/$studentMail.jpg";
        $pngImagePath = "studentImagesUnderReview/$studentMail.png";

        if (file_exists($jpegImagePath)) {
          $source = $jpegImagePath;
          $destination = "yellowStudentImages/$studentMail.jpeg";
        } else if (file_exists($jpgImagePath)) {
          $source = $jpgImagePath;
          $destination = "yellowStudentImages/$studentMail.jpg";
        } else {
          $source = $pngImagePath;
          $destination = "yellowStudentImages/$studentMail.png";
        }

        // Check if source file exists and move the file
        if (file_exists($source)) {
          rename($source, $destination);
        }
      }
      header("Location: http://localhost/Project/loginPage/loginPage.php");
      exit;
    }
  } else {
    do {
      // Generate a random 8-digit number for the card
      $ypCardNumber = random_int(10000000, 99999999);

      // Check if the generated card number already exists in the yellowstudent table
      $sql17 = "SELECT cardNumber FROM studentcard WHERE cardNumber = '$ypCardNumber'";
      $result17 = mysqli_query($connection, $sql17);
      $row17 = mysqli_fetch_assoc($result17);
    } while ($row17 != null); // Repeat if the card number already exists

    $sql3 = "INSERT INTO studentcard (studentId, card_type, stop, busNumber, cardNumber, status) VALUES ('$student_id', 'pink', NULL, '$route', '$ypCardNumber', 'not paid')";
    $result3 = mysqli_query($connection, $sql3);

    $increasePink++;
    // $sql8 = "UPDATE buscardcount SET pinkCardCount = '$increasePink' WHERE busNumber='$route'";
    // $result8 = mysqli_query($connection, $sql8);

    $sql9 = "INSERT INTO inboxtable (fromMail, toMail, content) VALUES ('mani@gmail.com', '$studentMail', 'Welcome to the bus card management system')";
    $result9 = mysqli_query($connection, $sql9);

    $sql5 = "DELETE FROM newApplications WHERE studentId='$student_id'";
    $result5 = mysqli_query($connection, $sql5);

    if ($result3) {
      $jpegImagePath = "studentImagesUnderReview/$studentMail.jpeg";
      $jpgImagePath = "studentImagesUnderReview/$studentMail.jpg";
      $pngImagePath = "studentImagesUnderReview/$studentMail.png";

      if (file_exists($jpegImagePath)) {
        $source = $jpegImagePath;
        $destination = "pinkStudentImages/$studentMail.jpeg";
      } else if (file_exists($jpgImagePath)) {
        $source = $jpgImagePath;
        $destination = "pinkStudentImages/$studentMail.jpg";
      } else {
        $source = $pngImagePath;
        $destination = "pinkStudentImages/$studentMail.png";
      }

      // Check if source file exists and move the file
      if (file_exists($source)) {
        rename($source, $destination);
      }
    }
    header("Location:http://localhost/Project/loginPage/loginPage.php");
    exit;
  }
}

if ($studentType == 'yellow') {
  // Fetch student details using the student ID
  // $sql = "SELECT * FROM yellowStudent WHERE studentId='$student_id'";
  $sql = "SELECT * FROM studentcard WHERE studentId='$student_id'";
  $result = mysqli_query($connection, $sql);

  // Fetch the result as an associative array
  $studentStatus = mysqli_fetch_assoc($result);

  $studentCardStatus = $studentStatus['status'];
  $cardStudentBusStop = $studentStatus['stop'];
  $cardStudentCardNumber = $studentStatus['cardNumber'];
  $cardStudentBusNumber = $studentStatus['busNumber'];

  // Fetch student name using the student ID
  $sql = "SELECT * FROM studentidandpassword WHERE id='$student_id'";
  $result = mysqli_query($connection, $sql);

  // Fetch the result as an associative array
  $cardStudent = mysqli_fetch_assoc($result);

  $cardStudentPassword = $cardStudent['password'];
  $cardStudentName = $cardStudent['name'];
  $cardStudentMail = $cardStudent['mail'];
  $feedbackStudentMail = $cardStudentMail;

  $cardStudentMailSplitted = strtoupper(explode('@', $cardStudentMail)[0]);

  // fetch count of inbox
  $sql = "SELECT COUNT(*) AS inboxCount FROM inboxTable WHERE toMail='$cardStudentMail'";
  $result = mysqli_query($connection, $sql);

  // Fetch the result as an associative array
  $staff = mysqli_fetch_assoc($result);

  $inboxCount = $staff['inboxCount'];

  $jpegImagePath = "$cardStudentMail.jpeg";
  $jpgImagePath = "$cardStudentMail.jpg";
  $pngImagePath = "$cardStudentMail.png";

  $imageSrc = "";

  if (file_exists($jpegImagePath)) {
    $imageSrc = "$jpegImagePath";
  } else if (file_exists($jpgImagePath)) {
    $imageSrc = "$jpgImagePath";
  } else {
    $imageSrc = "$pngImagePath";
  }

  echo "<script>
  document.addEventListener('DOMContentLoaded', () => {
      
      const item2 = document.getElementById('item2');
      item2.style.display = 'none'; 
                 
       const welcomeMessage = document.getElementById('welcomeMessage');
      welcomeMessage.style.display = 'none';

       const newStudentMessage = document.getElementById('newStudentMessage');
      newStudentMessage.style.display = 'none';

      const profile2 = document.getElementById('profile2');
      profile2.style.display = 'none';

      const driverWindow2 = document.getElementById('driverWindow2');
      driverWindow2.style.display = 'none';

      const makePinkPaymentForm = document.getElementById('makePinkPaymentForm');
      makePinkPaymentForm.style.display = 'none';

      if (inboxCount == 0) {
    const noNotification = document.getElementById('noNotifications');
    noNotification.style.display = 'inline';
    const envelopeNumber = document.getElementById('envelopeNumber');
    envelopeNumber.style.display = 'none';
  }
  });
</script>";

  if ($studentCardStatus == 'paid') {
    echo "<script>
            document.addEventListener('DOMContentLoaded', () => {
                const item4BottomOne = document.getElementById('item4BottomOne');
                item4BottomOne.style.display = 'none';                           
            });
        </script>";

    // $sql = "SELECT * FROM paymentHistoryTable WHERE ID='$student_id'";
    // $result = mysqli_query($connection, $sql);

    // // Fetch the result as an associative array
    // $cardStudent = mysqli_fetch_assoc($result);

    // $cardStudentTime = $cardStudent['TIME'];
    // $cardStudentAmount = $cardStudent['Amount'];
    // $cardStudentStop = $cardStudent['Stop'];
    $sql = "SELECT * FROM paymentHistoryTable WHERE ID='$student_id'";
    $result = mysqli_query($connection, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
      // Fetch the result as an associative array
      $cardStudent = mysqli_fetch_assoc($result);

      $cardStudentTime = $cardStudent['TIME'];
      $cardStudentAmount = $cardStudent['Amount'];
      $cardStudentStop = $cardStudent['Stop'];
    } else {
      // Handle the case where no records are found
      // echo "No payment history found for this student.";
      $cardStudentTime = 'N/A';
      $cardStudentAmount = 'N/A';
      $cardStudentStop = 'N/A';
    }
  }

  if ($studentCardStatus == 'not paid') {
    echo "<script>
            document.addEventListener('DOMContentLoaded', () => {                               
                const item4BottomTwo = document.getElementById('item4BottomTwo');
                item4BottomTwo.style.display = 'none';             
            });
        </script>";
  }
}

// Assuming $student_id and $studentType are already set
if ($studentType == 'pink') {
  //item4BottomOne
  // Fetch student details using the student ID
  // $sql = "SELECT * FROM pinkStudent WHERE studentId='$student_id'";
  $sql = "SELECT * FROM studentcard WHERE studentId='$student_id'";
  $result = mysqli_query($connection, $sql);

  if ($result && mysqli_num_rows($result) > 0) {
    $studentStatus = mysqli_fetch_assoc($result);
    $cardStudentCardNumber = $studentStatus['cardNumber'];
    $cardStudentBusNumber = $studentStatus['busNumber'];
  } else {
    // Handle case where no matching student card is found
    $cardStudentCardNumber = null;
    $cardStudentBusNumber = null;
  }

  $sql78 = "SELECT Stop FROM paymenthistorytable WHERE ID='$student_id' ORDER BY TIME DESC LIMIT 1";
  $result78 = mysqli_query($connection, $sql78);

  if ($result78 && mysqli_num_rows($result78) > 0) {
    $studentStatus78 = mysqli_fetch_assoc($result78);
    $cardStudentBusStop = $studentStatus78['Stop'];
  } else {
    $cardStudentBusStop = null;
  }

  $sql68 = "SELECT COUNT(*) AS dailyCount FROM paymenthistorytable WHERE busNumber='$cardStudentBusNumber' AND DATE(DATE) = CURDATE()";
  $result68 = mysqli_query($connection, $sql68);

  if ($result68) {
    $row = mysqli_fetch_assoc($result68);
    if ($row['dailyCount'] >= 15) {
      // Bus card has exceeded the 15 daily card generation limit
      echo "<script>
            document.addEventListener('DOMContentLoaded', () => {
                const item4BottomOne = document.getElementById('item4BottomOne');
                item4BottomOne.style.display = 'none';
            });
        </script>";
    }
  }



  // Fetch student name using the student ID
  // $sql = "SELECT * FROM studentIdAndPassword WHERE ID='$student_id'";
  $sql = "SELECT * FROM studentidandpassword WHERE id='$student_id'";
  $result = mysqli_query($connection, $sql);

  // Fetch the result as an associative array
  $cardStudent = mysqli_fetch_assoc($result);

  $cardStudentPassword = $cardStudent['password'];
  $cardStudentName = $cardStudent['name'];
  $cardStudentMail = $cardStudent['mail'];
  $feedbackStudentMail = $cardStudentMail;

  $cardStudentMailSplitted = strtoupper(explode('@', $cardStudentMail)[0]);

  $jpegImagePath = "$cardStudentMail.jpeg";
  $jpgImagePath = "$cardStudentMail.jpg";
  $pngImagePath = "$cardStudentMail.png";

  $imageSrc = "";

  if (file_exists($jpegImagePath)) {
    $imageSrc = "$jpegImagePath";
  } else if (file_exists($jpgImagePath)) {
    $imageSrc = "$jpgImagePath";
  } else {
    $imageSrc = "$pngImagePath";
  }

  // fetch count of inbox
  $sql = "SELECT COUNT(*) AS inboxCount FROM inboxTable WHERE toMail='$cardStudentMail'";
  $result = mysqli_query($connection, $sql);

  // Fetch the result as an associative array
  $staff = mysqli_fetch_assoc($result);

  $inboxCount = $staff['inboxCount'];

  // fetch sum of payment
  $sql = "SELECT SUM(Amount) AS amountSum FROM paymenthistorytable WHERE ID='$student_id'";
  $result = mysqli_query($connection, $sql);

  // Fetch the result as an associative array
  $staff = mysqli_fetch_assoc($result);

  $amountSum = $staff['amountSum'];

  $currentDate = date("Y-m-d");

  $sql103 = "SELECT DATE FROM paymenthistorytable WHERE ID='$student_id' AND DATE='$currentDate'";
  $result103 = mysqli_query($connection, $sql103);

  if (mysqli_num_rows($result103) > 0) {
    // Student has paid today
    echo "<script>
document.addEventListener('DOMContentLoaded', () => {      
const item4BottomOne = document.getElementById('item4BottomOne');
    item4BottomOne.style.display = 'none';
  });
</script>";
  }


  if ($amountSum <= 0) {
    echo "<script>
    document.addEventListener('DOMContentLoaded', () => {
        
  const item4BottomTwo = document.getElementById('item4BottomTwo');
        item4BottomTwo.style.display = 'none'; 

      });
  </script>";
  }

  if ($amountSum > 0) {
    // exceed 7 trips check
    $sql = "SELECT ID, 
               COUNT(*) AS paymentCount, 
               MONTH(DATE) AS paymentMonth,
               YEAR(DATE) AS paymentYear
        FROM paymenthistorytable
        WHERE ID = '$student_id'
          AND time != '0000-00-00 00:00:00'
          AND YEAR(DATE) = YEAR(CURDATE()) 
          AND MONTH(DATE) = MONTH(CURDATE())
        GROUP BY ID, YEAR(DATE), MONTH(DATE)
        HAVING paymentCount >= 7;";

    $result = mysqli_query($connection, $sql);

    // Check if any rows are returned
    if (mysqli_num_rows($result) > 0) {
      // Student has exceeded the 7 payment limit
      echo "<script>
  document.addEventListener('DOMContentLoaded', () => {      
const item4BottomOne = document.getElementById('item4BottomOne');
      item4BottomOne.style.display = 'none';
    });
</script>";
    }
  }

  echo "<script>
  document.addEventListener('DOMContentLoaded', () => {
      
const item2 = document.getElementById('item2');
      item2.style.display = 'none'; 

       busBodyContainer.classList.remove('busBodyContainer-yellow-hover-effect');
        busBodyContainer.classList.add('busBodyContainer-pink-hover-effect');
                 
const profile1 = document.getElementById('profile1');
      profile1.style.display = 'none';

      const driverWindow1 = document.getElementById('driverWindow1');
      driverWindow1.style.display = 'none';

       const welcomeMessage = document.getElementById('welcomeMessage');
      welcomeMessage.style.display = 'none';

       const newStudentMessage = document.getElementById('newStudentMessage');
      newStudentMessage.style.display = 'none';

      const makeYellowPaymentForm = document.getElementById('makeYellowPaymentForm');
      makeYellowPaymentForm.style.display = 'none';

      if (inboxCount == 0) {
    const noNotification = document.getElementById('noNotifications');
    noNotification.style.display = 'inline';
    const envelopeNumber = document.getElementById('envelopeNumber');
    envelopeNumber.style.display = 'none';
  }
  });
</script>";
}


$sql = "SELECT mailId FROM adminidandpassword;";
$result = mysqli_query($connection, $sql);
if ($result) {
  $row = mysqli_fetch_assoc($result); // Fetch the single row
  $adminMailId = $row['mailId'];
} else {
  echo "Error: " . mysqli_error($connection);
}

?>

<!DOCTYPE html>
<html>

<head>
  <title> STUDENT </title>
  <link rel="stylesheet" href="studentPage.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <div class="allContainer">
    <div id="sideNavigationBar" class="sideNavigationBar">
      <div class="firstDivision">
        <div class="toggleIcon">
          <i id="toggleButton" class="fa-solid fa-minimize hover-effect"></i>
        </div>
      </div>
      <div class="welcomeMessage routeBoxText hover-effect" id="welcomeMessage"> Welcome to Bus Card Management System </div>
      <div class="secondDivision" id="secondDivision">
        <div class="profile1" id="profile1">
          <img id="studentProfile" src="http://localhost/Project/student/yellowStudentImages/<?php echo $imageSrc; ?>">
        </div>
        <div class="profile2" id="profile2">
          <img id="studentProfile" src="http://localhost/Project/student/pinkStudentImages/<?php echo $imageSrc; ?>">
        </div>
        <div class="adminNameAndMail">
          <div class="adminName">
            <p class="hover-effect hiText" style="font-weight: 500;"> Hi, </p>
            <p class="hover-effect adminNameText" style="font-weight: 900;"> <?php echo $cardStudentName; ?> </p>
          </div>
          <div class="adminMail">
            <p class="font-color hover-effect adminMailText" style="font-size: 0.6em;"> <?php echo $cardStudentMail; ?> </p>
          </div>
        </div>
      </div>
      <div class="sideNavigationBarList" id="sideNavigationBarList">
        <ul>
          <li>
            <a class="sideNavbarContents hover-effect"
              href="">
              <div class="item1" id="item1">
                <div class="item1Top">
                  <div class="item1TopLeft">
                    <i class="fa-regular fa-address-card"></i>
                  </div>
                  <div class="item1TopRight">
                    <p> Bus Card </p>
                  </div>
                </div>
              </div>
            </a>
          </li>
          <li>
            <a onclick="showForm()">
              <div class="item2" id="item2">
                <div class="item2Top">
                  <div class="item2TopLeft">
                    <i class="fa-solid fa-bus"></i>
                  </div>
                  <div class="item2TopRight">
                    <p> Apply </p>
                  </div>
                </div>
              </div>
            </a>


            <script>
              function showForm() {
                const newStudentMessage = document.getElementById('newStudentMessage');
                newStudentMessage.style.display = 'none';
                const applicationForm = document.getElementById('applicationForm');
                applicationForm.style.display = 'flex';
              }
            </script>

          </li>


          <a href="http://localhost/Project/student/Displocation.php">
            <li>
              <div class="sideNavbarContents hover-effect">
                <!-- <div class="sideNavbarContents hover-effect" onclick="showLocationPane()"> -->
                <script>
                  function showLocationPane() {
                    // Hide all other panes and show the locationPane
                    const busCardPane = document.getElementById('loader');
                    const clockContainer = document.getElementById('clock-container');
                    const busBodyContainer = document.getElementById('busBodyContainer');
                    const locationPane = document.getElementById('locationPane');
                    const feedbackPane = document.getElementById('feedback');
                    const editProfilePane = document.getElementById('editProfile');
                    const inbox = document.getElementById('inbox');
                    const paymentHistory = document.getElementById('paymentHistory');
                    const makeYellowPayment = document.getElementById('makeYellowPayment');
                    const makePinkPayment = document.getElementById('makePinkPayment');

                    makePinkPayment.style.display = 'none';
                    makeYellowPayment.style.display = 'none';
                    inbox.style.display = 'none';
                    busCardPane.style.display = 'none';
                    locationPane.style.display = 'block'; // Show location pane
                    feedbackPane.style.display = 'none';
                    editProfilePane.style.display = 'none';
                    busBodyContainer.classList.remove('busBodyContainer-yellow-hover-effect');
                    busBodyContainer.classList.remove('busBodyContainer-pink-hover-effect');
                    clockContainer.style.display = 'none';
                    paymentHistory.style.display = 'none';

                    // Trigger map redraw
                    // if (map) {
                    //   google.maps.event.trigger(map, "resize");
                    //   map.setCenter(marker.getPosition()); 
                    // }
                  }



                  function fetchBusLocation() {
                    fetch('Displocation.php') // Call your PHP backend for location data
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
                      });
                  }

                  function updateBusLocation(location) {
                    const busLocation = {
                      lat: parseFloat(location.latitude),
                      lng: parseFloat(location.longitude)
                    };

                    // Update Google Maps marker and status
                    map.setCenter(busLocation);
                    marker.setPosition(busLocation);

                    const localTime = new Date(location.timestamp).toLocaleString('en-IN', {
                      timeZone: 'Asia/Kolkata'
                    });
                    document.getElementById('status').textContent = `Bus location updated at ${localTime}`;
                  }
                </script>

                <div class="item3" id="item3">
                  <div class="item3Top">
                    <div class="item3TopLeft">
                      <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div class="item3TopRight">
                      <p> Location </p>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </a>

          <li>
            <div class="item4" id="item4">
              <div class="item4Top">
                <div class="item4TopLeft">
                  <i class="fa-regular fa-credit-card"></i>
                </div>
                <div class="item4TopRight">
                  <p> Payment </p>
                </div>
              </div>
              <div class="item4Bottom">
                <a class="sideNavbarContents hover-effect"
                  onclick="showMakePaymentPane()">

                  <script>
                    function showMakePaymentPane() {
                      const busCardPane = document.getElementById('loader');
                      const clockContainer = document.getElementById('clock-container');
                      const busBodyContainer = document.getElementById('busBodyContainer');
                      const locationPane = document.getElementById('locationPane');
                      const feedbackPane = document.getElementById('feedback');
                      const editProfilePane = document.getElementById('editProfile');
                      const inbox = document.getElementById('inbox');
                      const paymentHistory = document.getElementById('paymentHistory');
                      const makeYellowPayment = document.getElementById('makeYellowPayment');
                      const makePinkPayment = document.getElementById('makePinkPayment');
                      makePinkPayment.style.display = 'flex';
                      makeYellowPayment.style.display = 'flex';
                      inbox.style.display = 'none';
                      busCardPane.style.display = 'none';
                      locationPane.style.display = 'none';
                      feedbackPane.style.display = 'none';
                      editProfilePane.style.display = 'none';
                      busBodyContainer.classList.remove('busBodyContainer-yellow-hover-effect');
                      busBodyContainer.classList.remove('busBodyContainer-pink-hover-effect');
                      clockContainer.style.display = 'none';
                      paymentHistory.style.display = 'none';
                    }
                  </script>
                  <div class="item4BottomOne" id="item4BottomOne">
                    <div class="item4BottomLeftOne">
                      <i class="fa-solid fa-money-check-dollar"></i>
                    </div>
                    <div class="item4BottomRightOne">
                      Make Payment
                    </div>
                  </div>
                </a>
                <a class="sideNavbarContents hover-effect"
                  onclick="showPaymentHistoryPane()">

                  <script>
                    function showPaymentHistoryPane() {
                      const busCardPane = document.getElementById('loader');
                      const clockContainer = document.getElementById('clock-container');
                      const busBodyContainer = document.getElementById('busBodyContainer');
                      const locationPane = document.getElementById('locationPane');
                      const feedbackPane = document.getElementById('feedback');
                      const editProfilePane = document.getElementById('editProfile');
                      const paymentHistory = document.getElementById('paymentHistory');
                      const inbox = document.getElementById('inbox');
                      const makeYellowPayment = document.getElementById('makeYellowPayment');
                      const makePinkPayment = document.getElementById('makePinkPayment');
                      makePinkPayment.style.display = 'none';
                      makeYellowPayment.style.display = 'none';
                      busBodyContainer.classList.remove('busBodyContainer-yellow-hover-effect');
                      busBodyContainer.classList.remove('busBodyContainer-pink-hover-effect');
                      inbox.style.display = 'none';
                      clockContainer.style.display = 'inline';
                      busCardPane.style.display = 'none';
                      locationPane.style.display = 'none';
                      feedbackPane.style.display = 'none';
                      editProfilePane.style.display = 'none';
                      paymentHistory.style.display = 'flex';
                    }
                  </script>
                  <div class="item4BottomTwo" id="item4BottomTwo">
                    <div class="item4BottomLeftTwo">
                      <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div class="item4BottomRightTwo">
                      Payment History
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </li>
          <li>
            <a class="sideNavbarContents hover-effect"
              onclick="showFeedbackPane()">

              <script>
                function showFeedbackPane() {
                  const busCardPane = document.getElementById('loader');
                  const clockContainer = document.getElementById('clock-container');
                  const busBodyContainer = document.getElementById('busBodyContainer');
                  const locationPane = document.getElementById('locationPane');
                  const feedbackPane = document.getElementById('feedback');
                  const editProfilePane = document.getElementById('editProfile');
                  const paymentHistory = document.getElementById('paymentHistory');
                  const inbox = document.getElementById('inbox');
                  const makeYellowPayment = document.getElementById('makeYellowPayment');
                  const makePinkPayment = document.getElementById('makePinkPayment');
                  makePinkPayment.style.display = 'none';
                  makeYellowPayment.style.display = 'none';
                  busBodyContainer.classList.remove('busBodyContainer-yellow-hover-effect');
                  busBodyContainer.classList.remove('busBodyContainer-pink-hover-effect');
                  inbox.style.display = 'none';
                  clockContainer.style.display = 'none';
                  busCardPane.style.display = 'none';
                  locationPane.style.display = 'none';
                  feedbackPane.style.display = 'flex';
                  editProfilePane.style.display = 'none';
                  paymentHistory.style.display = 'none';
                }
              </script>
              <div class="item5" id="item5">
                <div class="item5Top">
                  <div class="item5TopLeft">
                    <i class="fa-solid fa-comment"></i>
                  </div>
                  <div class="item5TopRight">
                    <p> Feedback </p>
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
                  const clockContainer = document.getElementById('clock-container');
                  const busBodyContainer = document.getElementById('busBodyContainer');
                  const locationPane = document.getElementById('locationPane');
                  const feedbackPane = document.getElementById('feedback');
                  const editProfilePane = document.getElementById('editProfile');
                  const paymentHistory = document.getElementById('paymentHistory');
                  const inbox = document.getElementById('inbox');
                  const makeYellowPayment = document.getElementById('makeYellowPayment');
                  const makePinkPayment = document.getElementById('makePinkPayment');
                  makePinkPayment.style.display = 'none';
                  makeYellowPayment.style.display = 'none';
                  busBodyContainer.classList.remove('busBodyContainer-yellow-hover-effect');
                  busBodyContainer.classList.remove('busBodyContainer-pink-hover-effect');
                  inbox.style.display = 'none';
                  clockContainer.style.display = 'none';
                  busCardPane.style.display = 'none';
                  locationPane.style.display = 'none';
                  feedbackPane.style.display = 'none';
                  paymentHistory.style.display = 'none';
                  editProfilePane.style.display = 'flex';
                }
              </script>
              <div class="item6" id="item6">
                <div class="item5Top">
                  <div class="item5TopLeft">
                    <i class="fa-solid fa-user-pen"></i>
                  </div>
                  <div class="item5TopRight">
                    <p> Edit Profile </p>
                  </div>
                </div>
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="rightPane" id="rightPane">
      <div class="dashboardTextAndIcons" id="dashboardTextAndIcons">
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
      <div class="busBodyContainer busBodyContainer-yellow-hover-effect" id="busBodyContainer">
        <div class="loader" id="loader">
          <div class="busBody">
            <div class="upperBody">
              <div class="peopleWindows">
                <div class="window1">
                  <div class="showCardStudentNameBusStop">
                    <p id="showCardStudentName"> Name: <?php echo $cardStudentName; ?> </p>
                    <p id="showCardBusStop"> Stop: <?php echo $cardStudentBusStop; ?> </p>
                  </div>
                  <div class="showCardStudentNameCardNumberBusNumber">
                    <p id="showCardRollNumber"> ID: <?php echo $cardStudentMailSplitted; ?> </p>
                    <p id="showCardCardNumber"> Card Number: <?php echo $cardStudentCardNumber; ?> </p>
                    <p id="showCardBusNumber"> Bus Number: <?php echo $cardStudentBusNumber; ?> </p>
                  </div>
                </div>
              </div>
              <div class="driverWindow1" id="driverWindow1">
                <img id="studentProfile" src="http://localhost/Project/student/yellowStudentImages/<?php echo $imageSrc; ?>">
              </div>
              <div class="driverWindow2" id="driverWindow2">
                <img id="studentProfile" src="http://localhost/Project/student/pinkStudentImages/<?php echo $imageSrc; ?>">
              </div>
            </div>
            <div class="collegeName">
              RCMAS
            </div>
            <div class="lowerBody">
              <div class="backLight">
              </div>
              <div class="headlight">
              </div>
            </div>
            <div class="backTyre">
              <div class="innerBackTyre">
              </div>
            </div>
            <div class="frontTyre">
              <div class="innerFrontTyre">
              </div>
            </div>
          </div>
        </div>

        <div class="inbox" id="inbox">
          <div id="notificationsContainer">
            <span class="noNotifications" id="noNotifications"> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> No messages...come back later </span>
          </div>
        </div>
        <form id="moveNotificationForm" method="post" action="moveNotification.php" style="display: none;">
          <input type="hidden" name="inboxId" id="inboxId">
          <input type="hidden" name="cardStudentMail" value="<?php echo $cardStudentMail; ?>">
        </form>

        <script>
          let inboxShown = false;

          function showInbox() {
            const busCardPane = document.getElementById('loader');
            const clockContainer = document.getElementById('clock-container');
            const paymentHistory = document.getElementById('paymentHistory');
            const feedbackPane = document.getElementById('feedback');
            const editProfilePane = document.getElementById('editProfile');
            const inboxPane = document.getElementById('inbox');
            const busBodyContainer = document.getElementById('busBodyContainer');
            const locationPane = document.getElementById('locationPane');
            const makeYellowPayment = document.getElementById('makeYellowPayment');
            const makePinkPayment = document.getElementById('makePinkPayment');


            // Show or hide inbox
            if (inboxShown) {
              makePinkPayment.style.display = 'none';
              makeYellowPayment.style.display = 'none';
              busCardPane.style.display = 'none';
              clockContainer.style.display = 'none';
              paymentHistory.style.display = 'none';
              feedbackPane.style.display = 'none';
              editProfilePane.style.display = 'none';
              locationPane.style.display = 'none';
              inboxPane.style.display = 'flex';
              busBodyContainer.classList.remove('busBodyContainer-yellow-hover-effect');
              busBodyContainer.classList.remove('busBodyContainer-pink-hover-effect');
              return;
            }

            makePinkPayment.style.display = 'none';
            makeYellowPayment.style.display = 'none';
            busCardPane.style.display = 'none';
            clockContainer.style.display = 'none';
            paymentHistory.style.display = 'none';
            locationPane.style.display = 'none';
            busBodyContainer.classList.remove('busBodyContainer-yellow-hover-effect');
            busBodyContainer.classList.remove('busBodyContainer-pink-hover-effect');
            feedbackPane.style.display = 'none';
            editProfilePane.style.display = 'none';
            inboxPane.style.display = 'flex';

            // Define notifications array using PHP
            const notifications = [
              <?php
              $result = mysqli_query($connection, "SELECT inboxId, fromMail, content, dateAndTime FROM inboxTable WHERE toMail='$cardStudentMail'");
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






        <div class="clock-container" id="clock-container">
          <p id="date" class="routeBoxText">Loading date...</p>
          <p id="time" class="routeBoxText">Loading time...</p>
        </div>

        <form action="http://localhost/Project/student/makePayment.php" method="POST" id="makeYellowPaymentForm">
          <div class="makeYellowPayment" id="makeYellowPayment">
            <?php
            if ($cardStudentBusNumber == 1) {
              echo '
            <select name="yellowStop" id="yellowStop" required>
                <option value="" disabled selected>Select a Stop</option>
                <option id="thoppumpadi" value="1">Thoppumpadi (7.20 am)</option>
                <option id="thevara" value="2">Thevara</option>
                <option id="manoramaJunction" value="3">Manorama junction</option>
                <option id="kundanoor" value="4">Kundanoor (7.35am)</option>
                <option id="maradu" value="5">Maradu</option>
                <option id="petta" value="6">Petta (7.40 am)</option>
            </select>
            <input type="hidden" name="campusStopNumber" id="campusStopNumber" value="7">
        ';
            } elseif ($cardStudentBusNumber == 2) {
              echo '
            <select name="yellowStop" id="yellowStop" required>
                <option value="" disabled selected>Select a Stop</option>
                <option id="kadavanthara" value="1">Kadavanthara (7.20 am)</option>
                <option id="kumaranasanJunction" value="2">Kumaranasan junction</option>
                <option id="janatha" value="3">Janatha (7.25 am)</option>
                <option id="vyttila" value="4">Vyttila</option>
                <option id="vadakkekotta" value="5">Vadakkekotta (7.40 am)</option>
                <input type="hidden" name="campusStopNumber" id="campusStopNumber" value="6">
            </select>
        ';
            } elseif ($cardStudentBusNumber == 3) {
              echo '
            <select name="yellowStop" id="yellowStop" required>
                <option value="" disabled selected>Select a Stop</option>
                <option id="chottanikaraTemple" value="1">Chottanikara temple stop (7.20 am)</option>
                <option id="thiruvankulam" value="2">Thiruvankulam (7.30 am)</option>
                <option id="tripunitharaStand" value="3">Tripunithara stand (7.45 am)</option>
                <option id="karigachira" value="4">Karigachira (7.50 am )</option>
                <input type="hidden" name="campusStopNumber" id="campusStopNumber" value="5">
            </select>
        ';
            } elseif ($cardStudentBusNumber == 4) {
              echo '
            <select name="yellowStop" id="yellowStop" required>
                <option value="" disabled selected>Select a Stop</option>
                <option id="highcourt" value="1">Highcourt (7.20 am)</option>
                <option id="kacheripadi" value="2">Kacheripadi</option>
                <option id="kaloorStand" value="3">Kaloor stand (7.30 am)</option>
                <option id="kaloorStadium" value="4">Kaloor stadium</option>
                <option id="vazhakala" value="5">Vazhakala (7.40)</option>
                <option id="padamugal" value="6">Padamugal</option>
                <input type="hidden" name="campusStopNumber" id="campusStopNumber" value="7">
            </select>
        ';
            } elseif ($cardStudentBusNumber == 5) {
              echo '
            <select name="yellowStop" id="yellowStop" required>
                <option value="" disabled selected>Select a Stop</option>
                <option id="angamaly" value="1">Angamaly (7.00 am)</option>
                <option id="kariyad" value="2">Kariyad</option>
                <option id="athani" value="3">Athani (7.10 am)</option>
                <option id="dhesam" value="4">Dhesam</option>
                <option id="paravoorJunction" value="5">Paravoor junction</option>
                <option id="thottakattukara" value="6">Thottakattukara</option>
                <option id="aluva" value="7">Aluva (7.25am)</option>
                <option id="garage" value="8">Garage</option>
                <option id="ambattukavu" value="9">Ambattukavu</option>
                <option id="mutton" value="10">Mutton</option>
                <option id="premier" value="11">Premier (7.40am)</option>
                <option id="bmc" value="12">BMC</option>
                <input type="hidden" name="campusStopNumber" id="campusStopNumber" value="13">
            </select>
        ';
            } elseif ($cardStudentBusNumber == 6) {
              echo '
            <select name="yellowStop" id="yellowStop" required>
                <option value="" disabled selected>Select a Stop</option>
                <option id="koonamavu" value="1">Koonamavu (7.15 am)</option>
                <option id="varapuzha" value="2">Varapuzha</option>
                <option id="cheranallur" value="3">Cheranallur</option>
                <option id="thaikavu" value="4">Thaikavu</option>
                <option id="edappallyKunnumpuram" value="5">Edappally kunnumpuram (7.35am)</option>
                <option id="edappallyLulu" value="6">Edappally Lulu (7.40am)</option>
                <option id="pathadippalam" value="7">Pathadippalam</option>
                <option id="universityJunction" value="8">University jn.</option>
                <option id="changapuzhaNagar" value="9">Changapuzha nagar</option>
                <option id="hmt" value="10">HMT</option>
                <option id="toshiba" value="11">Toshiba</option>
                <option id="vallathol" value="12">Vallathol</option>
                <option id="olimugalChurch" value="13">Olimugal church</option>
                <input type="hidden" name="campusStopNumber" id="campusStopNumber" value="14">
            </select>
        ';
            } elseif ($cardStudentBusNumber == 7) {
              echo '
            <select name="yellowStop" id="yellowStop" required>
                <option value="" disabled selected>Select a Stop</option>
                <option id="unichira" value="1">Unichira (7.20 am)</option>
                <option id="edappallyChurch" value="2">Edappally Church (7.30am)</option>
                <option id="changapuzhaPark" value="3">Changapuzha park</option>
                <option id="palarivattam" value="4">Palarivattam</option>
                <option id="mamangalam" value="5">Mamangalam</option>
                <option id="pipeline" value="6">Pipe line (7.40am)</option>
                <option id="alinchodu" value="7">Alinchodu</option>
                <option id="chembumukku" value="8">Chembumukku</option>
                <input type="hidden" name="campusStopNumber" id="campusStopNumber" value="9">
            </select>
        ';
            } elseif ($cardStudentBusNumber == 8) {
              echo '
            <select name="yellowStop" id="yellowStop" required>
                <option value="" disabled selected>Select a Stop</option>
                <option id="perumbavoor" value="1">Perumbavoor (7.00 am)</option>
                <option id="ponjassery" value="2">Ponjassery</option>
                <option id="kavungaparambu" value="3">Kavungaparambu</option>
                <option id="kitex" value="4">Kitex (7.35 am)</option>
                <option id="kizakkambalam" value="5">Kizakkambalam</option>
                <option id="pallikara" value="6">Pallikara</option>
                <option id="wonderla" value="7">Wonderla</option>
                <option id="vikasvani" value="8">Vikasvani</option>
                <option id="tengode" value="9">Tengode</option>
                <option id="edachira" value="10">Edachira (7.45 am)</option>
                <option id="kakkanadIndianCoffeeHouse" value="11">Kakkanad Indian coffee house (7.50 am)</option>
                <option id="more" value="12">More</option>
                <option id="csez" value="13">CSEZ</option>
                <input type="hidden" name="campusStopNumber" id="campusStopNumber" value="14">
            </select>            
        ';
            } else {
              echo "<p>Invalid Bus Number</p>";
            }
            ?>

            <input type="hidden" name="makePaymentStudentStop" id="makePaymentStudentStop">
            <input type="hidden" name="makePaymentStudentBusNumber" id="makePaymentStudentBusNumber" value="<?php echo htmlspecialchars($cardStudentBusNumber); ?>">
            <input type="hidden" name="makePaymentStudentId" id="makePaymentStudentId" value="<?php echo htmlspecialchars($student_id); ?>">
            <input type="hidden" name="makePaymentStudentMail" id="makePaymentStudentMail" value="<?php echo htmlspecialchars($cardStudentMail); ?>">
            <input type="submit" name="submitYellowPayment" id="submitYellowPayment" value="Proceed to Payment">
          </div>

          <script>
            document.addEventListener("DOMContentLoaded", function() {
              const yellowStop = document.getElementById("yellowStop");
              yellowStop.addEventListener("change", function() {
                const selectedOptionId = this.options[this.selectedIndex].id;
                document.getElementById("makePaymentStudentStop").value = selectedOptionId;
              });
            });
          </script>
        </form>


        <form action="http://localhost/Project/student/makePayment.php" method="POST" id="makePinkPaymentForm">
          <div class="makePinkPayment" id="makePinkPayment">
            <?php
            if ($cardStudentBusNumber == 1) {
              echo '
            <select name="pinkStop" id="pinkStop" required>
                <option value="" disabled selected>Select a Stop</option>
                <option id="thoppumpadi" value="1">Thoppumpadi (7.20 am)</option>
                <option id="thevara" value="2">Thevara</option>
                <option id="manoramaJunction" value="3">Manorama junction</option>
                <option id="kundanoor" value="4">Kundanoor (7.35am)</option>
                <option id="maradu" value="5">Maradu</option>
                <option id="petta" value="6">Petta (7.40 am)</option>
            </select>
            <input type="hidden" name="pinkCampusStopNumber" id="pinkCampusStopNumber" value="7">
        ';
            } elseif ($cardStudentBusNumber == 2) {
              echo '
            <select name="pinkStop" id="pinkStop" required>
                <option value="" disabled selected>Select a Stop</option>
                <option id="kadavanthara" value="1">Kadavanthara (7.20 am)</option>
                <option id="kumaranasanJunction" value="2">Kumaranasan junction</option>
                <option id="janatha" value="3">Janatha (7.25 am)</option>
                <option id="vyttila" value="4">Vyttila</option>
                <option id="vadakkekotta" value="5">Vadakkekotta (7.40 am)</option>
                <input type="hidden" name="pinkCampusStopNumber" id="pinkCampusStopNumber" value="6">
            </select>
        ';
            } elseif ($cardStudentBusNumber == 3) {
              echo '
            <select name="pinkStop" id="pinkStop" required>
                <option value="" disabled selected>Select a Stop</option>
                <option id="chottanikaraTemple" value="1">Chottanikara temple stop (7.20 am)</option>
                <option id="thiruvankulam" value="2">Thiruvankulam (7.30 am)</option>
                <option id="tripunitharaStand" value="3">Tripunithara stand (7.45 am)</option>
                <option id="karigachira" value="4">Karigachira (7.50 am )</option>
                <input type="hidden" name="pinkCampusStopNumber" id="pinkCampusStopNumber" value="5">
            </select>
        ';
            } elseif ($cardStudentBusNumber == 4) {
              echo '
            <select name="pinkStop" id="pinkStop" required>
                <option value="" disabled selected>Select a Stop</option>
                <option id="highcourt" value="1">Highcourt (7.20 am)</option>
                <option id="kacheripadi" value="2">Kacheripadi</option>
                <option id="kaloorStand" value="3">Kaloor stand (7.30 am)</option>
                <option id="kaloorStadium" value="4">Kaloor stadium</option>
                <option id="vazhakala" value="5">Vazhakala (7.40)</option>
                <option id="padamugal" value="6">Padamugal</option>
                <input type="hidden" name="pinkCampusStopNumber" id="pinkCampusStopNumber" value="7">
            </select>
        ';
            } elseif ($cardStudentBusNumber == 5) {
              echo '
            <select name="pinkStop" id="pinkStop" required>
                <option value="" disabled selected>Select a Stop</option>
                <option id="angamaly" value="1">Angamaly (7.00 am)</option>
                <option id="kariyad" value="2">Kariyad</option>
                <option id="athani" value="3">Athani (7.10 am)</option>
                <option id="dhesam" value="4">Dhesam</option>
                <option id="paravoorJunction" value="5">Paravoor junction</option>
                <option id="thottakattukara" value="6">Thottakattukara</option>
                <option id="aluva" value="7">Aluva (7.25am)</option>
                <option id="garage" value="8">Garage</option>
                <option id="ambattukavu" value="9">Ambattukavu</option>
                <option id="mutton" value="10">Mutton</option>
                <option id="premier" value="11">Premier (7.40am)</option>
                <option id="bmc" value="12">BMC</option>
                <input type="hidden" name="pinkCampusStopNumber" id="pinkCampusStopNumber" value="13">
            </select>
        ';
            } elseif ($cardStudentBusNumber == 6) {
              echo '
            <select name="pinkStop" id="pinkStop" required>
                <option value="" disabled selected>Select a Stop</option>
                <option id="koonamavu" value="1">Koonamavu (7.15 am)</option>
                <option id="varapuzha" value="2">Varapuzha</option>
                <option id="cheranallur" value="3">Cheranallur</option>
                <option id="thaikavu" value="4">Thaikavu</option>
                <option id="edappallyKunnumpuram" value="5">Edappally kunnumpuram (7.35am)</option>
                <option id="edappallyLulu" value="6">Edappally Lulu (7.40am)</option>
                <option id="pathadippalam" value="7">Pathadippalam</option>
                <option id="universityJunction" value="8">University jn.</option>
                <option id="changapuzhaNagar" value="9">Changapuzha nagar</option>
                <option id="hmt" value="10">HMT</option>
                <option id="toshiba" value="11">Toshiba</option>
                <option id="vallathol" value="12">Vallathol</option>
                <option id="olimugalChurch" value="13">Olimugal church</option>
                <input type="hidden" name="pinkCampusStopNumber" id="pinkCampusStopNumber" value="14">
            </select>
        ';
            } elseif ($cardStudentBusNumber == 7) {
              echo '
            <select name="pinkStop" id="pinkStop" required>
                <option value="" disabled selected>Select a Stop</option>
                <option id="unichira" value="1">Unichira (7.20 am)</option>
                <option id="edappallyChurch" value="2">Edappally Church (7.30am)</option>
                <option id="changapuzhaPark" value="3">Changapuzha park</option>
                <option id="palarivattam" value="4">Palarivattam</option>
                <option id="mamangalam" value="5">Mamangalam</option>
                <option id="pipeline" value="6">Pipe line (7.40am)</option>
                <option id="alinchodu" value="7">Alinchodu</option>
                <option id="chembumukku" value="8">Chembumukku</option>
                <input type="hidden" name="pinkCampusStopNumber" id="pinkCampusStopNumber" value="9">
            </select>
        ';
            } elseif ($cardStudentBusNumber == 8) {
              echo '
            <select name="pinkStop" id="pinkStop" required>
                <option value="" disabled selected>Select a Stop</option>
                <option id="perumbavoor" value="1">Perumbavoor (7.00 am)</option>
                <option id="ponjassery" value="2">Ponjassery</option>
                <option id="kavungaparambu" value="3">Kavungaparambu</option>
                <option id="kitex" value="4">Kitex (7.35 am)</option>
                <option id="kizakkambalam" value="5">Kizakkambalam</option>
                <option id="pallikara" value="6">Pallikara</option>
                <option id="wonderla" value="7">Wonderla</option>
                <option id="vikasvani" value="8">Vikasvani</option>
                <option id="tengode" value="9">Tengode</option>
                <option id="edachira" value="10">Edachira (7.45 am)</option>
                <option id="kakkanadIndianCoffeeHouse" value="11">Kakkanad Indian coffee house (7.50 am)</option>
                <option id="more" value="12">More</option>
                <option id="csez" value="13">CSEZ</option>
                <input type="hidden" name="pinkCampusStopNumber" id="pinkCampusStopNumber" value="14">
            </select>            
        ';
            } else {
              echo "<p>Invalid Bus Number</p>";
            }
            ?>

            <input type="date" name="makePaymentStudentDate" id="makePaymentStudentDate">

            <script>
              document.addEventListener("DOMContentLoaded", function() {
                const dateInput = document.getElementById("makePaymentStudentDate");
                const today = new Date();

                // Function to format date to YYYY-MM-DD
                function formatDate(date) {
                  const year = date.getFullYear();
                  const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                  const day = String(date.getDate()).padStart(2, '0');
                  return `${year}-${month}-${day}`;
                }

                // Get today's date in local format
                const todayDate = formatDate(today);
                dateInput.setAttribute("min", todayDate); // Set minimum to today

                // Get the current time to check if it's past 8:30 AM
                const currentHour = today.getHours();
                const currentMinute = today.getMinutes();

                // If current time is past 6:30 AM, disable today's date but allow future dates
                if (currentHour > 8 || (currentHour === 8 && currentMinute >= 30)) {
                  dateInput.setAttribute("min", tomorrowDate()); // Allow selection starting tomorrow
                }

                // Function to get tomorrow's date in YYYY-MM-DD format
                function tomorrowDate() {
                  const tomorrow = new Date();
                  tomorrow.setDate(today.getDate() + 1); // Add one day
                  return formatDate(tomorrow);
                }
              });
            </script>




            <div class="pinkMakePaymentDaySlot" id="pinkMakePaymentDaySlot">
              <div>
                <input type="radio" name="daySlot" value="Morning" required>
                <label class="daySlotText"> Morning </label>
              </div>

              <div>
                <input type="radio" name="daySlot" value="Evening">
                <label class="daySlotText"> Evening </label>
              </div>
            </div>

            <input type="hidden" name="makePinkPaymentStudentStop" id="makePinkPaymentStudentStop">

            <input type="hidden" name="makePaymentStudentBusNumber" id="makePaymentStudentBusNumber" value="<?php echo htmlspecialchars($cardStudentBusNumber); ?>">
            <input type="hidden" name="makePaymentStudentId" id="makePaymentStudentId" value="<?php echo htmlspecialchars($student_id); ?>">
            <input type="hidden" name="makePaymentStudentMail" id="makePaymentStudentMail" value="<?php echo htmlspecialchars($cardStudentMail); ?>">
            <input type="submit" name="submitPinkPayment" id="submitPinkPayment" value="Proceed to Payment">
          </div>

          <script>
            document.addEventListener("DOMContentLoaded", function() {
              const pinkStop = document.getElementById("pinkStop");
              pinkStop.addEventListener("change", function() {
                const selectedPinkOptionId = this.options[this.selectedIndex].id;
                document.getElementById("makePinkPaymentStudentStop").value = selectedPinkOptionId;
              });
            });
          </script>
        </form>

        <div class="paymentHistory" id="paymentHistory">
          <p class="routeBoxText"> ID:<?php echo $student_id; ?> </p>
          <p class="routeBoxText"> Mail:<?php echo $cardStudentMail; ?> </p>
          <p class="routeBoxText"> Time: <?php echo $cardStudentTime; ?> </p>
          <p class="routeBoxText"> Amount:<?php echo $cardStudentAmount; ?> </p>
          <p class="routeBoxText"> Stop: <?php echo $cardStudentStop; ?> </p>
        </div>

        <script>
          function updateTime() {
            const now = new Date();

            // Get formatted time with milliseconds
            const timeString = now.toLocaleTimeString('en-GB', {
              hour12: false
            }) + ':' + now.getMilliseconds();

            // Get formatted date
            const dateString = now.toLocaleDateString('en-GB', {
              weekday: 'long',
              year: 'numeric',
              month: 'long',
              day: 'numeric'
            });

            // Update the time and date in the HTML
            document.getElementById('time').textContent = timeString;
            document.getElementById('date').textContent = dateString;
          }

          setInterval(updateTime, 10); // Update every 10 milliseconds
          updateTime(); // Initial call to display date and time immediately
        </script>


        <!-- map integration -->
        <div class="locationPane" id="locationPane">
          <!-- <h1>Live Bus Location</h1>

          <div id="status" style="height:200px; width:100%;">Fetching bus location...</div>

          <div id="map" style="padding: 10px; background: #f1f1f1; font-family: Arial, sans-serif;"></div>

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
             </script> -->
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
            <!-- <label class="editProfileText"> Current Name: <span> <?php echo "$cardStudentName"; ?> </span> </label> -->
            <input type="text" name="staffNewName" id="staffNewName" placeholder="Name: <?php echo $cardStudentName; ?>" maxlength="20">
            <br>

            <!-- <label class="editProfileText"> Current ID: <span> <?php echo "$student_id"; ?> </span> </label> -->
            <input type="text" name="staffNewId" id="staffNewId" placeholder="User ID: <?php echo $student_id; ?>" maxlength="20">
            <br>

            <!-- <label class="editProfileText"> Current Password: <span> <?php echo "$cardStudentPassword"; ?> </span> </label> -->
            <input type="text" name="staffNewPassword" id="staffNewPassword" placeholder="Password: <?php echo $cardStudentPassword; ?>"
              maxlength="20">

            <input type="submit" name="submitStaffNewDetails" id="submitStaffNewDetails">

            <span class="submitMessageOne" id="submitMessageOne"> Note: You will need to sign back in after making the changes </span>
            <p class="submitMessageTwo" id="submitMessageTwo"> Profile Updated...redirecting in <span class="submitMessageTwoSpan" id="submitMessageTwoSpan"> 3 </span> s </p>
            <span class="submitMessageThree" id="submitMessageThree"> Error Updating Profile </span>
          </div>
        </form>
      </div>



      <div class="newStudentMessage" id="newStudentMessage">
        <p id="newStudentMessageText"> Apply for a card to proceed </p>
        <p id="newStudentUnderReviewText"> Your application has been submitted successfully and is under review. </p>
        <p id="newStudentUnderReviewRejectedText"> Thank you for your application. All seats for the Yellow Card are currently filled, but you may apply for the Pink Card as an alternative. </p>
      </div>

      <form action="" id="applicationForm" method="post" enctype="multipart/form-data">
        <div class="formLeftRight">
          <div class="applyFormLeft">
            <input type="text" name="studentName" placeholder="<?php echo $newStudentName; ?>" id="studentName" readonly>


            <div class="uploadImageContainer routeBoxText">
              <input type="file" name="studentImage" id="studentImage" accept=".jpeg, .jpg, .png" required>
              <label for="studentImage" id="studentImageLabel" class="hover-effect"> Profile picture </label>
            </div>
          </div>

          <div class="applyFormRight">
            <select name="routeNumber" id="routeNumber" required>
              <option value="" disabled selected>Select a Route</option>
              <option id="routeOne" value="1">Route One</option>
              <option id="routeTwo" value="2">Route Two</option>
              <option id="routeThree" value="3">Route Three</option>
              <option id="routeFour" value="4">Route Four</option>
              <option id="routeFive" value="5">Route Five</option>
              <option id="routeSix" value="6">Route Six</option>
              <option id="routeSeven" value="7">Route Seven</option>
              <option id="routeEight" value="8">Route Eight</option>
            </select>

            <div class="cardColorOptionContainer" id="cardColorOptionContainer">
              <div class="cardColorOptionOne">
                <input type="radio" name="cardColor" value="Yellow" required>
                <p class="cardColorOptionContainerText"> Yellow </p>

              </div>
              <br>
              <div class="cardColorOptionTwo">

                <input type="radio" name="cardColor" value="Pink">
                <p class="cardColorOptionContainerText"> Pink </p>
              </div>

            </div>
            <p class="cardColorOptionContainerText"> Card type </p>
          </div>
        </div>

        <input type="submit" id="submitButton" name="submitButton">


        <div class="routeBox" id="routeBox">
          <div class="innerRouteBox">
            <p class="routeBoxText hover-effect"> ◉ Route 1: Thoppumpadi (7.20 am) -> Thevara -> Manorama junction -> Kundanoor (7.35am) -> Maradu ->
              Petta (7.40 am) -> Campus
            </p>
            <p class="routeBoxText hover-effect">
              ◉ Route 2: Kadavanthara (7.20 am) -> Kumaranasan junction -> Janatha (7.25 am) ->
              Vyttila -> Vadakkekotta
              (7.40 am) -> Campus
            </p>
            <p class="routeBoxText hover-effect">
              ◉ Route 3: Chottanikara temple stop (7.20 am) -> Thiruvankulam (7.30 am) ->
              Tripunithara stand (7.45 am) -> Karigachira (7.50 am ) -> Campus
            </p>
            <p class="routeBoxText hover-effect">
              ◉ Route 4: Highcourt (7.20 am) -> Kacheripadi -> Kaloor stand (7.30 am) -> Kaloor
              stadium
              -> Vazhakala(7.40) -> Padamugal -> Campus
            </p>
            <p class="routeBoxText hover-effect">
              ◉ Route 5: Angamaly (7.00 am) -> Kariyad -> Athani (7.10 am) -> Dhesam -> Paravoor
              junction -> Thottakattukara -> Aluva (7.25am) -> Garage -> Ambattukavu -> Mutton -> Premier (7.40am) ->
              BMC -> Campus
            </p>
            <p class="routeBoxText hover-effect">
              ◉ Route 6: Koonamavu (7.15 am) -> Varapuzha -> Cheranallur -> Thaikavu -> Edappally kunnumpuram (7.35am) ->
              Edappally Lulu (7.40am) -> Pathadippalam -> University jn. -> Changapuzha nagar -> HMT ->
              Toshiba -> Vallathol -> Olimugal church -> Campus
            </p>
            <p class="routeBoxText hover-effect">
              ◉ Route 7: Unichira (7.20 am) -> Edappally Church (7.30am) -> Changapuzha park ->
              Palarivattam -> Mamangalam -> Pipe line (7.40am) -> Alinchodu ->
              Chembumukku -> Campus
            </p>
            <p class="routeBoxText hover-effect">
              ◉ Route 8: Perumbavoor (7.00 am) -> Ponjassery -> Kavungaparambu -> Kitex (7.35 am) ->
              Kizakkambalam -> Pallikara -> Wonderla -> Vikasvani -> Tengode -> Edachira (7.45 am) ->
              Kakkanad Indian coffee house (7.50 am) -> More -> CSEZ -> Campus
            </p>
          </div>
        </div>
      </form>

      <?php
      // Ensure the form is submitted
      if (isset($_POST['submitButton'])) {

        // Get form data
        $studentName = $newStudentName;
        $routeNumber = $_POST['routeNumber'];
        $cardType = $_POST['cardColor'];
        $mailId = $studentMail; // Mail ID is fetched from the DB in the earlier part of the script
        $StudentcardNumber = $_POST[''];

        // File upload logic
        $targetDir = "studentImagesUnderReview/";
        $fileName = basename($_FILES['studentImage']['name']);
        $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Rename the file using the student's mail ID
        $newFileName = $mailId . '.' . $imageFileType;
        $targetFile = $targetDir . $newFileName;

        // Check if the uploaded file is an image
        $check = getimagesize($_FILES['studentImage']['tmp_name']);

        if ($check !== false) {
          // Move the uploaded file to the target directory
          if (move_uploaded_file($_FILES['studentImage']['tmp_name'], $targetFile)) {
            // echo "The file " . htmlspecialchars($newFileName) . " has been uploaded.";

            // Insert the form data into the database

            $sql = "INSERT INTO newApplications (name, route, filePath, mailId, studentID, cardType) 
                    VALUES ('$studentName', '$routeNumber', '$targetFile', '$mailId', '$student_id', '$cardType')";
            // $sql = "INSERT INTO studentcard (studentId, card_type, stop, busNumber, cardNumber,) 
            //  VALUES ('$student_id', '$cardType', '', '$routeNumber', '$StudentcardNumber')";

            if (mysqli_query($connection, $sql)) {
              $sqlTwo = "DELETE FROM newStudent WHERE studentId= '$student_id'";
              $result = mysqli_query($connection, $sqlTwo);

              echo "<script>
            document.addEventListener('DOMContentLoaded', () => {
                const newStudentUnderReviewText = document.getElementById('newStudentUnderReviewText');
                newStudentUnderReviewText.style.display = 'inline';

                const newStudentUnderReviewRejectedText = document.getElementById('newStudentUnderReviewRejectedText');
                newStudentUnderReviewRejectedText.style.display = 'none';

                const dashboardTextAndIcons = document.getElementById('dashboardTextAndIcons');
                dashboardTextAndIcons.style.display = 'none'; 
                
                const secondDivision = document.getElementById('secondDivision');
                secondDivision.style.display = 'none'; 

                const busBodyContainer = document.getElementById('busBodyContainer');
                busBodyContainer.style.display = 'none';
                
                const applicationForm = document.getElementById('applicationForm');
                applicationForm.style.display = 'none';

                const sideNavigationBar = document.getElementById('sideNavigationBar');
                sideNavigationBar.style.display = 'none';
                sideNavigationBar.style.width = '0em';

                const applyCardMessage = document.getElementById('newStudentMessageText');
                applyCardMessage.style.display = 'none';

                const rightPane = document.getElementById('rightPane');
                rightPane.style.width = '91em';
                rightPane.style.marginLeft = '2em';  
                
                setTimeout(function() {
                window.location.href = 'http://localhost/Project/loginPage/loginPage.php';
                }, 3000);

            });
        </script>";
            } else {
              echo "Error: " . mysqli_error($connection);
            }
          } else {
            echo "Sorry, there was an error uploading your file.";
          }
        } else {
          echo "<p class='routeBoxText'> File is not an image </p>";
        }
      }



      //feedback submit
      if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['submitFeedback'])) {
          if (!empty(trim(($_POST['staffFeedback'])))) {
            $staffFeedback = $_POST['staffFeedback'];
            $currentDateAndTime = date("Y-m-d H:i:s");
            $sql = "INSERT INTO inboxtable (fromMail, toMail, content, dateAndTime) VALUES ('$feedbackStudentMail', '$adminMailId', '$staffFeedback', '$currentDateAndTime');";
            $result = mysqli_query($connection, $sql);

            if ($result) {
              echo "<script>     
              const message = document.getElementById('feedbackSubmitMessage');     
              message.style.display = 'block'; 
              const busCardPane = document.getElementById('loader');
                  const busBodyContainer = document.getElementById('busBodyContainer');
                  const locationPane = document.getElementById('locationPane');
                  const feedbackPane = document.getElementById('feedback');
                  const editProfilePane = document.getElementById('editProfile');
                  const inbox = document.getElementById('inbox');
                  busBodyContainer.classList.remove('busBodyContainer-yellow-hover-effect');
                  busBodyContainer.classList.remove('busBodyContainer-pink-hover-effect');
                  inbox.style.display = 'none';
                  busCardPane.style.display = 'none';
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
            $staffNewName = $cardStudentName;
          }

          if (!empty(trim(($_POST['staffNewId'])))) {
            $staffNewId = $_POST['staffNewId'];
          } else {
            $staffNewId = $student_id;
          }

          if (!empty(trim(($_POST['staffNewPassword'])))) {
            $staffNewPassword = $_POST['staffNewPassword'];
          } else {
            $staffNewPassword = $cardStudentPassword;
          }

          // $sql = "UPDATE studentIdAndPassword SET NAME = '$staffNewName', ID = '$staffNewId', PASSWORD = '$staffNewPassword' WHERE MAIL = '$cardStudentMail'";
          $sql = "UPDATE studentidandpassword SET name = '$staffNewName', id = '$staffNewId', password = '$staffNewPassword' WHERE mail = '$cardStudentMail'";
          $result = mysqli_query($connection, $sql);
          // $sql34 = "UPDATE yellowstudent SET studentId = '$staffNewId' WHERE MAIL = '$cardStudentMail'";
          // $result34 = mysqli_query($connection, $sql34);
          if ($result) {
            echo "<script> 
            const busCardPane = document.getElementById('loader');
                  const busBodyContainer = document.getElementById('busBodyContainer');
                  const locationPane = document.getElementById('locationPane');
                  const feedbackPane = document.getElementById('feedback');
                  const editProfilePane = document.getElementById('editProfile');
                  const inbox = document.getElementById('inbox');
                  busBodyContainer.classList.remove('busBodyContainer-yellow-hover-effect');
                  busBodyContainer.classList.remove('busBodyContainer-pink-hover-effect');
                  inbox.style.display = 'none';
                  busCardPane.style.display = 'none';
                  locationPane.style.display = 'none';
                  feedbackPane.style.display = 'none';
                  editProfilePane.style.display = 'flex';
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
      }

      ?>

    </div>
  </div>
  <script src="studentPage.js"></script>
</body>

</html>