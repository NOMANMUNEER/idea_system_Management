<?php
// Start the session at the very top, before any HTML
// This is required to use $_SESSION for success messages.
session_start();

// Include the header (which includes the db connection)
include 'includes/header.php';

// --- Form Processing Logic ---
// Check if the form was submitted by checking the request method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Get the data from the form
    // We use trim() to remove any extra whitespace from the beginning or end
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);

    // 2. Prepare the SQL query
    // We use '?' as placeholders. This is a "prepared statement".
    // It's the most secure way to prevent SQL injection attacks.
    $sql = "INSERT INTO ideas (title, description, category) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        
        // 3. Bind the variables to the placeholders
        // "sss" means the three variables are all "strings"
        $stmt->bind_param("sss", $title, $description, $category);

        // 4. Execute the statement
        if ($stmt->execute()) {
            // Success! Set a session message to show on the main page
            $_SESSION['message'] = "New idea added successfully!";
            $_SESSION['msg_type'] = "success"; // This will be used for alert color
            
            // 5. Redirect back to the index.php page
            header("Location: index.php");
            exit(); // Always call exit() after a header redirect
            
        } else {
            // Error
            echo '<div class="alert alert-danger">Error: Could not save the idea.</div>';
            // You can add more error details if needed: echo "Error: " . $stmt->error;
        }

        // 5. Close the statement
        $stmt->close();
        
    } else {
        echo '<div class="alert alert-danger">Error: Could not prepare the query.</div>';
        // echo "Error: " . $conn->error;
    }
    
    // We don't need to close $conn here, as the footer will handle it
}
// --- End of Form Processing Logic ---
?>

<h1 class="mb-4">Add a New Idea</h1>

<form action="add_idea.php" method="POST">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type.text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="category" class="form-label">Category</label>
        <input type.text" class="form-control" id="category" name="category" placeholder="e.g., Web App, Personal, Business">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="5"></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Save Idea</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

<?php
// Include the footer
include 'includes/footer.php';
?>