<?php

// Get the user ID from the session
$uid = $_SESSION['uid'];

// Prepare and execute the query
$query = $connection->prepare("SELECT can_post FROM users WHERE user_id = :uid");
$query->bindParam(':uid', $uid);
$query->execute();

// Fetch the result
$result = $query->fetch(PDO::FETCH_ASSOC);

// Set the session variable 'can_post' based on the query result
$_SESSION['can_post'] = $result['can_post'];