<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../../app/notactive/home.php');
    exit;
}

// Include database connection
require_once __DIR__ . '../../../config/database.php';

// Fetch the user to be edited
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
} else {
    header('Location: ../../users.php'); // Redirect if no user ID is provided
    exit;
}

// Handle form submission for updating user data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $active = isset($_POST['active']) ? 1 : 0;

    $update_query = "UPDATE users SET username = ?, email = ?, active = ? WHERE user_id = ?";
    $update_stmt = $mysqli->prepare($update_query);
    $update_stmt->bind_param("ssii", $username, $email, $active, $user_id);
    $update_stmt->execute();

    // Redirect to the user list after update
    header('Location: ../../users.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<!-- Admin Sidebar (same as in users.php) -->

<main class="w-3/4 ml-auto p-6 h-screen overflow-y-auto">
    <h2 class="text-2xl font-bold">Edit User</h2>

    <form method="POST" class="mt-6 bg-white p-6 rounded-lg shadow">
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="mt-2 w-full px-3 py-2 border rounded-md" required>

        <label for="email" class="block text-sm font-medium text-gray-700 mt-4">Email</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="mt-2 w-full px-3 py-2 border rounded-md" required>

        <label for="active" class="block text-sm font-medium text-gray-700 mt-4">Active</label>
        <input type="checkbox" name="active" id="active" <?php echo $user['active'] ? 'checked' : ''; ?> class="mt-2">

        <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md">Update User</button>
    </form>
</main>

</body>
</html>

<?php
$mysqli->close();
?>
