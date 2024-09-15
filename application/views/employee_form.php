<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Form</title>
    <style>
        /* Set background image for the entire page dynamically using base_url() */
        body {
            background-image: url('<?php echo base_url('assets/image/images.jpg'); ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            color: #fff; /* Text color for visibility */
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #ffffff;
        }

        form {
            background-color: rgba(0, 0, 0, 0.6); /* Add a darker semi-transparent background to the form */
            padding: 30px;
            margin: 50px auto;
            width: 400px; /* Increase the width of the form */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Add shadow for depth */
            color: #fff;
        }

        label, input {
            display: block;
            margin-bottom: 15px;
            font-size: 1.2em; /* Make the font size larger */
        }

        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            font-size: 1.1em;
            border-radius: 5px;
            border: none;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            font-size: 1.2em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Insert Employee Data</h1>
    
    <!-- Display success or error message -->
    <?php if($this->session->flashdata('success')): ?>
        <p style="color:green;"><?php echo $this->session->flashdata('success'); ?></p>
    <?php elseif($this->session->flashdata('error')): ?>
        <p style="color:red;"><?php echo $this->session->flashdata('error'); ?></p>
    <?php endif; ?>

    <form action="<?php echo base_url('ApiClientController/storeEmployee'); ?>" method="post">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
