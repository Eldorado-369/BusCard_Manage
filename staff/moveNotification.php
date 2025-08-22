<?php
$connection = mysqli_connect("localhost", "root", "", "busCardManagementSystem");

if ($connection->connect_error) {
    die("<p> <br> Connection Failed: " . $connection->connect_error . "</p> <br>");
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inboxId = $_POST['inboxId'];
    $staffMail = $_POST['staffMail'];

    // Check if inboxId and staffMail have values
    if (!empty($inboxId) && !empty($staffMail)) {
        // Move the notification to inboxReadTable
        $currentDateAndTime = date("Y-m-d H:i:s");
        $sqlMove = "INSERT INTO inboxReadTable (inboxReadId, readFromMail, readToMail, readContent, dateAndTime)
                SELECT inboxId, fromMail, toMail, content, '$currentDateAndTime' FROM inboxTable WHERE inboxId='$inboxId' AND toMail='$staffMail'";

        $resultMove = mysqli_query($connection, $sqlMove);

        // Check if the move operation succeeded
        if ($resultMove) {
            // Delete the notification from inboxTable
            $sqlDelete = "DELETE FROM inboxTable WHERE inboxId='$inboxId' AND toMail='$staffMail'";
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
        echo "Invalid inboxId or staffMail.";
    }
}

// Redirect back to inbox page
header("Location: staffPage.php");
exit();
