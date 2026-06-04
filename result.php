<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$solution = isset($_SESSION['latest_solution']) ? $_SESSION['latest_solution'] : null;

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
                <button onclick="toggleTheme()" class="theme-btn" style="background: none; border: 1px solid rgba(255,255,255,0.3); width: auto; padding: 8px 16px;">🌓</button>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
        
        <div class="result-card">
            <h2>✨ Optimal Solution for: <?php echo htmlspecialchars($solution['problem_title']); ?></h2>
            
            <?php if($solution['best_solution'] && !empty($solution['best_solution']['burgers'])): ?>
                
                <!-- OPTIMAL SOLUTION WITH IMAGES -->
                <div class="optimal-solution">
                    <h3>✅ Recommended Burgers:</h3>
                    <ul>
                        <?php foreach($solution['best_solution']['burgers'] as $burger): ?>
                            <li style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                                <?php if(!empty($burger['image_path'])): ?>
                                    <img src="<?php echo $burger['image_path']; ?>" width="60" height="60" style="border-radius: 50%; object-fit: cover;">
                                <?php else: ?>
                                    <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px;">🍔</div>
                                <?php endif; ?>
                                <div style="flex: 1;">
                                    <strong><?php echo htmlspecialchars($burger['name']); ?></strong>
                                    <div style="font-size: 14px; color: rgba(255,255,255,0.7);">
                                        Taste: <?php echo $burger['taste_score']; ?>/10 | Calories: <?php echo $burger['calories']; ?> kcal
                                    </div>
                                </div>
                                <div style="font-size: 20px; font-weight: bold;">
                                    RM<?php echo number_format($burger['price'], 2); ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- SUMMARY -->
                <div class="summary">
                    <div>
                        <div style="font-size: 32px;">💰</div>
                        <div><strong>Total Price</strong></div>
                        <div style="font-size: 24px;">RM<?php echo number_format($solution['best_solution']['total_price'], 2); ?></div>
                    </div>
                    <div>
                        <div style="font-size: 32px;">😋</div>
                        <div><strong>Total Taste</strong></div>
                        <div style="font-size: 24px;"><?php echo $solution['best_solution']['total_taste']; ?> / <?php echo $solution['best_solution']['count'] * 10; ?></div>
                    </div>
                    <div>
                        <div style="font-size: 32px;">🔥</div>
                        <div><strong>Total Calories</strong></div>
                        <div style="font-size: 24px;"><?php echo $solution['best_solution']['total_calories']; ?> kcal</div>
                    </div>
                    <div>
                        <div style="font-size: 32px;">💵</div>
                        <div><strong>Budget Remaining</strong></div>
                        <div style="font-size: 24px;">RM<?php echo number_format($solution['budget'] - $solution['best_solution']['total_price'], 2); ?></div>
                    </div>
                </div>
                
                <!-- OBJECTIVE VALUE -->
                <div class="objective-value">
                    <h3>📈 Optimum Objective Value (Z)</h3>
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
            
            <!-- SIMPLE BUTTONS ONLY -->
            <div class="buttons">
                <a href="dashboard.php" class="btn">🏠 Dashboard</a>
                <a href="input.php" class="btn">🔄 Try Again</a>
            </div>
            
        </div>
    </div>

    <script>
    // Dark/Light mode toggle
    function toggleTheme() {
        document.body.classList.toggle('light-mode');
        const isLight = document.body.classList.contains('light-mode');
        localStorage.setItem('theme', isLight ? 'light' : 'dark');
    }

    // Load saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'light') {
        document.body.classList.add('light-mode');
    }
    </script>
</body>
</html>