<?php
require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plan_name = isset($_POST['plan_name']) ? $_POST['plan_name'] : 'Unnamed Plan';
    $budget = isset($_POST['budget']) ? floatval($_POST['budget']) : 0;
    $burger_ids = isset($_POST['burger_ids']) ? $_POST['burger_ids'] : '';
    $total_taste = isset($_POST['total_taste']) ? intval($_POST['total_taste']) : 0;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO saved_plans (user_id, plan_name, budget, burger_ids, total_taste) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $plan_name, $budget, $burger_ids, $total_taste]);
        
        $_SESSION['success_message'] = "Plan saved successfully!";
    } catch(PDOException $e) {
        $_SESSION['error_message'] = "Failed to save plan: " . $e->getMessage();
    }
    
    header("Location: saved_plans.php");
    exit();
}
?>