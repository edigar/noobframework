<?php
//Fonte: http://sooho.com.br/2017/04/26/vendas-usando-o-pagseguro-exemplo-de-sistema-em-php-parte-3/
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

        $this->setExpiretime( $expire_time );

        //session_start();
        header("Cache-control: private");

        # definindo configurações do arquivo ini(evita JS acessar sessão)
        ini_set('session.cookie_httponly' , true);
        ini_set('session.use_only_cookies', true);
        session_start();
        
        # troca o ID da sessão a cada atualização da página
        # quando fecha browser destrói sessão
        # impede roubo de sessão
        session_regenerate_id();

        # verificando se sessão esta configurada para expirar apos inatividade
        if(!is_null( $this->session_expire_time)) {
            # verificando se sessão não expirou por tempo
            if(isset($_SESSION['SS_ULTIMA_ATIVIDADE']) && (time() - $_SESSION['SS_ULTIMA_ATIVIDADE'] > $this->session_expire_time ) ) {
                # Sessão expirada: destrói a sessão
                $this->destroy();
            }
        }
        # determinando a ultima atividade no sistema do usuário
        $_SESSION['SS_ULTIMA_ATIVIDADE'] = time();
    }

    public static function getInstance(int $expire_time = null ): Session {
        if(!isset(self::$instance)) self::$instance = new self($expire_time);

        return self::$instance;
    }

    public function setExpiretime(int $__value = null ): void {
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
