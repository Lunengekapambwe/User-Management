<?php
$conn = new mysqli("localhost", "root", "", "user_image_db");

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    
    $result = $conn->query("SELECT image_path FROM users WHERE id = $id");
    $row = $result->fetch_assoc();

    if ($row) {
        $imagePath = "uploads/" . $row['image_path'];

        
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        
        $conn->query("DELETE FROM users WHERE id = $id");
    }
}

header("Location: index.php");
exit;
