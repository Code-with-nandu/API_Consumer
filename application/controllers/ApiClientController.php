
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
