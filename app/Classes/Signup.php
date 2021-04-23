<?php

namespace Classes;

class Signup {

    private const IMAGE_EXTENSIONS = ['jpeg', 'png', 'jpg'];
    private const IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/jpg'];

    /**
     * @var string
     */
    private string $full_name = '';
    /**
     * @var string
     */
    private string $last_name = '';
    /**
     * @var string
     */
    private string $username = '';
    /**
     * @var string
     */
    private string $password = '';
    /**
     * @var string
     */
    public $image_name;
    /**
     * @var array
     */
    public array $error_message = [];

    public function __construct() {
        session_start();
    }

    public function setImage(array $image = []) {
        try {
            if(isset($image)){
                $img_name = $image['name'];
                $img_type = $image['type'];
                $tmp_name = $image['tmp_name'];
                
                $img_explode = explode('.',$img_name);
                $img_ext = $img_explode[1];

                if($image['size'] !== 0 && in_array($img_ext, self::IMAGE_EXTENSIONS) && in_array($img_type, self::IMAGE_TYPES)){
                    $this->image_name = md5(time() .$img_name) . '.' . $img_ext;
                    move_uploaded_file($tmp_name, realpath(dirname(__FILE__, 3)).'/storage/images/' . $this->image_name);
                } else {
                    throw new \Exception('Por favor, selecciona una imagen con formato .jpeg, .png .jpg');
                }
            } else {
                throw new \Exception('Debes incluir una imagen para tu perfil');
            }

        } catch(\Exception $e) {
            $this->error_message[] = $e->getMessage();
        }
    }

    private function encryptPassword(): Signup {
        $encrypt_pass = password_hash($this->password, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->password = $encrypt_pass;
        return $this;
    }

    public function setFullName(?string $value) {
        try {
            if(!empty($value)) {
                $this->full_name = escapeStrings($value);
            } else {
                throw new \Exception('Introduce tu nombre');
            }
        } catch(\Exception $e) {
            $this->error_message[] = $e->getMessage();
        }
    }

    public function setLastName(?string $value) {
        try {
            if(!empty($value)) {
                $this->last_name = escapeStrings($value);
            } else {
                throw new \Exception('Introduce tu apellido');
            }
        } catch(\Exception $e) {
            $this->error_message[] = $e->getMessage();
        }
    }

    public function setUsername(?string $value) {
        try {
            if(!empty($value)) {
                $this->username = escapeStrings($value);
            } else {
                throw new \Exception('Introduce un usario');
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
                throw new \Exception('Introduce una contraseña correcta');
            }
        } catch(\Exception $e) {
            $this->error_message[] = $e->getMessage();
        }
    }

    public function registerUser(int $status = 1) {
        try {
            if(!empty($this->full_name) && !empty($this->last_name) && !empty($this->username) && !empty($this->password)) {
                $database = new DB;
                $this->encryptPassword();
                $random_id = rand(time(), 100000000);

                $database->insert('INSERT INTO users (unique_id, fname, lname, user, password, img, status) VALUES (?, ?, ?, ?, ?, ?, ?)', 
                    [$random_id, $this->full_name, $this->last_name, $this->username, $this->password, $this->image_name, $status]);

                $_SESSION['unique_id'] = $random_id;

                sendWebLog(200, ['description' => 'success']);
            } else {
                throw new \Exception();
            }       
        } catch(\Exception $e) {
            sendWebLog(422, ['description' => $this->error_message]);
        }
    }
    
}