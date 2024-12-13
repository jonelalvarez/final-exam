<?php require_once '../core/dbConfig.php'; ?>
<?php require_once '../core/models.php'; ?>

<?php
if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-success'>{$_SESSION['message']}</div>";
    unset($_SESSION['message']); // Clear the message after displaying it
}
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
    <title>HR Dashboard</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h1>Welcome, HR Admin</h1>

        <div class="row">
            <!-- Job Post Form -->
            <div class="col-md-6">
                <h2>Create a Job Post</h2>
                <form action="../core/handleForms.php" method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Job Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Job Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="requirements" class="form-label">Requirements</label>
                        <textarea class="form-control" id="requirements" name="requirements" rows="3"></textarea>
                    </div>
                    <button type="submit" name="createJobPostBtn" class="btn btn-primary">Post Job</button>
                </form>
            </div>

            <!-- Application Management -->
            <div class="col-md-6">
                <h2>Manage Applications</h2>
                <?php
                $applications = getAllApplications($pdo);
                if ($applications):
                    ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Applicant</th>
                                <th>Job</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td><?= $app['applicant_id']; ?></td>
                                    <td><?= $app['job_post_id']; ?></td>
                                    <td><?= ucfirst($app['status']); ?></td>
                                    <td>
                                        <form action="../core/handleForms.php" method="POST" class="d-inline">
                                            <input type="hidden" name="application_id" value="<?= $app['application_id']; ?>">
                                            <button type="submit" name="acceptApplicationBtn"
                                                class="btn btn-success btn-sm">Accept</button>
                                        </form>
                                        <form action="../core/handleForms.php" method="POST" class="d-inline">
                                            <input type="hidden" name="application_id" value="<?= $app['application_id']; ?>">
                                            <button type="submit" name="rejectApplicationBtn"
                                                class="btn btn-danger btn-sm">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No applications found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>



    </div>


</body>

</html>

<!-- <h1 style="text-align: center;">Suspend Accounts</h1>
<?php $getAllUsers = getAllUsers($pdo); ?>
<?php foreach ($getAllUsers as $row) { ?>
    <div class="container" style="display: flex; justify-content: center;">
        <div class="userInfo"
            style="background-color: ghostwhite; border-style: solid; border-color: gray;width: 50%; margin-top: 25px; padding: 50px;">
            <h3>Username: <span style="color: blue"><?php echo $row['username']; ?></span></h3>
            <h3>First Name: <span style="color: blue"><?php echo $row['first_name']; ?></span></h3>
            <h3>Last Name: <span style="color: blue"><?php echo $row['last_name']; ?></span></h3>
            <h3>Date Joined: <span style="color: blue"><?php echo $row['date_added']; ?></span></h3>

            <?php if ($row['is_suspended'] == 0) { ?>
                <a href="suspendAcc.php?user_id=<?php echo $row['user_id']; ?>" style="float: right;">Suspend
                    Account</a>
            <?php } else { ?>
                <a href="suspendAcc.php?user_id=<?php echo $row['user_id']; ?>" style="float: right;">Unsuspend
                    Account</a>
            <?php } ?>

        </div>
    </div>
<?php } ?> -->