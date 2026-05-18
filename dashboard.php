<?php
require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - OptiBurger</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🍔 OptiBurger</h1>
            <div class="user-info">
                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
        
        <div class="dashboard">
            <h2>What would you like to do?</h2>
            
            <div class="menu-grid">
                <a href="input.php" class="menu-card">
                    <div class="icon">➕</div>
                    <h3>New Problem</h3>
                    <p>Create a new burger optimization problem</p>
                </a>
                
                <a href="history.php" class="menu-card">
                    <div class="icon">📋</div>
                    <h3>History</h3>
                    <p>View your past optimization solutions</p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>