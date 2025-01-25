<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
date_default_timezone_set('America/Sao_Paulo');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Game extends REST_Controller {

    public function index_get(){
        $this->response(['status' => 0, 'message' => 'Acesso negado'], 400);
    }

    public function index_post() {
        $this->response(['status' => 0, 'message' => 'Acesso negado'], 400);
    }

    public function new_game_generated_post() {
       
        $data = array(
            'win' =>  rand(2,3)
        );
        
        $this->db->insert('game_history', $data);
        $insert_id = $this->db->insert_id();

        $response = array('id' => $insert_id);
        $this->response($response, 200);
    }

    public function game_over_post() {

        $data['token'] = $this->input->post('_token');

        $user = $this->db->get_where('users', array('token' => $data['token']))->row();

        //var_dump($user);
        echo $user->balance;
        //$this->response($response, 200);
    }

    public function add_bet_post() {
        $data['token'] = $this->input->post('_token');

        $user = $this->db->get_where('users', array('token' => $data['token']))->row();


        $insert = array(
            "gameid" => $this->input->post('all_bets[0][game_id]'),
            "bet" => $this->input->post('all_bets[0][bet_amount]'),
            "user" => $user->id,
        );

        $this->db->insert('bets', $insert);
        $insert_id = $this->db->insert_id();

        $newbalance = round($user->balance - $this->input->post('all_bets[0][bet_amount]'));

        $this->db->where('id', $user->id);
        $this->db->update('users', array('balance' => $newbalance));

       

        $response = [
            "isSuccess" => true,
            "data" => [
                "wallet_balance" => $newbalance,
                "return_bets" => [
                    [
                        "bet_id" => $insert_id
                    ]
                ]
            ],
            "message" => ""
        ];
        $this->response($response, 200);
    }

    public function currentlybet_post() {
        $gameId = $this->input->post('game_id');
    
        // Se o ID do jogo for 0, substituímos por um número randômico
        if ($gameId == 0) {
            $gameId = rand(1, 100); // Ajuste os limites conforme necessário
        }
    
        // Consulta à base de dados com JOIN para obter dados das apostas e dos usuários
        $query = $this->db->select('bets.*, users.id as userID')
                          ->from('bets')
                          ->join('users', 'users.id = bets.user')
                          ->where('gameid', $gameId)
                          ->get();
    
        // Resultados da consulta
        $allbets = $query->result_array();
    
        // Processamento das apostas obtidas
        $currentGameBet = [];
        foreach ($allbets as $bet) {
            // Inclui o userID e uma imagem aleatória para cada aposta
            $currentGameBet[] = array(
                "userid" => $bet['userID'],  // Inclui o userID real
                "amount" => $bet['bet'],
                "image"  => base_url('public/images/avtar/av-' . rand(1, 23) . '.png')
            );
        }
    
        // Simulação de algumas apostas adicionais
        $names = ['Carlos', 'Ana', 'Pedro', 'Mariana', 'Lucas', 'Beatriz', 'João', 'Camila', 'Rafael', 'Fernanda'];
        $lastnames = ['Silva', 'Santos', 'Oliveira', 'Souza', 'Lima', 'Pereira', 'Ferreira', 'Almeida', 'Costa', 'Gomes'];
    
        for ($i = 0; $i < rand(10, 100); $i++) {
            // Geração de um nome aleatório
            $randomFirstName = $names[array_rand($names)];
            $randomLastName = $lastnames[array_rand($lastnames)];
            $randomUserName = substr($randomFirstName, 0, 2) . '**' . substr($randomLastName, 0, 2) . '***';
    
            // Escolha de uma imagem aleatória
            $randomImage = base_url('public/images/avtar/av-' . rand(1, 23) . '.png');
    
            $currentGameBet[] = array(
                "userid" => $randomUserName, // Nome aleatório do usuário
                "amount" => rand(9, 250),
                "image"  => $randomImage // Imagem aleatória
            );
        }
    
        $currentGame = array("id" => $gameId);
        $currentGameBetCount = count($currentGameBet);
        $response = array(
            "currentGame" => $currentGame,
            "currentGameBet" => $currentGameBet,
            "currentGameBetCount" => $currentGameBetCount
        );
    
        // Resposta em formato JSON
        $this->response($response, 200);
    }
    
    


    public function increamentor_post() {

        $gameid = $this->input->post('game_id');
        $game = $this->db->get_where('game_history', array('id' => $gameid))->row();

        $win = base64_encode($game->win);

        $response = array(
            "result" => $win,
            "status" => true
        );
        $this->response($response, 200);
    }
    public function crash_plane_post() {
        return 1;
    }


    public function cash_out_get() {
        
        $data['token'] = $this->input->get('_token');
        $data['game_id'] = $this->input->get('game_id');
        $win_multiplier = $this->input->get('win_multiplier');
        $data['bet_id'] = $this->input->get('bet_id');

        $user = $this->db->get_where('users', array('token' => $data['token']))->row();
        $bet = $this->db->get_where('bets', array('id' => $data['bet_id']))->row();

        $win = ($win_multiplier * $bet->bet);
        $newbalance = round($user->balance + $win);

        $this->db->where('id', $user->id);
        $this->db->update('users', array('balance' => $newbalance));

        $this->db->where('id', $bet->id);
        $this->db->update('bets', array('multi' => $win_multiplier, 'win' => $win));

        $response = [
            "message" => "",
            "data" => [
                "wallet_balance" => $newbalance,
                "cash_out_amount" => (float) number_format($win, 2, '.', '')
            ],
            "isSuccess" => true
        ];
        $this->response($response, 200);
    }

    public function my_bets_history_post(){
        $data['token'] = $this->input->post('_token');
        $user = $this->db->get_where('users', array('token' => $data['token']))->row();
        $bets = $this->db->get_where('bets', array('user' => $user->id))->result();

        
        $this->response($bets, 200);

    }
}
