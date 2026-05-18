<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check if form was submitted
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: input.php");
    exit();
}

// Get form data with default values (prevent undefined errors)
$problem_title = isset($_POST['problem_title']) ? $_POST['problem_title'] : 'Untitled Problem';
$budget = isset($_POST['budget']) ? floatval($_POST['budget']) : 15;
$max_burgers = isset($_POST['max_burgers']) ? intval($_POST['max_burgers']) : 2;
$selected_burgers = isset($_POST['burgers']) ? $_POST['burgers'] : [];
$include_calories = isset($_POST['include_calories']);

// Check if at least one burger is selected
if(empty($selected_burgers)) {
    $_SESSION['error'] = "Please select at least one burger!";
    header("Location: input.php");
    exit();
}

// Get burger details from database
$placeholders = str_repeat('?,', count($selected_burgers) - 1) . '?';
$stmt = $pdo->prepare("SELECT * FROM burgers WHERE id IN ($placeholders)");
$stmt->execute($selected_burgers);
$burgers = $stmt->fetchAll();

if(empty($burgers)) {
    $_SESSION['error'] = "No burgers found!";
    header("Location: input.php");
    exit();
}

// BRUTE FORCE LP SOLVER
$best_solution = null;
$best_value = -999999;

$n = count($burgers);
$total_combinations = pow(2, $n);

for($i = 0; $i < $total_combinations; $i++) {
    $combination = [];
    $total_price = 0;
    $total_taste = 0;
    $total_calories = 0;
    $count = 0;
    
    for($j = 0; $j < $n; $j++) {
        if($i & (1 << $j)) {
            $combination[] = $burgers[$j];
            $total_price += $burgers[$j]['price'];
            $total_taste += $burgers[$j]['taste_score'];
            $total_calories += $burgers[$j]['calories'];
            $count++;
        }
    }
    
    // Check constraints
    $valid = true;
    if($total_price > $budget) $valid = false;
    if($count > $max_burgers) $valid = false;
    if($include_calories && $total_calories > 1200) $valid = false;
    
    if($valid && $total_taste > $best_value) {
        $best_value = $total_taste;
        $best_solution = [
            'burgers' => $combination,
            'total_price' => $total_price,
            'total_taste' => $total_taste,
            'total_calories' => $total_calories,
            'count' => $count
        ];
    }
}

// Save problem to database
$stmt = $pdo->prepare("INSERT INTO lp_problems (user_id, problem_title, budget, max_burgers) VALUES (?, ?, ?, ?)");
$stmt->execute([$_SESSION['user_id'], $problem_title, $budget, $max_burgers]);
$problem_id = $pdo->lastInsertId();

// Save solution to database if found
if($best_solution && !empty($best_solution['burgers'])) {
    foreach($best_solution['burgers'] as $burger) {
        $stmt = $pdo->prepare("INSERT INTO lp_solutions (problem_id, burger_id, quantity, optimal_value) VALUES (?, ?, ?, ?)");
        $stmt->execute([$problem_id, $burger['id'], 1, $best_value]);
    }
}

// Store results in session
$_SESSION['latest_solution'] = [
    'problem_title' => $problem_title,
    'budget' => $budget,
    'max_burgers' => $max_burgers,
    'best_solution' => $best_solution,
    'best_value' => $best_value,
    'problem_id' => $problem_id,
    'include_calories' => $include_calories
];

header("Location: result.php");
exit();
?>