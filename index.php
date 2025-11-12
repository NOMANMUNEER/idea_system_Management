<?php
// Include the header
include 'includes/header.php';

// Check for any session messages (e.g., "Idea saved!")
// We'll add this feature later, but it's good to have the spot for it.
if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-' . $_SESSION['msg_type'] . '">' . $_SESSION['message'] . '</div>';
    // Unset the message so it doesn't show again on refresh
    unset($_SESSION['message']);
    unset($_SESSION['msg_type']);
}
?>

<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">My Ideas</h1>
    </div>
</div>

<div class="row">
    <?php
    // 1. Write the SQL query to get all ideas
    // We order by 'status' (pending first) and then by 'created_at' (newest first)
    $sql = "SELECT * FROM ideas ORDER BY status ASC, created_at DESC";
    
    // 2. Run the query
    $result = $conn->query($sql);

    // 3. Check if there are any results
    if ($result->num_rows > 0) {
        // 4. Loop through each row (each idea)
        while($row = $result->fetch_assoc()) {
    ?>
    
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card <?php echo ($row['status'] == 1) ? 'text-bg-light text-decoration-line-through' : ''; ?>">
                <div class="card-body">
                    <h5 class="card-title">
                        <?php echo htmlspecialchars($row['title']); ?>
                    </h5>
                    
                    <h6 class="card-subtitle mb-2 text-muted">
                        Category: <?php echo htmlspecialchars($row['category']); ?>
                    </h6>
                    
                    <p class="card-text">
                        <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                    </p>
                    
                    <p class="card-text"><small class="text-muted">
                        Created: <?php echo date('M j, Y \a\t g:ia', strtotime($row['created_at'])); ?>
                    </small></p>

                    <a href="edit_idea.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                    <a href="delete_idea.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger">Delete</a>
                    
                    <?php if ($row['status'] == 0): ?>
                        <a href="mark_done.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-success float-end">Mark as Done</a>
                    <?php else: ?>
                        <span class="badge bg-success float-end">Done</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        } // End of while loop
    } else {
        // 5. Show a message if no ideas are found
    ?>
        <div class="col">
            <div class="alert alert-info">
                No ideas found yet. <a href="add_idea.php">Add your first one!</a>
            </div>
        </div>
    <?php
    } // End of if
    
    // We don't need to close $conn here because the footer will be included.
    ?>
</div>


<?php
// Include the footer
include 'includes/footer.php';
?>