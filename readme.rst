	set up the two CodeIgniter projects, where one exposes data via a REST API and the other consumes it

Step:	Headings 	Details
TS00.47	Download Code igniter 	https://codeigniter.com/userguide3/installation/downloads.html

TS 00.48	Copy paste in htdocs folder in Xampp	C:\xampp\htdocs\6_api\funda_api_s1\application\controllers\Welcome.php
C:\xampp\htdocs\1_api
http://localhost/1_api/funda/
	1. First Project (API Provider)	
	Download the Required file 	1.	RestController.php
2.	Rest_controller_lang.php
3.	Rest.php
4.	Format.php
https://www.fundaofwebit.com/post/codeigniter-3-restful-api-tutorial-using-postman#google_vignette


	Paste the language file in language folder 	C:\xampp\htdocs\6_api\funda_api_s1\application\language\english\rest_controller_lang.php
	Code are here 	
	Copy the( rest.php ) and pestle the following path	C:\xampp\htdocs\6_api\funda_api_s1\application\config\rest.php
	Code are here	
	Copy the restContrer.php & Format.php int he following path	C:\xampp\htdocs\6_api\funda_api_s1\application\libraries\RestController.php
C:\xampp\htdocs\6_api\funda_api_s1\application\libraries\Format.php

		
	`Composer check	Open >new terminal>
Write “Composer”

	Call page through composer 
	php -S localhost:8000
	Call the page 	http://localhost:8000


		 
	Controller create : 
C:\xampp\htdocs\1_api\API_Provider\application\controllers\ApiDemo.php
	1. First Project (API Provider)
In your first project, you have the controller ApiDemoController. Let's refine it a bit by preparing to send data.
ApiDemoController:
php
Copy code
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

use chriskacerguis\RestServer\RestController;

class ApiDemo extends RestController
{
    public function __construct()
    {
        parent::__construct();
        // Load models if necessary
    }

    public function users_get()
    {
        // Sample data that will be returned as JSON
        $users = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com']
        ];

        // Respond with data and HTTP status code
        $this->response($users, RestController::HTTP_OK);
    }
}
?>

•	 Explanation:
o	The users_get function returns a list of users as JSON.
o	You could modify this to fetch data from your database using models.
	Route create 
	$route['api'] = 'ApiDemo/users';
C:\xampp\htdocs\1_api\API_Provider\application\config\routes.php

		
	Call the page	http://localhost:8000/api
		 
		
	2. Second Project (API Consumer)
In the second project, you need to make an HTTP request to the first project's API and handle the response.
	
	ApiClientController:	C:\xampp\htdocs\1_api\API_Consumer\application\controllers\ApiClientController.php

		
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiClientController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_users()
    {
        // The API URL of the first project
        $api_url = 'http://localhost:8000/api';

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            echo "cURL Error: " . curl_error($ch);
        } else {
            // Decode the response
            $data['users'] = json_decode($response, true);

            // Load view and pass the data (You can replace 'users_view' with your actual view)
            $this->load->view('users_view', $data);
        }

        // Close the cURL session
        curl_close($ch);
    }
}
?>


	View to Display Data (users_view.php)	C:\xampp\htdocs\1_api\API_Consumer\application\views\users_view.php

		<html>
<head>
    <title>Users List</title>
</head>
<body>
    <h1>List of Users</h1>
    <ul>
        <?php if (!empty($users)) : ?>
            <?php foreach ($users as $user) : ?>
                <li><?php echo $user['name']; ?> (<?php echo $user['email']; ?>)</li>
            <?php endforeach; ?>
        <?php else : ?>
            <li>No users found.</li>
        <?php endif; ?>
    </ul>
</body>
</html>


	Second Project (Client Routes):
In application/config/routes.php, add the route for the client:
	$route['client/get_users'] = 'ApiClientController/get_users';


	Run the composer	php -S localhost:8080
 
	Call the page 	http://localhost:8080/client/get_users
		 
		
		…or create a new repository on the command line
echo "# API_Consumer" >> README.md
git init
git add README.md
git commit -m "first commit"
git branch -M master
git remote add origin https://github.com/Code-with-nandu/API_Consumer.git
git push -u origin master
…or push an existing repository from the command line
git remote add origin https://github.com/Code-with-nandu/API_Consumer.git
git branch -M master
git push -u origin master

		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		

