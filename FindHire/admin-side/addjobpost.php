<?php require_once '../core/dbConfig.php'; ?>
<?php require_once '../core/models.php'; ?>


<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}

$getUserByID = getUserByID($pdo, $_SESSION['user_id']);

if ($getUserByID['is_admin'] == 0) {
    header("Location: ../index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $job_title = trim($_POST['job_title']);
        $job_description = trim($_POST['job_description']);
        $job_requirements = trim($_POST['job_requirements']);
        $posted_by = $_SESSION['user_id'];

        if (createJobPost($pdo, $job_title, $job_description, $job_requirements, $posted_by)) {
            $_SESSION['message'] = "Job post created successfully!";
        } else {
            $_SESSION['message'] = "Failed to create job post.";
        }
    } ?>
    <form method="POST">
        <label for="job_title">Job Title:</label>
        <input type="text" id="job_title" name="job_title" required>
        <label for="job_description">Description:</label>
        <textarea id="job_description" name="job_description" required></textarea>
        <label for="job_requirements">Requirements:</label>
        <textarea id="job_requirements" name="job_requirements" required></textarea>
        <button type="submit">Post Job</button>
    </form>

</body>

</html>