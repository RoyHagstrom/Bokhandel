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
        return trim(htmlspecialchars($data));
    }

    public function redirect($url)
    {
        ob_start(); 
        if (headers_sent()) {
            ob_end_clean(); 
            throw new RuntimeException("Headers already sent");
        }
    
        http_response_code(303);
        header("Location: $url", true, 303);
        ob_end_flush(); 
        exit;
    }
    

    public function checkLoginStatus()
    {
        return isset($_SESSION["uid"]);
    }

    public function searchBooks($searchTerm)
    {
        try {

            $searchTerm = trim($searchTerm);
            if (empty($searchTerm)) {
                http_response_code(400);
                echo json_encode(array("error" => "Search term is empty"));
                return;
            }
            $searchTerm = '%' . $this->conn->real_escape_string($searchTerm) . '%';
    

            $stmt = $this->conn->prepare("SELECT * FROM Book WHERE Title LIKE ? OR Author LIKE ? OR Description LIKE ? OR Genre LIKE ? ORDER BY RAND () LIMIT 4");
            $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();
            

            $books = [];
            while ($row = $result->fetch_assoc()) {
                $books[] = $row;
            }
    

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($books);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(array("error" => "Internal Server Error"));
        }
    }
    
    

public function register($username, $email, $password, $role)
{
    if (empty($username) || empty($email) || empty($password)) {
        throw new InvalidArgumentException("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException("Invalid email format.");
    }

    if (strlen($password) < 8) {
        throw new InvalidArgumentException("Password must be at least 8 characters long.");
    }

    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        throw new InvalidArgumentException("Username can only contain letters and numbers.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $this->conn->prepare(
        "INSERT INTO User (Username, Email, Password, Role) VALUES (?, ?, ?, ?)"
    );

    if (!$stmt) {
        throw new RuntimeException("Registration failed. Please try again later.");
    }

    $stmt->bind_param('ssss', $username, $email, $hashedPassword, $role);

    if ($stmt->execute()) {
        return "User registered successfully";
    } else {
        throw new RuntimeException("Registration failed. Please try again later.");
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
            $_SESSION["ulang"] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            $_SESSION["user_ip"] = $this->getUserIP();
            $_SESSION["access_logs"][] = array(
                "timestamp" => time(),
                "page" => $_SERVER['REQUEST_URI'],
                "action" => "Viewed"
            );
            
            return "success";
        } else {
            return "Incorrect password!";
        }
    }

    public function getUserIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}

