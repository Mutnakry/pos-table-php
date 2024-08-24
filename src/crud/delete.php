<?php
session_start(); // Start the session
// connect to database
// $conn = mysqli_connect('localhost', 'root', '', 'pos_minimart');

// Get the username from the session or set to 'Guest' if not logged in
$userName = isset($_SESSION['names']) ? $_SESSION['names'] : 'Guest';

include('./connection.php');

// Delete category and its image
if (isset($_GET['deletecategory'])) {
    $id = $_GET['deletecategory'];

    // Retrieve the image path from the database
    $query = "SELECT image FROM categories WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $imagePath = $row['image'];

    // Delete the category from the database
    $query = "DELETE FROM categories WHERE id = $id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        // Delete the image file from the server
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $_SESSION['success'] = "Category deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete category.";
    }

     // Redirect to another page with a success or error message
  header('Location: ../category.php');
  exit;
}

// delete.php
if (isset($_GET['deleteroom'])) {
    // Get the id from the URL
    $id = $_GET['deleteroom'];
    // $id = $_POST['id'];

    // Delete the data from the database
    $sql = "DELETE FROM room WHERE `id`='$id'";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        $_SESSION['success'] = "Room deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete room: " . mysqli_error($conn);
    }

    // Redirect to the room page or wherever you need
    header('Location: ../room.php');
    exit;
} else {
    // Redirect back if no id is provided
    $_SESSION['error'] = "Invalid room ID.";
    header('Location: ../room.php');
    exit;
}
?>
