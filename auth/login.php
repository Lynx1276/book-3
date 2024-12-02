<?php
session_start();

// Include database connection
$mysqli = require __DIR__ . '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Check in the `admin` table first
    $stmt = $mysqli->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();

    if ($admin && $password === $admin['password']) {
        // Admin login successful
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_gmail'] = $admin['email'];

        // Update the active status to 1 (online)
        $updateStmt = $mysqli->prepare("UPDATE admin SET active = 1 WHERE admin_id = ?");
        $updateStmt->bind_param("i", $admin['admin_id']);
        $updateStmt->execute();
        $updateStmt->close();

        // Redirect to admin dashboard
        header("Location: ../../admin/dashboard.php");
        exit();
    }

    // If not an admin, check the `users` table
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && $password === $user['password']) {
        // User login successful
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];

        // Update the active status to 1 (online)
        $updateStmt = $mysqli->prepare("UPDATE users SET active = 1 WHERE user_id = ?");
        $updateStmt->bind_param("i", $user['user_id']);
        $updateStmt->execute();
        $updateStmt->close();

        // Redirect to user home page
        header("Location: ../../app/active/home.php");
        exit();
    } else {
        echo "Invalid email or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="bg-white p-8 shadow-md rounded-lg w-96">
        <h1 class="text-2xl font-bold mb-4">Login</h1>
        <?php if (isset($error)): ?>
            <p class="text-red-500 mb-4"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
                <button type="submit"
                        class="w-full bg-indigo-500 text-white py-2 px-4 rounded-md hover:bg-indigo-600 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Login
                </button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <p>Don't have an account? <a href="signup.php" class="text-indigo-500">Sign up here</a></p>
        </div>
    </div>

</body>
</html>
