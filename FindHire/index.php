<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>
<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}

$getUserByID = getUserByID($pdo, $_SESSION['user_id']);

if ($getUserByID['is_admin'] == 1) {
    header("Location: admin-side/index.php");
}

if ($getUserByID['is_suspended'] == 1) {
    header("Location: suspended-account-error.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Dashboard</title>
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        /* Apply the background image to the body or container */
        body {
            background-image: url('images/background.jpg');
            background-size: cover;
            /* Set the width to 720px and auto height to maintain aspect ratio */

            /* Ensures the image covers the entire page */
            background-position: center center;
            /* Centers the image */
            background-attachment: fixed;
            /* Keeps the background fixed while scrolling */
            margin: 0;
            /* Removes default margin */
            height: auto;

            /* Ensures the body takes up the full height of the viewport */
        }

        /* You can also set this to .container if you want to limit the background to that div */
        .container {
            position: relative;
            z-index: 1;
        }

        /* Make sure navbar stays on top */
        .navbar {
            position: fixed;
            /* Keeps the navbar fixed at the top */
            width: 100%;
            z-index: 10;
            /* Keeps it above the background */
            background-color: rgba(0, 0, 0, 0.7);
            /* Optional: makes the navbar semi-transparent */
        }

        /* Button container styling */
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 200px;
            /* Increase margin to push buttons lower */
        }

        /* Button styling */
        .btn {
            padding: 15px 25px !important;
            /* Increase padding for larger buttons */
            font-size: 18px;
            /* Increase font size for bigger text */
            color: white;
            text-decoration: none;
            border-radius: 120px !important;
            /* Set border radius to 36px */
            text-align: center;
            transition: background-color 0.3s ease;
            /* Smooth hover transition */
        }

        .btn {
            background-color: black !important;
            color: white !important;
        }

        .btn:hover {
            background-color: black;
            /* Darker blue on hover */
            transform: scale(1.05);
            transition: transform 0.2s ease;
        }

        /* Center the logo container */
        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 100px !important;
            /* Optional: Add margin if you need some space above */
        }

        /* Make the logo bigger */
        .logo-container img {
            width: 600px !important;
            /* Set a larger width */
            height: 130px !important;
            /* Maintain aspect ratio */
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="logo-container">
            <img src="images/LOGO1.png" alt="logo" class="card-title card-img-top" style="width: 100px; height: 30px;">
        </div>

        <div class="button-container">
            <a href="jobposts.php" class="btn ">View Job Posting</a>
            <a href="messagehr.php" class="btn ">Message HR</a>
        </div>
    </div>
</body>

</html>