<?php
require 'db.php';
session_start();

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['username']) && !empty($data['username']) && isset($data['password']) && !empty($data['password'])) {
    $username = $data['username'];
    $password = $data['password'];

    $ip_address = $_SERVER['REMOTE_ADDR'];

    $stmt = $pdo->prepare('SELECT SUM(attempts) AS attempts, MAX(last_attempt) AS last_attempt FROM failed_logins WHERE ip_address = ? OR username = ?');
    $stmt->execute([$ip_address, $username]);
    $row = $stmt->fetch();
    if ($row && $row['attempts'] >= 3 && time() - strtotime($row['last_attempt']) < 60 * 60) {
        die(json_encode(['status' => 'error', 'message' => 'Too many failed login attempts. Please try again later']));
    }

    $stmt = $pdo->prepare('SELECT id, username, password, name, photo, dob FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        $stmt = $pdo->prepare('DELETE FROM failed_logins WHERE ip_address = ? OR username = ?');
        $stmt->execute([$ip_address, $username]);

        $_SESSION['user_id'] = $user['id'];
        unset($user['password']);
        echo json_encode(['status' => 'success', 'message' => 'You have been authorized!', 'user' => $user]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO failed_logins (ip_address, username, attempts, last_attempt) VALUES (?, ?, 1, NOW())
        ON DUPLICATE KEY UPDATE attempts = attempts + 1, last_attempt = NOW()');
        $stmt->execute([$ip_address, $username]);

        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Wrong username or password']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing username or password']);
}
?>