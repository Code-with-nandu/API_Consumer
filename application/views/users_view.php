
<!DOCTYPE html>
<html>
<head>
    <title>Users List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>List of Users  <button><a href="<?php echo base_url().'client/store'; ?>">Add Employee</a></button></h1>
    <?php if (!empty($users)) : ?>
        <table>
            <thead>
                <tr>
                    <th>Emplyee ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th> Emplyee Details </th>
                    <th> Update Emplyee  </th>
                    <th> Delete Emplyee  </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                     
                        <td><button><a href="<?php echo base_url().'get-employee/'.$user['id']; ?>">View This Emplyee</a></button></td>
                        <td><button><a href="<?php echo base_url().'load-update-form/'; ?>">Update Emplyee</a></button></td>
                        <td><button><a href="<?php echo base_url().'delete_employee/'.$user['id']; ?>">Delete This Emplyee</a></button></td>
                    
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No users found.</p>
    <?php endif; ?>
</body>
</html>
