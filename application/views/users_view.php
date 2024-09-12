<html>
<head>
    <title>Users List</title>
</head>
<body>
    <h1>List of Users</h1>
    <ul>
        <?php if (!empty($users)) : ?>
            <?php foreach ($users as $user) : ?>
                <li><?php echo $user['id']; ?> (<?php echo $user['first_name']; ?>)<?php echo $user['last_name']; ?> (<?php echo $user['phone']; ?>)<?php echo $user['email']; ?></li>
            <?php endforeach; ?>
        <?php else : ?>
            <li>No users found.</li>
        <?php endif; ?>
    </ul>
</body>
</html>
