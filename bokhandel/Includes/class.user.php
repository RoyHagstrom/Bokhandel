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

    public function searchBooks($searchTerm)
    {
        try {
            if (empty(trim($searchTerm))) {
                http_response_code(400);
                echo json_encode([]);
                exit;
            }

            $searchTerm = '%' . $this->conn->real_escape_string(trim($searchTerm)) . '%';
            $stmt = $this->conn->prepare("SELECT * FROM Book WHERE Title LIKE ? OR Author LIKE ? ORDER BY RAND() LIMIT 4");
            $stmt->bind_param("ss", $searchTerm, $searchTerm);
            $stmt->execute() or die($this->conn->error);
            $result = $stmt->get_result();
            $books = $result->fetch_all(MYSQLI_ASSOC);

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($books);
        } catch (Exception $e) {
            http_response_code(500);
        }
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
        $stmt_getUser = $this->conn->prepare("SELECT * FROM User WHERE Username = ? OR Email = ?");
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
