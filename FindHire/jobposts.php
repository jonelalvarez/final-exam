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
            background-position: center center;
            background-attachment: fixed;
            margin: 0;
            height: 100vh;
            /* Add space for fixed navbar */
        }

        /* Make sure navbar stays on top */
        .navbar {
            position: fixed;
            width: 100%;
            z-index: 10;
        }

        .job-feed {
            padding: 20px;
            padding-top: 40px;

        }

        .job-posts {
            max-height: 600px;
            /* Set a fixed height */
            overflow-y: scroll;
            /* Enable vertical scrolling */
            margin-top: 20px;
            /* Space between header and posts */
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            /* Optional: Slightly transparent background */
            border-radius: 8px;
            /* Optional: Rounded corners */
        }

        .job-post {
            background-color: #f9f9f9;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .job-post h3 {
            margin: 0;
            font-size: 1.2em;
            color: #333;
        }

        .job-post p {
            font-size: 1em;
            color: #555;
        }

        /* Button styles */
        .btn-dashboard {
            padding: 15px 30px;
            font-size: 18px;
            color: white;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 36px;
            text-align: center;
            display: inline-block;
            margin: 10px 0;
            transition: background-color 0.3s ease;
        }

        .btn-dashboard:hover {
            background-color: #0056b3;
        }

        h2 {
            font-size: 2em;
            color: #fff !important;
        }

        p {
            font-size: 1.1em;
            color: #fff;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="job-feed">
            <h2>Welcome to Job Posting Dashboard</h2>
            <p>Here you can apply for job posts</p>


            <div class="job-posts">
                <?php
                if (isset($_SESSION['message'])) {
                    echo "<div class='alert alert-success'>{$_SESSION['message']}</div>";
                    unset($_SESSION['message']);
                }
                ?>
                <?php
                $jobPosts = getJobPosts($pdo);
                if ($jobPosts) {
                    foreach ($jobPosts as $job) {
                        echo "<div class='job-post'>";
                        echo "<h3>{$job['job_title']}</h3>";
                        echo "<p>{$job['job_description']}</p>";
                        echo "<p><strong>Requirements:</strong> {$job['job_requirements']}</p>";
                        echo "<form action='apply.php' method='POST' enctype='multipart/form-data'>";
                        echo "<input type='hidden' name='job_post_id' value='{$job['job_id']}'>";
                        echo "<div class='mb-3'>";
                        echo "<label for='cover_letter'>Why are you the best applicant?</label>";
                        echo "<textarea name='cover_letter' id='cover_letter' class='form-control' required></textarea>";
                        echo "</div>";
                        echo "<div class='mb-3'>";
                        echo "<label for='resume'>Upload Resume (PDF only):</label>";
                        echo "<input type='file' name='resume' id='resume' class='form-control' accept='.pdf' required>";
                        echo "</div>";
                        echo "<button type='submit' name='applyBtn' class='btn btn-primary'>Apply</button>";
                        echo "</form>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>