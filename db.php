<?php

mysqli_report(MYSQLI_REPORT_OFF);

$host = "10.0.2.4";
$user = "blinkituser";
$pass = "StrongPassword123";
$db   = "blinkit";

$conn = new mysqli(
    $host,
    $user,
    $pass,
    $db
);

if ($conn->connect_errno) {

    die(
        "<h2>Database Connection Failed</h2>" .
        "<p>Error Code: " .
        $conn->connect_errno .
        "</p>" .
        "<p>" .
        htmlspecialchars(
            $conn->connect_error
        ) .
        "</p>"
    );

}

$conn->set_charset("utf8mb4");

?>
