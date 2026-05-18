<?php
require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("
    SELECT p.*, 
           GROUP_CONCAT(DISTINCT b.name SEPARATOR ' + ') as burgers,
           MAX(s.optimal_value) as optimal_value
    FROM lp_problems p
    LEFT JOIN lp_solutions s ON p.id = s.problem_id
    LEFT JOIN burgers b ON s.burger_id = b.id
    WHERE p.user_id = ?
    GROUP BY p.id
    ORDER BY p.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$problems = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History - OptiBurger</title>
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
            <h2>📋 Your Optimization History</h2>
            
            <?php if(count($problems) > 0): ?>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Problem Title</th>
                            <th>Budget (RM)</th>
                            <th>Max Burgers</th>
                            <th>Best Combo</th>
                            <th>Taste Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($problems as $problem): ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($problem['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($problem['problem_title']); ?></td>
                            <td>RM<?php echo number_format($problem['budget'], 2); ?></td>
                            <td><?php echo $problem['max_burgers']; ?></td>
                            <td><?php echo htmlspecialchars($problem['burgers'] ?? 'No solution'); ?></td>
                            <td><strong><?php echo $problem['optimal_value'] ?? '-'; ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="error-message">
                    <p>No problems saved yet.</p>
                    <a href="input.php" class="btn">Create your first problem!</a>
                </div>
            <?php endif; ?>
            
            <div style="margin-top: 20px; text-align: center;">
                <a href="dashboard.php" class="btn">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>