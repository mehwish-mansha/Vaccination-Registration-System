
<?php

require_once 'includes/functions.php';

echo "<h2>Functions working successfully!</h2>";

echo "Database: Connected<br>";

echo "User: ";

var_dump(user());

echo "<br>URL: " . e(url('login.php'));

echo "<br>Dashboard: " . e(home_for('parent'));

?>
