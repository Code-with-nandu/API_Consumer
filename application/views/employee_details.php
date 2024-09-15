<!-- application/views/employee_view.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8d7da;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .employee-container {
            text-align: center;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }

        p {
            font-size: 1.2em;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="employee-container">
        <h1>Employee Details</h1>
        <p><strong>First Name:</strong> <?php echo $employee['first_name']; ?></p>
        <p><strong>Last Name:</strong> <?php echo $employee['last_name']; ?></p>
        <p><strong>Phone:</strong> <?php echo $employee['phone']; ?></p>
        <p><strong>Email:</strong> <?php echo $employee['email']; ?></p>
        <button><a href="<?php echo base_url().'client/get_users'; ?>">View All Emplyee</a></button>
    </div>  
</body>
</html>
