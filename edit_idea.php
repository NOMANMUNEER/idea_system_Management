<?php
// Start the session for success messages
session_start();

// Include the header
include 'includes/header.php';

// --- Initialize variables ---
$idea_title = '';
$idea_description = '';
$idea_category = '';
$idea_id = 0;

// --- Handle POST Request (Form Submission) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Get data from the submitted form
    $idea_id = $_POST['id']; // Get the ID from the hidden field
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);

    // 2. Prepare the UPDATE query
    // We update the row WHERE the id matches
    $sql = "UPDATE ideas SET title = ?, description = ?, category = ? WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        
        // 3. Bind the variables
        // "sssi" = String, String, String, Integer
        $stmt->bind_param("sssi", $title, $description, $category, $idea_id);

        // 4. Execute the statement
        if ($stmt->execute()) {
            // Success! Set a message and redirect
            $_SESSION['message'] = "Idea updated successfully!";
            $_SESSION['msg_type'] = "success";
            header("Location: index.php");
            exit();
        } else {
            echo '<div class="alert alert-danger">Error: Could not update the idea.</div>';
        }
        
        // 5. Close the statement
        $stmt->close();
    } else {
        echo '<div class="alert alert-danger">Error: Could not prepare the query.</div>';
    }
}

// --- Handle GET Request (Show the Form) ---
// We check if an 'id' was passed in the URL (e.g., edit_idea.php?id=5)
else if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $idea_id = $_GET['id'];
    
    // 1. Prepare a SELECT query to get the existing data
    $sql = "SELECT * FROM ideas WHERE id = ?";
    
    if($stmt = $conn->prepare($sql)) {
        
        // 2. Bind the ID
        // "i" = Integer
        $stmt->bind_param("i", $idea_id);
        
        // 3. Execute and get the result
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            // 4. Fetch the data
            $idea = $result->fetch_assoc();
            
            // 5. Store data in variables to pre-fill the form
            $idea_title = $idea['title'];
            $idea_description = $idea['description'];
            $idea_category = $idea['category'];
            
        } else {
            // No idea found with that ID
            echo '<div class="alert alert-danger">Error: No idea found.</div>';
            exit(); // Stop the script
        }
        
        $stmt->close();
        
    } else {
        echo '<div class="alert alert-danger">Error: Could not prepare the query.</div>';
        exit();
    }
} else {
    // No ID was provided in the URL
    echo '<div class="alert alert-danger">Error: Invalid request. No ID provided.</div>';
    exit(); // Stop the script
}

?>

<h1 class="mb-4">Edit Idea</h1>

<form action="edit_idea.php" method="POST">

    <input type="hidden" name="id" value="<?php echo $idea_id; ?>">

    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" 
               value="<?php echo htmlspecialchars($idea_title); ?>" required>
    </div>
    <div class="mb-3">
        <label for="category" class="form-label">Category</label>
        <input type="text" class="form-control" id="category" name="category" 
               value="<?php echo htmlspecialchars($idea_category); ?>" placeholder="e.g., Web App, Personal, Business">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" 
                  rows="5"><?php echo htmlspecialchars($idea_description); ?></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Update Idea</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

<?php
// Include the footer
include 'includes/footer.php';
?>