<?php
    // Start session
    session_start();
    
    // Array to store validation errors
    $errmsg_arr = array();
    
    // Validation error flag
    $errflag = false;
    
    // Connect to MySQL server
    $conn = new mysqli('db', 'root', 'root', 'sales');
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    //Function to sanitize values received from the form. Prevents SQL injection
    function clean($conn, $str) {
        $str = trim($str);
        if (get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }
        return $conn->real_escape_string($str);
    }
    
    // Sanitize the POST values
    $login = clean($conn, $_POST['username']);
    $password = clean($conn, $_POST['password']);
    
    // Input Validations
    if ($login == '') {
        $errmsg_arr[] = 'Username missing';
        $errflag = true;
    }
    if ($password == '') {
        $errmsg_arr[] = 'Password missing';
        $errflag = true;
    }
    
    // If there are input validations, redirect back to the login form
    if ($errflag) {
        $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
        session_write_close();
        header("location: index.php");
        exit();
    }
    
    // Create query
    $qry = "SELECT * FROM user WHERE username='$login' AND password='$password'";
    $result = $conn->query($qry);
    
    // Check whether the query was successful or not
    if ($result) {
        if ($result->num_rows > 0) {
            // Login Successful
            session_regenerate_id();
            $member = $result->fetch_assoc();
            $_SESSION['SESS_MEMBER_ID'] = $member['id'];
            $_SESSION['SESS_FIRST_NAME'] = $member['name'];
            $_SESSION['SESS_LAST_NAME'] = $member['position'];
            $_SESSION['SESS_PRO_PIC'] = $member['profImage'];
            session_write_close();
            header("location: main/index.php");
            exit();
        } else {
            // Login failed
            header("location: index.php");
            exit();
        }
    } else {
        die("Query failed: " . $conn->error);
    }
?>
