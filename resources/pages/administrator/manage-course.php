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
if (isset($_POST["addUnit"])) {
    $unitName = htmlspecialchars(trim($_POST["unitName"]));
    $unitCode = htmlspecialchars(trim($_POST["unitCode"]));
    $courseID = filter_var($_POST["course"], FILTER_VALIDATE_INT);
    $dateRegistered = date("Y-m-d");

    if ($unitName && $unitCode && $courseID) {
        $query = $pdo->prepare("SELECT * FROM tblunit WHERE unitCode = :unitCode");
        $query->bindParam(':unitCode', $unitCode);
        $query->execute();

        if ($query->rowCount() > 0) {
            $_SESSION['message'] = "Unit Already Exists";
        } else {
            $query = $pdo->prepare("INSERT INTO tblunit (name, unitCode, courseID, dateCreated) 
                                     VALUES (:name, :unitCode, :courseID, :dateCreated)");
            $query->bindParam(':name', $unitName);
            $query->bindParam(':unitCode', $unitCode);
            $query->bindParam(':courseID', $courseID);
            $query->bindParam(':dateCreated', $dateRegistered);
            $query->execute();

            $_SESSION['message'] = "Unit Inserted Successfully";
        }
    } else {
        $_SESSION['message'] = "Invalid input for unit";
    }
}


