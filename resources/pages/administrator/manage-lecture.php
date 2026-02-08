<?php

if (isset($_POST["addLecture"])) {
    // Securely handle input
    $firstName = htmlspecialchars(trim($_POST["firstName"]));
    $lastName = htmlspecialchars(trim($_POST["lastName"]));
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $phoneNumber = htmlspecialchars(trim($_POST["phoneNumber"]));
    $faculty = htmlspecialchars(trim($_POST["faculty"]));
    $dateRegistered = date("Y-m-d");
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Secure password hashing

    if ($email && $firstName && $lastName && $phoneNumber && $faculty) {
        try {
            // Check if lecture already exists
            $query = $pdo->prepare("SELECT * FROM tbllecture WHERE emailAddress = :email");
            $query->bindParam(':email', $email);
            $query->execute();

            if ($query->rowCount() > 0) {
                $_SESSION['message'] = "Lecture Already Exists";
            } else {
                // Insert new lecture
                $query = $pdo->prepare("INSERT INTO tbllecture 
                    (firstName, lastName, emailAddress, password, phoneNo, facultyCode, dateCreated) 
                    VALUES (:firstName, :lastName, :email, :password, :phoneNumber, :faculty, :dateCreated)");
                $query->bindParam(':firstName', $firstName);
                $query->bindParam(':lastName', $lastName);
                $query->bindParam(':email', $email);
                $query->bindParam(':password', $hashedPassword);
                $query->bindParam(':phoneNumber', $phoneNumber);
                $query->bindParam(':faculty', $faculty);
                $query->bindParam(':dateCreated', $dateRegistered);

                $query->execute();

                $_SESSION['message'] = "Lecture Added Successfully";
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['message'] = "Invalid input. Please check your data.";
    }

}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="resources/images/logo/attnlg.png" rel="icon">

    <title>AMS - Dashboard</title>
    <link rel="stylesheet" href="resources/assets/css/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">

</head>

<body>

</body>

</html>
