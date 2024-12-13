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
    <title>Message an HR</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        body {
            background-image: url('images/background.jpg');
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            margin: 0;
            height: 100vh;
        }

        .container {
            padding: 20px;
        }

        .message-box {
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 15px;
            border-radius: 8px;
        }

        textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .btn-send {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-send:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <?php if (isset($_SESSION['message'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?php echo $_SESSION['message']; ?>',
                confirmButtonText: 'OK'
            });
        </script>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="container">
        <div class="msg-feed">
            <h2>Message an HR</h2>
            <p>Here you can follow up your job application.</p>

            <form action="core/handleForms.php" method="POST">
                <div class="message-box">
                    <label for="receiver_id">Choose HR Admin:</label>
                    <select name="receiver_id" id="receiver_id" class="select2">
                        <option value="" disabled selected>Select an HR Admin</option>
                        <?php
                        $admins = getAllAdmins($pdo);
                        foreach ($admins as $admin) {
                            echo "<option value='{$admin['user_id']}'>{$admin['username']}</option>";
                        }
                        ?>
                    </select>

                    <!-- Message Textarea -->
                    <label for="message_text">Your Message:</label>
                    <textarea name="message_text" id="message_text" placeholder="Type your message here..."
                        required></textarea>

                    <!-- Send Button -->
                    <button type="submit" name="sendMessageBtn" class="btn-send">Send</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();
        });
    </script>
</body>

</html>