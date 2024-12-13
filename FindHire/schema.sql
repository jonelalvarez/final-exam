CREATE TABLE user_accounts (
	user_id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(255),
	first_name VARCHAR(255),
	last_name VARCHAR(255),
	password TEXT,
	is_admin TINYINT(1) NOT NULL DEFAULT 0,
	is_suspended TINYINT(1) NOT NULL DEFAULT 0,
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE job_posts (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    job_title VARCHAR(255) NOT NULL,
    job_description TEXT NOT NULL,
    job_requirements TEXT NOT NULL,
    date_posted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    posted_by INT NOT NULL,
    FOREIGN KEY (posted_by) REFERENCES user_accounts(user_id)
);

CREATE TABLE job_applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,
    job_id INT NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    update_message TEXT,
    updated_by INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (applicant_id) REFERENCES user_accounts(user_id),
    FOREIGN KEY (job_id) REFERENCES job_posts(job_id),
    FOREIGN KEY (updated_by) REFERENCES user_accounts(user_id)
);

CREATE TABLE messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message_text TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES user_accounts(user_id),
    FOREIGN KEY (receiver_id) REFERENCES user_accounts(user_id)
);
CREATE TABLE replies (
    reply_id INT AUTO_INCREMENT PRIMARY KEY,
    original_message_id INT NOT NULL,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    reply_text TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (original_message_id) REFERENCES messages(message_id),
    FOREIGN KEY (sender_id) REFERENCES user_accounts(user_id),
    FOREIGN KEY (receiver_id) REFERENCES user_accounts(user_id)
);

