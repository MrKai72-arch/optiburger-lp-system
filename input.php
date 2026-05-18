<?php
require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get all burgers
$stmt = $pdo->query("SELECT * FROM burgers");
$burgers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Problem - OptiBurger</title>
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
        
        <div class="form-container">
            <h2>Enter Your Burger Optimization Problem</h2>
            
            <form method="POST" action="solve.php">
                <div class="form-group">
                    <label>Problem Title:</label>
                    <input type="text" name="problem_title" placeholder="e.g., My Lunch Budget" required>
                </div>
                
                <div class="form-group">
                    <label>Your Budget (RM):</label>
                    <input type="number" name="budget" step="0.50" value="15" required>
                </div>
                
                <div class="form-group">
                    <label>Maximum number of burgers:</label>
                    <input type="number" name="max_burgers" value="2" min="1" max="4" required>
                </div>
                
                <h3>Select Available Burgers:</h3>
                <table class="burger-table">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Burger</th>
                            <th>Price (RM)</th>
                            <th>Taste Score</th>
                            <th>Calories</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($burgers as $burger): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="burgers[]" value="<?php echo $burger['id']; ?>" checked>
                            </td>
                            <td><?php echo htmlspecialchars($burger['name']); ?></td>
                            <td><?php echo $burger['price']; ?></td>
                            <td><?php echo $burger['taste_score']; ?>/10</td>
                            <td><?php echo $burger['calories']; ?> kcal</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="include_calories">
                        Include calorie limit? (Maximum 1200 kcal)
                    </label>
                </div>
                
                <button type="submit">Find Optimal Solution 🚀</button>
            </form>
        </div>
    </div>
</body>
</html>