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
    <!-- Chart.js for graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        <div style="font-size: 24px; color: #FFD93D;">RM<?php echo number_format($solution['best_solution']['total_price'], 2); ?></div>
                    </div>
                    <div>
                        <div style="font-size: 32px;">😋</div>
                        <div><strong>Total Taste</strong></div>
                        <div style="font-size: 24px; color: #4ECDC4;"><?php echo $solution['best_solution']['total_taste']; ?> / <?php echo $solution['best_solution']['count'] * 10; ?></div>
                    </div>
                    <div>
                        <div style="font-size: 32px;">🔥</div>
                        <div><strong>Total Calories</strong></div>
                        <div style="font-size: 24px;"><?php echo $solution['best_solution']['total_calories']; ?> kcal</div>
                    </div>
                    <div>
                        <div style="font-size: 32px;">💵</div>
                        <div><strong>Budget Remaining</strong></div>
                        <div style="font-size: 24px; color: #FF6B6B;">RM<?php echo number_format($solution['budget'] - $solution['best_solution']['total_price'], 2); ?></div>
                    </div>
                </div>
                
                <!-- OBJECTIVE VALUE -->
                <div class="objective-value">
                    <h3>📈 Optimum Objective Value (Z)</h3>
                    <div class="z-value"><?php echo $solution['best_value']; ?></div>
                    <p>Maximum taste satisfaction achievable</p>
                </div>
                
                <!-- CHART.JS GRAPH -->
                <div style="margin: 40px 0; text-align: center;">
                    <h3>📊 Taste Score & Price Comparison</h3>
                    <canvas id="tasteChart" style="max-width: 500px; margin: 20px auto; max-height: 400px;"></canvas>
                </div>
                
                <!-- NUTRITION INFO -->
                <div class="nutrition-box" style="background: rgba(255,255,255,0.05); padding: 25px; border-radius: 24px; margin: 30px 0;">
                    <h3>🥗 Nutrition Information</h3>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px; text-align: center;">
                        <div>
                            <div style="font-size: 40px;">🥩</div>
                            <div><strong id="totalProtein"><?php echo $solution['best_solution']['total_protein'] ?? 0; ?>g</strong></div>
                            <div>Protein</div>
                        </div>
                        <div>
                            <div style="font-size: 40px;">🧈</div>
                            <div><strong id="totalFat"><?php echo $solution['best_solution']['total_fat'] ?? 0; ?>g</strong></div>
                            <div>Fat</div>
                        </div>
                        <div>
                            <div style="font-size: 40px;">🍚</div>
                            <div><strong id="totalCarbs"><?php echo $solution['best_solution']['total_carbs'] ?? 0; ?>g</strong></div>
                            <div>Carbs</div>
                        </div>
                    </div>
                </div>
                
                <!-- SENSITIVITY ANALYSIS SLIDER -->
                <div class="sensitivity-box" style="background: rgba(255,255,255,0.05); padding: 25px; border-radius: 24px; margin: 30px 0;">
                    <h3>💰 Try Different Budgets</h3>
                    <p style="margin: 15px 0;">Move the slider to see how your burger combo changes:</p>
                    
                    <input type="range" id="budgetSlider" min="5" max="30" step="0.5" 
                           value="<?php echo $solution['budget']; ?>" 
                           style="width: 100%; margin: 20px 0;">
                    
                    <div style="display: flex; justify-content: space-between;">
                        <span>RM5</span>
                        <span>RM15</span>
                        <span>RM30</span>
                    </div>
                    
                    <div id="sensitivityResult" style="margin-top: 25px; padding: 20px; background: rgba(255,255,255,0.03); border-radius: 20px;">
                        <p>💰 <strong>Current Budget:</strong> RM<span id="budgetValue"><?php echo $solution['budget']; ?></span></p>
                        <div id="sensitivityData">
                            <p>🍔 <strong>Recommended Combo:</strong> <?php echo implode(' + ', array_column($solution['best_solution']['burgers'], 'name')); ?></p>
                            <p>😋 <strong>Taste Score:</strong> <?php echo $solution['best_value']; ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- TOP 5 COMPARISON TABLE -->
                <?php if(isset($_SESSION['top_combinations']) && !empty($_SESSION['top_combinations'])): ?>
                <div style="margin: 40px 0;">
                    <h3>🏆 Top 5 Best Combinations</h3>
                    <table class="comparison-table" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                        <thead>
                            <tr style="background: rgba(78,205,196,0.2);">
                                <th style="padding: 12px;">Rank</th>
                                <th style="padding: 12px;">Burgers</th>
                                <th style="padding: 12px;">Price (RM)</th>
                                <th style="padding: 12px;">Taste Score</th>
                                <th style="padding: 12px;">Calories</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($_SESSION['top_combinations'] as $rank => $combo): ?>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <td style="padding: 12px; text-align: center;">#<?php echo $rank + 1; ?></td>
                                <td style="padding: 12px;">
                                    <?php echo implode(' + ', array_column($combo['burgers'], 'name')); ?>
                                </td>
                                <td style="padding: 12px;">RM<?php echo number_format($combo['price'], 2); ?></td>
                                <td style="padding: 12px;">
                                    <strong><?php echo $combo['taste']; ?></strong>/<?php echo $combo['count'] * 10; ?>
                                </td>
                                <td style="padding: 12px;"><?php echo $combo['calories']; ?> kcal</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
                
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
            
            <!-- BUTTONS -->
            <div class="buttons">
                <a href="dashboard.php" class="btn">🏠 Dashboard</a>
                <a href="input.php" class="btn">🔄 Try Again</a>
                <button onclick="window.print()" class="btn" style="background: #FF6B6B;">📄 Save as PDF</button>
                <button onclick="savePlan()" class="btn" style="background: #FFD93D; color: #333;">💾 Save This Plan</button>
            </div>
            
            <!-- SHARE BUTTONS -->
            <div style="display: flex; gap: 10px; justify-content: center; margin: 20px 0; flex-wrap: wrap;">
                <button onclick="shareOnTwitter()" class="btn" style="background: #1DA1F2;">🐦 Twitter</button>
                <button onclick="shareOnWhatsApp()" class="btn" style="background: #25D366;">💬 WhatsApp</button>
                <button onclick="copyToClipboard()" class="btn" style="background: #FF6B6B;">📋 Copy Link</button>
            </div>
        </div>
    </div>

    <script>
    // Chart.js initialization
    <?php if($solution['best_solution'] && !empty($solution['best_solution']['burgers'])): ?>
    const burgerNames = <?php echo json_encode(array_column($solution['best_solution']['burgers'], 'name')); ?>;
    const tasteScores = <?php echo json_encode(array_column($solution['best_solution']['burgers'], 'taste_score')); ?>;
    const prices = <?php echo json_encode(array_column($solution['best_solution']['burgers'], 'price')); ?>;

    const ctx = document.getElementById('tasteChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: burgerNames,
            datasets: [
                {
                    label: 'Taste Score (1-10)',
                    data: tasteScores,
                    backgroundColor: '#4ECDC4',
                    borderRadius: 10,
                    yAxisID: 'y'
                },
                {
                    label: 'Price (RM)',
                    data: prices,
                    backgroundColor: '#FFD93D',
                    borderRadius: 10,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top', labels: { color: '#fff' } },
                title: { display: true, text: 'Burger Comparison Chart', color: '#fff' }
            },
            scales: {
                y: { 
                    title: { display: true, text: 'Taste Score', color: '#fff' }, 
                    beginAtZero: true, 
                    max: 10,
                    ticks: { color: '#fff' }
                },
                y1: { 
                    title: { display: true, text: 'Price (RM)', color: '#fff' }, 
                    position: 'right', 
                    beginAtZero: true,
                    ticks: { color: '#fff' }
                },
                x: { ticks: { color: '#fff' } }
            }
        }
    });
    <?php endif; ?>

    // Sensitivity Analysis
    const budgetSlider = document.getElementById('budgetSlider');
    if(budgetSlider) {
        const budgetValue = document.getElementById('budgetValue');
        const sensitivityData = document.getElementById('sensitivityData');
        
        budgetSlider.addEventListener('input', function() {
            const newBudget = this.value;
            budgetValue.innerText = newBudget;
            sensitivityData.innerHTML = '<div class="loading"></div> Calculating...';
            
            fetch(`ajax_solve.php?budget=${newBudget}&max_burgers=<?php echo $solution['max_burgers'] ?? 2; ?>`)
                .then(response => response.json())
                .then(data => {
                    sensitivityData.innerHTML = `
                        <p>🍔 <strong>Recommended Combo:</strong> ${data.burgers || 'No valid combo'}</p>
                        <p>😋 <strong>Taste Score:</strong> ${data.taste || 0}</p>
                        <p>💰 <strong>Total Price:</strong> RM${data.price || 0}</p>
                    `;
                })
                .catch(error => {
                    sensitivityData.innerHTML = '<p>Error calculating. Please try again.</p>';
                });
        });
    }

    // Share functions
    function shareOnTwitter() {
        const text = encodeURIComponent(`I found the perfect burger combo with OptiBurger! Taste score: <?php echo $solution['best_value'] ?? 0; ?>/20`);
        window.open(`https://twitter.com/intent/tweet?text=${text}`, '_blank');
    }

    function shareOnWhatsApp() {
        const text = encodeURIComponent(`Check out my optimal burger combo from OptiBurger! Taste score: <?php echo $solution['best_value'] ?? 0; ?>/20`);
        window.open(`https://wa.me/?text=${text}`, '_blank');
    }

    function copyToClipboard() {
        const url = window.location.href;
        navigator.clipboard.writeText(url);
        alert('✅ Link copied to clipboard! Share with your friends!');
    }

    function savePlan() {
        const planName = prompt('Enter a name for this plan:', 'My Burger Combo');
        if(planName) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'save_plan.php';
            
            const inputs = [
                {name: 'plan_name', value: planName},
                {name: 'budget', value: '<?php echo $solution['budget']; ?>'},
                {name: 'burger_ids', value: '<?php echo json_encode(array_column($solution['best_solution']['burgers'], 'id')); ?>'},
                {name: 'total_taste', value: '<?php echo $solution['best_value']; ?>'}
            ];
            
            inputs.forEach(input => {
                const field = document.createElement('input');
                field.type = 'hidden';
                field.name = input.name;
                field.value = input.value;
                form.appendChild(field);
            });
            
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Dark/Light mode toggle
    function toggleTheme() {
        document.body.classList.toggle('light-mode');
        const isLight = document.body.classList.contains('light-mode');
        localStorage.setItem('theme', isLight ? 'light' : 'dark');
        
        // Update chart colors if needed
        location.reload();
    }

    // Load saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'light') {
        document.body.classList.add('light-mode');
    }
    </script>
</body>
</html>