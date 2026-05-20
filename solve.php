<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check if form was submitted
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: input.php");
    exit();
}

// Get form data
$problem_title = isset($_POST['problem_title']) ? $_POST['problem_title'] : 'My Problem';
$budget = isset($_POST['budget']) ? floatval($_POST['budget']) : 15;
$max_burgers = isset($_POST['max_burgers']) ? intval($_POST['max_burgers']) : 2;
$include_calories = isset($_POST['include_calories']) ? true : false;

// Get selected burgers
if(!isset($_POST['burgers']) || empty($_POST['burgers'])) {
    $_SESSION['error'] = "Please select at least one burger!";
    header("Location: input.php");
    exit();
}

$selected_burgers = $_POST['burgers'];

// Fetch burgers from database (include all columns for nutrition)
$placeholders = implode(',', array_fill(0, count($selected_burgers), '?'));
$stmt = $pdo->prepare("SELECT * FROM burgers WHERE id IN ($placeholders)");
$stmt->execute($selected_burgers);
$burgers = $stmt->fetchAll();

if(empty($burgers)) {
    $_SESSION['error'] = "No burgers found!";
    header("Location: input.php");
    exit();
}

// ============================================
// LP SOLVER - Find best combination
// ============================================
$best_taste = -1;
$best_combo = null;
$n = count($burgers);

// Store ALL valid combinations for comparison table
$all_combinations = [];

// Try all possible combinations (2^n)
for($i = 0; $i < pow(2, $n); $i++) {
    $total_price = 0;
    $total_taste = 0;
    $total_calories = 0;
    $total_protein = 0;
    $total_fat = 0;
    $total_carbs = 0;
    $count = 0;
    $current_combo = [];
    
    for($j = 0; $j < $n; $j++) {
        if($i & (1 << $j)) {
            $burger = $burgers[$j];
            $current_combo[] = $burger;
            $total_price += $burger['price'];
            $total_taste += $burger['taste_score'];
            $total_calories += $burger['calories'];
            $total_protein += isset($burger['protein']) ? $burger['protein'] : 0;
            $total_fat += isset($burger['fat']) ? $burger['fat'] : 0;
            $total_carbs += isset($burger['carbs']) ? $burger['carbs'] : 0;
            $count++;
        }
    }
    
    // Check constraints
    $budget_ok = ($total_price <= $budget);
    $max_burgers_ok = ($count <= $max_burgers);
    $calories_ok = ($include_calories ? $total_calories <= 1200 : true);
    
    // Skip empty combinations
    if($count == 0) {
        continue;
    }
    
    // Store ALL valid combinations for comparison table
    if($budget_ok && $max_burgers_ok && $calories_ok) {
        $all_combinations[] = [
            'burgers' => $current_combo,
            'price' => $total_price,
            'taste' => $total_taste,
            'calories' => $total_calories,
            'protein' => $total_protein,
            'fat' => $total_fat,
            'carbs' => $total_carbs,
            'count' => $count
        ];
        
        // Track the best combination
        if($total_taste > $best_taste) {
            $best_taste = $total_taste;
            $best_combo = [
                'burgers' => $current_combo,
                'total_price' => $total_price,
                'total_taste' => $total_taste,
                'total_calories' => $total_calories,
                'total_protein' => $total_protein,
                'total_fat' => $total_fat,
                'total_carbs' => $total_carbs,
                'count' => $count
            ];
        }
    }
}

// Sort all combinations by taste score (highest first) for comparison table
usort($all_combinations, function($a, $b) {
    return $b['taste'] - $a['taste'];
});

// Store top 10 combinations in session (or all if less than 10)
$_SESSION['top_combinations'] = array_slice($all_combinations, 0, 10);

// ============================================
// SAVE TO DATABASE
// ============================================
try {
    // Save problem
    $stmt = $pdo->prepare("INSERT INTO lp_problems (user_id, problem_title, budget, max_burgers) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $problem_title, $budget, $max_burgers]);
    $problem_id = $pdo->lastInsertId();
    
    // Save solution if found
    if($best_combo && !empty($best_combo['burgers'])) {
        foreach($best_combo['burgers'] as $burger) {
            $stmt = $pdo->prepare("INSERT INTO lp_solutions (problem_id, burger_id, quantity, optimal_value) VALUES (?, ?, ?, ?)");
            $stmt->execute([$problem_id, $burger['id'], 1, $best_taste]);
        }
    }
} catch(PDOException $e) {
    // Continue even if save fails (for development)
    error_log("Database error: " . $e->getMessage());
    $problem_id = null;
}

// ============================================
// STORE IN SESSION FOR RESULT PAGE
// ============================================
$_SESSION['latest_solution'] = [
    'problem_title' => $problem_title,
    'budget' => $budget,
    'max_burgers' => $max_burgers,
    'best_solution' => $best_combo,
    'best_value' => $best_taste,
    'problem_id' => $problem_id,
    'include_calories' => $include_calories,
    'all_combinations_count' => count($all_combinations)
];

// ============================================
// REDIRECT TO RESULT PAGE
// ============================================
header("Location: result.php");
exit();
?>