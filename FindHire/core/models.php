<?php

require_once 'dbConfig.php';

function checkIfUserExists($pdo, $username)
{
    $response = array();
    $sql = "SELECT * FROM user_accounts WHERE username = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$username])) {

        $userInfoArray = $stmt->fetch();

        if ($stmt->rowCount() > 0) {
            $response = array(
                "result" => true,
                "status" => "200",
                "userInfoArray" => $userInfoArray
            );
        } else {
            $response = array(
                "result" => false,
                "status" => "400",
                "message" => "User doesn't exist from the database"
            );
        }
    }

    return $response;

}

function insertNewUser($pdo, $username, $first_name, $last_name, $password)
{
    $response = array();
    $checkIfUserExists = checkIfUserExists($pdo, $username);

    if (!$checkIfUserExists['result']) {

        $sql = "INSERT INTO user_accounts (username, first_name, last_name, password) 
		VALUES (?,?,?,?)";

        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$username, $first_name, $last_name, $password])) {
            $response = array(
                "status" => "200",
                "message" => "User successfully inserted!"
            );
        } else {
            $response = array(
                "status" => "400",
                "message" => "An error occured with the query!"
            );
        }
    } else {
        $response = array(
            "status" => "400",
            "message" => "User already exists!"
        );
    }

    return $response;
}


function getAllUsers($pdo)
{
    $sql = "SELECT * FROM user_accounts";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute();

    if ($executeQuery) {
        return $stmt->fetchAll();
    }
}
function getAllAdmins($pdo)
{
    $sql = "SELECT * FROM user_accounts WHERE is_admin = 1";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute();

    if ($executeQuery) {
        return $stmt->fetchAll();
    }
}

function getUserByID($pdo, $user_id)
{
    $sql = "SELECT * FROM user_accounts WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$user_id]);

    if ($executeQuery) {
        return $stmt->fetch();
    }
}



function createJobPost($pdo, $title, $description, $requirements, $posted_by)
{
    $sql = "INSERT INTO job_posts (job_title, job_description, job_requirements, posted_by) 
            VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$title, $description, $requirements, $posted_by]);
}


function getJobPosts($pdo)
{
    $sql = "SELECT jp.*, ua.username AS posted_by_name FROM job_posts jp
            JOIN user_accounts ua ON jp.posted_by = ua.user_id
            ORDER BY date_posted DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function updateApplicationStatus($pdo, $application_id, $status, $updated_by, $update_message = null)
{
    $sql = "UPDATE job_applications 
            SET status = ?, updated_by = ?, update_message = ?, updated_at = NOW() 
            WHERE application_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$status, $updated_by, $update_message, $application_id]);
}


function getAllApplications($pdo)
{
    $sql = "SELECT * FROM job_applications";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute();

    if ($executeQuery) {
        return $stmt->fetchAll();
    }
}




function applyForJob($pdo, $job_post_id, $applicant_id, $cover_letter, $resume_path)
{
    $sql = "INSERT INTO job_applications (job_post_id, applicant_id, cover_letter, resume_path) 
            VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$job_post_id, $applicant_id, $cover_letter, $resume_path]);
}




// Save a message to the database
function saveMessage($pdo, $sender_id, $receiver_id, $message_text)
{
    $sql = "INSERT INTO messages (sender_id, receiver_id, message_text) VALUES (:sender_id, :receiver_id, :message_text)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':sender_id' => $sender_id,
        ':receiver_id' => $receiver_id,
        ':message_text' => $message_text
    ]);
    return $stmt->rowCount() > 0;
}
function getMessagesForAdmin($pdo, $admin_id)
{
    $sql = "
        SELECT 
            m.message_text, 
            m.sent_at, 
            u.username AS sender_username 
        FROM messages m
        INNER JOIN user_accounts u ON m.sender_id = u.user_id
        WHERE m.receiver_id = :admin_id
        ORDER BY m.sent_at DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':admin_id' => $admin_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function saveReply($pdo, $original_message_id, $sender_id, $receiver_id, $reply_text)
{
    try {
        $sql = "INSERT INTO messages (sender_id, receiver_id, message_text) 
                VALUES (:sender_id, :receiver_id, :reply_text)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':sender_id' => $sender_id,
            ':receiver_id' => $receiver_id,
            ':reply_text' => $reply_text,
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        error_log("Error in saveReply: " . $e->getMessage());
        return false;
    }
}
