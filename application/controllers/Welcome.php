<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['title'] = 'Mini ERP - Bem-vindo';
        
        $this->load->view('templates/header', $data);
        $this->load->view('welcome_message');
        $this->load->view('templates/footer');
    }
}
?>