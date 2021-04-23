<?php

namespace Classes;

use \Helpers\Logger;
use \Helpers\Tools;

class Login {

    /**
     * @var string
     */
    private string $username = '';
    /**
     * @var string
     */
    private string $password = '';
    /**
     * @var array
     */
    public array $error_message = [];

    public function __construct() {
        session_start();
    }

    public function setUsername(?string $value) {
        try {
            if(!empty($value)) {
                $this->username = escapeStrings($value);
            } else {
                throw new \Exception('El campo usuario está vacio');
            }
        } catch(\Exception $e) {
            $this->error_message[] = $e->getMessage();
        }
    }

    public function setPassword(?string $value) {
        try {
            if(!empty($value)) {
                $this->password = escapeStrings($value);
            } else {
                throw new \Exception('El campo contraseña está vacio');
            }
        } catch(\Exception $e) {
            $this->error_message[] = $e->getMessage();
        }
    }

    public function verifyLogin() {
        try {
            $database = new DB;
            if(!empty($this->password) && !empty($this->username)) {
                $content = $database->selectOneColumn('SELECT unique_id, user, password FROM users WHERE user = ?', [$this->username]);
                if(!empty($content)) {
                    if(password_verify($this->password, $content['password'])) {
                        Logger::sendWebLog(200, ['description' => 'success']);
                        $_SESSION['unique_id'] = $content['unique_id'];
                        sendWebLog(200, ['description' => 'login suscessfull']);
                        $database->query("UPDATE users SET status = ? WHERE unique_id = ?", [1, $content['unique_id']]);
                    } else {
                        throw new \Exception('Contraseña incorrecta');
                    }
                } else {
                    throw new \Exception('No se ha encontrado el usuario especificado');
                }
            }
        } catch(\Exception $e) {
            sendWebLog(410, $e->getMessage());
        }
    }

}