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
            if (empty(trim($searchTerm))) {
                http_response_code(400);
                echo json_encode([]);
                exit;
            }

            $searchTerm = '%' . $this->conn->real_escape_string(trim($searchTerm)) . '%';
            $likeClauses = array();
            for($i = 0; $i < 2; $i++) {
                $likeClauses[] = "(?:Title|Author) LIKE ?";
            }
            $stmt = $this->conn->prepare("SELECT * FROM Book WHERE " . implode(' OR ', $likeClauses) . " ORDER BY BookID DESC LIMIT 4");
            $searchTerms = array_fill(0, 4, $searchTerm);
            array_walk($searchTerms, function(&$value, $index) use ($searchTerm) {
                if(preg_match('/\s/', $value)) {
                    $words = explode(' ', $value);
                    $lastWord = array_pop($words);
                    $value = implode(' ', $words) . '%' . str_repeat('_', strlen($lastWord)) . str_repeat('[a-z]', strlen($lastWord));
                }
            });
            $searchTerms = array_merge($searchTerms, array_fill(0, 2, $searchTerm));
            call_user_func_array([$stmt, 'bind_param'], array_merge(array(str_repeat('s', strlen($searchTerms))), $searchTerms));
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

    public function register($username, $email, $password, $role = "Regular")
    {
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
            return "success";
        } else {
            return "Incorrect password!";
        }
    }
}

