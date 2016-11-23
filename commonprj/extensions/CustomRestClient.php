<?php
namespace commonprj\extensions;

use \Yii;
use commonprj\extensions\RESTClient;
/**
 * Кастомизированный класс для создания REST-клиента
 * 
 * @author        	Kalinin Andrey
 * 
 * @property string $error Error while execution
 * @property boolean $isError True if error exists
 * 
 */
class CustomRestClient extends \yii\base\Component
{
    /**
     * Максимальное количество попыток
     */
    const MAX_ATTEMPTS = 3;
    /**
     * Url
     * @var string
     */
    public $url;
    /**
     * Login
     * @var string
     */
    public $login;
    /**
     * Password
     * @var string
     */
    public $password;
    /**
     * Auth method
     * @var type 
     */
    public $method;
    /**
     * Массив authorization
     * @var array
     */
    public $authorization   = [];
    /**
     * Method
     * @var string
     */
    //public $method;
    /**
     * Array of name-value pairs of params
     * @var array
     */
    //public $params;   //  вщзможно, будет использоваться
    /**
     * Full response of last call
     * @var array
     */
    //public $lastResponse;
    /**
     * RESTClient
     * @var RESTClient
     */
    private $_client;
    /**
     * Иннициализация компонента
     */
    public function init()
    {
        parent::init();
        
        $config = [
            'server'    => $this->url,
        ];
        $auth = $this->_getAuthorizationConfig();
        
        if ( !empty($auth['login']) ) {
            $config['http_user'] = $this->login      = $auth['login'];
        }
        
        if ( !empty($auth['password']) ) {
            $config['http_pass'] = $this->password   = $auth['password'];
        }
        
        if ( !empty($auth['method']) ) {
            $config['http_auth'] = $this->method   = $auth['method'];
        }
        
        $this->_client = new RESTClient($config);
        $this->_client->ssl(FALSE);
        
    }
    /**
     * Иннициализация REST-клиента
     * @param array $_config
     */
    private function _initialize($_config)
    {
        $config = [];
        
        if ( !empty($_config['login']) ) {
            $config['http_user'] = $this->login      = $_config['login'];
        }
        
        if ( !empty($_config['password']) ) {
            $config['http_pass'] = $this->password   = $_config['password'];
        }
        
        if ( !empty($_config['method']) ) {
            $config['http_auth'] = $this->method    = $_config['method'];
        }
        
        if ( empty($_config['server']) ) {
            $_config['server']   = $this->url;
        }
        $config['server']   = $_config['server'];
        
        $this->_client->initialize($config);
    }
    /**
     * Сеттер пар-ов аутентификации
     * @param array $auth
     * @throws \BadMethodCallException
     */
    public function setAuthorization( $auth )
    {
        
        if ( empty($auth['login']) ) {
            throw new \BadMethodCallException(__METHOD__ . '() error: login is empty');
        }
        
        if ( empty($auth['password']) ) {
            throw new \BadMethodCallException(__METHOD__ . '() error: password is empty');
        }
        
        if ( empty($auth['method']) ) {
            $auth['method'] = 'basic';
        }
        
        $this->_initialize($auth);
    }
    
    /**
     * Получение параметров авторизации
     * @param string $key [optional]
     * @return array => [ login => <val>, password => <val> ]
     */
    private function _getAuthorizationConfig($key = NULL)
    {
        $authorization = [];
        
        if ( !empty($this->authorization) && is_array($this->authorization) ) {
            
            if ( is_array($this->authorization) ) {
                
                if ( !empty($key) ) {
                    $authorization = $this->authorization[$key];
                } else {
                    $authorization = reset($this->authorization);
                }
            } else {
                $authorization = $this->authorization;
            }
        }
        return $authorization;
    }
    
    public function __get($name)
    {
        return $this->_client->{$name};
    }
    
    public function __set($name, $value)
    {
        return $this->_client->{$name}  = $value;
    }

	public function __call($method, $arguments)
	{
        $i = 0;
        
        if ( in_array($method, [ 'get', 'post', 'put', 'delete', 'head' ]) ) {
            $return = $isSuccess  = FALSE;
            // в случае сбоя, сделаем несколько попыток
            do {
                ++$i;
                $data   = call_user_func_array(array($this->_client, $method), $arguments);
                $error  = $this->_client->getErrorString();

                if ( empty($error) ) {
                    $isSuccess  = TRUE;
                    $return     = $data;
                }
            } while( $i < self::MAX_ATTEMPTS && !$isSuccess );
        } else {
            $return = call_user_func_array(array($this->_client, $method), $arguments);
        }
        return $return;
	}
    /**
     * Была-ли ошибка
     * @return boolean
     */
    public function getIsError()
    {
        $status = $this->_client->status();
        return $status >= 300 || $status < 200;
    }
    /**
     * Получение ошибки
     * @return string
     */
    public function getError()
    {
        return $this->_client->getErrorString();
    }
    /**
     * Возвращает код http-ответа
     * @param string $theURL    URL запроса
     * @return string           Код ответа, например: '200'
     */
    public function getHttpResponseCode( $uri )
    {
        $result = $this->head($uri);
        return $this->_client->status();
    }
    /**
     * Проверка на существование адреса URL по заголовкам
     * @param string $uri
     * @return boolean
     */
    public function isUriExists( $uri )
    {
        return $this->getHttpResponseCode($uri) === 200;
    }
    
}