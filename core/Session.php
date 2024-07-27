<?php

namespace Core;

class Session {

    private static Session $instance;

    private ?int $session_expire_time = null ;

    /**
     * @param ?int $expire_time Session expire time
     */
    public function __construct(?int $expire_time = null)
    {
        self::$instance = &$this;

        $this->setExpiretime($expire_time);

        header("Cache-control: private");

        ini_set('session.cookie_httponly' , true);
        ini_set('session.use_only_cookies', true);
        session_start();
        
        session_regenerate_id();

        if(!is_null($this->session_expire_time)) {
            if(isset($_SESSION['SS_LAST_ACTIVITY']) && (time() - $_SESSION['SS_LAST_ACTIVITY'] > $this->session_expire_time ) ) {
                $this->destroy();
            }
        }

        $_SESSION['SS_LAST_ACTIVITY'] = time();
    }

    /**
     * Get an instance of Session (Singleton)
     * 
     * @param ?int $expire_time Session expire time
     * 
     * @return self
     */
    public static function getInstance(?int $expire_time = null ): Session
    {
        if(!isset(self::$instance)) self::$instance = new self($expire_time);

        return self::$instance;
    }

    /**
     * Set session expire time
     * 
     * @param ?int $__value Expire time
     * 
     * @return void
     */
    public function setExpiretime(?int $__value = null): void
    {
        if($__value != null)
            $this->session_expire_time = $__value;
    }

    /**
     * Get session expire time
     * 
     * @return int
     */
    public function getExpiretime(): int
    {
        return $this->session_expire_time ;
    }

    public function __set(string $name, $value): void
    {
        $this->set($name, $value);
    }

    public function __get(string $name)
    {
        return $this->get($name);
    }

    /**
     * Set a session item (variable)
     *
     * @param string $__name item name
     * @param mixed $__value item value
     */
    public function set(string $__name, mixed $__value): void
    {
        $_SESSION[trim($__name)] = $__value;
    }

    /**
     * Get a session item (variable)
     *
     * @param ?string $__name item name
     *
     * @return mixed value of the item
     */
    public function get(?string $__name = null): mixed
    {
        if($__name == null) return null;

        return $_SESSION[trim($__name)] ?? null;
    }

    /**
     * Remove a session item (variable)
     * 
     * @param string $name item name
     * 
     * @return void
     */
    public function delete(string $name): void
    {
        unset($_SESSION[trim($name)]);
    }

    /**
     * Destroy the session
     * 
     * @return void
     */
    function destroy(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}
