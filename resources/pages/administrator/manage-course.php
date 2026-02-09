<?php


if (isset($_POST["addCourse"])) {
    $courseName = htmlspecialchars(trim($_POST["courseName"])); // Escape and trim whitespace
    $courseCode = htmlspecialchars(trim($_POST["courseCode"]));
    $facultyID = filter_var($_POST["faculty"], FILTER_VALIDATE_INT);
    $dateRegistered = date("Y-m-d");

    if ($courseName && $courseCode && $facultyID) {
        $query = $pdo->prepare("SELECT * FROM tblcourse WHERE courseCode = :courseCode");
        $query->bindParam(':courseCode', $courseCode);
        $query->execute();

        if ($query->rowCount() > 0) {
            $_SESSION['message'] = "Course Already Exists";
        } else {
            $query = $pdo->prepare("INSERT INTO tblcourse (name, courseCode, facultyID, dateCreated) 
                                     VALUES (:name, :courseCode, :facultyID, :dateCreated)");
            $query->bindParam(':name', $courseName);
            $query->bindParam(':courseCode', $courseCode);
            $query->bindParam(':facultyID', $facultyID);
            $query->bindParam(':dateCreated', $dateRegistered);
            $query->execute();

            $_SESSION['message'] = "Course Inserted Successfully";
        }
    } else {
        $_SESSION['message'] = "Invalid input for course";
    }
}

