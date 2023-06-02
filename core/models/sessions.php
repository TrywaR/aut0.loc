<?php
/**
 * Sessions work and token
 */
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class session extends model
{
    public $table_name = 'sessions';
    public $key = 'sKey';

    public $id = '';
    public $jwt = '';
    public $user = '';
    public $user_id = '';
    public $date_start = '';

    // Запуск и работа сессии
    function install() {
        // Нет ключа
        if ( ! $this->jwt ) {
            return json_encode(["message" => "Access denied","error" => "not key"]);
        }
        // Ключ ок
        try {
            return $this->parse_jwt();
        }
        // Ошибка ключа
        catch (Exception $e) {
            return json_encode(["message" => "Access denied","error" => $e->getMessage()]);
        }
    }

    // Создание ключа
    function create_jwt (){
        $payload = [
            'iss' => config::$site_port.config::$site_url,
            'aud' => config::$site_port.config::$site_url,
            'iat' => 1356999524,
            'nbf' => 1357000000,
            'user' => $this->user,
        ];
        $this->jwt = JWT::encode($payload, $this->key, 'HS256');
        return $this->jwt;
    }

    // Расшифровка ключа
    function parse_jwt ( $sJwt = '' ) {
        $sJwt = $sJwt ? $sJwt : $this->jwt;
        return JWT::decode($sJwt, new Key($this->key, 'HS256'));
    }

    function __construct( $iId = 0 ){
        if ( ! $iId ) {
            $this->date_start = date('Y-m-d H:i:s');
        }
    }
}
