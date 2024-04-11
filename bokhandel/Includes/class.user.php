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

public function searchBooks($searchTerm)
    {
        try {
            if (!empty($searchTerm)) {
                $stmt = $this->conn->prepare("SELECT * FROM bokhandel.Book WHERE (Title LIKE ? OR Author LIKE ?) LIMIT 16");
                if (!$stmt) {
                    throw new Exception("Failed to prepare SQL statement: " . $this->conn->error);
                }
                $searchTerm = '%' . $searchTerm . '%';
                $stmt->bind_param("ss", $searchTerm, $searchTerm);
            } else {
                echo json_encode([]);
                exit; 
            }

            if (!$stmt->execute()) {
                throw new Exception("Failed to execute SQL statement: " . $stmt->error);
            }

            $result = $stmt->get_result();

            $books = $result->fetch_all(MYSQLI_ASSOC);

            header('Content-Type: application/json');
            echo json_encode($books);
        } catch (Exception $e) {
            echo "Failed to search for books. Please try again later.";
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
