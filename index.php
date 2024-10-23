<?php
        session_start();
        if (!isset($_SESSION['server']) || !isset($_SESSION['database']) || !isset($_SESSION['username']) || !isset($_SESSION['password'])) {
            $urlHost = 'http://localhost:8081/example/connect.php';
            header('Location: '.$urlHost);
            
            die("Không có database nào được kết nối. Hãy kết nối database trước!");
        }
        
        $server = $_SESSION['server'];
        $database = $_SESSION['database'];
        $username = $_SESSION['username'];
        $password = $_SESSION['password'];
        
        $conn = new mysqli($server, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">PHP Example</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        <a class="nav-link" href="connect.php">Connect MySQL</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="container my-3">
        <nav class="alert alert-primary" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Index</li>
            </ol>
        </nav>

        <?php
            $sql = "SELECT * FROM course";
            $result = $conn->query($sql);
            
            if ($result === false) {
                die("Error in SQL query: " . $conn->error);
            }
            if ($result->num_rows > 0) {
                echo '<div class="row row-cols-1 row-cols-md-2 g-4">';
                while($row = $result->fetch_assoc()) {
                    echo '<div class="col">
                            <div class="card">
                                <img src="' . $row["imageUrl"] . '" class="card-img-top" alt="' . $row["title"] . '">
                                <div class="card-body">
                                    <h5 class="card-title">' . $row["title"] . '</h5>
                                    <p class="card-text">' . $row["description"] . '</p>
                                </div>
                            </div>
                        </div>';
                }
                echo '</div>';
            }else {
                echo "No courses found";
            }
            $conn->close(); // Đóng kết nối

        if (isset($_POST['submit'])) {
            $filename = $_POST['filename'];  // Lấy tên file từ form
            $content = ""; // Nội dung muốn ghi vào file
        
            // Lấy dữ liệu từ database để ghi vào file
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $content .= "Title: " . $row["title"] . "\n";
                    $content .= "Description: " . $row["description"] . "\n";
                    $content .= "Image: " . $row["image"] . "\n\n";
                }
            } else {
                $content = "No courses found\n";
            }
        
            // Kiểm tra và ghi file
            if (!file_put_contents($filename . ".txt", $content)) {
                echo '<div class="alert alert-danger" role="alert">Failed to write the file.</div>';
                
            } else {
                echo '<div class="alert alert-success" role="alert">File "' . $filename . '.txt" has been written successfully!</div>';
            }
        }
        ?>


        <hr>
        <form class="row" method="POST" enctype="multipart/form-data">
            <div class="col">
                <div class="form-floating mb-3">
                    <input value="data" type="text" class="form-control" id="server" placeholder="File name" name="filename">
                    <label for="data">File name</label>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Write file</button>
            </div>
            <div class="col">
            </div>
        </form>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>