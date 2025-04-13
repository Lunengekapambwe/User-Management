<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management  System</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="icon" href="images/icon.png" type="image/x-icon">
</head>
<body>
    <header>
        <h1>Welcome User Management System</h1>
    </header>
    <?php
    // Step 1: Define variables to store user input and error messages
$name = $email = $phone = $comment = $id_number = $city = "";
$nameErr = $emailErr = $phoneErr = $imageErr = $idNumberErr = $cityErr = "";

// Step 2: Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate Name (Required and must contain only letters & spaces)
    if (empty($_POST["name"])) { // Check if name is empty
        $nameErr = "Name is required"; // Set error message
    } else {
        $name = clean_input($_POST["name"]); // Clean input
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) { // Check if name contains only letters & spaces
            $nameErr = "Only letters and white space allowed"; // Set error message
        }
    }

    // Validate Email (Required and must be in correct email format)
    if (empty($_POST["email"])) { // Check if email is empty
        $emailErr = "Email is required"; // Set error message
    } else {
        $email = clean_input($_POST["email"]); // Clean input
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Check if email format is valid
            $emailErr = "Invalid email format"; // Set error message
        }
    }

     // Validate Email (Required and must be in correct email format)
    if (empty($_FILES["image"]["name"])) { // Check if image is empty
        $imageErr = "Please attach a photo"; // Set error message
    } else {
        $target_dir = "uploads/"; // Directory to save uploaded files
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
        }
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            $imageErr = "Only JPG, JPEG, PNG, and GIF files are allowed"; // Set error message
        } elseif ($_FILES["image"]["size"] > 2000000) { // Check file size (e.g., 2MB limit)
            $imageErr = "File size must be less than 2MB"; // Set error message
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $imageSuccess = "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
            } else {
                $imageErr = "Sorry, there was an error uploading your file."; // Set error message
            }
        }
    }
    // Validate Phone Number (Required and must be a valid format)
    if (empty($_POST["phone"])) { // Check if phone number is empty
        $phoneErr = "Phone number is required"; // Set error message
    } else {
        $phone = clean_input($_POST["phone"]); // Clean input
        if (!preg_match("/^\+?[0-9]{10,15}$/", $phone)) { // Check if phone number is valid
            $phoneErr = "Invalid phone number format"; // Set error message
        }
    }

    // Validate ID Number (Required)
    if (empty($_POST["id_number"])) {
        $idNumberErr = "ID Number is required"; // Set error message
    } else {
        $id_number = clean_input($_POST["id_number"]); // Clean input
        // Add any additional validation for ID Number if needed
    }

    // Validate City (Required)
    if (empty($_POST["city"])) {
        $cityErr = "City is required"; // Set error message
    } else {
        $city = clean_input($_POST["city"]); // Clean input
        // Add any additional validation for City if needed
    }

    // Validate Comment (Optional, just clean the input)
    $comment = clean_input($_POST["comment"]); // Clean input
}

// Step 3: Function to clean input data (prevents hacking)
function clean_input($data) {
    $data = trim($data); // Remove spaces from the beginning & end
    $data = stripslashes($data); // Remove backslashes (\)
    $data = htmlspecialchars($data); // Convert special characters to HTML entities
    return $data; // Return cleaned data
}
?>

    <main>
    <section id="user-form">
    <h2>User Registration</h2>

<p><span class="required">* Required fields</span></p>

<!-- Step 4: Create the HTML form -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data"> 
    <!-- Name field -->
    Name: <input type="text" name="name" value="<?php echo $name; ?>"> <!-- Retains value after submission -->
    <span class="error">* <?php echo $nameErr; ?></span> <!-- Show error if any -->
    <br><br>
    
    <!-- Email field -->
    Email: <input type="text" name="email" value="<?php echo $email; ?>"> <!-- Retains value -->
    <span class="error">* <?php echo $emailErr; ?></span> <!-- Show error if any -->
    <br><br>
    
    <!-- Website field -->
    Phone No.: <input type="text" name="phone" value="<?php echo $phone; ?>"> <br><!-- Retains value -->
    <span class="error"><?php echo $phoneErr; ?></span> <!-- Show error if any -->
    <br><br>

    <!-- ID Number field -->
    ID Number: <input type="text" name="id_number" value="<?php echo isset($_POST['id_number']) ? htmlspecialchars($_POST['id_number']) : ''; ?>"> <!-- Retains value -->
    <span class="error"><?php echo isset($idNumberErr) ? $idNumberErr : ''; ?></span> <!-- Show error if any -->
    <br><br>
    

    <!-- City field -->
    City: <input type="text" name="city" value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>"> <!-- Retains value -->
    <span class="error"><?php echo isset($cityErr) ? $cityErr : ''; ?></span> <!-- Show error if any -->
    <br><br>
    Upload Photo: <i style="font-size: smaller;"> Passport size photo or portrait </i> <br> <br> <input type="file" name="image" accept="image/*"><br>
    <span class="error">* <?php echo $imageErr; ?></span> <!-- Show error if any -->
    <br><br>
    <!-- Comment textarea -->
    Comment:<br> <textarea name="comment" rows="5" cols="40"><?php echo $comment; ?></textarea> <!-- Retains value -->
    <br><br>
    
    <!-- Submit button -->
    <input type="submit" name="submit" value="Submit"> <!-- Submits the form -->
</form>

    </section>

    <section class="validation">
    <?php

    
        // Step 5: Display submitted data if there are no errors
        if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($nameErr) && empty($emailErr) && empty($websiteErr)) {
            echo "<h2>Your Submitted Data:</h2>"; // Display heading
            echo "Name: " . $name . "<br>"; // Show Name
            echo "Email: " . $email . "<br>"; // Show Email
            echo "Phone Number: " . $phone . "<br>"; // Show Website (if provided)
            echo "Comment: " . nl2br($comment) . "<br>"; // Show Comment with line breaks
        }
        ?>

    </section>



