<?php

namespace Core;

class Session {

    private static $instance;
    private $session_expire_time = null ;

    /**
     * @access public
     * @param int - tempo máximo para expiração da sessão
     */
    public function __construct(int $expire_time = null) {
        self::$instance = &$this;

        $this->setExpiretime($expire_time);

        header("Cache-control: private");

        ini_set('session.cookie_httponly' , true);
        ini_set('session.use_only_cookies', true);
        session_start();
        
        session_regenerate_id();

        if(!is_null( $this->session_expire_time)) {
            if(isset($_SESSION['SS_ULTIMA_ATIVIDADE']) && (time() - $_SESSION['SS_ULTIMA_ATIVIDADE'] > $this->session_expire_time ) ) {
                $this->destroy();
            }
        }

        $_SESSION['SS_ULTIMA_ATIVIDADE'] = time();
    }

    public static function getInstance(int $expire_time = null ): Session {
        if(!isset(self::$instance)) self::$instance = new self($expire_time);

        return self::$instance;
    }

    public function setExpiretime(int $__value = null): void {
        if($__value != null)
            $this->session_expire_time = $__value;
    }

    public function getExpiretime(): int {
        return $this->session_expire_time ;
    }

    public function __set(string $name, $value): void {
        $this->set($name, $value);
    }

    public function __get(string $name) {
        return $this->get($name);
    }

    public function set(string $__name, $__value): void {
        $_SESSION[trim($__name)] = $__value;
    }

    public function get(string $__name = null) {
        if($__name == null) return null;
        if(isset($_SESSION[trim($__name)]))
            return $_SESSION[trim($__name)];
        else
            return null;
    }

    public function del(string $name): void {
        unset($_SESSION[trim($name)]);
    }

    function destroy(): void {
        $_SESSION = array();
        session_destroy();
    }
}
