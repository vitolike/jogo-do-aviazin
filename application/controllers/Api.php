<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Api extends REST_Controller {

    public function index_get(){
        $this->response(['status' => 0, 'message' => 'Acesso negado'], 400);
    }

    public function index_post() {

    $input_data = $this->input->raw_input_stream;
    $data = json_decode($input_data, true);

        if (!isset($data['method'])) {
            $this->response(['status' => 0, 'message' => 'O campo "method" é obrigatório'], 400);
            return;
        }
      
        switch ($data['method']) {
            case 'game_launch':
                $this->game_launch($data);
                break;
            default:
                $this->response(['status' => 0, 'message' => 'Método não suportado'], 400);
                break;
        }
    }

    private function game_launch($data) {
        $userCheck = $this->db->get_where('users', array('user_code' => $data['user_code']))->result();
    
        // Se não encontrou o usuário, cria um novo
        if ($userCheck == null) {
            $insertData = array(
                'user_code' => $data['user_code'],
                'agent_code' => $data['agent_code'],
                'balance' =>  $data['balance'],
                'uuid' => $this->engine->uuid(),
                'ip' => $this->input->ip_address(),
                'token' => md5($data['user_code'].$data['agent_code'].date('yyddmmhhmmss')),
                'currency' => 'BRL',
                'lang' => $data['lang'],
            );
            $this->user->create($insertData);
            $userCheck = array((object)$insertData); // Cria um objeto temporário para o novo usuário
        }
    
        // Verifica se $userCheck não é null e contém pelo menos um elemento
        if (!empty($userCheck) && isset($userCheck[0]->token)) {
            //$this->webhook->getBalance($data['user_code']);
    
            $gameURL = base_url().'play?token='.$userCheck[0]->token;

            $this->response(['status' => 1, 'message' => 'GAME_URL', 'launch_url' => $gameURL], 200);
        } else {
            $this->response(['status' => 0, 'message' => 'Usuário não encontrado'], 404);
        }
        return;
    }
}
