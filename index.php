<?php
// Define the file where tasks will be stored
$taskFile = "tasks.txt";

// Load existing tasks
$tasks = file_exists($taskFile) ? file($taskFile, FILE_IGNORE_NEW_LINES) : [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["task"])) {
    $task = trim($_POST["task"]);
    if (!empty($task)) {
        $tasks[] = $task; // Add task to the list
        file_put_contents($taskFile, implode("\n", $tasks) . "\n"); // Save tasks to file
    }
}

// Handle task deletion
if (isset($_GET["delete"])) {
    $deleteIndex = (int) $_GET["delete"];
    if (isset($tasks[$deleteIndex])) {
        unset($tasks[$deleteIndex]);
        $tasks = array_values($tasks); // Reindex array to prevent gaps
        if (empty($tasks)) {
            unlink($taskFile); // Delete the file if no tasks remain
        } else {
            file_put_contents($taskFile, implode("\n", $tasks) . "\n");
        }
    }
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
</head>
<body>
    <h1>My To-Do List</h1>
    
    <!-- Form to Add Task -->
    <form method="post">
        <input type="text" name="task" placeholder="Enter a task" required>
        <button type="submit">Add</button>
    </form>

    <!-- Display Tasks -->
    <ul>
        <?php foreach ($tasks as $index => $task): ?>
            <li>
                <?php echo htmlspecialchars($task); ?>
                <a href="?delete=<?php echo $index; ?>" style="color: red; text-decoration: none;">❌</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
