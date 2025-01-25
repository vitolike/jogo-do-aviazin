<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Engine
{
    protected $CI;
    
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function urlServer()
    {
        return getenv('APP_URL');
    }
    private function json_output($status_code, $arrayMessage)
    {
        $this->output->set_status_header($status_code);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($arrayMessage, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function output($status_code, $arrayMessage)
    {

        header('Access-Control-Allow-Origin: *');
        header("Content-type: application/json; charset=utf-8");

        $status = array(
            200 => '200 OK',
            201 => '201 CREATED',
            400 => '400 Bad Request',
            422 => 'Unprocessable Entity',
            500 => '500 Internal Server Error'
            );
        header('Status: '.$status[$status_code]);
        
        echo json_encode($arrayMessage);

    }
    public function guid() {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535),
            mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535),
            mt_rand(0, 65535), mt_rand(0, 65535));
    }
    public function uuid() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits para o tempo
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits para o campo 'time_hi_and_version',
            // quatro bits mais significativos armazenam a versão UUID
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits para 'clock_seq_hi_and_reserved',
            // dois bits mais significativos são zeros e 6 bits menos significativos para o campo 'clock_seq_low'
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits para nodos
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    public function traceid() {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $digits = '0123456789';

        $traceid = '';

        // Adiciona 6 letras aleatórias
        for ($i = 0; $i < 6; $i++) {
            $traceid .= $letters[rand(0, strlen($letters) - 1)];
        }

        // Adiciona 2 números aleatórios
        for ($i = 0; $i < 2; $i++) {
            $traceid .= $digits[rand(0, strlen($digits) - 1)];
        }

        // Adiciona '15' fixo
        $traceid .= 'G2A';

        return $traceid;
    }

    public function GetSign($key, $message) {
        return strtoupper(hash_hmac('sha256', pack('A*', $message), pack('A*', $key)));
    }

    public function time_ago($timestamp) {
        $current_time = time();
        $timestamp = strtotime($timestamp);
        $time_diff = $current_time - $timestamp;
        $seconds = $time_diff;
        $minutes = round($seconds / 60);
        $hours = round($seconds / 3600);
        $days = round($seconds / 86400);
        $weeks = round($seconds / 604800);
        $months = round($seconds / 2629440);
        $years = round($seconds / 31553280);
    
        if ($seconds <= 60) {
            return 'Agora mesmo';
        } elseif ($minutes <= 60) {
            if ($minutes == 1) {
                return '1 minuto atrás';
            } else {
                return "$minutes minutos atrás";
            }
        } elseif ($hours <= 24) {
            if ($hours == 1) {
                return '1 hora atrás';
            } else {
                return "$hours horas atrás";
            }
        } elseif ($days <= 7) {
            if ($days == 1) {
                return '1 dia atrás';
            } else {
                return "Há $days dias atrás";
            }
        } elseif ($weeks <= 4.3) {
            if ($weeks == 1) {
                return '1 semana atrás';
            } else {
                return "$weeks semanas atrás";
            }
        } elseif ($months <= 12) {
            if ($months == 1) {
                return '1 mês atrás';
            } else {
                return "Há $months meses atrás";
            }
        } else {
            if ($years == 1) {
                return '1 ano atrás';
            } else {
                return "Há $years anos atrás";
            }
        }
    }

    public function time($datetime_string) {

        $timezone = new DateTimeZone('America/Sao_Paulo');
        $datetime = new DateTime($datetime_string);
        
        $datetime->setTimezone($timezone);
        $formatted_datetime = $datetime->format('d/m/Y H:i:s');
        
        return $formatted_datetime;
    }
}


/* End of file Engine.php and path \application\libraries\Engine.php */
