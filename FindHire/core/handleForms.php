<?php

require_once 'dbConfig.php';
require_once 'models.php';


if (isset($_POST['insertNewUserBtn'])) {
    $username = trim($_POST['username']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($username) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {

        if ($password == $confirm_password) {

            $insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, password_hash($password, PASSWORD_DEFAULT));
            $_SESSION['message'] = $insertQuery['message'];

            if ($insertQuery['status'] == '200') {
                $_SESSION['message'] = $insertQuery['message'];
                $_SESSION['status'] = $insertQuery['status'];
                header("Location: ../login.php");
            } else {
                $_SESSION['message'] = $insertQuery['message'];
                $_SESSION['status'] = $insertQuery['status'];
                header("Location: ../register.php");
            }

        } else {
            $_SESSION['message'] = "Please make sure both passwords are equal";
            $_SESSION['status'] = '400';
            header("Location: ../register.php");
        }

    } else {
        $_SESSION['message'] = "Please make sure there are no empty input fields";
        $_SESSION['status'] = '400';
        header("Location: ../register.php");
    }
}

if (isset($_POST['loginUserBtn1'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $loginQuery = checkIfUserExists($pdo, $username);

        if ($loginQuery['result']) {
            $userIDFromDB = $loginQuery['userInfoArray']['user_id'];
            $usernameFromDB = $loginQuery['userInfoArray']['username'];
            $passwordFromDB = $loginQuery['userInfoArray']['password'];

            if ($password === $passwordFromDB) {
                $_SESSION['user_id'] = $userIDFromDB;
                $_SESSION['username'] = $usernameFromDB;
                header("Location: ../index.php");
                exit;
            } else {
                $_SESSION['message'] = "Username/password invalid";
                $_SESSION['status'] = "400";
                header("Location: ../login.php");
            }

        } else {
            $_SESSION['message'] = "User not found.";
            $_SESSION['status'] = "400";
        }
    } else {
        $_SESSION['message'] = "Please fill in all fields.";
        $_SESSION['status'] = "400";
    }
    header("Location: ../login.php");
    exit;
}


if (isset($_GET['logoutUserBtn'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    header("Location: ../login.php");
}


if (isset($_POST['createJobPostBtn'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $posted_by = $_SESSION['user_id']; // Assuming the logged-in user's ID is stored in session

    if (createJobPost($pdo, $title, $description, $requirements, $posted_by)) {
        $_SESSION['message'] = "Job post created successfully!";
    } else {
        $_SESSION['message'] = "Failed to create job post.";
    }
    header("Location: ../admin-side/index.php");
    exit;
}


if (isset($_POST['acceptApplicationBtn']) || isset($_POST['rejectApplicationBtn'])) {
    $application_id = $_POST['application_id'];
    $status = isset($_POST['acceptApplicationBtn']) ? 'accepted' : 'rejected';
    $updated_by = $_SESSION['user_id']; // Assuming the logged-in user's ID is stored in session
    $update_message = isset($_POST['update_message']) ? $_POST['update_message'] : null;

    if (updateApplicationStatus($pdo, $application_id, $status, $updated_by, $update_message)) {
        $_SESSION['message'] = "Application status updated.";
    } else {
        $_SESSION['message'] = "Failed to update application status.";
    }
    header("Location: ../admin-side/index.php");
    exit;
}



if (isset($_POST['sendMessageBtn'])) {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $message_text = trim($_POST['message_text']);

    if (!empty($receiver_id) && !empty($message_text)) {
        if (saveMessage($pdo, $sender_id, $receiver_id, $message_text)) {
            $_SESSION['message'] = "Message sent successfully!";
        } else {
            $_SESSION['message'] = "Failed to send the message.";
        }
    } else {
        $_SESSION['message'] = "All fields are required.";
    }

    header("Location: ../messagehr.php");
    exit;
}

if (isset($_SESSION['message'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?php echo $_SESSION['message']; ?>',
            confirmButtonText: 'OK'
        });
    </script>
    <?php unset($_SESSION['message']); ?>
<?php endif;

if (isset($_POST['replyMessageBtn'])) {
    $original_message_id = $_POST['message_id'];
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id']; // Applicant's ID
    $reply_text = trim($_POST['reply_text']);

    // Debugging lines
    error_log("Original Message ID: $original_message_id");
    error_log("Sender ID: $sender_id");
    error_log("Receiver ID: $receiver_id");
    error_log("Reply Text: $reply_text");

    if (!empty($original_message_id) && !empty($receiver_id) && !empty($reply_text)) {
        if (saveReply($pdo, $original_message_id, $sender_id, $receiver_id, $reply_text)) {
            $_SESSION['message'] = "Reply sent successfully!";
        } else {
            $_SESSION['message'] = "Failed to send the reply.";
        }
    } else {
        $_SESSION['message'] = "All fields are required.";
    }

    header("Location: ../admin-side/viewmessage.php");
    exit;
}
