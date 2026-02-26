<?php

if (isset($_POST['addStudent'])) {

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $registrationNumber = $_POST['registrationNumber'];
    $courseCode = $_POST['course'];
    $faculty = $_POST['faculty'];
    $dateRegistered = date("Y-m-d");

    $imageFileNames = []; // Array to hold image file names

    // Process and save images
    $folderPath = "resources/labels/{$registrationNumber}/";
    if (!file_exists($folderPath)) {
        mkdir($folderPath, 0777, true);
    }

    for ($i = 1; $i <= 5; $i++) {
        if (isset($_POST["capturedImage$i"])) {
            $base64Data = explode(',', $_POST["capturedImage$i"])[1];
            $imageData = base64_decode($base64Data);
            $fileName = "{$registrationNumber}_image{$i}.png";
            $labelName = "{$i}.png";
            file_put_contents("{$folderPath}{$labelName}", $imageData);
            $imageFileNames[] = $fileName;
        }

    }
