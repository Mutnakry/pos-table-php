<?php
session_start(); // Start the session
// connect to database
include('./connection.php');

// Get the username from the session or set to 'Guest' if not logged in
$userName = isset($_SESSION['names']) ? $_SESSION['names'] : 'Guest';

if (isset($_POST['savecategory'])) {
    $id = $_POST['id'];
    $names = $_POST['names'];
    $detail = $_POST['detail'];

    // Fetch the old image path from the database
    $query = "SELECT image FROM categories WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $oldImagePath = $row['image'];

    // Handle the file upload
    $imageUpdated = false;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $imageName = basename($image['name']);
        $imageTemp = $image['tmp_name'];
        $imageSize = $image['size'];
        $imageType = $image['type'];

        // Validate the file type and size
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (in_array($imageType, $allowedTypes) && $imageSize <= $maxSize) {
            // Create the upload directory if it doesn't exist
            $targetDirectory = '../uploads/';
            if (!is_dir($targetDirectory)) {
                mkdir($targetDirectory, 0777, true);
            }

            // Create a unique name for the image and move it to the desired directory
            $targetFile = $targetDirectory . uniqid() . '_' . $imageName;

            if (move_uploaded_file($imageTemp, $targetFile)) {
                // Delete the old image file if it exists
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $imageUpdated = true;
            } else {
                $_SESSION['error'] = "Failed to move uploaded file.";
                header('Location: ../category/category.php');
                exit;
            }
        } else {
            $_SESSION['error'] = "Invalid file type or size.";
            header('Location: ../category.php');
            exit;
        }
    }

    if ($imageUpdated) {
        // Update the category with the new image
        $query = "UPDATE categories SET names = '$names', detail = '$detail', userNote = '$userName', image = '$targetFile' WHERE id = $id";
    } else {
        // Update the category without changing the image
        $query = "UPDATE categories SET names = '$names', detail = '$detail', userNote = '$userName' WHERE id = $id";
    }

    $result = mysqli_query($conn, $query);
    if ($result) {
        $_SESSION['success'] = "Category updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update category.";
    }
    // Redirect to another page with a success or error message
    header('Location: ../category.php');
    exit;
}


if (isset($_POST['updateroom'])) {
    // Get the form data and sanitize it
    $id = $_POST['id']; // Assuming you pass the id to identify the record
    $names = $_POST['names'];
    $table = $_POST['table'];
    $status = $_POST['status'];

    // Update the data in the database
    $sql = "UPDATE room SET `names`='$names', `table`='$table', `userNote`='$userName' WHERE `id`='$id'";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        $_SESSION['success'] = "Room updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update room: " . mysqli_error($conn);
    }

    // Redirect to the room page or wherever you need
    header('Location: ../room.php');
    exit;
}
