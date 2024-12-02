<?php
session_start();

// Check if the admin is logged in
$admin = isset($_SESSION['admin_id']);

// Include database my$mysqliection
require_once __DIR__ . '../../config/database.php';

// Fetch suppliers
$suppliers = [];
$query = "SELECT * FROM suppliers ORDER BY created_at DESC";
$result = $mysqli->query($query);
if ($result->num_rows > 0) {
    $suppliers = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100">

<aside class="w-1/4 bg-gray-800 text-white h-screen fixed">
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-2xl font-bold">Admin Dashboard</h1>
            <p class="text-sm text-gray-400">Manage everything in one place</p>
        </div>
        <nav class="mt-6">
            <ul>
                <li class="mb-2">
                    <a href="dashboard.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Dashboard</a>
                </li>
                <li class="mb-2">
                    <a href="books.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Books</a>
                </li>
                <li class="mb-2">
                    <a href="users.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Users</a>
                </li>
                <li class="mb-2">
                    <a href="authors.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Authors</a>
                </li>
                <li class="mb-2">
                    <a href="publishers.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Publishers</a>
                </li>
                <li class="mb-2">
                    <a href="suppliers.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Suppliers</a>
                </li>
                <li class="mb-2">
                    <a href="stocks.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Stocks</a>
                </li>
                <li class="mb-2">
                    <a href="sales.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Sales</a>
                </li>
                <li class="mt-6">
                    <a href="../logout.php" class="bg-red-500 px-4 py-2 mx-6 rounded text-center block hover:bg-red-600">
                        Logout
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="w-3/4 ml-auto p-6 h-screen overflow-y-auto">
        <h2 class="text-2xl font-bold">Suppliers</h2>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                    <?= $_SESSION['success'] ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

        <div class="mt-4">
            <!-- Add Supplier Button -->
            <button 
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                onclick="document.getElementById('addSupplierModal').classList.remove('hidden')"
            >
                Add Supplier
            </button>

            <!-- Suppliers Table -->
            <div class="mt-6 bg-white rounded shadow p-4">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="p-2 border">#</th>
                            <th class="p-2 border">Name</th>
                            <th class="p-2 border">Contact</th>
                            <th class="p-2 border">Address</th>
                            <th class="p-2 border">Status</th>
                            <th class="p-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suppliers as $supplier): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border"><?= $supplier['supplier_id'] ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($supplier['name']) ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($supplier['contact'] ?? '-') ?></td>
                            <td class="p-2 border"><?= htmlspecialchars($supplier['address'] ?? '-') ?></td>
                            <td class="p-2 border"><?= $supplier['active'] ? 'Active' : 'Inactive' ?></td>
                            <td class="p-2 border">
                                <button 
                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                                    onclick="editSupplier(<?= htmlspecialchars(json_encode($supplier)) ?>)"
                                >
                                    Edit
                                </button>
                                <a 
                                    href="delete_supplier.php?id=<?= $supplier['supplier_id'] ?>" 
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                                    onclick="return confirm('Are you sure you want to delete this supplier?')"
                                >
                                    Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Add Supplier Modal -->
    <div 
        id="addSupplierModal" 
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center"
    >
        <div class="bg-white p-6 rounded shadow-lg w-1/3">
            <h3 class="text-lg font-bold mb-4">Add Supplier</h3>
            <form action="./suppliers/add_supplier.php" method="POST">
                <label class="block mb-2">Name</label>
                <input 
                    type="text" 
                    name="name" 
                    class="w-full p-2 border rounded mb-4" 
                    required 
                />
                <label class="block mb-2">Contact</label>
                <input 
                    type="text" 
                    name="contact" 
                    class="w-full p-2 border rounded mb-4" 
                />
                <label class="block mb-2">Address</label>
                <textarea 
                    name="address" 
                    class="w-full p-2 border rounded mb-4"
                ></textarea>
                <label class="block mb-2">Active</label>
                <select name="active" class="w-full p-2 border rounded mb-4">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                <button 
                    type="submit" 
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                >
                    Save
                </button>
                <button 
                    type="button" 
                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400"
                    onclick="document.getElementById('addSupplierModal').classList.add('hidden')"
                >
                    Cancel
                </button>
            </form>
        </div>
    </div>

    <script>
        function editSupplier(supplier) {
            alert('Edit Supplier Functionality to be implemented.');
            // Populate and show a modal for editing
        }
    </script>
</body>
</html>
