<?php
error_reporting(E_ALL);

$connection = mysqli_connect("localhost", "root", "", "busCardManagementSystem");

if ($connection->connect_error) {
    die("<p> <br> Connection Failed: " . $connection->connect_error . "</p> <br>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Function to check if a variable is empty
    function isEmpty($value)
    {
        return !isset($value) || trim($value) === '';
    }

    // Proceed with login based on which submit button was clicked
    if (isset($_POST['adminSubmit'])) {
        // Admin button was clicked
        $ADMIN_ID = $_POST['personId'];
        $ADMIN_PASSWORD = $_POST['password'];
        $pass=md5($ADMIN_PASSWORD );

        // SQL query for admin login
        $sql = "SELECT * FROM adminIdAndPassword WHERE ID='$ADMIN_ID' AND PASSWORD='$ADMIN_PASSWORD'";
        $result = mysqli_query($connection, $sql);

        $admin = mysqli_fetch_assoc($result);

        // Check if there is a matching admin
        if (mysqli_num_rows($result) == 1) {
            // Admin exists and password matches
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['admin_id'] = $admin['ID'];
            header("Location: http://localhost/Project/admin/adminPage.php");
            exit;
        } else {
            // Admin does not exist or password is incorrect
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('invalidCredentials').style.display = 'inline';
            });      
                  </script>";
        }
    } elseif (isset($_POST['studentSubmit'])) {
        // Get student credentials
        $STUDENT_ID = $_POST['personId'];
        $STUDENT_PASSWORD = $_POST['password'];


        // SQL query for student login
        $sql = "SELECT * FROM studentidandpassword WHERE id='$STUDENT_ID' AND password='$STUDENT_PASSWORD'";
        $result = mysqli_query($connection, $sql);

        // Check if student exists
        if ($student = mysqli_fetch_assoc($result)) {
            session_start();
            $_SESSION['loggedin'] = true;
            // $_SESSION['student_id'] = $student['ID'];
            $_SESSION['student_id'] = $student['id'];

            // Determine student type
            $studentType = '';

            // Check for new student
            $sqlNew = "SELECT studentId FROM newStudent WHERE studentId='$STUDENT_ID'";
            $resultNew = mysqli_query($connection, $sqlNew);
            if (mysqli_num_rows($resultNew) > 0) {
                $studentType = 'new';
            }

            // Check for new student
            $sqlUnderReview = "SELECT studentId FROM newApplications WHERE studentId='$STUDENT_ID'";
            $resultUnderReview = mysqli_query($connection, $sqlUnderReview);
            if (mysqli_num_rows($resultUnderReview) > 0) {
                $studentType = 'underReview';
            }

            // // Check for yellowstudent
            // $sqlYellow = "SELECT studentId FROM yellowStudent WHERE studentId='$STUDENT_ID'";
            // $resultYellow = mysqli_query($connection, $sqlYellow);
            // if (mysqli_num_rows($resultYellow) > 0) {
            //     $studentType = 'yellow';
            // }

            // // Check for pink student
            // $sqlPink = "SELECT studentId FROM pinkStudent WHERE studentId='$STUDENT_ID'";
            // $resultPink = mysqli_query($connection, $sqlPink);
            // if (mysqli_num_rows($resultPink) > 0) {
            //     $studentType = 'pink';
            // }


            $sql7 = "SELECT card_type FROM studentcard WHERE studentId='$STUDENT_ID'";
            $result7 = mysqli_query($connection, $sql7);
            if ($result7) {
                $row7 = mysqli_fetch_assoc($result7);
                if (isset($row7['card_type'])) {
                    $studentType = $row7['card_type'];
                } else {
                    echo "Card type is not set for this student.";
                }
            } else {
                echo "Error in SQL query: " . mysqli_error($connection);
            }

            if (isset($student['id'])) {
                $_SESSION['student_id'] = $student['id'];
            } else {
                echo "Student ID not found.";
            }

            // Set student type session
            if ($studentType) {
                $_SESSION['student_type'] = $studentType;
                header("Location: http://localhost/Project/student/studentPage.php");
                exit;
            } else {
                // Handle case where student type is not found
                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('invalidCredentials').style.display = 'inline';
            });               
                  </script>";
            }
        } else {
            // Handle invalid credentials
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('invalidCredentials').style.display = 'inline';
            });               
                  </script>";
        }
    } elseif (isset($_POST['staffSubmit'])) {
        // Staff button was clicked
        // Retrieve login credentials from the form
        $STAFF_ID = $_POST['personId'];
        $STAFF_PASSWORD = $_POST['password'];

        // SQL query for staff login
        $query = "SELECT * FROM staffDetails WHERE ID='$STAFF_ID' AND PASSWORD='$STAFF_PASSWORD'";
        $result = mysqli_query($connection, $query);

        // Fetch the result as an associative array
        $staff = mysqli_fetch_assoc($result);

        if ($staff) {
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['staff_id'] = $staff['ID']; // Store staff ID in session
            header("Location: http://localhost/Project/staff/staffPage.php");
            exit;
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('invalidCredentials').style.display = 'inline';
            });               
                  </script>";
        }
    }


    // Close the connection
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width = device-width, initial-scale = 1.0">
    <title> Login Page </title>
    <link rel="stylesheet" href="loginPage.css">
    <link rel="icon" type="image/png" href="http://localhost/3%20BUS%20TICKET%20Project/common_assets/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="loginPage.js"></script>
</head>

<body>
    <div class="loader">
        <div class="busBody">
            <div class="upperBody">
                <div class="peopleWindows">
                    <div class="window1">
                    </div>
                    <div class="window2">
                    </div>
                    <div class="window3">
                    </div>
                </div>
                <div class="driverWindow">
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

    <form action="" id="loginPage" method="post">
        <div class="logInBox">
            <div class="credentials">
                <div class="detail">Login</div>
                <input type="text" name="personId" placeholder="User ID" id="personId" maxlength="20" required>
                <br> <br>
                <input type="password" name="password" placeholder="Password" id="password" maxlength="20" required>
                <i id="passwordToggleIcon" class="fa-solid fa-eye" onclick="togglePasswordVisibility()"></i>

                <span id="invalidCredentials" name="invalidCredentials"> Invalid username or password for the selected user type </span>
            </div>
            <div class="person">
                <input type="submit" value="ADMIN" id="adminSubmit" name="adminSubmit">
                <input type="submit" value="STUDENT" id="studentSubmit" name="studentSubmit">
                <input type="submit" value="STAFF" id="staffSubmit" name="staffSubmit">
            </div>
            <div class="createaccount">Create account <a href="http://localhost/Project/signUpPage/signUpPage.php">Sign-up</a></div>
        </div>
    </form>
</body>

</html>