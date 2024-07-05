<?php 
$host = 'localhost:3306';  
$user = 'user';  
$pass = '';  
$dbname = 'LoginDB';  
$charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['token'];
        $password = $_POST['password'];

        // Find the email associated with the token
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
        $stmt->execute([$token]);
        $reset = $stmt->fetch();

        if ($reset) {
            // Hash the new password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Update the user's password
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->execute([$hashedPassword, $reset['email']]);

            // Delete the password reset record
            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->execute([$reset['email']]);

            echo "Your password has been reset successfully.";
        } else {
            echo "Invalid or expired token.";
        }
    } else {
        // Display the password reset form
        $token = $_GET['token'];
        echo '<form method="POST">
                <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                <input type="password" name="password" placeholder="New Password" required>
                <button type="submit">Reset Password</button>
              </form>';
    }
?>
