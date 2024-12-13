<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['applyBtn'])) {
    $job_post_id = $_POST['job_post_id'];
    $cover_letter = trim($_POST['cover_letter']);
    $applicant_id = $_SESSION['user_id'];

    // Handle file upload
    if (!empty($_FILES['resume']['name'])) {
        $file_name = $_FILES['resume']['name'];
        $file_tmp = $_FILES['resume']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $upload_dir = 'uploads/resumes/';
        $new_file_name = uniqid() . '.' . $file_ext;

        if ($file_ext != 'pdf') {
            $_SESSION['message'] = "Only PDF files are allowed for resumes.";
            header("Location: index.php");
            exit;
        }

        if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
            $resume_path = $upload_dir . $new_file_name;

            // Save application to database
            if (applyForJob($pdo, $job_post_id, $applicant_id, $cover_letter, $resume_path)) {
                $_SESSION['message'] = "Application submitted successfully!";
            } else {
                $_SESSION['message'] = "Failed to submit application.";
            }
        } else {
            $_SESSION['message'] = "Failed to upload resume.";
        }
    } else {
        $_SESSION['message'] = "Please upload a resume.";
    }
    header("Location: jobposts.php");
    exit;
}
?>