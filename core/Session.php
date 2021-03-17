<?php

namespace Core;

class Session {

    /** @var Session */
    private static $instance;

    /** @var int|null  */
    private $session_expire_time = null ;

    /**
     * @param int|null $expire_time Tempo máximo para expiração da sessão
     */
    public function __construct(int $expire_time = null) {
        self::$instance = &$this;

        $this->setExpiretime($expire_time);

        header("Cache-control: private");

        ini_set('session.cookie_httponly' , true);
        ini_set('session.use_only_cookies', true);
        session_start();
        
        session_regenerate_id();

        if(!is_null($this->session_expire_time)) {
            if(isset($_SESSION['SS_ULTIMA_ATIVIDADE']) && (time() - $_SESSION['SS_ULTIMA_ATIVIDADE'] > $this->session_expire_time ) ) {
                $this->destroy();
            }
        }

        $_SESSION['SS_ULTIMA_ATIVIDADE'] = time();
    }

    /**
     * Get an instance of Session (Singleton)
     * 
     * @param int|null $expire_time Session expire time
     * 
     * @return self
     */
    public static function getInstance(int $expire_time = null ): Session {
        if(!isset(self::$instance)) self::$instance = new self($expire_time);

        return self::$instance;
    }

    /**
     * Set session expire time
     * 
     * @param int|null $__value Expire time
     * 
     * @return void
     */
    public function setExpiretime(int $__value = null): void {
        if($__value != null)
            $this->session_expire_time = $__value;
    }

    /**
     * Get session expire time
     * 
     * @return int
     */
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

    /**
     * Remove a session item (variable)
     * 
     * @param string $name item name
     * 
     * @return void
     */
    public function del(string $name): void {
        unset($_SESSION[trim($name)]);
    }

    /**
     * Destroy the session
     * 
     * @return void
     */
    function destroy(): void {
        $_SESSION = array();
        session_destroy();
    }
}
