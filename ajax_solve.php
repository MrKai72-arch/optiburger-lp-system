<?php
require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$budget = isset($_GET['budget']) ? floatval($_GET['budget']) : 15;
$max_burgers = isset($_GET['max_burgers']) ? intval($_GET['max_burgers']) : 2;

// Get all burgers
$stmt = $pdo->query("SELECT * FROM burgers");
$burgers = $stmt->fetchAll();

// Find best combo for this budget
$best_taste = -1;
$best_names = [];
$best_price = 0;

for($i = 0; $i < pow(2, count($burgers)); $i++) {
    $total_price = 0;
    $total_taste = 0;
    $count = 0;
    $names = [];
    
    for($j = 0; $j < count($burgers); $j++) {
        if($i & (1 << $j)) {
            $names[] = $burgers[$j]['name'];
            $total_price += $burgers[$j]['price'];
            $total_taste += $burgers[$j]['taste_score'];
            $count++;
        }
    }
    
    if($total_price <= $budget && $count <= $max_burgers && $count > 0 && $total_taste > $best_taste) {
        $best_taste = $total_taste;
        $best_names = $names;
        $best_price = $total_price;
    }
}

header('Content-Type: application/json');
echo json_encode([
    'burgers' => implode(' + ', $best_names),
    'taste' => $best_taste,
    'price' => $best_price
]);
?>