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

$applications = getAllApplications($pdo); // Function to fetch all applications

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];
    $update_message = $_POST['update_message'];
    $updated_by = $_SESSION['user_id'];

    if (updateApplicationStatus($pdo, $application_id, $status, $update_message, $updated_by)) {
        $_SESSION['message'] = "Application status updated!";
    } else {
        $_SESSION['message'] = "Failed to update application status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <table>
        <tr>
            <th>Applicant</th>
            <th>Job</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($applications as $application): ?>
            <tr>
                <td><?= $application['applicant_name'] ?></td>
                <td><?= $application['job_title'] ?></td>
                <td><?= $application['status'] ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="application_id" value="<?= $application['application_id'] ?>">
                        <select name="status">
                            <option value="accepted">Accept</option>
                            <option value="rejected">Reject</option>
                        </select>
                        <input type="text" name="update_message" placeholder="Message for the applicant" required>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>





</body>

</html>