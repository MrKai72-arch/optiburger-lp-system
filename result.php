<?php
require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$solution = $_SESSION['latest_solution'] ?? null;

if(!$solution) {
    header("Location: input.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Optimal Solution - OptiBurger</title>
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
        
        <div class="result-card">
            <h2>Optimal Solution for: <?php echo htmlspecialchars($solution['problem_title']); ?></h2>
            
            <?php if($solution['best_solution'] && !empty($solution['best_solution']['burgers'])): ?>
                <div class="optimal-solution">
                    <h3>✅ Recommended Burgers:</h3>
                    <ul>
                        <?php foreach($solution['best_solution']['burgers'] as $burger): ?>
                            <li>
                                🍔 <?php echo htmlspecialchars($burger['name']); ?> 
                                - RM<?php echo number_format($burger['price'], 2); ?> 
                                (Taste: <?php echo $burger['taste_score']; ?>/10)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="summary">
                    <h3>📊 Summary:</h3>
                    <p>💰 <strong>Total Price:</strong> RM<?php echo number_format($solution['best_solution']['total_price'], 2); ?></p>
                    <p>😋 <strong>Total Taste Score:</strong> <?php echo $solution['best_solution']['total_taste']; ?>/<?php echo $solution['best_solution']['count'] * 10; ?></p>
                    <p>🔥 <strong>Total Calories:</strong> <?php echo $solution['best_solution']['total_calories']; ?> kcal</p>
                    <p>💵 <strong>Budget Remaining:</strong> RM<?php echo number_format($solution['budget'] - $solution['best_solution']['total_price'], 2); ?></p>
                    <p>🍔 <strong>Number of Burgers:</strong> <?php echo $solution['best_solution']['count']; ?></p>
                </div>
                
                <div class="objective-value">
                    <h3>📈 Optimum Objective Value (Z):</h3>
                    <div class="z-value"><?php echo $solution['best_value']; ?></div>
                    <p>Maximum taste satisfaction achievable</p>
                </div>
                
            <?php else: ?>
                <div class="error-message">
                    <p>❌ No valid combination found within your constraints.</p>
                    <p>Suggestions:</p>
                    <ul>
                        <li>Increase your budget</li>
                        <li>Allow more burgers</li>
                        <li>Disable calorie limit</li>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="buttons">
                <a href="dashboard.php" class="btn">🏠 Back to Dashboard</a>
                <a href="input.php" class="btn">🔄 Try Another Problem</a>
            </div>
        </div>
    </div>
</body>
</html>