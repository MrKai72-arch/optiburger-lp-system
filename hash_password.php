<?php
// This file generates a password hash for your demo user
// After running this file, COPY the output and use it in your database

echo "<h1>Password Hash Generator</h1>";
echo "<hr>";

$password = "password123";
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<p><strong>Original Password:</strong> " . $password . "</p>";
echo "<p><strong>Generated Hash:</strong></p>";
echo "<textarea rows='3' cols='60' style='padding: 10px; font-family: monospace;'>" . $hash . "</textarea>";
echo "<p><strong>Copy this hash and use it in your SQL INSERT command!</strong></p>";

// Also show the SQL command ready to use
echo "<hr>";
echo "<h3>Copy this entire SQL command (replace YOUR_HASH with the hash above):</h3>";
echo "<textarea rows='5' cols='80' style='padding: 10px; background: #f0f0f0;'>";
echo "INSERT INTO users (username, password, full_name) VALUES \n";
echo "('student1', '" . $hash . "', 'Ali bin Ahmad');";
echo "</textarea>";
?>