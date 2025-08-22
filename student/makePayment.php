<?php

$connection = mysqli_connect("localhost", "root", "", "busCardManagementSystem");

if ($connection->connect_error) {
    die("<p> <br> Connection Failed: " . $connection->connect_error . "</p> <br>");
}

echo "<center><h2>Make Payment</h2></center>";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submitYellowPayment'])) {
        $studentId = $_POST['makePaymentStudentId'];
        $studentMail = $_POST['makePaymentStudentMail'];
        $stop = $_POST['makePaymentStudentStop'];
        $busNumber = $_POST['makePaymentStudentBusNumber'];

        $stopNumber = $_POST['yellowStop'];
        $campusStopNumber = $_POST['campusStopNumber'];
        $multiplier = ($campusStopNumber - $stopNumber) * 400000;
        $multiplierForDatabase = $multiplier / 100;

        // API Key and Merchant Details - Use environment variables for better security
        $apiKey = "ab3ab177-b468-4791-8071-275c404d8ab0";
        $merchantId = "PGTESTPAYUAT143";
        $keyIndex = 1;

        // Payment Data     
        $paymentData = array(
            'merchantId' => $merchantId,
            'merchantTransactionId' => uniqid("MT_"),
            'merchantUserId' => "MUID123",
            'amount' => $multiplier, // Amount in paisa (100 INR)
            'redirectUrl' => "http://localhost/Project/student/studentPage.php",
            'redirectMode' => "POST",
            'callbackUrl' => "http://localhost/Project/student/studentPage.php",
            'merchantOrderId' => uniqid("ORD_"),
            'mobileNumber' => "9188294021",
            'message' => "Payment of Rs $multiplier",
            'email' => "test@gmail.com",
            'shortName' => "Test",
            'paymentInstrument' => array(
                'type' => "PAY_PAGE",
            ),
        );

        // Convert Payment Data to JSON and Base64 Encode
        $jsonencode = json_encode($paymentData);
        $payloadMain = base64_encode($jsonencode);

        // Hash Payload
        $payload = $payloadMain . "/pg/v1/pay" . $apiKey;
        $sha256 = hash("sha256", $payload);
        $final_x_header = $sha256 . '###' . $keyIndex;

        // Prepare cURL Request
        $request = json_encode(array('request' => $payloadMain));
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-VERIFY: " . $final_x_header,
                "accept: application/json"
            ],
        ]);

        // Execute and Handle Response
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $res = json_decode($response);
            echo "<pre>"; // For debugging the response
            print_r($res);
            echo "</pre>";

            // Check if payment is successful
            if (isset($res->success) && $res->success == '1') {
                if (isset($res->data->instrumentResponse->redirectInfo->url)) {
                    $payUrl = $res->data->instrumentResponse->redirectInfo->url;

                    date_default_timezone_set('Asia/Kolkata');
                    $time = date('Y-m-d H:i:s');

                    $sql3 = "INSERT INTO paymenthistorytable (ID, MAIL, TIME, Amount, Stop, DATE, daySlot, busNumber) VALUES ('$studentId', '$studentMail', '$time', '$multiplierForDatabase', '$stop', NULL, NULL, '$busNumber')";
                    $result3 = mysqli_query($connection, $sql3);

                    // $sql4 = "UPDATE yellowstudent SET status = 'paid', stop = '$stop' WHERE studentId = '$studentId'";
                    // $result4 = mysqli_query($connection, $sql4);


                    $stmtUpdate = "UPDATE studentcard SET status = 'paid', stop = '$stop' WHERE studentId ='$studentId'";
                    $stmtUpdate1 = mysqli_query($connection, $stmtUpdate);

                    $sql99 = "UPDATE buscardcount SET yellowCardCount = yellowCardCount + 1 WHERE busNumber = '$busNumber'";
                    $result99 = mysqli_query($connection, $sql99);

                    $sql = "INSERT INTO inboxtable (fromMail, toMail, content) VALUES ('mani@gmail.com', '$studentMail', 'Your last payment of ₹$multiplierForDatabase was successful');";
                    $result = mysqli_query($connection, $sql);

                    header('Location: ' . $payUrl);
                    exit();
                } else {
                    echo "Payment failed: Redirect URL not available.";
                }
            } else {
                if (isset($res->message)) {
                    echo "Payment failed: " . $res->message;
                } else {
                    echo "Payment failed: No error message available.";
                }
            }
        }
    }

    if (isset($_POST['submitPinkPayment'])) {
        $studentId = $_POST['makePaymentStudentId'];
        $studentMail = $_POST['makePaymentStudentMail'];
        $busNumber = $_POST['makePaymentStudentBusNumber'];

        // Retrieve the value from the correct hidden input field
        $stop = isset($_POST['makePinkPaymentStudentStop']) ? trim($_POST['makePinkPaymentStudentStop']) : '';

        $date = $_POST['makePaymentStudentDate'];
        $daySlot = $_POST['daySlot'];

        $stopNumber = $_POST['pinkStop'];
        $campusStopNumber = $_POST['pinkCampusStopNumber'];
        $multiplier = ($campusStopNumber - $stopNumber) * 1000;
        $multiplierForDatabase = $multiplier / 100;

        // API Key and Merchant Details - Use environment variables for better security
        $apiKey = "ab3ab177-b468-4791-8071-275c404d8ab0";
        $merchantId = "PGTESTPAYUAT143";
        $keyIndex = 1;

        // Payment Data     
        $paymentData = array(
            'merchantId' => $merchantId,
            'merchantTransactionId' => uniqid("MT_"),
            'merchantUserId' => "MUID123",
            'amount' => $multiplier, // Amount in paisa (100 INR)
            'redirectUrl' => "http://localhost/Project/student/studentPage.php",
            'redirectMode' => "POST",
            'callbackUrl' => "http://localhost/Project/student/studentPage.php",
            'merchantOrderId' => uniqid("ORD_"),
            'mobileNumber' => "9188294021",
            'message' => "Payment of Rs $multiplier",
            'email' => "test@gmail.com",
            'shortName' => "Test",
            'paymentInstrument' => array(
                'type' => "PAY_PAGE",
            ),
        );

        // Convert Payment Data to JSON and Base64 Encode
        $jsonencode = json_encode($paymentData);
        $payloadMain = base64_encode($jsonencode);

        // Hash Payload
        $payload = $payloadMain . "/pg/v1/pay" . $apiKey;
        $sha256 = hash("sha256", $payload);
        $final_x_header = $sha256 . '###' . $keyIndex;

        // Prepare cURL Request
        $request = json_encode(array('request' => $payloadMain));
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-VERIFY: " . $final_x_header,
                "accept: application/json"
            ],
        ]);

        // Execute and Handle Response
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $res = json_decode($response);
            echo "<pre>"; // For debugging the response
            print_r($res);
            echo "</pre>";

            // Check if payment is successful
            if (isset($res->success) && $res->success == '1') {
                if (isset($res->data->instrumentResponse->redirectInfo->url)) {
                    $payUrl = $res->data->instrumentResponse->redirectInfo->url;

                    date_default_timezone_set('Asia/Kolkata');
                    $time = date('Y-m-d H:i:s');

                    $sql3 = "INSERT INTO paymenthistorytable (ID, MAIL, TIME, Amount, Stop, DATE, daySlot, busNumber) VALUES ('$studentId', '$studentMail', '$time', '$multiplierForDatabase', '$stop', '$date', '$daySlot', '$busNumber')";
                    $result3 = mysqli_query($connection, $sql3);


                    $sql99 = "UPDATE buscardcount SET pinkCardCount = pinkCardCount + 1 WHERE busNumber = '$busNumber'";
                    $result99 = mysqli_query($connection, $sql99);

                    $sql = "INSERT INTO inboxtable (fromMail, toMail, content) VALUES ('mani@gmail.com', '$studentMail', 'Your last payment of ₹$multiplierForDatabase was successful');";
                    $result = mysqli_query($connection, $sql);

                    header('Location: ' . $payUrl);
                    exit();
                } else {
                    echo "Payment failed: Redirect URL not available.";
                }
            } else {
                if (isset($res->message)) {
                    // echo "Payment failed: " . $res->message;
                    $sql = "INSERT INTO inboxtable (fromMail, toMail, content) VALUES ('mani@gmail.com', '$studentMail', 'Your last payment of ₹$multiplierForDatabase was unsuccessful');";
                    $result = mysqli_query($connection, $sql);
                } else {
                    // echo "Payment failed: No error message available.";
                    $sql = "INSERT INTO inboxtable (fromMail, toMail, content) VALUES ('mani@gmail.com', '$studentMail', 'Your last payment of ₹$multiplierForDatabase was unsuccessful');";
                    $result = mysqli_query($connection, $sql);
                }
            }
        }
    }
}
