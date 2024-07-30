<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json;charset=UTF-8");

// Database connection
require_once("connect_cid101g5.php");

$uploadURl = "/img/";
$filename = dirname(__FILE__);
$updatesave = realpath($filename . "/..") . $uploadURl;

// Initialize result array
$result = [];

try {
    // Prepare SQL statement
    $sql = "UPDATE coach SET coach_img = :file WHERE coach_id = :Id";
    $stmt = $pdo->prepare($sql);

    // Check if a file was uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // Get file info
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Sanitize file name
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        // Directory where the file is going to be saved
        $dest_path = $updatesave . $newFileName;

        // Move the file to the specified directory
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Update the database with the new file path
            $stmt->bindParam(':file', $newFileName);
            $stmt->bindParam(':Id', $_POST['Id'], PDO::PARAM_INT);
            $stmt->execute();
            
            $result['status'] = 'success';
            $result['message'] = 'File is successfully uploaded.';
            $result['file'] = $newFileName;
        } else {
            $result['status'] = 'error';
            $result['message'] = 'There was an error moving the uploaded file.';
        }
    } else {
        $result['status'] = 'error';
        $result['message'] = 'No file uploaded or there was an upload error.';
    }
} catch (Exception $e) {
    $result['status'] = 'error';
    $result['message'] = 'Database error: ' . $e->getMessage();
}

// Return result as JSON
echo json_encode($result);
?>
