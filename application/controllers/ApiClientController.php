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

    public function get_users()
    {
        // The API URL of the first project
        $api_url = 'http://localhost/1_api/API_Provider/index.php/api';

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
        $url = 'http://localhost/1_api/API_Provider/index.php/api/store'; // API endpoint
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

        // Redirect to the form page
        redirect('client/get_users');
    }

    public function form()
    {
        // Load a view for the form
        $this->load->view('employee_form');
    }

    // Method to fetch employee details by ID
    public function getEmployeeById($id)
    {
        // API URL of the First Project (API Provider)
        $api_url = 'http://localhost/1_api/API_Provider/index.php/api/find/' . $id;

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

    public function load_update_form()
    {
        $this->load->view('update_employee_view'); // Load the form view
    }

    // Function to handle form submission and update employee via API
    public function update_employee()
    {
        // Get form data
        $id = $this->input->post('id');
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $phone = $this->input->post('phone');
        $email = $this->input->post('email');

        // Prepare data to send to the API Provider     
        $data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone' => $phone,
            'email' => $email
        );

        // API endpoint (URL of the First Project's API)
        $url = "http://localhost/1_api/API_Provider/index.php/api/update/" . $id;

        // Initialize cURL
        $ch = curl_init($url);

        // Convert data array to JSON
        $json_data = json_encode($data);

        // Set cURL options for a PUT request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json_data)
        ));

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            // Load error view if there is an issue
            $data['message'] = $error;
            $this->load->view('error_view', $data);
        } else {
            curl_close($ch);

            // Decode the API response
            $api_response = json_decode($response, true);

            // Check the response status from the API
            if ($api_response['status'] === true) {
                $this->session->set_flashdata('success', $api_response['message']);
                redirect('client/get_users'); // Redirect to the URL you specified
            } else {
                $this->session->set_flashdata('error', $api_response['message']);
                redirect('client/error'); // Redirect to an error page
            }
        }
    }
 

    public function delete_employee($id)
    {
        // API endpoint (URL of the First Project's API)
        $url = "http://localhost/1_api/API_Provider/index.php/api/delete/" . $id;

        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options for a DELETE request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            // Redirect to an error page with an error message
            $this->session->set_flashdata('error', $error);
            redirect('client/error');
        } else {
            curl_close($ch);

            // Decode the API response
            $api_response = json_decode($response, true);

            // Check the response status from the API
            if ($api_response['status'] === true) {
                $this->session->set_flashdata('success', $api_response['message']);
                redirect('client/get_users'); // Redirect to the URL you specified
            } else {
                $this->session->set_flashdata('error', $api_response['message']);
                redirect('client/error'); // Redirect to an error page
            }
        }
    }
}
