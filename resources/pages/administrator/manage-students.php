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

    // Convert image file names to JSON
    $imagesJson = json_encode($imageFileNames);

    // Check for duplicate registration number
    $checkQuery = $pdo->prepare("SELECT COUNT(*) FROM tblstudents WHERE registrationNumber = :registrationNumber");
    $checkQuery->execute([':registrationNumber' => $registrationNumber]);
    $count = $checkQuery->fetchColumn();

    if ($count > 0) {
        $_SESSION['message'] = "Student with the given Registration No: $registrationNumber already exists!";
    } else {
        // Insert new student with images stored as JSON
        $insertQuery = $pdo->prepare("
        INSERT INTO tblstudents 
        (firstName, lastName, email, registrationNumber, faculty, courseCode, studentImage, dateRegistered) 
        VALUES 
        (:firstName, :lastName, :email, :registrationNumber, :faculty, :courseCode, :studentImage, :dateRegistered)
    ");
    
