<?php
session_start();

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

error_reporting(E_ALL);

$connection = mysqli_connect("localhost", "root", "", "busCardManagementSystem");

if ($connection->connect_error) {
    die("<p> <br> Connection Failed: " . $connection->connect_error . "</p> <br>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // If send OTP button was clicked
    if (isset($_POST['sendOtpButton'])) {
        if (empty(trim($_POST['emailId']))) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('emptyMail').style.display = 'inline';
                    });
                  </script>";
        } else {
            $EMAIL_ID = $_POST['emailId'];
            $_SESSION['EMAIL_ID'] = $_POST['emailId'];

            // SQL query for students emails checking for if already existing

            $sql = "SELECT * FROM studentEmails WHERE emailId='$EMAIL_ID'";
            // $sql = "SELECT * FROM studentcard WHERE emailId='$EMAIL_ID'";
            $result = mysqli_query($connection, $sql);

            // Check if there is a matching mail id
            if (mysqli_num_rows($result) == 1) {
                // Student mail exists
                echo "<script>                
                        document.addEventListener('DOMContentLoaded', () => {                            
                            const message = document.getElementById('mailAlreadyRegisteredMessage');
                            const span = document.getElementById('mailAlreadyRegisteredMessageSpan');

                            // Ensure the elements are shown
                            message.style.display = 'block'; // Show the message
                            span.style.display = 'inline'; // Show the countdown span

                            // Start countdown
                            let countdownValue = 5;
                            const countdownInterval = setInterval(() => {
                                span.textContent = countdownValue; // Update countdown value
                                countdownValue--;

                                if (countdownValue < 0) {
                                    clearInterval(countdownInterval);
                                    window.location.href = 'http://localhost/Project/loginPage/loginPage.php';
                                }
                            }, 1000);
                        });
                      </script>";
            } else {
                // Student mail doesn't exist
                if (str_ends_with(strtolower($EMAIL_ID), "@rajagiricollege.edu.in")) {

                    // Split the email at the "@" character
                    list($rollNumber, $collegeID) = explode("@", $EMAIL_ID);

                    // Generate OTP and save it in session for later validation
                    $otp = random_int(1000, 9999);
                    $_SESSION['otp'] = $otp;  // ** Store OTP in session variable **

                    // Create an instance of PHPMailer
                    $mail = new PHPMailer(true);

                    try {
                        // Server settings
                        $mail->isSMTP();                                            // Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                      // Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                        $mail->Username   = 'anjostalin04@gmail.com';               // SMTP username (your email)
                        $mail->Password   = 'lqgirdotwneetqlr';        // SMTP password (use app-specific password if 2FA is enabled)
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
                        $mail->Port       = 587;                                    // TCP port for TLS

                        // Recipients
                        $mail->setFrom('rcmas123@rajagiricollge.edu.in', 'RCMAS');    // Sender's email
                        $mail->addAddress($EMAIL_ID);                               // Recipient's email

                        // Content
                        $mail->isHTML(true);                                        // Set email format to HTML
                        $mail->Subject = 'Bus Card Management System: Mail ID Verification';
                        $mail->Body    = 'Dear <b>' . strtoupper($rollNumber) . '</b>, <br> <br> Welcome to the <b> BUS CARD MANAGEMENT SYSTEM. </b> <br> <br> Your verification code to verify your mail id is <b>' . $otp . '.</b> <br> <br> This is an automatically generated email - please do not reply to it.';
                        $mail->AltBody = $otp; // Plain text version

                        // Send the email
                        $mail->send();

                        echo "<script> document.addEventListener('DOMContentLoaded', function() {
                                        document.getElementById('emailId').style.display = 'none';
                                        document.getElementById('sendOtpButton').style.display = 'none';
                                        document.getElementById('mailIcon').style.display = 'none';
                                        document.getElementById('verifyOtpContainer').style.display = 'block';
                                        document.getElementById('credentials').style.display = 'none';

                                        var otpInputs = document.getElementsByClassName('otpInput');
                                        for (var i = 0; i < otpInputs.length; i++) {
                                            otpInputs[i].style.display = 'block';
                                        }
                            }); </script>";
                    } catch (Exception $e) {
                        if (strpos($e->getMessage(), 'Invalid address') !== false) {
                            echo "<script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        document.getElementById('unexistingMail').style.display = 'inline';
                                    });
                                  </script>";
                        } else {
                            error_log("Mailer Error: " . $e->getMessage());
                            echo "<script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        document.getElementById('unknownError').style.display = 'inline';
                                    });
                                  </script>";
                        }
                    }
                } else {
                    echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                document.getElementById('notCollegeMailError').style.display = 'inline';
                            });
                          </script>";
                }
            }
        }
    }

    // Verify OTP section
    if (isset($_POST['verifyOtpButton'])) {
        $enteredOtp = $_POST['combinedOtp'];
        $sessionOtp = $_SESSION['otp'] ?? null;

        if ($enteredOtp == $sessionOtp) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('credentials').style.display = 'none';
                document.getElementById('verifyOtpContainer').style.display = 'none';
                document.getElementById('logInBox').style.backgroundColor = 'rgba(0, 0, 0, 0)';
                document.getElementById('logInBox').style.boxShadow = 'none';
                document.getElementById('createAccountBox').style.display = 'flex';
            });
            </script>";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('incorrectOtpError').style.display = 'inline';
            });
            </script>";
        }
    }

    //sign up section
    if (isset($_POST['createAccountBoxSubmitButton'])) {
        $EMAIL_ID = $_SESSION['EMAIL_ID'];
        $enteredName = $_POST['createAccountBoxPersonName'];
        $enteredPersonID = $_POST['createAccountBoxPersonId'];
        $enteredPassword = $_POST['createAccountBoxPassword'];

        $sql = "INSERT INTO newstudent(studentId, mail, name) VALUES ('$enteredPersonID', '$EMAIL_ID', '$enteredName')";
        // $sql = "INSERT INTO studentcard (studentId, card_type, stop, busNumber, cardNumber) VALUES ('$enteredPersonID', '$EMAIL_ID', '$enteredName')";
        $result = mysqli_query($connection, $sql);
        $sql2 = "INSERT INTO studentidandpassword (id, password, name, mail, is_active) VALUES ('$enteredPersonID', '$enteredPassword', '$enteredName', '$EMAIL_ID', '1')";
        // $sql2 = "INSERT INTO studentidandpassword (ID, PASSWORD, name, mail) VALUES ('$enteredPersonID', '$enteredPassword', '$enteredName', '$EMAIL_ID')";
        $result2 = mysqli_query($connection, $sql2);

        $sql12 = "INSERT INTO studentemails (emailId) VALUES ('$EMAIL_ID')";
        $result12 = mysqli_query($connection, $sql12);

        echo "<script>                
                        document.addEventListener('DOMContentLoaded', () => {     
                            document.getElementById('emailId').style.display = 'none';
                            document.getElementById('sendOtpButton').style.display = 'none';   
                            document.getElementById('mailIcon').style.display = 'none';                 
                            const message1 = document.getElementById('mailRegisteredMessage');
                            const span1 = document.getElementById('mailAlreadyRegisteredMessageSpan1');

                            // Ensure the elements are shown
                            message1.style.display = 'block'; // Show the message
                            span1.style.display = 'inline'; // Show the countdown span

                            // Start countdown
                            let countdownValue1 = 5;
                            const countdownInterval1 = setInterval(() => {
                                span1.textContent = countdownValue1; // Update countdown value
                                countdownValue1--;

                                if (countdownValue1 < 0) {
                                    clearInterval(countdownInterval1);
                                    window.location.href = 'http://localhost/Project/loginPage/loginPage.php';
                                }
                            }, 1000);
                        });
                      </script>";
    }

    // Close the connection
    mysqli_close($connection);
}
?>



<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width = device-width, initial-scale = 1.0">
    <title> Sign Up </title>
    <link rel="stylesheet" href="signUpPage.css">
    <link rel="icon" type="image/png" href="http://localhost/3%20BUS%20TICKET%20Project/common_assets/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script defer src="signUpPage3.js"></script>
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
<div class="overlay"></div>
<div class="logInBox" id="logInBox"> 
    <form action="" method="POST"> 
        <div class="credentials" id="credentials">
            <div class="detail" >Create account</div>
            <input type="email" name="emailId" placeholder="collegemailid@rajagiricollege.edu.in" id="emailId" size="10" required> 
            <i class="fa-solid fa-envelope" title="College Mail ID" id="mailIcon"></i>
            <span id="emptyMail" name="emptyMail"> Enter your mail id </span> 
            <p id="mailAlreadyRegisteredMessage" name="mailAlreadyRegisteredMessage"> Mail already registered...redirecting in <span class="mailAlreadyRegisteredMessageSpan" id="mailAlreadyRegisteredMessageSpan" name="mailAlreadyRegisteredMessageSpan"> 5 </span> s </p> 
            <p id="mailRegisteredMessage" name="mailRegisteredMessage"> Mail registered, login to continue...redirecting in <span class="mailAlreadyRegisteredMessageSpan1" id="mailAlreadyRegisteredMessageSpan1" name="mailAlreadyRegisteredMessageSpan1"> 5 </span> s </p> 
            <span id="unexistingMail" name="unexistingMail"> Provided Mail ID doesn't exist </span> 
            <span id="unknownError" name="unknownError"> There was an issue sending the OTP. Please try again. </span> 
            <span id="notCollegeMailError" name="notCollegeMailError"> Please provide your college Mail ID </span> 
            <span id="incorrectOtpError" name="incorrectOtpError"> Invalid OTP...enter mail id and request for a new one </span> 
            <input type="submit" value="Send OTP" id="sendOtpButton" name="sendOtpButton"> </div> </form> 
            <form action="" method="POST"> <div class="createAccountBox" id="createAccountBox"> 
                <div class="createAccountBoxCredentials"> 
                    <input type="text" name="createAccountBoxPersonName" placeholder="Name" id="createAccountBoxPersonName" maxlength="20" required> <br> <br> 
                    <input type="text" name="createAccountBoxPersonId" placeholder="User ID" id="createAccountBoxPersonId" maxlength="20" required> <br> <br> 
                    <input type="password" name="createAccountBoxPassword" placeholder="Password" id="createAccountBoxPassword" maxlength="20" required> 
                        <i id="createAccountBoxPasswordToggleIcon" class="fa-solid fa-eye" onclick="togglePasswordVisibility()"></i> </div> <div class="createAccountBoxSubmit"> 
                    <input type="submit" value="Sign Up" id="createAccountBoxSubmitButton" name="createAccountBoxSubmitButton"> </div> </div> 
                    <div class="alreadyhaveanaccount" >already have an account <a href="http://localhost/Project/loginPage/loginPage.php">login</a></div><!--login option-->
            </form> 
            <form action="" method="POST"> 
                <div class="verifyOtpContainer" id="verifyOtpContainer"> 
                    <div class="verifyOtpInputs" id="verifyOtpInputs"> <!-- Add a hidden field for combined OTP --> 
                        <input type="hidden" name="combinedOtp" id="combinedOtp"> <!-- OTP Input Fields --> 
                        <input type="text" maxlength="1" required class="otpInput" id="otp1" oninput="handleInput(this, 'otp2')"> 
                        <input type="text" maxlength="1" required class="otpInput" id="otp2" oninput="handleInput(this, 'otp3')" onkeydown="moveToPrev(event, 'otp1')"> 
                        <input type="text" maxlength="1" required class="otpInput" id="otp3" oninput="handleInput(this, 'otp4')" onkeydown="moveToPrev(event, 'otp2')"> 
                        <input type="text" maxlength="1" required class="otpInput" id="otp4" oninput="submitOTP()" onkeydown="moveToPrev(event, 'otp3')"> </div> <br> <span id="timer">02:00</span> 
                            <div class="verifyOtpSubmit" id="verifyOtpSubmit"> 
                        <input type="submit" value="Verify OTP" id="verifyOtpButton" name="verifyOtpButton"> </div> </div>
                <script>
                    let timeLeft = 120 ; // Two minute in seconds
                    const timerDisplay = document.getElementById('timer');

                    function startTimer() {
                        const countdown = setInterval(() => {
                            timeLeft--;

                            // Calculate minutes and seconds
                            const minutes = Math.floor(timeLeft / 60);
                            const seconds = timeLeft % 60;

                            // Display the timer
                            timerDisplay.textContent = `${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                            // Redirect when time runs out
                            if (timeLeft <= 0) {
                                clearInterval(countdown);
                                window.location.href = "http://localhost/Project/signUpPage/signUpPage.php"; // Replace with your desired URL
                            }
                        }, 1000);
                    }
                    // Start the timer
                    startTimer();
                </script>
            </div>

            <script>
                // Array to hold the OTP values
                let otpValues = ['', '', '', ''];

                function handleInput(input, nextId) {
                    const index = parseInt(input.id.replace('otp', '')) - 1; // Get the index of the input
                    otpValues[index] = input.value; // Update the value in the array

                    // Move to the next input field if not empty
                    if (input.value) {
                        moveToNext(input, nextId);
                    }

                    // Update hidden input with combined OTP
                    document.getElementById('combinedOtp').value = otpValues.join('');
                }

                function moveToNext(currentInput, nextId) {
                    const nextInput = document.getElementById(nextId);
                    if (nextInput) {
                        nextInput.focus(); // Focus on the next input
                    }
                }

                function moveToPrev(event, prevId) {
                    if (event.key === 'Backspace') {
                        const prevInput = document.getElementById(prevId);
                        if (prevInput) {
                            prevInput.focus(); // Focus on the previous input when backspacing
                        }
                    }
                }

                function submitOTP() {
                    // Fetch the value of the last input directly
                    otpValues[3] = document.getElementById('otp4').value;

                    const otp = otpValues.join(''); // Join the array into a single string

                    // Update the combined OTP before submitting
                    document.getElementById('combinedOtp').value = otpValues.join('');
                }
            </script>


                

        </form>
    </div>

</body>

</html>