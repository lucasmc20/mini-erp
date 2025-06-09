<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Carregar helpers globais
        $this->load->helper('url');
        $this->load->helper('bootstrap');
        
        // Carregar bibliotecas globais
        $this->load->library('session');
        
    }
}
?>