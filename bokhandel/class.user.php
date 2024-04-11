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
    return isset($_SESSION["uid"]);
}


public function register($username, $email, $password)
{
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $this->conn->prepare("INSERT INTO User (Username, Email, Password, Role) VALUES (:username, :email, :password, 'Regular')");

    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

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
            "SELECT * FROM User WHERE Username = :username OR Email = :email"
        );
    
        $stmt_getUser->execute([
            ":username" => $cleanname,
            ":email" => $cleanname
        ]);
    
        $user = $stmt_getUser->fetch(PDO::FETCH_ASSOC);
    
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
