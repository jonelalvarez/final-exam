<?php require_once '../core/dbConfig.php'; ?>
<?php require_once '../core/models.php'; ?>

<?php

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

// Get the logged-in admin's user ID
$admin_id = $_SESSION['user_id'];

// Fetch messages sent to this admin
$messages = getMessagesForAdmin($pdo, $admin_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Messages</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f5f5f5;
        color: #333;
    }

    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }

    h2 {
        text-align: center;
        font-size: 2rem;
        color: #333;
        margin-bottom: 30px;
    }

    .msg-rcvd {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    .message {
        border-bottom: 1px solid #e0e0e0;
        padding: 20px 0;
    }

    .message:last-child {
        border-bottom: none;
    }

    .sender {
        font-weight: bold;
        font-size: 1.1rem;
    }

    .time {
        font-size: 0.9rem;
        color: #888;
        margin-top: 5px;
    }

    .text {
        margin: 15px 0;
        font-size: 1rem;
        line-height: 1.5;
        color: #555;
    }

    .reply-form {
        margin-top: 20px;
        background-color: #fafafa;
        padding: 15px;
        border-radius: 8px;
    }

    .reply-form textarea {
        width: 100%;
        height: 100px;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ddd;
        resize: none;
        font-size: 1rem;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    .btn-send {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        font-size: 1rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-send:hover {
        background-color: #45a049;
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
        .container {
            padding: 10px;
        }

        h2 {
            font-size: 1.5rem;
        }

        .msg-rcvd {
            padding: 15px;
        }

        .sender {
            font-size: 1rem;
        }

        .text {
            font-size: 0.95rem;
        }

        .reply-form textarea {
            font-size: 0.95rem;
        }

        .btn-send {
            font-size: 0.95rem;
        }
    }
</style>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="msg-rcvd">
            <h2>Messages Received</h2>
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $message): ?>
                    <div class="message">
                        <div class="sender">
                            From: <?php echo htmlspecialchars($message['sender_username']); ?>
                        </div>
                        <div class="time">
                            Sent at: <?php echo htmlspecialchars($message['sent_at']); ?>
                        </div>
                        <div class="text">
                            <?php echo nl2br(htmlspecialchars($message['message_text'])); ?>
                        </div>

                        <!-- Reply Form -->
                        <form action="../core/handleForms.php" method="POST" class="reply-form">
                            <input type="hidden" name="message_id"
                                value="<?php echo htmlspecialchars($message['message_id'] ?? ''); ?>">
                            <input type="hidden" name="receiver_id"
                                value="<?php echo htmlspecialchars($message['sender_id'] ?? ''); ?>">
                            <textarea name="reply_text" placeholder="Type your reply here..." required></textarea>
                            <button type="submit" name="replyMessageBtn" class="btn-send">Reply</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No messages received yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>