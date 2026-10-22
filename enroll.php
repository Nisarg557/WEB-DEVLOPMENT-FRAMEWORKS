<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $studentName = htmlspecialchars(trim($_POST['studentName']));
    $studentId = htmlspecialchars(trim($_POST['studentId']));
    $eventSelect = htmlspecialchars(trim($_POST['eventSelect']));

    if (!empty($studentName) && !empty($studentId) && !empty($eventSelect)) {
        
        $filename = 'enrolls.csv';
        $fileExists = file_exists($filename);
        
        $file = fopen($filename, 'a');
        
        if ($file) {
            if (!$fileExists) {
                fputcsv($file, ['Student Name', 'Student ID', 'Event Name', 'Enrollment Date']);
            }
            $timestamp = date("Y-m-d H:i:s");
            $formData = [$studentName, $studentId, $eventSelect, $timestamp];
            
            fputcsv($file, $formData);
            
            fclose($file);
            
            echo "<script>
                    alert('Success! $studentName has been enrolled in $eventSelect.');
                    window.location.href = 'mainpage.html';
                  </script>";
            exit();
        } else {
            echo "Error: Unable to open the file. Please check folder permissions.";
        }
    } else {
        echo "<script>
                alert('Error: All fields are required.');
                window.history.back();
              </script>";
    }
} else {
    header("Location: enroll.html");
    exit();
}
?>