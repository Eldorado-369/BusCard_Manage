<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "buscardmanagementsystem";

$connection = mysqli_connect($servername, $username, $password, $dbname);

if ($connection->connect_error) {
  die("<p> <br> Connection Failed: " . $connection->connect_error . "</p> <br>");
}

// Check if the staff is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: http://localhost/Project/loginPage/loginPage.php");
  exit;
}

// Get the logged-in admin ID from the session
$admin_id = $_SESSION['admin_id'];

$sql = "SELECT * FROM adminidandpassword WHERE ID='$admin_id'";
$result = mysqli_query($connection, $sql);

// Fetch the result as an associative array
$admin = mysqli_fetch_assoc($result);

$adminPassword = $admin['PASSWORD'];
$adminName = $admin['name'];  //not created admin name in the database
$adminMail = $admin['mailId'];
$feedbackadminMail = $adminMail;

$adminMailSplitted = strtoupper(explode('@', $adminMail)[0]);
// $name=strtoupper(explode('@',$adminMail)[1]);

// fetch count of inbox
$sql1 = "SELECT COUNT(*) AS inboxCount FROM inboxTable WHERE toMail='$adminMail'";
$result1 = mysqli_query($connection, $sql1);
// Fetch the result as an associative array
$staff = mysqli_fetch_assoc($result1);
$inboxCount = $staff['inboxCount'];



$jpegImagePath = "$adminMail.jpeg";
$jpgImagePath = "$adminMail.jpg";
$pngImagePath = "$adminMail.png";

$imageSrc = "";

if (file_exists($jpegImagePath)) {
  $imageSrc = "$jpegImagePath";
} else if (file_exists($jpgImagePath)) {
  $imageSrc = "$jpgImagePath";
} else {
  $imageSrc = "$pngImagePath";
}

//total buses
$totalBusCountSql = "SELECT COUNT(*) FROM busCountReachedOrNot";
$totalBusCountSqlResult = mysqli_query($connection, $totalBusCountSql);
$row = mysqli_fetch_array($totalBusCountSqlResult);
$totalBusCount = $row[0];

//reached buses count
$totalBusReachedCountSql = "SELECT COUNT(*) FROM busCountReachedOrNot where BUS_REACHED = '1'";
$totalBusReachedCountSqlResult = mysqli_query($connection, $totalBusReachedCountSql);
$row = mysqli_fetch_array($totalBusReachedCountSqlResult);
$totalBusesReached = $row[0];

echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const busReachedSlider = document.querySelector('.rightPaneFirstDivisionTopLeftTwoTopIndicatorSlider');
              busReachedSlider.style.setProperty('--end-width',' " . ($totalBusesReached * 2) . "em');
          });
      </script>";

//not reached buses count
$totalBusesNotReached = $totalBusCount - $totalBusesReached;

echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const busNotReachedSlider = document.querySelector('.rightPaneFirstDivisionTopLeftThreeTopIndicatorSlider');
              busNotReachedSlider.style.setProperty('--end-width', '" . ($totalBusesNotReached * 2) . "em');
          });
      </script>";

// pink card generated this year
$pinkCardGeneratedThisYearSql = "SELECT COUNT(*) FROM dateAndTime where YEAR(dateTimeColumn) = YEAR(CURDATE())";
$pinkCardGeneratedThisYearResult = mysqli_query($connection, $pinkCardGeneratedThisYearSql);
$row = mysqli_fetch_array($pinkCardGeneratedThisYearResult);
$pinkCardGeneratedThisYear = $row[0];

// pink card generated last year
$pinkCardGeneratedLastYearSql = "SELECT COUNT(*) FROM dateAndTime where YEAR(dateTimeColumn) = YEAR(CURDATE()) - 1";
$pinkCardGeneratedLastYearResult = mysqli_query($connection, $pinkCardGeneratedLastYearSql);
$row = mysqli_fetch_array($pinkCardGeneratedLastYearResult);
$pinkCardGeneratedLastYear = $row[0];

$firstPieChartPercentage = ($pinkCardGeneratedLastYear / ($pinkCardGeneratedThisYear + $pinkCardGeneratedLastYear)) * 100;

echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const pinkCardGeneratedThisYearPieChart = document.querySelector('.rightPaneFirstDivisionTopCenterThreePieChart');
              pinkCardGeneratedThisYearPieChart.style.setProperty('--division', '" . $firstPieChartPercentage . "%');
          });
      </script>";

// yellow card generated this year
$yellowCardGeneratedThisYearSql = "SELECT COUNT(*) FROM dateAndTime where YEAR(dateTimeColumn) = YEAR(CURDATE())";
$yellowCardGeneratedThisYearResult = mysqli_query($connection, $yellowCardGeneratedThisYearSql);
$row = mysqli_fetch_array($yellowCardGeneratedThisYearResult);
$yellowCardGeneratedThisYear = $row[0];

// yellow card generated last year
$yellowCardGeneratedLastYearSql = "SELECT COUNT(*) FROM dateAndTime where YEAR(dateTimeColumn) = YEAR(CURDATE()) - 1";
$yellowCardGeneratedLastYearResult = mysqli_query($connection, $yellowCardGeneratedLastYearSql);
$row = mysqli_fetch_array($yellowCardGeneratedLastYearResult);
$yellowCardGeneratedLastYear = $row[0];

$firstPieChartPercentage = ($yellowCardGeneratedLastYear / ($yellowCardGeneratedThisYear + $yellowCardGeneratedLastYear)) * 100;

echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const yellowCardGeneratedThisYearPieChart = document.querySelector('.rightPaneFirstDivisionTopRightThreePieChart');
              yellowCardGeneratedThisYearPieChart.style.setProperty('--division', '" . $firstPieChartPercentage . "%');
          });
      </script>";

// pink cards generated today bus number 1
$pinkCardGeneratedTodayBusOneSql = "SELECT COUNT(*) FROM busNumberAndIncome where BUS_NUMBER = 1";
$pinkCardGeneratedTodayBusOneResult = mysqli_query($connection, $pinkCardGeneratedTodayBusOneSql);
$row = mysqli_fetch_array($pinkCardGeneratedTodayBusOneResult);
$pinkCardGeneratedTodayBusOne = $row[0];
$height = ($pinkCardGeneratedTodayBusOne * 8) / 15;

echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const pinkCardGeneratedTodayBusOneSlider = document.querySelector('.rightPaneFirstDivisionBottomBottomOneCenterIndicatorSlider');
              pinkCardGeneratedTodayBusOneSlider.style.setProperty('--end-height', '" . $height . "em');
          });
      </script>";

$pinkCardGeneratedTodayBusOneIncomeSql = "SELECT SUM(BUS_INCOME) FROM busNumberAndIncome where BUS_NUMBER = 1";
$pinkCardGeneratedTodayBusOneIncomeResult = mysqli_query($connection, $pinkCardGeneratedTodayBusOneIncomeSql);
$row = mysqli_fetch_array($pinkCardGeneratedTodayBusOneIncomeResult);
$pinkCardGeneratedTodayBusOneIncome = $row[0];

// pink cards generated today bus number 2
$pinkCardGeneratedTodayBusTwoSql = "SELECT COUNT(*) FROM busNumberAndIncome where BUS_NUMBER = 2";
$pinkCardGeneratedTodayBusTwoResult = mysqli_query($connection, $pinkCardGeneratedTodayBusTwoSql);
$row = mysqli_fetch_array($pinkCardGeneratedTodayBusTwoResult);
$pinkCardGeneratedTodayBusTwo = $row[0];
$height = ($pinkCardGeneratedTodayBusTwo * 8) / 15;

echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const pinkCardGeneratedTodayBusTwoSlider = document.querySelector('.rightPaneFirstDivisionBottomBottomTwoCenterIndicatorSlider');
              pinkCardGeneratedTodayBusTwoSlider.style.setProperty('--end-height', '" . $height . "em');
          });
      </script>";

$pinkCardGeneratedTodayBusTwoIncomeSql = "SELECT SUM(BUS_INCOME) FROM busNumberAndIncome where BUS_NUMBER = 2";
$pinkCardGeneratedTodayBusTwoIncomeResult = mysqli_query($connection, $pinkCardGeneratedTodayBusTwoIncomeSql);
$row = mysqli_fetch_array($pinkCardGeneratedTodayBusTwoIncomeResult);
$pinkCardGeneratedTodayBusTwoIncome = $row[0];

// pink cards generated today bus number 3
$pinkCardGeneratedTodayBusThreeSql = "SELECT COUNT(*) FROM busNumberAndIncome where BUS_NUMBER = 3";
$pinkCardGeneratedTodayBusThreeResult = mysqli_query($connection, $pinkCardGeneratedTodayBusThreeSql);
$row = mysqli_fetch_array($pinkCardGeneratedTodayBusThreeResult);
$pinkCardGeneratedTodayBusThree = $row[0];
$height = ($pinkCardGeneratedTodayBusThree * 8) / 15;

echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const pinkCardGeneratedTodayBusThreeSlider = document.querySelector('.rightPaneFirstDivisionBottomBottomThreeCenterIndicatorSlider');
              pinkCardGeneratedTodayBusThreeSlider.style.setProperty('--end-height', '" . $height . "em');
          });
      </script>";

$pinkCardGeneratedTodayBusThreeIncomeSql = "SELECT SUM(BUS_INCOME) FROM busNumberAndIncome where BUS_NUMBER = 3";
$pinkCardGeneratedTodayBusThreeIncomeResult = mysqli_query($connection, $pinkCardGeneratedTodayBusThreeIncomeSql);
$row = mysqli_fetch_array($pinkCardGeneratedTodayBusThreeIncomeResult);
$pinkCardGeneratedTodayBusThreeIncome = $row[0];

// pink cards generated today bus number 4
$pinkCardGeneratedTodayBusFourSql = "SELECT COUNT(*) FROM busNumberAndIncome where BUS_NUMBER = 4";
$pinkCardGeneratedTodayBusFourResult = mysqli_query($connection, $pinkCardGeneratedTodayBusFourSql);
$row = mysqli_fetch_array($pinkCardGeneratedTodayBusFourResult);
$pinkCardGeneratedTodayBusFour = $row[0];
$height = ($pinkCardGeneratedTodayBusFour * 8) / 15;

echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const pinkCardGeneratedTodayBusFourSlider = document.querySelector('.rightPaneFirstDivisionBottomBottomFourCenterIndicatorSlider');
              pinkCardGeneratedTodayBusFourSlider.style.setProperty('--end-height', '" . $height . "em');
          });
      </script>";

$pinkCardGeneratedTodayBusFourIncomeSql = "SELECT SUM(BUS_INCOME) FROM busNumberAndIncome where BUS_NUMBER = 4";
$pinkCardGeneratedTodayBusFourIncomeResult = mysqli_query($connection, $pinkCardGeneratedTodayBusFourIncomeSql);
$row = mysqli_fetch_array($pinkCardGeneratedTodayBusFourIncomeResult);
$pinkCardGeneratedTodayBusFourIncome = $row[0];

// pink cards generated today bus number 5
$pinkCardGeneratedTodayBusFiveSql = "SELECT COUNT(*) FROM busNumberAndIncome where BUS_NUMBER = 5";
$pinkCardGeneratedTodayBusFiveResult = mysqli_query($connection, $pinkCardGeneratedTodayBusFiveSql);
$row = mysqli_fetch_array($pinkCardGeneratedTodayBusFiveResult);
$pinkCardGeneratedTodayBusFive = $row[0];
$height = ($pinkCardGeneratedTodayBusFive * 8) / 15;

echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const pinkCardGeneratedTodayBusFiveSlider = document.querySelector('.rightPaneFirstDivisionBottomBottomFiveCenterIndicatorSlider');
              pinkCardGeneratedTodayBusFiveSlider.style.setProperty('--end-height', '" . $height . "em');
          });
      </script>";

$pinkCardGeneratedTodayBusFiveIncomeSql = "SELECT SUM(BUS_INCOME) FROM busNumberAndIncome where BUS_NUMBER = 5";
$pinkCardGeneratedTodayBusFiveIncomeResult = mysqli_query($connection, $pinkCardGeneratedTodayBusFiveIncomeSql);
$row = mysqli_fetch_array($pinkCardGeneratedTodayBusFiveIncomeResult);
$pinkCardGeneratedTodayBusFiveIncome = $row[0];

// pink cards generated today bus number 6
$pinkCardGeneratedTodayBusSixSql = "SELECT COUNT(*) FROM busNumberAndIncome where BUS_NUMBER = 6";
$pinkCardGeneratedTodayBusSixResult = mysqli_query($connection, $pinkCardGeneratedTodayBusSixSql);
$row = mysqli_fetch_array($pinkCardGeneratedTodayBusSixResult);
$pinkCardGeneratedTodayBusSix = $row[0];
$height = ($pinkCardGeneratedTodayBusSix * 8) / 15;

echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const pinkCardGeneratedTodayBusSixSlider = document.querySelector('.rightPaneFirstDivisionBottomBottomSixCenterIndicatorSlider');
              pinkCardGeneratedTodayBusSixSlider.style.setProperty('--end-height', '" . $height . "em');
          });
      </script>";

$pinkCardGeneratedTodayBusSixIncomeSql = "SELECT SUM(BUS_INCOME) FROM busNumberAndIncome where BUS_NUMBER = 6";
$pinkCardGeneratedTodayBusSixIncomeResult = mysqli_query($connection, $pinkCardGeneratedTodayBusSixIncomeSql);
$row = mysqli_fetch_array($pinkCardGeneratedTodayBusSixIncomeResult);
$pinkCardGeneratedTodayBusSixIncome = $row[0];

// pink cards generated today bus number 7
$pinkCardGeneratedTodayBusSevenSql = "SELECT COUNT(*) FROM busNumberAndIncome where BUS_NUMBER = 7";
$pinkCardGeneratedTodayBusSevenResult = mysqli_query($connection, $pinkCardGeneratedTodayBusSevenSql);
$row = mysqli_fetch_array($pinkCardGeneratedTodayBusSevenResult);
$pinkCardGeneratedTodayBusSeven = $row[0];
$height = ($pinkCardGeneratedTodayBusSeven * 8) / 15;

echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const pinkCardGeneratedTodayBusSevenSlider = document.querySelector('.rightPaneFirstDivisionBottomBottomSevenCenterIndicatorSlider');
              pinkCardGeneratedTodayBusSevenSlider.style.setProperty('--end-height', '" . $height . "em');
          });
      </script>";

$pinkCardGeneratedTodayBusSevenIncomeSql = "SELECT SUM(BUS_INCOME) FROM busNumberAndIncome where BUS_NUMBER = 7";
$pinkCardGeneratedTodayBusSevenIncomeResult = mysqli_query($connection, $pinkCardGeneratedTodayBusSevenIncomeSql);
$row = mysqli_fetch_array($pinkCardGeneratedTodayBusSevenIncomeResult);
$pinkCardGeneratedTodayBusSevenIncome = $row[0];

// pink cards generated today bus number 8
$pinkCardGeneratedTodayBusEightSql = "SELECT COUNT(*) FROM busNumberAndIncome where BUS_NUMBER = 8";
$pinkCardGeneratedTodayBusEightResult = mysqli_query($connection, $pinkCardGeneratedTodayBusEightSql);
$row = mysqli_fetch_array($pinkCardGeneratedTodayBusEightResult);
$pinkCardGeneratedTodayBusEight = $row[0];
$height = ($pinkCardGeneratedTodayBusEight * 8) / 15;

echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const pinkCardGeneratedTodayBusEightSlider = document.querySelector('.rightPaneFirstDivisionBottomBottomEightCenterIndicatorSlider');
              pinkCardGeneratedTodayBusEightSlider.style.setProperty('--end-height', '" . $height . "em');
          });
      </script>";

$pinkCardGeneratedTodayBusEightIncomeSql = "SELECT SUM(BUS_INCOME) FROM busNumberAndIncome where BUS_NUMBER = 8";
$pinkCardGeneratedTodayBusEightIncomeResult = mysqli_query($connection, $pinkCardGeneratedTodayBusEightIncomeSql);
$row = mysqli_fetch_array($pinkCardGeneratedTodayBusEightIncomeResult);
$pinkCardGeneratedTodayBusEightIncome = $row[0];

// pink cards today total
$pinkCardsToday = $pinkCardGeneratedTodayBusOneIncome + $pinkCardGeneratedTodayBusTwoIncome + $pinkCardGeneratedTodayBusThreeIncome + $pinkCardGeneratedTodayBusFourIncome + $pinkCardGeneratedTodayBusFiveIncome + $pinkCardGeneratedTodayBusSixIncome + $pinkCardGeneratedTodayBusSevenIncome + $pinkCardGeneratedTodayBusEightIncome;

// pink card weekly incomes
// Initialize an associative array for the incomes
$weekIncome = array(
  'Monday' => 0,
  'Tuesday' => 0,
  'Wednesday' => 0,
  'Thursday' => 0,
  'Friday' => 0
);

// Query to get income for each day of this week
$query = "
  SELECT 
      DAYNAME(date) as day,
      SUM(income) as total_income,
      number
  FROM weeklyIncome
  WHERE WEEK(date) = WEEK(CURDATE())
  AND YEAR(date) = YEAR(CURDATE())
  GROUP BY day
";

$result = mysqli_query($connection, $query);

while ($row = mysqli_fetch_assoc($result)) {
  $weekIncome[$row['day']] = $row['total_income'];
  $weekNumber[$row['day']] = $row['number'];
}

// each day's income in the $weekIncome array
$mondayIncome = $weekIncome['Monday'];
$tuesdayIncome = $weekIncome['Tuesday'];
$wednesdayIncome = $weekIncome['Wednesday'];
$thursdayIncome = $weekIncome['Thursday'];
$fridayIncome = $weekIncome['Friday'];
$thisWeekTotal = $mondayIncome + $tuesdayIncome + $wednesdayIncome + $thursdayIncome + $fridayIncome;

if (isset($weekNumber['Monday'])) {
  $mondayCardsGenerated = $weekNumber['Monday'];
} else {
  $mondayCardsGenerated = 0;
}
$height = $mondayCardsGenerated / 6;
echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const pinkCardGeneratedMondaySliderOne = document.querySelector('.rightPaneSecondDivisionBottomTopRightOneBottomBar');
              pinkCardGeneratedMondaySliderOne.style.setProperty('--end-height', '" . $height . "em');
          });
      </script>";

if (isset($weekNumber['Tuesday'])) {
  $tuesdayCardsGenerated = $weekNumber['Tuesday'];
} else {
  $tuesdayCardsGenerated = 0;
}
$height = $tuesdayCardsGenerated / 6;
echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const pinkCardGeneratedMondaySliderTwo = document.querySelector('.rightPaneSecondDivisionBottomTopRightTwoBottomBar');
              pinkCardGeneratedMondaySliderTwo.style.setProperty('--end-height', '" . $height . "em');
          });
      </script>";

if (isset($weekNumber['Wednesday'])) {
  $wednesdayCardsGenerated = $weekNumber['Wednesday'];
} else {
  $wednesdayCardsGenerated = 0;
}
$height = $wednesdayCardsGenerated / 6;
echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const pinkCardGeneratedMondaySliderThree = document.querySelector('.rightPaneSecondDivisionBottomTopRightThreeBottomBar');
              pinkCardGeneratedMondaySliderThree.style.setProperty('--end-height', '" . $height . "em');
          });
      </script>";

if (isset($weekNumber['Thursday'])) {
  $thursdayCardsGenerated = $weekNumber['Thursday'];
} else {
  $thursdayCardsGenerated = 0;
}
$height = $thursdayCardsGenerated / 6;
echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const pinkCardGeneratedMondaySliderFour = document.querySelector('.rightPaneSecondDivisionBottomTopRightFourBottomBar');
              pinkCardGeneratedMondaySliderFour.style.setProperty('--end-height', '" . $height . "em');
          });
      </script>";

if (isset($weekNumber['Friday'])) {
  $fridayCardsGenerated = $weekNumber['Friday'];
} else {
  $fridayCardsGenerated = 0;
}
$height = $fridayCardsGenerated / 6;

echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              const pinkCardGeneratedMondaySliderFive = document.querySelector('.rightPaneSecondDivisionBottomTopRightFiveBottomBar');
              pinkCardGeneratedMondaySliderFive.style.setProperty('--end-height', '" . $height . "em');
          });
      </script>";


// Close the connection
mysqli_close($connection);


?>



<!DOCTYPE html>
<html>

<head>
  <title> ADMIN </title>
  <link rel="stylesheet" href="adminPage5.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
          <img id="adminProfile" src="../admin/adminProfile/mani@gmail.com.png"> <!--http://localhost/3%20BUS%20TICKET%20Project/admin/adminProfile/<?php echo $imageSrc; ?>-->
        </div>
        <div class="adminNameAndMail">
          <div class="adminName">
            <p class="hover-effect hiText" style="font-weight: 500;"> Hi, </p>
            <p class="hover-effect adminNameText" style="font-weight: 900;"> &nbsp <?php echo $adminName; ?> </p>
          </div>
          <div class="adminMail">
            <p class="font-color hover-effect adminMailText"> <?php echo $adminMail; ?> </p>
          </div>
        </div>
      </div>
      <div class="sideNavigationBarList">
        <ul>
          <li>
            <div class="item1">
              <div class="item1Top">
                <div class="item1TopLeft">
                  <i class="fa-regular fa-address-card"></i>
                </div>
                <div class="item1TopRight">
                  <p> Bus Card </p>
                </div>
              </div>
              <div class="item1Bottom">
                <div class="item1BottomOne">
                  <div class="item1BottomLeftOne">
                    <i class="fa-solid fa-square-plus"></i>
                  </div>
                  <a class="sideNavbarContents hover-effect"
                    onclick="showAddBusCardPane()">
                    <script>
                      function showAddBusCardPane() {
                        document.getElementById('addBusCardDiv').style.display = 'flex';
                        document.getElementById('editBusCardDiv').style.display = 'none';
                        document.getElementById('viewBusCardDiv').style.display = 'none';
                        document.getElementById('paymentHistoryDiv').style.display = 'none';
                        document.getElementById('addBusDiv').style.display = 'none';
                        document.getElementById('editBusDiv').style.display = 'none';
                        document.getElementById('viewBusDiv').style.display = 'none';
                        document.getElementById('locationDiv').style.display = 'none';
                        document.getElementById('addstaffDiv').style.display = 'none';
                        document.getElementById('editstaffDiv').style.display = 'none';
                        document.getElementById('viewstaffDiv').style.display = 'none';
                        document.getElementById('inboxDiv').style.display = 'none';
                        document.getElementById('sendNotificationsDiv').style.display = 'none';
                        document.getElementById('editProfileDiv').style.display = 'none';
                        document.getElementById('rightPaneFirstDivision').style.display = 'none';
                        document.getElementById('rightPaneSecondDivision').style.display = 'none';
                        document.getElementById('rightPane').style.height = '60em';
                        // document.body.style.overflow = 'hidden'; 
                        // document.getElementById('allContainer').style.height = '62em';
                      }
                    </script>
                    <div class="item1BottomRightOne">
                      Add Bus Card
                    </div>
                  </a>
                </div>
                <div class="item1BottomTwo">
                  <div class="item1BottomLeftTwo">
                    <i class="fa-solid fa-pen-to-square"></i>
                  </div>
                  <a class="sideNavbarContents hover-effect"
                    onclick="showEditBusCardPane()">
                    <script>
                      function showEditBusCardPane() {
                        document.getElementById('addBusCardDiv').style.display = 'none';
                        document.getElementById('editBusCardDiv').style.display = 'flex';
                        document.getElementById('viewBusCardDiv').style.display = 'none';
                        document.getElementById('paymentHistoryDiv').style.display = 'none';
                        document.getElementById('addBusDiv').style.display = 'none';
                        document.getElementById('editBusDiv').style.display = 'none';
                        document.getElementById('viewBusDiv').style.display = 'none';
                        document.getElementById('locationDiv').style.display = 'none';
                        document.getElementById('addstaffDiv').style.display = 'none';
                        document.getElementById('editstaffDiv').style.display = 'none';
                        document.getElementById('viewstaffDiv').style.display = 'none';
                        document.getElementById('inboxDiv').style.display = 'none';
                        document.getElementById('sendNotificationsDiv').style.display = 'none';
                        document.getElementById('editProfileDiv').style.display = 'none';
                        document.getElementById('rightPaneFirstDivision').style.display = 'none';
                        document.getElementById('rightPaneSecondDivision').style.display = 'none';
                        document.getElementById('rightPane').style.height = '48.5em';
                        document.body.style.overflow = 'hidden';
                      }
                    </script>
                    <div class="item1BottomRightTwo">
                      Edit Bus Card
                    </div>
                  </a>
                </div>
                <div class="item1BottomThree">
                  <div class="item1BottomLeftThree">
                    <i class="fa-solid fa-eye"></i>
                  </div>
                  <a class="sideNavbarContents hover-effect"
                    onclick="showViewBusCardPane()">
                    <script>
                      function showViewBusCardPane() {
                        document.getElementById('addBusCardDiv').style.display = 'none';
                        document.getElementById('editBusCardDiv').style.display = 'none';
                        document.getElementById('viewBusCardDiv').style.display = 'flex';
                        document.getElementById('paymentHistoryDiv').style.display = 'none';
                        document.getElementById('addBusDiv').style.display = 'none';
                        document.getElementById('editBusDiv').style.display = 'none';
                        document.getElementById('viewBusDiv').style.display = 'none';
                        document.getElementById('locationDiv').style.display = 'none';
                        document.getElementById('addstaffDiv').style.display = 'none';
                        document.getElementById('editstaffDiv').style.display = 'none';
                        document.getElementById('viewstaffDiv').style.display = 'none';
                        document.getElementById('inboxDiv').style.display = 'none';
                        document.getElementById('sendNotificationsDiv').style.display = 'none';
                        document.getElementById('editProfileDiv').style.display = 'none';
                        document.getElementById('rightPaneFirstDivision').style.display = 'none';
                        document.getElementById('rightPaneSecondDivision').style.display = 'none';
                        document.getElementById('rightPane').style.height = '48.5em';
                        document.body.style.overflow = 'hidden';
                      }
                    </script>
                    <div class="item1BottomRightThree">
                      View Bus Cards
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </li>
          <li>
            <div class="item6" id="item6">
              <div class="item6Top">
                <div class="item6TopLeft">
                  <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <a class="sideNavbarContents hover-effect"
                  onclick="showPaymentHistoryPane()">
                  <script>
                    function showPaymentHistoryPane() {
                      document.getElementById('addBusCardDiv').style.display = 'none';
                      document.getElementById('editBusCardDiv').style.display = 'none';
                      document.getElementById('viewBusCardDiv').style.display = 'none';
                      document.getElementById('paymentHistoryDiv').style.display = 'flex';
                      document.getElementById('addBusDiv').style.display = 'none';
                      document.getElementById('editBusDiv').style.display = 'none';
                      document.getElementById('viewBusDiv').style.display = 'none';
                      document.getElementById('locationDiv').style.display = 'none';
                      document.getElementById('addstaffDiv').style.display = 'none';
                      document.getElementById('editstaffDiv').style.display = 'none';
                      document.getElementById('viewstaffDiv').style.display = 'none';
                      document.getElementById('inboxDiv').style.display = 'none';
                      document.getElementById('sendNotificationsDiv').style.display = 'none';
                      document.getElementById('editProfileDiv').style.display = 'none';
                      document.getElementById('rightPaneFirstDivision').style.display = 'none';
                      document.getElementById('rightPaneSecondDivision').style.display = 'none';
                      document.getElementById('rightPane').style.height = '48.5em';
                      document.body.style.overflow = 'hidden';
                    }
                  </script>
                  <div class="item6TopRight">
                    <p> Payment History </p>
                  </div>
                </a>
              </div>
            </div>
          </li>
          <li>
            <div class="item2">
              <div class="item2Top">
                <div class="item2TopLeft">
                  <i class="fa-solid fa-bus"></i>
                </div>
                <div class="item2TopRight">
                  <p> Bus </p>
                </div>
              </div>
              <div class="item2Bottom">
                <div class="item2BottomOne">
                  <div class="item2BottomLeftOne">
                    <i class="fa-solid fa-square-plus"></i>
                  </div>
                  <a class="sideNavbarContents hover-effect"
                    onclick="showAddBusPane()">
                    <script>
                      function showAddBusPane() {
                        document.getElementById('addBusCardDiv').style.display = 'none';
                        document.getElementById('editBusCardDiv').style.display = 'none';
                        document.getElementById('viewBusCardDiv').style.display = 'none';
                        document.getElementById('paymentHistoryDiv').style.display = 'none';
                        document.getElementById('addBusDiv').style.display = 'flex';
                        document.getElementById('editBusDiv').style.display = 'none';
                        document.getElementById('viewBusDiv').style.display = 'none';
                        document.getElementById('locationDiv').style.display = 'none';
                        document.getElementById('addstaffDiv').style.display = 'none';
                        document.getElementById('editstaffDiv').style.display = 'none';
                        document.getElementById('viewstaffDiv').style.display = 'none';
                        document.getElementById('inboxDiv').style.display = 'none';
                        document.getElementById('sendNotificationsDiv').style.display = 'none';
                        document.getElementById('editProfileDiv').style.display = 'none';
                        document.getElementById('rightPaneFirstDivision').style.display = 'none';
                        document.getElementById('rightPaneSecondDivision').style.display = 'none';
                        document.getElementById('rightPane').style.height = '48.5em';
                        document.body.style.overflow = 'hidden';
                      }
                    </script>
                    <div class="item2BottomRightOne">
                      Add Bus
                    </div>
                  </a>
                </div>
                <div class="item2BottomTwo">
                  <div class="item2BottomLeftTwo">
                    <i class="fa-solid fa-pen-to-square"></i>
                  </div>
                  <a class="sideNavbarContents hover-effect"
                    onclick="showEditBusPane()">
                    <script>
                      function showEditBusPane() {
                        document.getElementById('addBusCardDiv').style.display = 'none';
                        document.getElementById('editBusCardDiv').style.display = 'none';
                        document.getElementById('viewBusCardDiv').style.display = 'none';
                        document.getElementById('paymentHistoryDiv').style.display = 'none';
                        document.getElementById('addBusDiv').style.display = 'none';
                        document.getElementById('editBusDiv').style.display = 'flex';
                        document.getElementById('viewBusDiv').style.display = 'none';
                        document.getElementById('locationDiv').style.display = 'none';
                        document.getElementById('addstaffDiv').style.display = 'none';
                        document.getElementById('editstaffDiv').style.display = 'none';
                        document.getElementById('viewstaffDiv').style.display = 'none';
                        document.getElementById('inboxDiv').style.display = 'none';
                        document.getElementById('sendNotificationsDiv').style.display = 'none';
                        document.getElementById('editProfileDiv').style.display = 'none';
                        document.getElementById('rightPaneFirstDivision').style.display = 'none';
                        document.getElementById('rightPaneSecondDivision').style.display = 'none';
                        document.getElementById('rightPane').style.height = '48.5em';
                        document.body.style.overflow = 'hidden';
                      }
                    </script>
                    <div class="item2BottomRightTwo">
                      Edit Bus Details
                    </div>
                  </a>
                </div>
                <div class="item2BottomThree">
                  <div class="item2BottomLeftThree">
                    <i class="fa-solid fa-eye"></i>
                  </div>
                  <a class="sideNavbarContents hover-effect"
                    onclick="showViewBusPane()">
                    <script>
                      function showViewBusPane() {
                        document.getElementById('addBusCardDiv').style.display = 'none';
                        document.getElementById('editBusCardDiv').style.display = 'none';
                        document.getElementById('viewBusCardDiv').style.display = 'none';
                        document.getElementById('paymentHistoryDiv').style.display = 'none';
                        document.getElementById('addBusDiv').style.display = 'none';
                        document.getElementById('editBusDiv').style.display = 'none';
                        document.getElementById('viewBusDiv').style.display = 'flex';
                        document.getElementById('locationDiv').style.display = 'none';
                        document.getElementById('addstaffDiv').style.display = 'none';
                        document.getElementById('editstaffDiv').style.display = 'none';
                        document.getElementById('viewstaffDiv').style.display = 'none';
                        document.getElementById('inboxDiv').style.display = 'none';
                        document.getElementById('sendNotificationsDiv').style.display = 'none';
                        document.getElementById('editProfileDiv').style.display = 'none';
                        document.getElementById('rightPaneFirstDivision').style.display = 'none';
                        document.getElementById('rightPaneSecondDivision').style.display = 'none';
                        document.getElementById('rightPane').style.height = '48.5em';
                        document.body.style.overflow = 'hidden';
                      }
                    </script>
                    <div class="item2BottomRightThree">
                      View Bus Details
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </li>
          <li>
            <div class="item7" id="item7">
              <div class="item7Top">
                <div class="item7TopLeft">
                  <i class="fa-solid fa-location-dot"></i>
                </div>
                <a class="sideNavbarContents hover-effect"
                  onclick="showLocationPane()">
                  <script>
                    function showLocationPane() {
                      document.getElementById('addBusCardDiv').style.display = 'none';
                      document.getElementById('editBusCardDiv').style.display = 'none';
                      document.getElementById('viewBusCardDiv').style.display = 'none';
                      document.getElementById('paymentHistoryDiv').style.display = 'none';
                      document.getElementById('addBusDiv').style.display = 'none';
                      document.getElementById('editBusDiv').style.display = 'none';
                      document.getElementById('viewBusDiv').style.display = 'none';
                      document.getElementById('locationDiv').style.display = 'flex';
                      document.getElementById('addstaffDiv').style.display = 'none';
                      document.getElementById('editstaffDiv').style.display = 'none';
                      document.getElementById('viewstaffDiv').style.display = 'none';
                      document.getElementById('inboxDiv').style.display = 'none';
                      document.getElementById('sendNotificationsDiv').style.display = 'none';
                      document.getElementById('editProfileDiv').style.display = 'none';
                      document.getElementById('rightPaneFirstDivision').style.display = 'none';
                      document.getElementById('rightPaneSecondDivision').style.display = 'none';
                      document.getElementById('rightPane').style.height = '48.5em';
                      document.body.style.overflow = 'hidden';
                    }
                  </script>
                  <div class="item7TopRight">
                    <p> Location </p>
                  </div>
                </a>
              </div>
            </div>
          </li>
          <li>
            <div class="item3">
              <div class="item3Top">
                <div class="item3TopLeft">
                  <i class="fa-solid fa-user"></i>
                </div>
                <div class="item3TopRight">
                  <p> Staff </p>
                </div>
              </div>
              <div class="item3Bottom">
                <div class="item3BottomOne">
                  <div class="item3BottomLeftOne">
                    <i class="fa-solid fa-square-plus"></i>
                  </div>
                  <a class="sideNavbarContents hover-effect"
                    onclick="showAddStaffPane()">
                    <script>
                      function showAddStaffPane() {
                        document.getElementById('addBusCardDiv').style.display = 'none';
                        document.getElementById('editBusCardDiv').style.display = 'none';
                        document.getElementById('viewBusCardDiv').style.display = 'none';
                        document.getElementById('paymentHistoryDiv').style.display = 'none';
                        document.getElementById('addBusDiv').style.display = 'none';
                        document.getElementById('editBusDiv').style.display = 'none';
                        document.getElementById('viewBusDiv').style.display = 'none';
                        document.getElementById('locationDiv').style.display = 'none';
                        document.getElementById('addstaffDiv').style.display = 'flex';
                        document.getElementById('editstaffDiv').style.display = 'none';
                        document.getElementById('viewstaffDiv').style.display = 'none';
                        document.getElementById('inboxDiv').style.display = 'none';
                        document.getElementById('sendNotificationsDiv').style.display = 'none';
                        document.getElementById('editProfileDiv').style.display = 'none';
                        document.getElementById('rightPaneFirstDivision').style.display = 'none';
                        document.getElementById('rightPaneSecondDivision').style.display = 'none';
                        document.getElementById('rightPane').style.height = '48.5em';
                        document.body.style.overflow = 'hidden';
                      }
                    </script>
                    <div class="item3BottomRightOne">
                      Add Staff
                    </div>
                  </a>
                </div>
                <div class="item3BottomTwo">
                  <div class="item3BottomLeftTwo">
                    <i class="fa-solid fa-pen-to-square"></i>
                  </div>
                  <a class="sideNavbarContents hover-effect"
                    onclick="showEditStaffPane()">
                    <script>
                      function showEditStaffPane() {
                        document.getElementById('addBusCardDiv').style.display = 'none';
                        document.getElementById('editBusCardDiv').style.display = 'none';
                        document.getElementById('viewBusCardDiv').style.display = 'none';
                        document.getElementById('paymentHistoryDiv').style.display = 'none';
                        document.getElementById('addBusDiv').style.display = 'none';
                        document.getElementById('editBusDiv').style.display = 'none';
                        document.getElementById('viewBusDiv').style.display = 'none';
                        document.getElementById('locationDiv').style.display = 'none';
                        document.getElementById('addstaffDiv').style.display = 'none';
                        document.getElementById('editstaffDiv').style.display = 'flex';
                        document.getElementById('viewstaffDiv').style.display = 'none';
                        document.getElementById('inboxDiv').style.display = 'none';
                        document.getElementById('sendNotificationsDiv').style.display = 'none';
                        document.getElementById('editProfileDiv').style.display = 'none';
                        document.getElementById('rightPaneFirstDivision').style.display = 'none';
                        document.getElementById('rightPaneSecondDivision').style.display = 'none';
                        document.getElementById('rightPane').style.height = '48.5em';
                        document.body.style.overflow = 'hidden';
                      }
                    </script>
                    <div class="item3BottomRightTwo">
                      Edit Staff Details
                    </div>
                  </a>
                </div>
                <div class="item3BottomThree">
                  <div class="item3BottomLeftThree">
                    <i class="fa-solid fa-eye"></i>
                  </div>
                  <a class="sideNavbarContents hover-effect"
                    onclick="showViewStaffPane()">
                    <script>
                      function showViewStaffPane() {
                        document.getElementById('addBusCardDiv').style.display = 'none';
                        document.getElementById('editBusCardDiv').style.display = 'none';
                        document.getElementById('viewBusCardDiv').style.display = 'none';
                        document.getElementById('paymentHistoryDiv').style.display = 'none';
                        document.getElementById('addBusDiv').style.display = 'none';
                        document.getElementById('editBusDiv').style.display = 'none';
                        document.getElementById('viewBusDiv').style.display = 'none';
                        document.getElementById('locationDiv').style.display = 'none';
                        document.getElementById('addstaffDiv').style.display = 'none';
                        document.getElementById('editstaffDiv').style.display = 'none';
                        document.getElementById('viewstaffDiv').style.display = 'flex';
                        document.getElementById('inboxDiv').style.display = 'none';
                        document.getElementById('sendNotificationsDiv').style.display = 'none';
                        document.getElementById('editProfileDiv').style.display = 'none';
                        document.getElementById('rightPaneFirstDivision').style.display = 'none';
                        document.getElementById('rightPaneSecondDivision').style.display = 'none';
                        document.getElementById('rightPane').style.height = '48.5em';
                        document.body.style.overflow = 'hidden';
                      }
                    </script>
                    <div class="item3BottomRightThree">
                      View Staff Details
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </li>
          <li>
            <div class="item4">
              <div class="item4Top">
                <div class="item4TopLeft">
                  <i class="fa-solid fa-bell"></i>
                </div>
                <div class="item4TopRight">
                  <p> Manage Notifications </p>
                </div>
              </div>
              <div class="item4Bottom">
                <div class="item4BottomOne">
                  <div class="item4BottomLeftOne">
                    <i class="fa-solid fa-envelope"></i>
                  </div>
                  <a class="sideNavbarContents hover-effect"
                    onclick="showInboxPane()">
                    <script>
                      function showInboxPane() {
                        document.getElementById('addBusCardDiv').style.display = 'none';
                        document.getElementById('editBusCardDiv').style.display = 'none';
                        document.getElementById('viewBusCardDiv').style.display = 'none';
                        document.getElementById('paymentHistoryDiv').style.display = 'none';
                        document.getElementById('addBusDiv').style.display = 'none';
                        document.getElementById('editBusDiv').style.display = 'none';
                        document.getElementById('viewBusDiv').style.display = 'none';
                        document.getElementById('locationDiv').style.display = 'none';
                        document.getElementById('addstaffDiv').style.display = 'none';
                        document.getElementById('editstaffDiv').style.display = 'none';
                        document.getElementById('viewstaffDiv').style.display = 'none';
                        document.getElementById('inboxDiv').style.display = 'flex';
                        document.getElementById('sendNotificationsDiv').style.display = 'none';
                        document.getElementById('editProfileDiv').style.display = 'none';
                        document.getElementById('rightPaneFirstDivision').style.display = 'none';
                        document.getElementById('rightPaneSecondDivision').style.display = 'none';
                        document.getElementById('rightPane').style.height = '48.5em';
                        document.body.style.overflow = 'hidden';
                      }
                    </script>
                    <div class="item4BottomRightOne">
                      Inbox
                    </div>
                  </a>
                </div>
                <div class="item4BottomTwo">
                  <div class="item4BottomLeftTwo">
                    <i class="fa-regular fa-paper-plane"></i>
                  </div>
                  <a class="sideNavbarContents hover-effect"
                    onclick="showSendNotificationsPane()">
                    <script>
                      function showSendNotificationsPane() {
                        document.getElementById('addBusCardDiv').style.display = 'none';
                        document.getElementById('editBusCardDiv').style.display = 'none';
                        document.getElementById('viewBusCardDiv').style.display = 'none';
                        document.getElementById('paymentHistoryDiv').style.display = 'none';
                        document.getElementById('addBusDiv').style.display = 'none';
                        document.getElementById('editBusDiv').style.display = 'none';
                        document.getElementById('viewBusDiv').style.display = 'none';
                        document.getElementById('locationDiv').style.display = 'none';
                        document.getElementById('addstaffDiv').style.display = 'none';
                        document.getElementById('editstaffDiv').style.display = 'none';
                        document.getElementById('viewstaffDiv').style.display = 'none';
                        document.getElementById('inboxDiv').style.display = 'none';
                        document.getElementById('sendNotificationsDiv').style.display = 'flex';
                        document.getElementById('editProfileDiv').style.display = 'none';
                        document.getElementById('rightPaneFirstDivision').style.display = 'none';
                        document.getElementById('rightPaneSecondDivision').style.display = 'none';
                        document.getElementById('rightPane').style.height = '48.5em';
                        document.body.style.overflow = 'hidden';
                      }
                    </script>
                    <div class="item4BottomRightTwo">
                      Send Notifications
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </li>
          <li>
            <div class="item5">
              <div class="item5Top">
                <div class="item5TopLeft">
                  <i class="fa-solid fa-user-pen"></i>
                </div>
                <a class="sideNavbarContents hover-effect"
                  onclick="showEditProfilePane()">
                  <script>
                    function showEditProfilePane() {
                      document.getElementById('addBusCardDiv').style.display = 'none';
                      document.getElementById('editBusCardDiv').style.display = 'none';
                      document.getElementById('viewBusCardDiv').style.display = 'none';
                      document.getElementById('paymentHistoryDiv').style.display = 'none';
                      document.getElementById('addBusDiv').style.display = 'none';
                      document.getElementById('editBusDiv').style.display = 'none';
                      document.getElementById('viewBusDiv').style.display = 'none';
                      document.getElementById('locationDiv').style.display = 'none';
                      document.getElementById('addstaffDiv').style.display = 'none';
                      document.getElementById('editstaffDiv').style.display = 'none';
                      document.getElementById('viewstaffDiv').style.display = 'none';
                      document.getElementById('inboxDiv').style.display = 'none';
                      document.getElementById('sendNotificationsDiv').style.display = 'none';
                      document.getElementById('editProfileDiv').style.display = 'flex';
                      document.getElementById('rightPaneFirstDivision').style.display = 'none';
                      document.getElementById('rightPaneSecondDivision').style.display = 'none';
                      // document.getElementById('sideNavBarRightPaneBox').style.display = 'flex';
                      document.getElementById('rightPane').style.height = '48.5em';
                      document.body.style.overflow = 'hidden';
                    }
                  </script>
                  <div class="item5TopRight">
                    <p> Edit Profile </p>
                  </div>
                </a>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="rightPane" id="rightPane">
      <div class="dashboardTextAndIcons">
        <a class="sideNavbarContents hover-effect"
          onclick="showDashboardPane()">
          <script>
            function showDashboardPane() {
              document.getElementById('addBusCardDiv').style.display = 'none';
              document.getElementById('editBusCardDiv').style.display = 'none';
              document.getElementById('viewBusCardDiv').style.display = 'none';
              document.getElementById('paymentHistoryDiv').style.display = 'none';
              document.getElementById('addBusDiv').style.display = 'none';
              document.getElementById('editBusDiv').style.display = 'none';
              document.getElementById('viewBusDiv').style.display = 'none';
              document.getElementById('locationDiv').style.display = 'none';
              document.getElementById('addstaffDiv').style.display = 'none';
              document.getElementById('editstaffDiv').style.display = 'none';
              document.getElementById('viewstaffDiv').style.display = 'none';
              document.getElementById('inboxDiv').style.display = 'none';
              document.getElementById('sendNotificationsDiv').style.display = 'none';
              document.getElementById('editProfileDiv').style.display = 'none';
              document.getElementById('rightPaneFirstDivision').style.display = 'flex';
              document.getElementById('rightPaneSecondDivision').style.display = 'flex';
              document.getElementById('rightPane').style.height = '99em';
              document.body.style.overflow = 'auto';
            }
          </script>
          <div class="dashboardText hover-effect">
            Dashboard
          </div>
        </a>
        <div class="topIcons">
          <a class="sideNavbarContents hover-effect"
            onclick="showInboxPane()">
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



      <!DOCTYPE html>
      <html lang="en">

      <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
      </head>

      <body>

        <!-- Add Bus Card Div  -->
        <div class="addBusCardDiv" id="addBusCardDiv">
          <form action="" id="applicationForm" method="post">
            <div class="headrightpanel">ADD BUS CARD DETAILS</div>
            <div class="formLeftRight">
              <div class="applyFormLeft">
                <input type="text" id="studentName" name="studentName" placeholder="Enter your name" required><br><br>
                <input type="email" id="studentEmail" name="studentEmail" placeholder="Enter your email" required><br><br>
                <input type="text" id="studentId" name="studentId" placeholder="Enter your student ID" required><br><br>
              </div>

              <div class="applyFormRight">
                <input type="password" id="studentPassword" name="studentPassword" placeholder="Enter your password" required><br><br>
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
                </select><br>

                <div class="cardtyperow">
                  <p class="cardtype">Card type</p>
                  <div class="cardColorOptionContainer" id="cardColorOptionContainer">
                    <div class="cardColorOptionOne">
                      <p class="cardColorOptionContainerText">Yellow</p>
                      <input type="radio" name="cardColor" value="Yellow" required>
                    </div>
                    <div class="cardColorOptionTwo">
                      <p class="cardColorOptionContainerText">Pink</p>
                      <input type="radio" name="cardColor" value="Pink">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="submit" name="submitButton" value="Submit" id="addCardSubmit">
          </form>

          <div class="routeBox">
            <div class="innerRouteBox">
              <p class="routeBoxText hover-effect">◉ Route 1: Thoppumpadi (7.20 am) -> Thevara -> Manorama junction -> Kundanoor (7.35 am) -> Maradu -> Petta (7.40 am) -> Campus</p>
              <p class="routeBoxText hover-effect">◉ Route 2: Kadavanthara (7.20 am) -> Kumaranasan junction -> Janatha (7.25 am) -> Vyttila -> Vadakkekotta (7.40 am) -> Campus</p>
              <p class="routeBoxText hover-effect">◉ Route 3: Chottanikara temple stop (7.20 am) -> Thiruvankulam (7.30 am) -> Tripunithara stand (7.45 am) -> Karigachira (7.50 am) -> Campus</p>
              <p class="routeBoxText hover-effect">◉ Route 4: Highcourt (7.20 am) -> Kacheripadi -> Kaloor stand (7.30 am) -> Kaloor stadium -> Vazhakala (7.40) -> Padamugal -> Campus</p>
              <p class="routeBoxText hover-effect">◉ Route 5: Angamaly (7.00 am) -> Kariyad -> Athani (7.10 am) -> Dhesam -> Paravoor junction -> Thottakattukara -> Aluva (7.25 am) -> Garage -> Ambattukavu -> Mutton -> Premier (7.40 am) -> BMC -> Campus</p>
              <p class="routeBoxText hover-effect">◉ Route 6: Koonamavu (7.15 am) -> Varapuzha -> Cheranallur -> Thaikavu -> Edappally kunnumpuram (7.35 am) -> Edappally Lulu (7.40 am) -> Pathadippalam -> University jn. -> Changapuzha nagar -> HMT -> Toshiba -> Vallathol -> Olimugal church -> Campus</p>
              <p class="routeBoxText hover-effect">◉ Route 7: Unichira (7.20 am) -> Edappally Church (7.30 am) -> Changapuzha park -> Palarivattam -> Mamangalam -> Pipe line (7.40 am) -> Alinchodu -> Chembumukku -> Campus</p>
              <p class="routeBoxText hover-effect">◉ Route 8: Perumbavoor (7.00 am) -> Ponjassery -> Kavungaparambu -> Kitex (7.35 am) -> Kizakkambalam -> Pallikara -> Wonderla -> Vikasvani -> Tengode -> Edachira (7.45 am) -> Kakkanad Indian coffee house (7.50 am) -> More -> CSEZ -> Campus</p>
            </div>
          </div>
        </div>

        <?php
        if (session_status() === PHP_SESSION_NONE) {
          session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
          unset($_SESSION['student_form_submitted']);
          $_POST = array();  // Clear any stored POST data
        }

        $connection = new mysqli("localhost", "root", "", "buscardmanagementsystem");
        if ($connection->connect_error) {
          die("Connection failed: " . $connection->connect_error);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
          unset($_SESSION['student_form_submitted']);
        }

        if (isset($_POST['submitButton']) && !isset($_SESSION['student_form_submitted'])) {
          $studentName = $_POST['studentName'];
          $studentId = $_POST['studentId'];
          $routeNumber = $_POST['routeNumber'];
          $cardType = $_POST['cardColor'];
          $studentMail = $_POST['studentEmail'];
          $password = $_POST['studentPassword'];

          // Comprehensive duplicate check across all relevant tables
          $duplicateFound = false;
          $duplicateMessage = "";

          // Check studentidandpassword table
          $checkUser = "SELECT * FROM studentidandpassword WHERE id = ? OR mail = ?";
          $stmt = $connection->prepare($checkUser);
          $stmt->bind_param("ss", $studentId, $studentMail);
          $stmt->execute();
          $result = $stmt->get_result();
          if ($result->num_rows > 0) {
            $duplicateFound = true;
            $duplicateMessage = "User already exists with this Student ID or Email in the system!";
          }
          $stmt->close();

          // Check yellowStudent table
          if (!$duplicateFound) {
            $checkExist = "SELECT * FROM studentcard WHERE studentId = ?";
            $stmt = $connection->prepare($checkExist);
            $stmt->bind_param("s", $studentId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
              $duplicateFound = true;
              $duplicateMessage = "Student already has a card assigned!";
            }
            $stmt->close();
          }

          if ($duplicateFound) {
            echo "<div id='alertMessage' class='alert-message'>
                $duplicateMessage
                <button onclick='closeAlert()' class='close-btn'>×</button>
              </div>";
          } else {
            // Start transaction
            $connection->begin_transaction();
            try {
              $isSuccess = true;

              // Check route number
              $sql7 = "SELECT * FROM buscardcount WHERE busNumber = ? FOR UPDATE";
              $stmt = $connection->prepare($sql7);
              $stmt->bind_param("s", $routeNumber);
              $stmt->execute();
              $result7 = $stmt->get_result();
              $stmt->close();

              if ($result7 && $result7->num_rows > 0) {
                $row7 = $result7->fetch_assoc();
                $increasePink = $row7['pinkCardCount'];
                $increaseYellow = $row7['yellowCardCount'];

                $sql = "INSERT INTO studentidandpassword(id, password, name, mail, is_active) VALUES (?, ?, ?, ?, '1')";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param("ssss", $studentId, $password, $studentName, $studentMail);
                $stmt->execute();

                if ($cardType == 'Yellow') {
                  do {
                    // Generate a random 8-digit number for the card
                    $ypCardNumber = random_int(10000000, 99999999);

                    // Check if the generated card number already exists in the studentcard table
                    $sql17 = "SELECT cardNumber FROM studentcard WHERE cardNumber = ?";
                    $stmt = $connection->prepare($sql17);
                    $stmt->bind_param("s", $ypCardNumber);
                    $stmt->execute();
                    $result17 = $stmt->get_result();
                  } while ($result17->num_rows > 0); // Repeat if the card number already exists

                  // Insert into studentcard table
                  $stmt = $connection->prepare("INSERT INTO studentcard (studentId, card_type, stop, busNumber, cardNumber, status) 
                                                VALUES (?, 'yellow', NULL, ?, ?, 'not paid')");
                  $stmt->bind_param("sss", $studentId, $routeNumber, $ypCardNumber);
                  $stmt->execute();

                  // Update yellow card count
                  $increaseYellow++;
                  $sql8 = "UPDATE buscardcount SET yellowCardCount = '$increaseYellow' WHERE busNumber='$routeNumber'";
                  $result8 = mysqli_query($connection, $sql8);
                } else {
                  do {
                    // Generate a random 8-digit number for the card
                    $ypCardNumber = random_int(10000000, 99999999);

                    // Check if the generated card number already exists in the studentcard table
                    $sql17 = "SELECT cardNumber FROM studentcard WHERE cardNumber = ?";
                    $stmt = $connection->prepare($sql17);
                    $stmt->bind_param("s", $ypCardNumber);
                    $stmt->execute();
                    $result17 = $stmt->get_result();
                  } while ($result17->num_rows > 0); // Repeat if the card number already exists

                  // Insert into studentcard table
                  $stmt = $connection->prepare("INSERT INTO studentcard (studentId, card_type, stop, busNumber, cardNumber, status) 
                                              VALUES (?, 'pink', NULL, ?, ?, 'not paid')");
                  $stmt->bind_param("sss", $studentId, $route, $ypCardNumber);
                  $stmt->execute();

                  $increasePink++;
                  $sql8 = "UPDATE buscardcount SET pinkCardCount = '$increasePink' WHERE busNumber='$route'";
                  $result8 = mysqli_query($connection, $sql8);
                }

                // Insert welcome message
                $currentDateAndTime = date("Y-m-d H:i:s");
                $stmt = $connection->prepare("INSERT INTO inboxtable (fromMail, toMail, content, dateAndTime) VALUES ('mani@gmail.com', ?, 'Welcome to the bus card management system', '$currentDateAndTime')");
                $stmt->bind_param("s", $studentMail);
                $stmt->execute();
                $stmt->close();

                // Commit transaction
                $connection->commit();

                $_SESSION['student_form_submitted'] = true;

                // In your PHP file where you show the success message
                echo "<div id='successMessage' class='success-message'>
            Student data successfully added! Card number: " .
                  ($cardType == 'Yellow' ? $ypCardNumber : $ypCardNumber) . "
            <button onclick='closeAlert()' class='close-btn'>×</button>
            <button onclick='resetForm()' class='btn btn-primary'>Add Another Student</button>
            </div>";
              } else {
                throw new Exception("Route number not found");
              }
            } catch (Exception $e) {
              // Rollback transaction on error
              $connection->rollback();
              echo "<div id='alertMessage' class='alert-message'>
                    Error: " . $e->getMessage() . "
                    <button onclick='closeAlert()' class='close-btn'>×</button>
                  </div>";
            }
          }
        }

        // $connection->close();
        ?>

        <script>
          function closeAlert() {
            const messages = document.querySelectorAll('.success-message, .alert-message');
            messages.forEach(message => {
              message.style.display = 'none';
            });
          }
        </script>


        <!--  Edit student starts -->

        <div class="editBusCardDiv" id="editBusCardDiv" style="background-color: #ffffff00;">
          <?php
          $conn = new mysqli("localhost", "root", "", "buscardmanagementsystem");
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }

          $message = "";
          $messageType = "";
          $showEditForm = false;

          if (isset($_GET['edit']) && isset($_GET['type'])) {
            $studentId = $_GET['edit'];
            $studentType = $_GET['type'];

            $sql = "SELECT sp.name, sc.busNumber, sc.cardNumber, sp.password, sc.studentId, sc.card_type, sc.stop
                    FROM studentcard sc
                    JOIN studentidandpassword sp ON sc.studentId = sp.id
                    WHERE sc.studentId = '$studentId' AND sc.card_type = '$studentType'";
            $result = mysqli_query($conn, $sql);

            if ($result && $result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $currentName = $row['name'];
              $currentBusNumber = $row['busNumber'];
              $currentCardNumber = $row['cardNumber'];
              $currentPassword = $row['password'];
              $cardStop = $row['stop'];
              $currentCardType = $row['card_type'];
              $showEditForm = true;
            } else {
              $message = "Student not found.";
              $messageType = "error";
            }
          }


          if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateStudent'])) {
            $studentId = $_POST['studentId'];
            $studentType = $_POST['studentType'];
            $name = $_POST['name'];
            $busNumber = $_POST['busNumber'];
            $cardStop = $_POST['stop'];
            $password = $_POST['password'];

            // Check if cardType is set, provide a default if not
            $cardType = isset($_POST['cardType']) ? $_POST['cardType'] : 'yellow';

            $sql1 = "
                UPDATE studentcard 
                SET 
                    card_type = '$cardType', 
                    stop = '$cardStop', 
                    busNumber = '$busNumber'
                WHERE studentId = '$studentId'
            ";

            $sql2 = "
                UPDATE studentidandpassword 
                SET 
                    name = '$name', 
                    password = '$password'
                WHERE id = '$studentId'
            ";

            $result1 = mysqli_query($conn, $sql1);
            $result2 = mysqli_query($conn, $sql2);

            if ($result1 && $result2) {
              $message = "Student details updated successfully!";
              $messageType = "success";
              $showEditForm = false;
            } else {
              $message = "Failed to update student details: " . mysqli_error($conn);
              $messageType = "error";
              $showEditForm = true;
            }
          }




          // Delete Student
          if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteStudent'])) {
            $studentId = $_POST['studentId'];
            $studentType = $_POST['studentType'];

            $allowedTypes = ['yellow', 'pink'];
            if (!in_array($studentType, $allowedTypes)) {
              die("Invalid table type.");
            }

            try {
              // Enable exception mode for mysqli
              mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

              // Begin transaction
              $conn->begin_transaction();

              // Determine the table based on the student type
              $table = ($studentType === 'yellow' || $studentType === 'pink') ? 'studentcard' : '';

              // Validate table name
              $allowedTables = ['studentcard'];
              if (in_array($table, $allowedTables)) {
                $studentId = mysqli_real_escape_string($conn, $studentId);

                // Perform the delete operation
                $sql = "DELETE FROM $table WHERE studentId = '$studentId'";
                $result = $conn->query($sql);

                // If the student is deleted from studentcard, delete from studentidandpassword too
                if ($result) {
                  $sql2 = "DELETE FROM studentidandpassword WHERE id = '$studentId'";
                  $result2 = $conn->query($sql2);

                  $conn->commit();
                  $message = "Student data deleted successfully!";
                  $messageType = "success";
                  $showEditForm = false;
                } else {
                  throw new Exception("Error deleting record: " . $conn->error);
                }
              } else {
                throw new Exception("Invalid table name.");
              }
            } catch (Exception $e) {
              // Rollback transaction if any query fails
              $conn->rollback();
              $message = "Error: " . $e->getMessage();
              $messageType = "error";
            }
          }



          ?>


          <!-- Alert Messages -->
          <?php if (!empty($message)) : ?>
            <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
              <?php echo htmlspecialchars($message); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <!-- Edit Form -->
          <div id="editStudentDet" style="display: <?php echo $showEditForm ? 'block' : 'none'; ?>;">
            <form method="post">
              <input type="hidden" name="studentId" value="<?php echo htmlspecialchars($studentId ?? ''); ?>">
              <input type="hidden" name="studentType" value="<?php echo htmlspecialchars($studentType ?? ''); ?>">

              <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" id="studentnameF" class="form-control" value="<?php echo htmlspecialchars($currentName ?? ''); ?>" required>
              </div>
              <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" id="studentPasswordF" class="form-control" value="<?php echo htmlspecialchars($currentPassword ?? ''); ?>" required>
              </div>
              <div class="mb-3">
                <label>Bus Number</label>
                <input type="text" name="busNumber" id="studentbusNumberF" class="form-control" value="<?php echo htmlspecialchars($currentBusNumber ?? ''); ?>" required>
              </div>
              <div class="mb-3">
                <label>Stop</label>
                <input type="text" name="stop" id="studentStopF" class="form-control" value="<?php echo htmlspecialchars($cardStop ?? ''); ?>" required>
              </div>
              <div class="mb-2 pt-3">

                <label>Card type</label>
                <div class="cardColorOptionContainer" id="cardColorOptionContainer">

                  <div class="cardColorOptionOne">
                    <p class="cardColorOptionContainerText">Yellow</p>
                    <input type="radio" name="cardType" value="yellow" <?php echo ($currentCardType === 'yellow') ? 'checked' : ''; ?> required>
                  </div>
                  <div class="cardColorOptionTwo">
                    <p class="cardColorOptionContainerText">Pink</p>
                    <input type="radio" name="cardType" value="pink" <?php echo ($currentCardType === 'pink') ? 'checked' : ''; ?>>
                  </div>



                </div>
              </div>
              <button type="submit" class="btn btn-primary " name="updateStudent">Update</button>
              <button type="submit" class="btn btn-primary" name="deleteStudent">Delete</button>
              <button type="button" class="btn btn-secondary" onclick="cancelEdit()">Cancel</button>
            </form>
          </div>


          <script>
            function editStudent(studentId, type) {
              window.location.href = `?edit=${encodeURIComponent(studentId)}&type=${encodeURIComponent(type)}`;
            }

            function cancelEdit() {
              window.location.href = window.location.pathname;
            }

            // Smooth scroll to the form on load if editing
            if (<?php echo json_encode($showEditForm); ?>) {
              document.getElementById('editStudentDet').scrollIntoView({
                behavior: 'smooth'
              });
            }
          </script>

          <!-- Pagination Table Wrapper -->
          <div class="container-fluid my-4" id="busDet" style="display: <?php echo $showEditForm ? 'none' : 'block'; ?>;">
            <div class="card">
              <div class="card-header" style="background-color:rgb(232, 234, 236);">
                <h3 class="card-title">Edit Student Card</h3>
                <div class="card-tools">
                  <input type="text" class="form-control" id="searchBar"
                    placeholder="Search here..." onkeyup="filterTable()">
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                  <table class="table table-striped table-bordered" id="busTable">
                    <thead>
                      <tr>
                        <th>Student Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Bus Number</th>
                        <th>Card Number</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sql = "SELECT 
                      sc.studentId, 
                      sp.name, 
                      sp.mail, 
                      sc.busNumber, 
                      sc.cardNumber, 
                      sc.status, 
                      sc.card_type AS type
                  FROM 
                      studentcard sc
                  JOIN 
                      studentidandpassword sp ON sc.studentId = sp.id
                  WHERE 
                      sp.is_active = 1";

                      $result = mysqli_query($conn, $sql);

                      if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                          echo "<tr>
                                <td>" . htmlspecialchars($row['studentId'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['name'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['mail'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['busNumber'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['cardNumber'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['type'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['status'] ?? 'N/A') . "</td>                                
                                <td>
                                    <button class='btn btn-sm btn-primary' onclick='editStudent(\"" . htmlspecialchars($row['studentId']) . "\", \"" . htmlspecialchars($row['type']) . "\")'>Edit</button>
                                </td>
                            </tr>";
                        }
                      } else {
                        echo "<tr><td colspan='7' class='text-center'>No students found.</td></tr>";
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- End of Edit bus card div -->



        <!-- View Bus Card Divs -->

        <div class="viewBusCardDiv" id="viewBusCardDiv" style="background-color: #ffffff00;">
          <div class="container-fluid my-4" id="busDet" style="display: <?php echo $showEditForm ? 'none' : 'block'; ?>;">
            <div class="card">
              <div class="card-header" style="background-color:rgb(232, 234, 236);">
                <h3 class="card-title">View Student Card</h3>
                <div class="card-tools">
                  <input type="text" class="form-control" id="searchBar"
                    placeholder="Search here..." onkeyup="filterTable()">
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                  <table class="table table-striped table-bordered" id="busTable">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Bus Number</th>
                        <th>Card Number</th>
                        <th>Type</th>
                        <th>Status</th>

                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sql = "SELECT 
                      sc.studentId, 
                      sp.name, 
                      sp.mail, 
                      sc.busNumber, 
                      sc.cardNumber, 
                      sc.status, 
                      sc.card_type AS type
                  FROM 
                      studentcard sc
                  JOIN 
                      studentidandpassword sp ON sc.studentId = sp.id
                  WHERE 
                      sp.is_active = 1";

                      $result = mysqli_query($conn, $sql);

                      if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                          echo "<tr>
                                <td>" . htmlspecialchars($row['name'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['mail'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['busNumber'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['cardNumber'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['type'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['status'] ?? 'N/A') . "</td>
                               </tr>";
                        }
                      } else {
                        echo "<tr><td colspan='7' class='text-center'>No students found.</td></tr>";
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>

        </div>


        <!-- Payment History Table -->
        <div class="paymentHistoryDiv" id="paymentHistoryDiv" style="background-color: #ffffff00;">
          <div class="container-fluid my-4" id="busDet" style="display: <?php echo $showEditForm ? 'none' : 'block'; ?>;">
            <div class="card">
              <div class="card-header" style="background-color:rgb(232, 234, 236);">
                <h3 class="card-title">View Student Card</h3>
                <div class="card-tools">
                  <input type="text" class="form-control" id="searchBar"
                    placeholder="Search here..." onkeyup="filterTable()">
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                  <table class="table table-striped table-bordered" id="busTable">
                    <thead>
                      <tr>
                        <th>Student ID</th>
                        <th>Bus Number</th>
                        <th>Time</th>
                        <th>Amount</th>
                        <th>Stop</th>
                        <th>Card Type</th>
                      </tr>
                    </thead>

                    <tbody>

                      <?php
                      ob_start();
                      try {
                        $sql = "SELECT 
                            ph.ID,
                            ph.busNumber,
                            ph.TIME,
                            ph.Amount, 
                            ph.Stop,
                            sc.card_type
                        FROM 
                            paymenthistorytable ph
                        JOIN
                           studentcard sc ON ph.ID=sc.studentId
                        ORDER BY TIME DESC";  // Most recent payments first

                        $result = mysqli_query($conn, $sql);

                        if ($result) {
                          while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['ID'] ?? '') . "</td>
                                    <td>" . htmlspecialchars($row['busNumber'] ?? '') . "</td>
                                    <td class='time-column'>" . htmlspecialchars(date('Y-m-d H:i:s', strtotime($row['TIME'])) ?? '') . "</td>
                                    <td class='amount-column'>₹" . htmlspecialchars(number_format($row['Amount'], 2) ?? '') . "</td>
                                    <td>" . htmlspecialchars($row['Stop'] ?? '') . "</td>
                                    <td>" . htmlspecialchars($row['card_type'] ?? '') . "</td>

                                </tr>";
                          }
                        }
                      } catch (Exception $e) {
                        echo "Error: " . $e->getMessage();
                      }


                      ob_end_flush();
                      ?>


                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>

        </div>


        <div class="addBusDiv" id="addBusDiv">
          <form action="" id="applicationFormAddBus" method="post">
            <div class="headrightpanel">ADD BUS DETAILS</div>
            <input type="text" id="vehicleNumber" name="bus_id" placeholder="Enter Vehicle Number" required>
            <input type="text" id="busNumber" name="busNumber" placeholder="Enter Bus Number" required>
            <input type="date" id="insuranceDateUpto" name="insurance_date" placeholder="Enter Insurance date upto" required>
            <input type="date" id="pollutionDateUpto" name="pollution_date" placeholder="Enter Pollution date upto" required>
            <input type="text" id="fuelDetails" name="fuel" placeholder="Enter fuel details" required>
            <input type="submit" name="submitButton1" value="Submit">
          </form>

          <?php
          if (session_status() === PHP_SESSION_NONE) {
            session_start();
          }

          if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            unset($_SESSION['bus_form_submitted']);
            $_POST = array();
          }

          $connection = new mysqli("localhost", "root", "", "buscardmanagementsystem");
          if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
          }

          if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            unset($_SESSION['bus_form_submitted']);
          }

          if (isset($_POST['submitButton1']) && !isset($_SESSION['bus_form_submitted'])) {
            // Check if all required fields are set
            if (
              isset($_POST['bus_id']) && isset($_POST['busNumber']) &&
              isset($_POST['insurance_date']) && isset($_POST['pollution_date']) &&
              isset($_POST['fuel'])
            ) {

              $vehicleNum = $_POST['bus_id'];
              $busNumber = (int)$_POST['busNumber'];
              $insurance = $_POST['insurance_date'];
              $pollution = $_POST['pollution_date'];
              $fuel = $_POST['fuel'];

              // Debug output
              echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px 0;'>";
              echo "Received POST data:<br>";
              echo "Vehicle Number: " . $vehicleNum . "<br>";
              echo "Bus Number: " . $busNumber . "<br>";
              echo "Insurance Date: " . $insurance . "<br>";
              echo "Pollution Date: " . $pollution . "<br>";
              echo "Fuel: " . $fuel . "<br>";
              echo "</div>";

              // Comprehensive duplicate check
              $duplicateFound = false;
              $duplicateMessage = "";

              // Check if bus number already exists
              $checkNumExist = "SELECT * FROM busdetails WHERE busNumber = ?";
              $stmt = $connection->prepare($checkNumExist);
              $stmt->bind_param("i", $busNumber);
              $stmt->execute();
              $result = $stmt->get_result();
              if ($result->num_rows > 0) {
                $duplicateFound = true;
                $duplicateMessage = "Bus number already assigned!";
              }
              $stmt->close();

              if ($duplicateFound) {
                echo "<div id='alertMessage' class='alert-message'>
                    $duplicateMessage
                    <button onclick='closeAlert()' class='close-btn'>×</button>
                </div>";
              } else {
                // Start transaction
                $connection->begin_transaction();
                try {
                  // Insert values
                  $stmt = $connection->prepare("INSERT INTO busdetails (bus_id, busNumber, insurance_date, pollution_date, fuel) VALUES (?, ?, ?, ?, ?)");
                  $stmt->bind_param("sisss", $vehicleNum, $busNumber, $insurance, $pollution, $fuel);

                  if ($stmt->execute()) {
                    $connection->commit();
                    $_SESSION['bus_form_submitted'] = true;
                    echo "<div id='successMessage' class='success-message'>
                            Bus data successfully added! 
                            <button onclick='closeAlert()' class='close-btn'>×</button>
                            <button onclick='resetForm()' class='btn btn-primary'>Add Another Bus</button>
                        </div>";
                  } else {
                    throw new Exception("Failed to insert bus details");
                  }
                  $stmt->close();
                } catch (Exception $e) {
                  $connection->rollback();
                  echo "<div id='alertMessage' class='alert-message'>
                        Error: " . $e->getMessage() . "
                        <button onclick='closeAlert()' class='close-btn'>×</button>
                    </div>";
                }
              }
            } else {
              echo "<div id='alertMessage' class='alert-message'>
                Please fill in all required fields.
                <button onclick='closeAlert()' class='close-btn'>×</button>
            </div>";
            }
          }

          $connection->close();
          ?>

          <script>
            function closeAlert() {
              const messages = document.querySelectorAll('.success-message, .alert-message');
              messages.forEach(message => {
                if (message) {
                  message.style.display = 'none';
                }
              });
            }

            function resetForm() {
              document.getElementById('applicationFormAddBus').reset();
            }

            document.addEventListener('DOMContentLoaded', function() {
              var today = new Date().toISOString().split('T')[0];
              var insuranceDate = document.getElementById("insuranceDateUpto");
              var pollutionDate = document.getElementById("pollutionDateUpto");

              insuranceDate.setAttribute('min', today);
              pollutionDate.setAttribute('min', today);
            });
          </script>
        </div>

        <!-- ADD BUS ENDS HERE -->






        <?php
        // Database connection
        $conn = new mysqli("localhost", "root", "", "buscardmanagementsystem");
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        $message = "";
        $messageType = "";
        $showEditForm = false;

        // Handle Edit Operation
        if (isset($_GET['edit'])) {
          $busNumber = $_GET['edit'];

          $query = "SELECT * FROM busdetails WHERE busNumber=?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param('s', $busNumber);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $currentBusId = $row['bus_id'];
            $currentBusNumber = $row['busNumber'];
            $currentBusInsurance = $row['insurance_date'];
            $currentBusPollution = $row['pollution_date'];
            $currentBusFuel = $row['fuel'];
            $showEditForm = true;
          } else {
            $message = "No bus found with the given number.";
            $messageType = "error";
          }
        }

        // Handle Delete Operation
        if (isset($_GET['delete'])) {
          $busId = $_GET['delete'];

          $sql = "DELETE FROM busdetails WHERE bus_id=?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("s", $busId);

          if ($stmt->execute()) {
            $message = "Bus deleted successfully!";
            $messageType = "success";
          } else {
            $message = "Error deleting the bus.";
            $messageType = "error";
          }
        }

        // Handle Update Operation
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateBus'])) {
          $busId = $_POST['bus_id'];
          $busNumber = $_POST['busNumber'];
          $insuranceDate = $_POST['insurance_date'];
          $pollutionDate = $_POST['pollution_date'];
          $fuelDetails = $_POST['fuel'];

          $query = "UPDATE busdetails SET busNumber=?, insurance_date=?, pollution_date=?, fuel=? WHERE bus_id=?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("sssss", $busNumber, $insuranceDate, $pollutionDate, $fuelDetails, $busId);

          if ($stmt->execute()) {
            $message = "Bus details updated successfully!";
            $messageType = "success";
            $showEditForm = false; // Hide the form after successful update
          } else {
            $message = "Error updating bus details.";
            $messageType = "error";
            $showEditForm = true; // Keep the form visible if update fails
          }
        }
        ?>

        <div class="editBusDiv" id="editBusDiv" style="background-color: #ffffff00;">
          <!-- Message Alert -->
          <?php if (!empty($message)) : ?>
            <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
              <?php echo htmlspecialchars($message); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <!-- Edit Form -->
          <div id="editBusDet" style="display: <?php echo $showEditForm ? 'block' : 'none'; ?>;">
            <div class="card">
              <div class="card-header" style="background-color:rgb(232, 234, 236);">
                <h3 class="card-title">Edit Bus Details</h3>
              </div>
              <div class="card-body">
                <form action="" method="post">
                  <input type="hidden" name="bus_id" value="<?php echo htmlspecialchars($currentBusId ?? ''); ?>">

                  <div class="mb-3">
                    <label class="form-label">Bus Number</label>
                    <input type="text" class="form-control" name="busNumber"
                      value="<?php echo htmlspecialchars($currentBusNumber ?? ''); ?>" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Insurance Date</label>
                    <input type="date" class="form-control" name="insurance_date"
                      value="<?php echo htmlspecialchars($currentBusInsurance ?? ''); ?>" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Pollution Date</label>
                    <input type="date" class="form-control" name="pollution_date"
                      value="<?php echo htmlspecialchars($currentBusPollution ?? ''); ?>" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Fuel Details</label>
                    <input type="text" class="form-control" name="fuel"
                      value="<?php echo htmlspecialchars($currentBusFuel ?? ''); ?>" required>
                  </div>

                  <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" name="updateBus">Update Bus</button>
                    <button type="button" class="btn btn-secondary" onclick="cancelEdit()">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Bus Details Table -->
          <div class="container-fluid my-4" id="busDet" style="display: <?php echo $showEditForm ? 'none' : 'block'; ?>;">
            <div class="card">
              <div class="card-header" style="background-color:rgb(232, 234, 236);">
                <h3 class="card-title">Edit Bus Details</h3>
                <!-- <div class="card-tools">
                  <input type="text" class="form-control" id="searchBar"
                    placeholder="Search here..." onkeyup="filterTable()">
                </div> -->
              </div>
              <div class="card-body">
                <table class="table table-striped table-bordered" id="busTable">
                  <thead>
                    <tr>
                      <th>Bus ID</th>
                      <th>Bus Number</th>
                      <th>Insurance Date</th>
                      <th>Pollution Date</th>
                      <th>Fuel</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT * FROM busdetails ORDER BY bus_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                    <td>" . htmlspecialchars($row['bus_id']) . "</td>
                                    <td>" . htmlspecialchars($row['busNumber']) . "</td>
                                    <td>" . htmlspecialchars($row['insurance_date']) . "</td>
                                    <td>" . htmlspecialchars($row['pollution_date']) . "</td>
                                    <td>" . htmlspecialchars($row['fuel']) . "</td>
                                    <td>
                                        <button class='btn btn-sm btn-primary' onclick='editBus(\"" . htmlspecialchars($row['busNumber']) . "\")'>Edit</button>   
                                        <button class='btn btn-sm btn-danger' onclick='deleteBus(\"" . htmlspecialchars($row['bus_id']) . "\")'>Delete</button>
                                    </td>
                                </tr>";
                      }
                    } else {
                      echo "<tr><td colspan='6' class='text-center'>No buses found.</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <script>
          function editBus(busNumber) {
            window.location.href = '?edit=' + encodeURIComponent(busNumber);
          }

          function deleteBus(busId) {
            if (confirm('Are you sure you want to delete this bus?')) {
              window.location.href = '?delete=' + encodeURIComponent(busId);
            }
          }

          function cancelEdit() {
            window.location.href = window.location.pathname;
          }

          function filterTable() {
            const input = document.getElementById('searchBar');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('busTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
              let show = false;
              const cells = rows[i].getElementsByTagName('td');

              for (let cell of cells) {
                const text = cell.textContent || cell.innerText;
                if (text.toLowerCase().includes(filter)) {
                  show = true;
                  break;
                }
              }

              rows[i].style.display = show ? '' : 'none';
            }
          }

          // Auto-hide alerts after 5 seconds
          document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.getElementsByClassName('alert');
            for (let alert of alerts) {
              setTimeout(function() {
                alert.classList.remove('show');
                setTimeout(function() {
                  alert.remove();
                }, 150);
              }, 5000);
            }
          });
        </script>


        <!-- EDIT BUS ENDS HERE -->


        <div class="viewBusDiv" id="viewBusDiv">
          <div class="container-fluid my-4">
            <div class="card">
              <div class="card-header" style="background-color:rgb(232, 234, 236);">
                <h3 class="card-title">Bus Details</h3>
              </div>
              <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-striped table-bordered" id="busTable">
                  <thead>
                    <tr>
                      <th>Bus ID</th>
                      <th>Bus Number</th>
                      <th>Insurance Date</th>
                      <th>Pollution Date</th>
                      <th>Fuel</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT * FROM busdetails ORDER BY busNumber";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                        <td>" . htmlspecialchars($row['bus_id']) . "</td>
                        <td>" . htmlspecialchars($row['busNumber']) . "</td>
                        <td>" . htmlspecialchars($row['insurance_date']) . "</td>
                        <td>" . htmlspecialchars($row['pollution_date']) . "</td>
                        <td>" . htmlspecialchars($row['fuel']) . "</td>
                      </tr>";
                      }
                    } else {
                      echo "<tr><td colspan='5' class='text-center'>No buses found.</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>


        <!-- HERE VIEW STOP ENDS -->

        <div class="locationDiv" id="locationDiv">
          <div class="locationHead" style="display: flex;">Bus Locations</div>
          <div class="container">
            <div class="button-grid">
              <a href="http://localhost/Project/student/Displocation.php" class="bus-button" style=" text-decoration: none; color: white; ">Bus 1</a>
              <a href="http://localhost/Project/student/Displocation.php" class="bus-button" style=" text-decoration: none; color: white; ">Bus 2</a>
              <a href="http://localhost/Project/student/Displocation.php" class="bus-button" style=" text-decoration: none; color: white; ">Bus 3</a>
              <a href="http://localhost/Project/student/Displocation.php" class="bus-button" style=" text-decoration: none; color: white; ">Bus 4</a>
            </div>
            <div class="button-grid">
              <a href="http://localhost/Project/student/Displocation.php" class="bus-button" style=" text-decoration: none; color: white; ">Bus 5</a>
              <a href="http://localhost/Project/student/Displocation.php" class="bus-button" style=" text-decoration: none; color: white; ">Bus 6</a>
              <a href="http://localhost/Project/student/Displocation.php" class="bus-button" style=" text-decoration: none; color: white; ">Bus 7</a>
              <a href="http://localhost/Project/student/Displocation.php" class="bus-button" style=" text-decoration: none; color: white; ">Bus 8</a>
            </div>
          </div>
        </div>




        <div class="addstaffDiv" id="addstaffDiv">
          <form action="" id="applicationFormAddStaff" method="post">
            <div class="headrightpanel">ADD STAFF DETAILS</div>
            <input type="text" id="staffName" name="staffName" placeholder="Enter staff name" required>
            <input type="text" id="staffId" name="staffId" placeholder="Enter Staff ID" required>
            <input type="password" id="staffPassword" name="staffPassword" placeholder="Enter staff Password" required>
            <input type="password" id="staffPasswordConfirm" name="staffPasswordConfirm" placeholder="Confirm Password" required>
            <input type="text" id="stopDetails" name="stopDetails" placeholder="Enter route detail" required>
            <input type="text" id="alternateBusNumber" name="busNumber" placeholder="Enter bus number" required>
            <input type="submit" name="submitButton2" value="Submit">
          </form>

          <?php
          if (session_status() === PHP_SESSION_NONE) {
            session_start();
          }

          if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            unset($_SESSION['staff_form_submitted']);
            $_POST = [];
          }

          $connection = new mysqli("localhost", "root", "", "buscardmanagementsystem");
          if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
          }

          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitButton2'])) {
            $staffName = trim($_POST['staffName']);
            $staffId = trim($_POST['staffId']);
            $staffPassword = trim($_POST['staffPassword']);
            $staffPasswordConfirm = trim($_POST['staffPasswordConfirm']);
            $routeDetails = trim($_POST['stopDetails']);
            $busNumber = trim($_POST['busNumber']);

            if ($staffPassword !== $staffPasswordConfirm) {
              echo "<div id='alertMessage' class='alert-message'>
            Passwords do not match.
            <button onclick='closeAlert()' class='close-btn'>×</button>
            </div>";
            } else {
              // Check for duplicate staff ID
              $duplicateCheckQuery = "SELECT ID FROM staffdetails WHERE ID = ?";
              $stmt = $connection->prepare($duplicateCheckQuery);
              $stmt->bind_param("s", $staffId);
              $stmt->execute();
              $result = $stmt->get_result();

              if ($result->num_rows > 0) {
                echo "<div id='alertMessage' class='alert-message'>
                Staff ID already exists!
                <button onclick='closeAlert()' class='close-btn'>×</button>
            </div>";
              } else {
                $insertQuery = "INSERT INTO staffdetails (NAME, ID, PASSWORD, stop, busNumber) VALUES (?, ?, ?, ?, ?)";
                $stmt = $connection->prepare($insertQuery);
                $stmt->bind_param("sssss", $staffName, $staffId, $staffPassword, $routeDetails, $busNumber);

                if ($stmt->execute()) {
                  echo "<div id='successMessage' class='success-message'>
                    Staff data successfully added! 
                    <button onclick='closeAlert()' class='close-btn'>×</button>
                    <button onclick='resetForm()' class='btn btn-primary'>Add Another Staff</button>
                </div>";
                } else {
                  echo "<div id='alertMessage' class='alert-message'>
                    Error adding staff details.
                    <button onclick='closeAlert()' class='close-btn'>×</button>
                </div>";
                }
              }
              $stmt->close();
            }
          }

          $connection->close();
          ?>
          <script>
            function closeAlert() {
              const messages = document.querySelectorAll('.success-message, .alert-message');
              messages.forEach(message => {
                if (message) {
                  message.style.display = 'none';
                }
              });
            }

            function resetForm() {
              document.getElementById('applicationFormAddStaff').reset();
              const messages = document.querySelectorAll('.success-message, .alert-message');
              messages.forEach(message => message.style.display = 'none');
            }
          </script>
        </div>

        <!--  HERE ADD STAFF ENDS -->

        <?php
        // Database connection
        $conn = new mysqli("localhost", "root", "", "buscardmanagementsystem");
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        $message = "";
        $messageType = "";
        $showEditForm = false;

        // Handle Edit Operation
        if (isset($_GET['edit'])) {
          $staffId = $_GET['edit'];

          $query = "SELECT * FROM staffdetails WHERE ID=?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param('s', $staffId);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $currentName = $row['NAME'];
            $currentStaffId = $row['ID'];
            $currentPassword = $row['PASSWORD'];
            $currentRoute = $row['stop'];
            $currentBusNumber = $row['busNumber'];
            $showEditForm = true;
          } else {
            $message = "No staff found with the given ID.";
            $messageType = "error";
          }
        }

        // Handle Delete Operation
        if (isset($_GET['delete'])) {
          $staffId = $_GET['delete'];

          $sql = "DELETE FROM staffdetails WHERE ID=?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("s", $staffId);

          if ($stmt->execute()) {
            $message = "Staff deleted successfully!";
            $messageType = "success";
          } else {
            $message = "Error deleting the staff.";
            $messageType = "error";
          }
        }

        // Handle Update Operation
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateStaff'])) {
          $staffId = $_POST['ID'];
          $busNumber = $_POST['busNumber'];
          $staffName = $_POST['NAME'];
          $staffPassword = $_POST['PASSWORD'];
          $staffRoute = $_POST['stop'];

          $query = "UPDATE staffdetails SET busNumber=?, NAME=?, PASSWORD=?, stop=? WHERE ID=?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("issss", $busNumber, $staffName, $staffPassword, $staffRoute, $staffId);

          if ($stmt->execute()) {
            $message = "Staff details updated successfully!";
            $messageType = "success";
            $showEditForm = false; // Hide the form after successful update
          } else {
            $message = "Error updating staff details.";
            $messageType = "error";
            $showEditForm = true; // Keep the form visible if update fails
          }
        }
        ?>

        <div class="editstaffDiv" id="editstaffDiv" style="background-color: #ffffff00;">
          <!-- Message Alert -->
          <?php if (!empty($message)) : ?>
            <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
              <?php echo htmlspecialchars($message); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <div id="editStaffDet" style="display: <?php echo $showEditForm ? 'block' : 'none'; ?>;">
            <div class="card">
              <div class="card-header" style="background-color:rgb(232, 234, 236);">
                <h3 class="card-title">Edit Staff Details</h3>
              </div>
              <div class="card-body">
                <form action="" method="post">
                  <input type="hidden" name="ID" value="<?php echo htmlspecialchars($currentStaffId ?? ''); ?>">

                  <div class="mb-3">
                    <label class="form-label">Bus Number</label>
                    <input type="text" class="form-control" name="busNumber"
                      value="<?php echo htmlspecialchars($currentBusNumber ?? ''); ?>" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="NAME"
                      value="<?php echo htmlspecialchars($currentName ?? ''); ?>" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="PASSWORD"
                      value="<?php echo htmlspecialchars($currentPassword ?? ''); ?>" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Route Details</label>
                    <input type="text" class="form-control" name="stop"
                      value="<?php echo htmlspecialchars($currentRoute ?? ''); ?>" required>
                  </div>

                  <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" name="updateStaff">Update Bus</button>
                    <button type="button" class="btn btn-secondary" onclick="cancelEdit()">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Staff Details Table -->
          <div class="container-fluid my-4" id="busDet" style="display: <?php echo $showEditForm ? 'none' : 'block'; ?>;">
            <div class="card">
              <div class="card-header" style="background-color:rgb(232, 234, 236);">
                <h3 class="card-title">Edit Staff Details</h3>
              </div>
              <div class="card-body">
                <table class="table table-striped table-bordered" id="staffTable">
                  <thead>
                    <tr>
                      <th>Staff ID</th>
                      <th>Staff Name</th>
                      <th>Staff Route</th>
                      <th>Password</th>
                      <th>Bus Number</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT ID, NAME, stop, PASSWORD, busNumber FROM staffdetails ORDER BY busNumber";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                    <td>" . htmlspecialchars($row['ID']) . "</td>
                                    <td>" . htmlspecialchars($row['NAME']) . "</td>
                                    <td>" . htmlspecialchars($row['stop']) . "</td>
                                    <td>" . htmlspecialchars($row['PASSWORD']) . "</td>
                                    <td>" . htmlspecialchars($row['busNumber']) . "</td>
                                    <td>
                                        <button class='btn btn-sm btn-primary' onclick='editStaff(\"" . htmlspecialchars($row['busNumber']) . "\")'>Edit</button>
                                        <button class='btn btn-sm btn-danger' onclick='deleteStaff(\"" . htmlspecialchars($row['ID']) . "\")'>Delete</button>
                                    </td>
                                </tr>";
                      }
                    } else {
                      echo "<tr><td colspan='6' class='text-center'>No buses found.</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <script>
          function editStaff(busNumber) {
            window.location.href = '?edit=' + encodeURIComponent(busNumber);
          }

          function deleteStaff(ID) {
            if (confirm('Are you sure you want to delete this bus?')) {
              window.location.href = '?delete=' + encodeURIComponent(ID);
            }
          }

          function cancelEdit() {
            window.location.href = window.location.pathname;
          }


          // Auto-hide alerts after 5 seconds
          document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.getElementsByClassName('alert');
            for (let alert of alerts) {
              setTimeout(function() {
                alert.classList.remove('show');
                setTimeout(function() {
                  alert.remove();
                }, 150);
              }, 5000);
            }
          });
        </script>

        <!-- EDIT STAFF ENDS HERE -->

        <div class="viewstaffDiv" id="viewstaffDiv">
          <div class="container-fluid my-4">
            <div class="card">
              <div class="card-header" style="background-color:rgb(232, 234, 236);">
                <h3 class="card-title">Staff Details</h3>
              </div>
              <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-striped table-bordered" id="busTable">
                  <thead>
                    <tr>
                      <th>Staff ID</th>
                      <th>Staff Name</th>
                      <th>Staff Route</th>
                      <th>Password</th>
                      <th>Bus Number</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT  ID,NAME,stop,PASSWORD,busNumber FROM staffdetails ORDER BY busNumber";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                        <td>" . htmlspecialchars($row['ID']) . "</td>
                        <td>" . htmlspecialchars($row['NAME']) . "</td>
                        <td>" . htmlspecialchars($row['stop']) . "</td>
                        <td>" . htmlspecialchars($row['PASSWORD']) . "</td>
                        <td>" . htmlspecialchars($row['busNumber']) . "</td>
                      </tr>";
                      }
                    } else {
                      echo "<tr><td colspan='5' class='text-center'>No buses found.</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>


        <div class="inboxDiv" id="inboxDiv">
          <style>
            .inboxDiv {
              display: flex;
              flex-direction: column;
              align-items: center;
              margin-top: 20px;
              color: #a5aaac;
              display: none;
              animation: animateFirstDivision 1s forwards;
              margin-top: 1rem;
              padding-left: 0rem;
            }

            .header-container {
              display: flex;
              justify-content: center;
              align-items: center;
              width: 100%;
            }

            .headrightpanel {
              font-size: 2.5rem;
              font-weight: bold;
              text-align: center;
              color: blueviolet;
              padding: 10px;
              border-bottom: 2px solid #dce6f0;
              width: 100%;
              max-width: 900px;
            }

            .card-body {
              display: flex;
              justify-content: center;
              width: 100%;
              padding: 20px;
              margin-top: 0rem;
              margin-right: 2.5rem;
              flex: 1 1 auto;
              color: var(--bs-card-color);
            }

            .container {
              margin-top: -5rem;
              max-height: 60rem;
              max-width: 900px;
              margin: 20px auto;
              padding: 20px;
            }

            .message-card {
              width: 50rem;
            }

            .top-right-button {
              position: absolute;
              top: 10px;
              right: 10px;
            }

            .scrollable-content {
              max-height: 500px;
              overflow-y: auto;
              padding-right: 10px;
            }

            .scrollable-content::-webkit-scrollbar {
              width: 8px;
            }

            .scrollable-content::-webkit-scrollbar-thumb {
              background-color: #007bff;
              border-radius: 10px;
            }

            .scrollable-content::-webkit-scrollbar-track {
              background-color: #f1f1f1;
            }

            .message-card {
              display: flex;
              flex-direction: column;
              background-color: #fff;
              border-radius: 10px;
              border: 1px solid #dce6f0;
              padding: 20px;
              margin: 15px 0;
              box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
              transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .message-card:hover {
              transform: translateY(-5px);
              box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            }

            .message-header {
              display: flex;
              justify-content: space-between;
              align-items: center;
              font-weight: bold;
              margin-bottom: 10px;
            }

            .text-primary {
              color: #007bff;
              display: flex;
              align-items: center;
            }

            .top-right-button {
              background-color: #ff4d4d;
              border: none;
              border-radius: 50%;
              padding: 5px 10px;
              color: white;
              font-size: 12px;
              cursor: pointer;
              transition: background-color 0.3s ease;
            }

            .top-right-button:hover {
              background-color: #e60000;
            }

            .message-body {
              font-size: 16px;
              color: #333;
              word-wrap: break-word;
            }

            .dismiss-form {
              margin: 0;
              display: flex;
              align-items: center;
            }

            @media (max-width: 768px) {
              .message-card {
                padding: 15px;
              }

              .message-header {
                flex-direction: column;
                align-items: flex-start;
              }

              .top-right-button {
                align-self: flex-start;
                margin-top: 10px;
              }
            }

            @media (max-width: 480px) {
              .message-body {
                font-size: 14px;
              }

              .top-right-button {
                font-size: 10px;
                padding: 3px 8px;
              }
            }

            #studContent {
              margin-top: 0;
              margin-bottom: 1rem;
              padding: 1rem;
            }
          </style>

          <?php
          $connection = mysqli_connect("localhost", "root", "", "busCardManagementSystem");

          if ($connection->connect_error) {
            die("<div class='alert alert-danger'>Connection Failed: " . $connection->connect_error . "</div>");
          }

          function fetchAdminMessages($connection)
          {
            $query = "SELECT inboxId, fromMail, content 
                          FROM inboxTable 
                          WHERE toMail = 'mani@gmail.com' 
                          ORDER BY inboxId DESC";

            $result = $connection->query($query);
            $messages = [];

            while ($row = $result->fetch_assoc()) {
              $messages[] = [
                'inboxId' => $row['inboxId'],
                'fromMail' => $row['fromMail'],
                'content' => $row['content']
              ];
            }

            return $messages;
          }

          function moveToInboxRead($connection, $inboxId)
          {
            $query = "SELECT * FROM inboxTable WHERE inboxId = ?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("i", $inboxId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
              return "No message found.";
            }

            $row = $result->fetch_assoc();
            $fromMail = $row['fromMail'];
            $toMail = $row['toMail'];
            $content = $row['content'];

            $insertQuery = "INSERT INTO inboxReadTable (readFromMail, readToMail, readContent) VALUES (?, ?, ?)";
            $insertStmt = $connection->prepare($insertQuery);
            $insertStmt->bind_param("sss", $fromMail, $toMail, $content);

            if ($insertStmt->execute()) {
              $deleteQuery = "DELETE FROM inboxTable WHERE inboxId = ?";
              $deleteStmt = $connection->prepare($deleteQuery);
              $deleteStmt->bind_param("i", $inboxId);

              return $deleteStmt->execute() ? "Message dismissed successfully." : "Error deleting message.";
            } else {
              return "Error processing message.";
            }
          }

          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'dismiss') {
            $inboxId = $_POST['inboxId'];
            $result = moveToInboxRead($connection, $inboxId);
            echo "<div class='alert alert-info text-center'>$result</div>";
          }

          $messages = fetchAdminMessages($connection);
          ?>
          <div class="headrightpanel" style="display: flex; justify-content: center; align-items: center;">Inbox</div>
          <div class="card-body" style="max-height: 500px; overflow-y: auto;">
            <div class="container">
              <div class="inbox-container">
                <div class="scrollable-content"></div>
                <?php if (empty($messages)): ?>
                  <div class="empty-inbox">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p class="lead">No new messages</p>
                  </div>
                <?php else: ?>
                  <?php foreach ($messages as $message): ?>
                    <div class="card message-card">
                      <div class="message-header">
                        <span class="text-primary">
                          <i class="fas fa-envelope me-2"></i>
                          <?php echo htmlspecialchars($message['fromMail']); ?>
                        </span>
                        <form action="" method="post" class="dismiss-form">
                          <input type="hidden" name="action" value="dismiss">
                          <input type="hidden" name="inboxId" value="<?php echo $message['inboxId']; ?>">
                          <button type="submit" class="btn btn-danger btn-sm top-right-button">x</button>
                        </form>
                      </div>
                      <div class="message-body">
                        <p id="studContent"><?php echo htmlspecialchars($message['content']); ?></p>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>

        </div>
        <!--  admin Inbox ends here -->


        <!-- Admin Notification  -->
        <div class="sendNotificationsDiv" id="sendNotificationsDiv" style="padding-left:0rem; background-color: rgba(232, 234, 236, 0)">
          <div class="container">
            <div class="headrightpanel" style="display: flex; justify-content: center; align-items: center;">Send Notification</div>
            <form method="POST">
              <div class="form-group">
                <!-- <label for="toEmail">Student Email (Leave empty to send to all):</label> -->
                <input type="email" id="toEmail" name="toEmail" placeholder="Enter student email">
              </div>
              <div class="form-group">
                <textarea id="message" name="message" rows="15" cols="50" placeholder="Enter your message" required></textarea>
              </div>
              <button type="submit" name="sendNotification" class="sendNotification">Send</button>
            </form>
          </div>

          <?php
          // Database connection
          $connection = mysqli_connect("localhost", "root", "", "busCardManagementSystem");
          if (!$connection) {
            die("<div style='color: red;'>Connection Failed: " . mysqli_connect_error() . "</div>");
          }
          $adminEmail = 'mani@gmail.com';

          // Handle form submission
          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sendNotification'])) {
            $toEmail = $_POST['toEmail'];
            $message = mysqli_real_escape_string($connection, $_POST['message']);
            $currentDateAndTime = date("Y-m-d H:i:s");

            if (empty($toEmail)) {
              // Send to all students
              $query = "INSERT INTO inboxTable (fromMail, toMail, content, dateAndTime) 
                      SELECT '$adminEmail', email, '$message', '$currentDateAndTime' FROM studentsTable";
            } else {
              // Send to a specific student
              $toEmail = mysqli_real_escape_string($connection, $toEmail);
              $query = "INSERT INTO inboxTable (fromMail, toMail, content, dateAndTime) 
                      VALUES ('$adminEmail', '$toEmail', '$message', '$currentDateAndTime')";
            }

            // Execute the query
            if (mysqli_query($connection, $query)) {
              echo "<div style='color: green; text-align: center;'>Notification sent successfully!</div>";
            } else {
              // Debugging: show the MySQL error
              echo "<div style='color: red; text-align: center;'>Error: " . mysqli_error($connection) . "</div>";
            }
          }

          mysqli_close($connection);
          ?>
        </div>





        <!-- Here Edit Admin Profile starts -->
        <div class="editProfileDiv" id="editProfileDiv">
          <?php
          $connection = mysqli_connect("localhost", "root", "", "busCardManagementSystem");

          if ($connection->connect_error) {
            die("<p> <br> Connection Failed: " . $connection->connect_error . "</p> <br>");
          }
          if (!isset($connection) || !$connection) {
            die("Database connection is not initialized or valid.");
          }
          if (!isset($connection)) {
            die("Database connection is not initialized.");
          }
          $admin_id = $_SESSION['admin_id'];
          $sql = "SELECT * FROM adminidandpassword WHERE ID='$admin_id'";
          $result = mysqli_query($connection, $sql);

          if (!$result) {
            echo "console.error('Database query failed: " . mysqli_error($connection) . "');";
          }



          if ($result && mysqli_num_rows($result) > 0) {
            $admin = mysqli_fetch_assoc($result);
            $adminId = $admin['ID'];
            $adminName = $admin['name'];
            $adminPassword = $admin['PASSWORD'];
            $adminMail = $admin['mailId'];
          } else {
            die("Admin not found in the database.");
          }
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['submitAdminNewDetails'])) {
              $adminNewName = !empty(trim($_POST['adminNewName'])) ? $_POST['adminNewName'] : $adminName;
              $adminNewId = !empty(trim($_POST['adminNewId'])) ? $_POST['adminNewId'] : $adminId;
              $adminNewPassword = !empty(trim($_POST['adminNewPassword'])) ? password_hash($_POST['adminNewPassword'], PASSWORD_BCRYPT) : $adminPassword;

              $stmt = $connection->prepare("UPDATE adminidandpassword SET name = ?, ID = ?, PASSWORD = ? WHERE mailId = ?");
              $stmt->bind_param("ssss", $adminNewName, $adminNewId, $adminNewPassword, $adminMail);
              $result = $stmt->execute();

              if ($result) {
                echo "<script>
                document.addEventListener('DOMContentLoaded', (event) => {
                    const messageOne = document.getElementById('submitMessageOne');
                    const message = document.getElementById('submitMessageTwo');
                    const span = document.getElementById('submitMessageTwoSpan');

                    messageOne.style.display = 'none';
                    message.style.display = 'block';
                    span.style.display = 'inline';

                    let countdownValue = 3;
                    const countdownInterval = setInterval(() => {
                        span.textContent = countdownValue;
                        countdownValue--;

                        if (countdownValue < 0) {
                            clearInterval(countdownInterval);
                            window.location.href = 'http://localhost/Project/staff/logout.php';
                        }
                    }, 1000);
                });
            </script>";
              } else {
                echo "<script>
                document.addEventListener('DOMContentLoaded', (event) => {
                    const messageOne = document.getElementById('submitMessageOne');
                    const messageThree = document.getElementById('submitMessageThree');
                    messageOne.style.display = 'none';
                    messageThree.style.display = 'block';
                });
            </script>";
              }
              $stmt->close();
            }
          }
          mysqli_close($connection);
          ?>


          <form action="" method="POST">
            <div class="editProfile" id="editProfile">
              <div class="headrightpanel">Edit Profile </div>
              <input type="text" name="adminNewName" id="adminNewName" placeholder=<?php echo "$adminName"; ?> maxlength="20">

              <input type="text" name="adminNewId" id="adminNewId" placeholder=<?php echo "$adminId"; ?> maxlength="20">

              <input type="text" name="adminNewPassword" id="adminNewPassword" placeholder=<?php echo "$adminPassword"; ?>
                maxlength="20">

              <input type="submit" name="submitAdminNewDetails" id="submitAdminNewDetails">

              <span class="submitMessageOne" id="submitMessageOne"> Note: You will need to sign back in after making the changes </span>
              <p class="submitMessageTwo" id="submitMessageTwo"> Profile Updated...redirecting in <span class="submitMessageTwoSpan" id="submitMessageTwoSpan"> 3 </span> s </p>
              <span class="submitMessageThree" id="submitMessageThree"> Error Updating Profile </span>
            </div>
          </form>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
      </body>

      </html>



      <div class="rightPaneFirstDivision" id="rightPaneFirstDivision">
        <div class="rightPaneFirstDivisionTop">
          <div class="rightPaneFirstDivisionTopLeft hover-effect font-color">
            <div class="rightPaneFirstDivisionTopLeftOne">
              <div class="rightPaneFirstDivisionTopLeftOneOne">
                <?php echo $totalBusCount; ?>
              </div>
              <div class="rightPaneFirstDivisionTopLeftOneTwo">
                No. of Buses
              </div>
            </div>
            <div class="rightPaneFirstDivisionTopLeftTwo">
              <div class="rightPaneFirstDivisionTopLeftTwoTop">
                <div class="rightPaneFirstDivisionTopLeftTwoTopBaseSlider">
                  <div class="rightPaneFirstDivisionTopLeftTwoTopIndicatorSlider">
                  </div>
                </div>
                <?php echo $totalBusesReached; ?>
              </div>
              <div class="rightPaneFirstDivisionTopLeftTwoBottom">
                Buses Reached
              </div>
            </div>
            <div class="rightPaneFirstDivisionTopLeftThree">
              <div class="rightPaneFirstDivisionTopLeftThreeTop">
                <div class="rightPaneFirstDivisionTopLeftThreeTopBaseSlider">
                  <div class="rightPaneFirstDivisionTopLeftThreeTopIndicatorSlider">
                  </div>
                </div>
                <?php echo $totalBusesNotReached; ?>
              </div>
              <div class="rightPaneFirstDivisionTopLeftThreeBottom">
                Buses Running
              </div>
            </div>
          </div>
          <div class="rightPaneFirstDivisionTopCenter font-color hover-effect">
            <div class="rightPaneFirstDivisionTopCenterOne">
              No. of Cards Generated
            </div>
            <div class="rightPaneFirstDivisionTopCenterTwo">
              <div class="rightPaneFirstDivisionTopCenterTwoTop">
                <div class="rightPaneFirstDivisionTopCenterTwoTopOne">
                </div>
                <div class="rightPaneFirstDivisionTopCenterTwoTopTwo">
                  This Year
                </div>
                <div class="rightPaneFirstDivisionTopCenterTwoTopThree">
                  <?php echo $pinkCardGeneratedThisYear; ?>
                </div>
              </div>
              <div class="rightPaneFirstDivisionTopCenterTwoBottom">
                <div class="rightPaneFirstDivisionTopCenterTwoBottomOne">
                </div>
                <div class="rightPaneFirstDivisionTopCenterTwoBottomTwo">
                  Previous Year
                </div>
                <div class="rightPaneFirstDivisionTopCenterTwoBottomThree">
                  <?php echo $pinkCardGeneratedLastYear; ?>
                </div>
              </div>
            </div>
            <div class="rightPaneFirstDivisionTopCenterThree">
              <div class="rightPaneFirstDivisionTopCenterThreePieChart">
                <div class="pinkPieChartCover">
                </div>
              </div>
            </div>
          </div>
          <div class="rightPaneFirstDivisionTopRight font-color yellow-hover-effect">
            <div class="rightPaneFirstDivisionTopRightOne">
              No. of Cards Generated
            </div>
            <div class="rightPaneFirstDivisionTopRightTwo">
              <div class="rightPaneFirstDivisionTopRightTwoTop">
                <div class="rightPaneFirstDivisionTopRightTwoTopOne">
                </div>
                <div class="rightPaneFirstDivisionTopRightTwoTopTwo">
                  This Year
                </div>
                <div class="rightPaneFirstDivisionTopRightTwoTopThree">
                  <?php echo $pinkCardGeneratedThisYear; ?>
                </div>
              </div>
              <div class="rightPaneFirstDivisionTopRightTwoBottom">
                <div class="rightPaneFirstDivisionTopRightTwoBottomOne">
                </div>
                <div class="rightPaneFirstDivisionTopRightTwoBottomTwo">
                  Previous Year
                </div>
                <div class="rightPaneFirstDivisionTopRightTwoBottomThree">
                  <?php echo $pinkCardGeneratedLastYear; ?>
                </div>
              </div>
            </div>
            <div class="rightPaneFirstDivisionTopRightThree">
              <div class="rightPaneFirstDivisionTopRightThreePieChart">
                <div class="yellowPieChartCover">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="rightPaneFirstDivisionBottom font-color hover-effect">
          <div class="rightPaneFirstDivisionBottomTop">
            <div class="rightPaneFirstDivisionBottomTopLeft">
              <div class="rightPaneFirstDivisionBottomTopLeftTop">
                Today
              </div>
              <div class="rightPaneFirstDivisionBottomTopLeftBottom">
                ₹<?php echo $pinkCardsToday; ?>
              </div>
            </div>
            <div class="rightPaneFirstDivisionBottomTopRight">
              <div class="rightPaneFirstDivisionBottomTopRightTop">
                <div class="rightPaneFirstDivisionBottomTopRightTopLeft">
                </div>
                <div class="rightPaneFirstDivisionBottomTopRightTopRight">
                  No. of Receipts Generated
                </div>
              </div>
            </div>
          </div>
          <div class="rightPaneFirstDivisionBottomBottom">
            <div class="rightPaneFirstDivisionBottomBottomOne">
              <div class="rightPaneFirstDivisionBottomBottomOneTop">
                <p> <?php echo $pinkCardGeneratedTodayBusOne; ?> </p>
              </div>
              <div class="rightPaneFirstDivisionBottomBottomOneCenter">
                <div class="rightPaneFirstDivisionBottomBottomOneCenterBaseSlider">
                  <div class="rightPaneFirstDivisionBottomBottomOneCenterIndicatorSlider">
                  </div>
                </div>
              </div>
              <div class="rightPaneFirstDivisionBottomBottomOneBottom">
                <div class="rightPaneFirstDivisionBottomBottomOneBottomTop">
                  Bus No. 1
                </div>
                <div class="rightPaneFirstDivisionBottomBottomOneBottomBottom">
                  ₹<?php echo $pinkCardGeneratedTodayBusOneIncome; ?>
                </div>
              </div>
            </div>
            <div class="rightPaneFirstDivisionBottomBottomTwo">
              <div class="rightPaneFirstDivisionBottomBottomTwoTop">
                <p> <?php echo $pinkCardGeneratedTodayBusTwo; ?> </p>
              </div>
              <div class="rightPaneFirstDivisionBottomBottomTwoCenter">
                <div class="rightPaneFirstDivisionBottomBottomTwoCenterBaseSlider">
                  <div class="rightPaneFirstDivisionBottomBottomTwoCenterIndicatorSlider">
                  </div>
                </div>
              </div>
              <div class="rightPaneFirstDivisionBottomBottomTwoBottom">
                <div class="rightPaneFirstDivisionBottomBottomTwoBottomTop">
                  Bus No. 2
                </div>
                <div class="rightPaneFirstDivisionBottomBottomTwoBottomBottom">
                  ₹<?php echo $pinkCardGeneratedTodayBusTwoIncome; ?>
                </div>
              </div>
            </div>
            <div class="rightPaneFirstDivisionBottomBottomThree">
              <div class="rightPaneFirstDivisionBottomBottomThreeTop">
                <p> <?php echo $pinkCardGeneratedTodayBusThree; ?> </p>
              </div>
              <div class="rightPaneFirstDivisionBottomBottomThreeCenter">
                <div class="rightPaneFirstDivisionBottomBottomThreeCenterBaseSlider">
                  <div class="rightPaneFirstDivisionBottomBottomThreeCenterIndicatorSlider">
                  </div>
                </div>
              </div>
              <div class="rightPaneFirstDivisionBottomBottomThreeBottom">
                <div class="rightPaneFirstDivisionBottomBottomThreeBottomTop">
                  Bus No. 3
                </div>
                <div class="rightPaneFirstDivisionBottomBottomThreeBottomBottom">
                  ₹<?php echo $pinkCardGeneratedTodayBusThreeIncome; ?>
                </div>
              </div>
            </div>
            <div class="rightPaneFirstDivisionBottomBottomFour">
              <div class="rightPaneFirstDivisionBottomBottomFourTop">
                <p> <?php echo $pinkCardGeneratedTodayBusFour; ?> </p>
              </div>
              <div class="rightPaneFirstDivisionBottomBottomFourCenter">
                <div class="rightPaneFirstDivisionBottomBottomFourCenterBaseSlider">
                  <div class="rightPaneFirstDivisionBottomBottomFourCenterIndicatorSlider">
                  </div>
                </div>
              </div>
              <div class="rightPaneFirstDivisionBottomBottomFourBottom">
                <div class="rightPaneFirstDivisionBottomBottomFourBottomTop">
                  Bus No. 4
                </div>
                <div class="rightPaneFirstDivisionBottomBottomFourBottomBottom">
                  ₹<?php echo $pinkCardGeneratedTodayBusFourIncome; ?>
                </div>
              </div>
            </div>
            <div class="rightPaneFirstDivisionBottomBottomFive">
              <div class="rightPaneFirstDivisionBottomBottomFiveTop">
                <p> <?php echo $pinkCardGeneratedTodayBusFive; ?> </p>
              </div>
              <div class="rightPaneFirstDivisionBottomBottomFiveCenter">
                <div class="rightPaneFirstDivisionBottomBottomFiveCenterBaseSlider">
                  <div class="rightPaneFirstDivisionBottomBottomFiveCenterIndicatorSlider">
                  </div>
                </div>
              </div>
              <div class="rightPaneFirstDivisionBottomBottomFiveBottom">
                <div class="rightPaneFirstDivisionBottomBottomFiveBottomTop">
                  Bus No. 5
                </div>
                <div class="rightPaneFirstDivisionBottomBottomFiveBottomBottom">
                  ₹<?php echo $pinkCardGeneratedTodayBusFiveIncome; ?>
                </div>
              </div>
            </div>
            <div class="rightPaneFirstDivisionBottomBottomSix">
              <div class="rightPaneFirstDivisionBottomBottomSixTop">
                <p> <?php echo $pinkCardGeneratedTodayBusSix; ?> </p>
              </div>
              <div class="rightPaneFirstDivisionBottomBottomSixCenter">
                <div class="rightPaneFirstDivisionBottomBottomSixCenterBaseSlider">
                  <div class="rightPaneFirstDivisionBottomBottomSixCenterIndicatorSlider">
                  </div>
                </div>
              </div>
              <div class="rightPaneFirstDivisionBottomBottomSixBottom">
                <div class="rightPaneFirstDivisionBottomBottomSixBottomTop">
                  Bus No. 6
                </div>
                <div class="rightPaneFirstDivisionBottomBottomSixBottomBottom">
                  ₹<?php echo $pinkCardGeneratedTodayBusSixIncome; ?>
                </div>
              </div>
            </div>
            <div class="rightPaneFirstDivisionBottomBottomSeven">
              <div class="rightPaneFirstDivisionBottomBottomSevenTop">
                <p> <?php echo $pinkCardGeneratedTodayBusSeven; ?> </p>
              </div>
              <div class="rightPaneFirstDivisionBottomBottomSevenCenter">
                <div class="rightPaneFirstDivisionBottomBottomSevenCenterBaseSlider">
                  <div class="rightPaneFirstDivisionBottomBottomSevenCenterIndicatorSlider">
                  </div>
                </div>
              </div>
              <div class="rightPaneFirstDivisionBottomBottomSevenBottom">
                <div class="rightPaneFirstDivisionBottomBottomSevenBottomTop">
                  Bus No. 7
                </div>
                <div class="rightPaneFirstDivisionBottomBottomSevenBottomBottom">
                  ₹<?php echo $pinkCardGeneratedTodayBusSevenIncome; ?>
                </div>
              </div>
            </div>
            <div class="rightPaneFirstDivisionBottomBottomEight">
              <div class="rightPaneFirstDivisionBottomBottomEightTop">
                <p> <?php echo $pinkCardGeneratedTodayBusEight; ?> </p>
              </div>
              <div class="rightPaneFirstDivisionBottomBottomEightCenter">
                <div class="rightPaneFirstDivisionBottomBottomEightCenterBaseSlider">
                  <div class="rightPaneFirstDivisionBottomBottomEightCenterIndicatorSlider">
                  </div>
                </div>
              </div>
              <div class="rightPaneFirstDivisionBottomBottomEightBottom">
                <div class="rightPaneFirstDivisionBottomBottomEightBottomTop">
                  Bus No. 8
                </div>
                <div class="rightPaneFirstDivisionBottomBottomEightBottomBottom">
                  ₹<?php echo $pinkCardGeneratedTodayBusEightIncome; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="rightPaneSecondDivision font-color hover-effect" id="rightPaneSecondDivision">
        <div class="rightPaneSecondDivisionTop">
          <div class="rightPaneSecondDivisionTopLeft">
            <div class="rightPaneSecondDivisionTopLeftTop">
              This Week
            </div>
            <div class="rightPaneSecondDivisionTopLeftBottom">
              ₹<?php echo $thisWeekTotal; ?>
            </div>
          </div>
          <div class="rightPaneSecondDivisionTopRight">
            <div class="rightPaneSecondDivisionTopRightTop">
              <div class="rightPaneSecondDivisionTopRightTopLeft">
              </div>
              <div class="rightPaneSecondDivisionTopRightTopRight">
                No. of Receipts Generated
              </div>
            </div>
          </div>
        </div>
        <div class="rightPaneSecondDivisionBottom">
          <div class="rightPaneSecondDivisionBottomTop">
            <div class="rightPaneSecondDivisionBottomTopLeft">
              <p> 140 </p>
              <p> 120 </p>
              <p> 100 </p>
              <p> 80 </p>
              <p> 60 </p>
              <p> 40 </p>
              <p> 20 </p>
            </div>
            <div class="rightPaneSecondDivisionBottomTopRight">
              <div class="rightPaneSecondDivisionBottomTopRightOne">
                <div class="rightPaneSecondDivisionBottomTopRightOneTop">
                  <div class="rightPaneSecondDivisionBottomTopRightOneTopNumber">
                    <?php echo $mondayCardsGenerated; ?>
                  </div>
                </div>
                <div class="rightPaneSecondDivisionBottomTopRightOneBottom">
                  <div class="rightPaneSecondDivisionBottomTopRightOneBottomBar">
                  </div>
                </div>
              </div>
              <div class="rightPaneSecondDivisionBottomTopRightTwo">
                <div class="rightPaneSecondDivisionBottomTopRightTwoTop">
                  <div class="rightPaneSecondDivisionBottomTopRightTwoTopNumber">
                    <?php echo $tuesdayCardsGenerated; ?>
                  </div>
                </div>
                <div class="rightPaneSecondDivisionBottomTopRightTwoBottom">
                  <div class="rightPaneSecondDivisionBottomTopRightTwoBottomBar">
                  </div>
                </div>
              </div>
              <div class="rightPaneSecondDivisionBottomTopRightThree">
                <div class="rightPaneSecondDivisionBottomTopRightThreeTop">
                  <div class="rightPaneSecondDivisionBottomTopRightThreeTopNumber">
                    <?php echo $wednesdayCardsGenerated; ?>
                  </div>
                </div>
                <div class="rightPaneSecondDivisionBottomTopRightThreeBottom">
                  <div class="rightPaneSecondDivisionBottomTopRightThreeBottomBar">
                  </div>
                </div>
              </div>
              <div class="rightPaneSecondDivisionBottomTopRightFour">
                <div class="rightPaneSecondDivisionBottomTopRightFourTop">
                  <div class="rightPaneSecondDivisionBottomTopRightFourTopNumber">
                    <?php echo $thursdayCardsGenerated; ?>
                  </div>
                </div>
                <div class="rightPaneSecondDivisionBottomTopRightFourBottom">
                  <div class="rightPaneSecondDivisionBottomTopRightFourBottomBar">
                  </div>
                </div>
              </div>
              <div class="rightPaneSecondDivisionBottomTopRightFive">
                <div class="rightPaneSecondDivisionBottomTopRightFiveTop">
                  <div class="rightPaneSecondDivisionBottomTopRightFiveTopNumber">
                    <?php echo $fridayCardsGenerated; ?>
                  </div>
                </div>
                <div class="rightPaneSecondDivisionBottomTopRightFiveBottom">
                  <div class="rightPaneSecondDivisionBottomTopRightFiveBottomBar">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="rightPaneSecondDivisionBottomBottom">
            <div class="rightPaneSecondDivisionBottomBottomLeft">
              0
            </div>
            <div class="rightPaneSecondDivisionBottomBottomRight">
              <div class="rightPaneSecondDivisionBottomBottomRightOne">
                <div class="rightPaneSecondDivisionBottomBottomRightOneTop">
                  Monday
                </div>
                <div class="rightPaneSecondDivisionBottomBottomRightOneBottom">
                  ₹<?php echo $mondayIncome; ?>
                </div>
              </div>
              <div class="rightPaneSecondDivisionBottomBottomRightTwo">
                <div class="rightPaneSecondDivisionBottomBottomRightTwoTop">
                  Tuesday
                </div>
                <div class="rightPaneSecondDivisionBottomBottomRightTwoBottom">
                  ₹<?php echo $tuesdayIncome; ?>
                </div>
              </div>
              <div class="rightPaneSecondDivisionBottomBottomRightThree">
                <div class="rightPaneSecondDivisionBottomBottomRightThreeTop">
                  Wednesday
                </div>
                <div class="rightPaneSecondDivisionBottomBottomRightThreeBottom">
                  ₹<?php echo $wednesdayIncome; ?>
                </div>
              </div>
              <div class="rightPaneSecondDivisionBottomBottomRightFour">
                <div class="rightPaneSecondDivisionBottomBottomRightFourTop">
                  Thursday
                </div>
                <div class="rightPaneSecondDivisionBottomBottomRightFourBottom">
                  ₹<?php echo $thursdayIncome; ?>
                </div>
              </div>
              <div class="rightPaneSecondDivisionBottomBottomRightFive">
                <div class="rightPaneSecondDivisionBottomBottomRightFiveTop">
                  Friday
                </div>
                <div class="rightPaneSecondDivisionBottomBottomRightFiveBottom">
                  ₹<?php echo $fridayIncome; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="adminPage1.js"></script>
</body>

</html>