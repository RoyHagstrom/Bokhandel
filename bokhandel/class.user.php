<?php

class USER
{
    private $conn;

    public function __construct($pdo)
    {
        $this->conn = $pdo;
    }

    private function cleanInput($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    public function redirect($url)
{
    header("Location: $url");
    exit(); 
}



    public function checkLoginStatus()
{
    return isset($_SESSION["UserID"]);
}


public function register($username, $email, $password)
{
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $this->conn->prepare("INSERT INTO User (Username, Email, Password, Role) VALUES (?, ?, ?, 'Regular')");

    $stmt->bind_param('sss', $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        return "User registered successfully";
    } else {
        return "Registration failed";
    }
}




public function login()
{
    $cleanname = $this->cleanInput($_POST["Username"]);

    $stmt_getUser = $this->conn->prepare(
        "SELECT * FROM User WHERE Username = ? OR Email = ?"
    );

    $stmt_getUser->bind_param('ss', $cleanname, $cleanname);

    $stmt_getUser->execute();

    $result = $stmt_getUser->get_result();

    $user = $result->fetch_assoc();

    if (!$user) {
        return "No such user or email in database";
    }

    $verify = password_verify($_POST["Password"], $user["Password"]);

    if ($verify) {
        $_SESSION["uname"] = $user["Username"];
        $_SESSION["urole"] = $user["Role"];
        $_SESSION["uid"] = $user["UserID"];
        return "success";
    } else {
        return "Incorrect password!";
    }
}

    
}

?>
