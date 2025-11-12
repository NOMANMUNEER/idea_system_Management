<?php
// Start the session at the very top
session_start();

// Include the database connection
include 'includes/db.php';

// Check if an ID is present in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    // 1. Get the ID from the URL
    $idea_id = $_GET['id'];
    
    // 2. Prepare the DELETE query
    $sql = "DELETE FROM ideas WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        
        // 3. Bind the ID
        $stmt->bind_param("i", $idea_id);
        
        // 4. Execute the statement
        if ($stmt->execute()) {
            // Success
            $_SESSION['message'] = "Idea deleted successfully.";
            $_SESSION['msg_type'] = "danger"; // Use red for deletion
        } else {
            // Failure
            $_SESSION['message'] = "Error: Could not delete the idea.";
            $_SESSION['msg_type'] = "danger";
        }
        
        // 5. Close the statement
        $stmt->close();
        
    } else {
        $_SESSION['message'] = "Error: Could not prepare the query.";
        $_SESSION['msg_type'] = "danger";
    }
    
    // 6. Close the database connection
    $conn->close();

} else {
    // No ID was provided
    $_SESSION['message'] = "Error: Invalid request. No ID provided.";
    $_SESSION['msg_type'] = "danger";
}

// 7. Always redirect back to the index page
header("Location: index.php");
exit();
?>