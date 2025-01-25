<?php 

class User_model extends CI_Model {

    public function get($user_code){
        $this->db->where('user_code', $user_code);
        $query = $this->db->get('users')->result();
        return $query;
    }

    public function create($insertData) {
        $this->db->insert('users', $insertData);
        $id = $this->db->insert_id();
        return $id;
    }

    public function getByToken($token) {
        $this->db->where('token', $token);
        $users = $this->db->get('users')->result();
        return $users;
    }

    public function createSessionToken($user_code){
        $expiration_time = date('Y-m-d H:i:s', strtotime('+50 minutes'));

        $newToken = array(
            'token' => md5($user_code.date('yyddmmhhmmss')),
            'user_code' => $user_code,
            'expiration_time' => $expiration_time,
            'expired' => 0);

        $this->db->insert('session', $newToken);
        return $newToken['token'];
    }

    public function getBySessionToken($token) {
        $this->db->where('token', $token);
        $query = $this->db->get('session')->result();

        $this->db->where('user_code', $query[0]->user_code);
        $users = $this->db->get('users')->result();
        return $users;
    }
}