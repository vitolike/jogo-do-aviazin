<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('America/Sao_Paulo');

class Play extends CI_Controller {

    public function index(){

        date_default_timezone_set('America/Sao_Paulo');
        
        $data['token'] = $this->input->get('token');

        $data['user'] = $this->db->get_where('users', array('token' => $data['token']))->row();

        $data['bet'] = $this->db->get_where('bets', array('user' => $data['user']->id))->result();

        $this->db->limit(20);
        $data['game_history'] = $this->db->get('game_history')->result();

        $data['last_id'] = $this->db->select('id')->order_by('id', 'DESC')->limit(1)->get('game_history')->row()->id;
        $this->load->view('aviator/index', $data);

    }
    public function teste(){

        $data['token'] = $this->input->get('token');

        $userCheck = $this->db->get_where('users', array('token' => $data['token']))->row();

        var_dump($userCheck);

    }
}
