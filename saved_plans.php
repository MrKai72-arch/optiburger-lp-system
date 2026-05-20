<?php
require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get all saved plans for this user
$stmt = $pdo->prepare("SELECT * FROM saved_plans WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$plans = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Plans - OptiBurger</title>
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
        
        <div class="form-container">
            <h2>💾 Your Saved Burger Plans</h2>
            
            <?php if(isset($_SESSION['success_message'])): ?>
                <div class="success-message" style="background: rgba(78,205,196,0.2); padding: 15px; border-radius: 16px; margin-bottom: 20px; color: #4ECDC4;">
                    ✅ <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['error_message'])): ?>
                <div class="error-message" style="margin-bottom: 20px;">
                    ❌ <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>
            
            <?php if(count($plans) > 0): ?>
                <div class="plans-grid">
                    <?php foreach($plans as $plan): ?>
                        <div class="plan-card" style="background: rgba(255,255,255,0.05); padding: 25px; border-radius: 24px; margin-bottom: 20px; transition: transform 0.3s;">
                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                                <h3 style="margin: 0;">🍔 <?php echo htmlspecialchars($plan['plan_name']); ?></h3>
                                <span style="font-size: 14px; color: rgba(255,255,255,0.6);">📅 <?php echo date('d/m/Y H:i', strtotime($plan['created_at'])); ?></span>
                            </div>
                            <hr style="margin: 15px 0; border-color: rgba(255,255,255,0.1);">
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; text-align: center;">
                                <div>
                                    <div style="font-size: 24px;">💰</div>
                                    <div><strong>RM<?php echo number_format($plan['budget'], 2); ?></strong></div>
                                    <div style="font-size: 12px;">Budget</div>
                                </div>
                                <div>
                                    <div style="font-size: 24px;">😋</div>
                                    <div><strong><?php echo $plan['total_taste']; ?></strong></div>
                                    <div style="font-size: 12px;">Taste Score</div>
                                </div>
                                <div>
                                    <div style="font-size: 24px;">🍔</div>
                                    <div><strong><?php echo substr_count($plan['burger_ids'], '"') / 2; ?></strong></div>
                                    <div style="font-size: 12px;">Burgers</div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 60px 20px;">
                    <div style="font-size: 64px; margin-bottom: 20px;">💾</div>
                    <h3>No saved plans yet</h3>
                    <p style="margin: 20px 0;">Create an optimal burger combo and save it from the results page!</p>
                    <a href="input.php" class="btn">➕ Create Your First Plan</a>
                </div>
            <?php endif; ?>
            
            <div style="margin-top: 30px; text-align: center;">
                <a href="dashboard.php" class="btn">🏠 Back to Dashboard</a>
            </div>
        </div>
    </div>

    <script>
    function toggleTheme() {
        document.body.classList.toggle('light-mode');
        const isLight = document.body.classList.contains('light-mode');
        localStorage.setItem('theme', isLight ? 'light' : 'dark');
    }

    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'light') {
        document.body.classList.add('light-mode');
    }
    </script>
</body>
</html>