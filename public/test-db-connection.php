<!DOCTYPE html>
<html>
<head>
    <title>Database Connection Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Database Connection Test</h1>
    
    <?php
    // Test without Laravel
    $host = 'localhost';
    $port = 3306;
    $database = 'itcenter';
    $username = 'root';
    $password = '';
    
    echo "<h2>Attempting to connect to MySQL...</h2>";
    echo "<p class='info'>Host: $host:$port</p>";
    echo "<p class='info'>Database: $database</p>";
    
    try {
        $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "<p class='success'>✓ Database connection successful!</p>";
        echo "<p class='success'>MySQL is running and accessible.</p>";
        echo "<p class='info'>You can now test the Laravel application.</p>";
        
        // Get MySQL version
        $version = $pdo->query('SELECT VERSION()')->fetchColumn();
        echo "<p class='info'>MySQL Version: $version</p>";
        
        $pdo = null;
        
    } catch (PDOException $e) {
        echo "<p class='error'>✗ Database connection failed!</p>";
        echo "<p class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        
        if (strpos($e->getMessage(), 'Connection refused') !== false || 
            strpos($e->getMessage(), 'actively refused it') !== false) {
            echo "<p class='error'><strong>MySQL server is not running!</strong></p>";
            echo "<p class='info'>This is the perfect scenario to test the database down error page.</p>";
        }
        
        echo "<h3>What to do next:</h3>";
        echo "<ul>";
        echo "<li>If you want to test the error page: Visit <a href='/'>http://127.0.0.1:8000/</a></li>";
        echo "<li>If you want to start MySQL: Run <code>net start MySQL80</code> (Windows) or <code>sudo service mysql start</code> (Linux/Mac)</li>";
        echo "</ul>";
    }
    ?>
    
    <h2>Actions</h2>
    <ul>
        <li><a href="/">Go to Home Page (Test Error Page if DB is down)</a></li>
        <li><a href="javascript:location.reload()">Refresh This Page</a></li>
    </ul>
    
    <hr>
    <small>Place this file in the public directory and access via http://127.0.0.1:8000/test-db-connection.php</small>
</body>
</html>
