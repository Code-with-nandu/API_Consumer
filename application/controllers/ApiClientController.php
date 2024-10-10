<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiClientController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('curl'); // Load cURL library
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function login_view()
    {
        // Load the login view
        $this->load->view('login_form');
    }

    public function login()
    {
        // Get login data from the form
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Determine the API URL based on the environment
        $url = $this->config->item('environment') === 'production'
            ? "https://krishnendudalui.in.net/API_Provider/index.php/auth/login" // Live URL
            : "http://localhost/1_api/API_Provider/index.php/auth/login"; // Local URL

        // Prepare data to send to the API Provider
        $data = [
            'username' => $username,
            'password' => $password
        ];

        // Initialize cURL
        $ch = curl_init($url);
        $json_data = json_encode($data);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json_data)
        ]);

        // Execute the cURL request and get the response
        $response = curl_exec($ch);

        // Handle cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            $this->session->set_flashdata('error', $error);
            redirect('auth-login'); // Redirect back to login form with error message
        } else {
            curl_close($ch);
            // Decode the API response
            $api_response = json_decode($response, true);

            // Check the response status
            if (!empty($api_response['status']) && $api_response['status'] === true) {
                // Store the token in the session
                $this->session->set_userdata('token', $api_response['token']);
                // Redirect to a protected page
                redirect('client/get_users');
            } else {
                $this->session->set_flashdata('error', $api_response['message'] ?? 'Login failed');
                redirect('auth-login');
            }
        }
    }

    // public function get_users()
    // {
    //     // Determine the API URL for getting users
    //     $api_url = $this->config->item('environment') === 'production'
    //         ? "https://krishnendudalui.in.net/API_Provider/index.php/api"
    //         : "http://localhost/1_api/API_Provider/index.php/api";

    //     // Initialize cURL session
    //     $ch = curl_init($api_url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //     // Execute the cURL request
    //     $response = curl_exec($ch);

    //     // Check for errors
    //     if ($response === false) {
    //         echo "cURL Error: " . curl_error($ch);
    //     } else {
    //         // Decode the response
    //         $data['users'] = json_decode($response, true);
    //         // Load view and pass the data
    //         $this->load->view('users_view', $data);
    //     }

    //     // Close the cURL session
    //     curl_close($ch);
    // }
    public function get_users()
{
    // Determine the API URL
    $api_url = $this->config->item('environment') === 'production'
        ? "https://krishnendudalui.in.net/API_Provider/index.php/api"
        : "http://localhost/1_api/API_Provider/index.php/api";

    // Initialize cURL session
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for errors
    if ($response === false) {
        echo "cURL Error: " . curl_error($ch);
        return; // exit if there is an error
    }

    // Log the response for debugging
    log_message('debug', 'API Response: ' . $response);
    
    // Decode the response
    $data['users'] = json_decode($response, true);
    
    // Check if users were returned
    if (empty($data['users'])) {
        echo "No users found in the API response.";
    } else {
        // Load view and pass the data
        $this->load->view('users_view', $data);
    }

    // Close the cURL session
    curl_close($ch);
}


    public function storeEmployee()
    {
        // Data to be sent to the API
        $data = [
            'first_name' => $this->input->post('first_name'),
            'last_name'  => $this->input->post('last_name'),
            'phone'      => $this->input->post('phone'),
            'email'      => $this->input->post('email'),
        ];

        // cURL configuration for POST request to the API
        $url = $this->config->item('environment') === 'production'
            ? "https://krishnendudalui.in.net/API_Provider/index.php/api/store"
            : "http://localhost/1_api/API_Provider/index.php/api/store"; // API endpoint

        $response = $this->curl->simple_post($url, $data);

        if ($response) {
            $result = json_decode($response);
            if ($result->status) {
                $this->session->set_flashdata('success', $result->message);
            } else {
                $this->session->set_flashdata('error', $result->message);
            }
        } else {
            $this->session->set_flashdata('error', 'Failed to communicate with the API');
        }

        // Redirect to the users page
        redirect('client/get_users');
    }

    public function form()
    {
        // Load a view for the form
        $this->load->view('employee_form');
    }

    public function getEmployeeById($id)
    {
        // API URL of the First Project (API Provider)
        $api_url = $this->config->item('environment') === 'production'
            ? "https://krishnendudalui.in.net/API_Provider/index.php/api/find/$id"
            : "http://localhost/1_api/API_Provider/index.php/api/find/$id";

        // Sending GET request to the API provider
        $response = $this->curl->simple_get($api_url);

        if ($response) {
            // Decode the JSON response
            $data['employee'] = json_decode($response, true);
            // Load the view to display the employee details
            $this->load->view('employee_details', $data);
        } else {
            // Handle failure case
            $data['error'] = 'Failed to retrieve employee details.';
            $this->load->view('error_view', $data);
        }
    }

    public function load_update_form($id)
    {
        $data['id'] = $id; // Pass ID to the view if needed
        $this->load->view('update_employee_view', $data); // Load the form view
    }

    public function update_employee()
    {
        // Get form data
        $id = $this->input->post('id');
        $data = [
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'phone' => $this->input->post('phone'),
            'email' => $this->input->post('email')
        ];

        // API endpoint (URL of the First Project's API)
        $url = $this->config->item('environment') === 'production'
            ? "https://krishnendudalui.in.net/API_Provider/index.php/api/update/$id"
            : "http://localhost/1_api/API_Provider/index.php/api/update/$id";

        // Initialize cURL
        $ch = curl_init($url);
        $json_data = json_encode($data);

        // Set cURL options for a PUT request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json_data)
        ]);

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            $data['message'] = $error;
            $this->load->view('error_view', $data);
        } else {
            curl_close($ch);
            // Decode the API response
            $api_response = json_decode($response, true);

            // Check the response status from the API
            if ($api_response['status'] === true) {
                $this->session->set_flashdata('success', $api_response['message']);
                redirect('client/get_users'); // Redirect to the users page
            } else {
                $this->session->set_flashdata('error', $api_response['message']);
                redirect('client/error'); // Redirect to an error page
            }
        }
    }

    public function delete_employee($id)
    {
        // API endpoint (URL of the First Project's API)
        $url = $this->config->item('environment') === 'production'
            ? "https://krishnendudalui.in.net/API_Provider/index.php/api/delete/$id"
            : "http://localhost/1_api/API_Provider/index.php/api/delete/$id";

        // Initialize cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            $this->session->set_flashdata('error', $error);
            redirect('client/error');
        } else {
            curl_close($ch);
            // Decode the API response
            $api_response = json_decode($response, true);

            // Check the response status from the API
            if ($api_response['status'] === true) {
                $this->session->set_flashdata('success', $api_response['message']);
                redirect('client/get_users'); // Redirect to the users page
            } else {
                $this->session->set_flashdata('error', $api_response['message']);
                redirect('client/error'); // Redirect to an error page
            }
        }
    }
}
