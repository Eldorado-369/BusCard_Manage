<?php
$connection = mysqli_connect("localhost", "root", "", "busCardManagementSystem");

if ($connection->connect_error) {
    die("<p> <br> Connection Failed: " . $connection->connect_error . "</p> <br>");
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inboxId = $_POST['inboxId'];
    $cardStudentMail = $_POST['cardStudentMail'];

    // Check if inboxId and cardStudentMail have values
    if (!empty($inboxId) && !empty($cardStudentMail)) {
        // Move the notification to inboxReadTable
        $currentDateAndTime = date("Y-m-d H:i:s");
        $sqlMove = "INSERT INTO inboxReadTable (inboxReadId, readFromMail, readToMail, readContent, dateAndTime)
                SELECT inboxId, fromMail, toMail, content, '$currentDateAndTime' FROM inboxTable WHERE inboxId='$inboxId' AND toMail='$cardStudentMail'";

        $resultMove = mysqli_query($connection, $sqlMove);

        // Check if the move operation succeeded
        if ($resultMove) {
            // Delete the notification from inboxTable
            $sqlDelete = "DELETE FROM inboxTable WHERE inboxId='$inboxId' AND toMail='$cardStudentMail'";
            $resultDelete = mysqli_query($connection, $sqlDelete);

            if (!$resultDelete) {
                // Log or display the error if delete fails
                echo "Error deleting record from inboxTable: " . mysqli_error($connection);
            }
        } else {
            // Log or display the error if insert fails
            echo "Error moving record to inboxReadTable: " . mysqli_error($connection);
        }
    } else {
        echo "Invalid inboxId or cardStudentMail.";
    }
}

// Redirect back to inbox page
header("Location: studentPage.php");
exit();
