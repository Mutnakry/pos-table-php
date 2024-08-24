<?php
session_start();
include('./connection.php');

// Get the username from the session or set to 'Guest' if not logged in
$userName = isset($_SESSION['names']) ? $_SESSION['names'] : 'Guest';

/// insert category
if (isset($_POST['savecategory'])) {
    // Get the form data
    $names = $_POST['names'];
    $detail = $_POST['detail'];

    // Handle the file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $imageName = basename($image['name']);
        $imageTemp = $image['tmp_name'];
        $imageSize = $image['size'];
        $imageType = $image['type'];

        // Validate file type and size
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
                // Use a prepared statement to prevent SQL injection
                $stmt = $conn->prepare("INSERT INTO categories (names, detail, userNote, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param('ssss', $names, $detail, $userName, $targetFile);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "Category added successfully!";
                } else {
                    $_SESSION['error'] = "Failed to add category.";
                }

                $stmt->close();
            } else {
                $_SESSION['error'] = "Failed to move uploaded file.";
            }
        } else {
            $_SESSION['error'] = "Invalid file type or size.";
        }
    } else {
        $_SESSION['error'] = "No file uploaded or upload error.";
    }

    // Redirect to another page with a success or error message
    header('Location: ../category.php');
    exit;
}


if (isset($_POST['saveroom'])) {
    // Get the form data and sanitize it
    $names = $_POST['names'];
    $table = $_POST['table'];
    // Insert the data into the database
    $sql = "INSERT INTO room (`names`, `table`, `status`,`userNote`) VALUES ('$names', '$table', 1,'$userName')";
   $query=mysqli_query($conn,$sql);
    if ($query) {
        $_SESSION['success'] = "Category added successfully!";
    } else {
        $_SESSION['error'] = "Failed to add category: ";
    }

    // Redirect to the category page or wherever you need
    header('Location: ../room.php');
    exit;
}
?>
