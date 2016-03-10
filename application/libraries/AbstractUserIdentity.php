<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Abstract user identity
 * You must implement the following methods in your subclass
 *
 * @abstract AbstractUserIdentity::authenticate()
 * @abstract AbstractUserIdentity::getId()
 * @abstract AbstractUserIdentity::getName()
 * @abstract AbstractUserIdentity::keepLogin()
 *          
 * @author Dave Xie <hhxsv5@sina.com>
 */
abstract class AbstractUserIdentity
{

    /**
     * No error
     *
     * @var int
     */
    const ERROR_NONE = 0;

    /**
     * Username invalid
     *
     * @var int
     */
    const ERROR_USERNAME_INVALID = 1;

    /**
     * Password invalid
     *
     * @var int
     */
    const ERROR_PASSWORD_INVALID = 2;

    /**
     * User has been disabled
     *
     * @var int
     */
    const ERROR_USER_DISABLED = 3;

    /**
     * User's IP has been disabled
     *
     * @var int
     */
    const ERROR_IP_DISABLED = 4;

    /**
     * Session id for identity
     *
     * @var string
     */
    const SESSION_USER_IDENTITY_ID = '_sess_identity_';

    /**
     * Error messages
     *
     * @var array
     */
    private static $errorMsgs = [
        self::ERROR_NONE => '没有错误',
        self::ERROR_IP_DISABLED => 'IP已禁用',
        self::ERROR_USERNAME_INVALID => '账户不存在',
        self::ERROR_PASSWORD_INVALID => '密码错误',
        self::ERROR_USER_DISABLED => '账户已禁用'
    ];

    /**
     *
     * @var int authenticate error code
     */
    protected $errorCode = self::ERROR_NONE;

    /**
     *
     * @var string username
     */
    protected $username;

    /**
     *
     * @var string password
     */
    protected $password;

    /**
     *
     * @var string login IP
     */
    protected $ip;

    /**
     *
     * @var bool whether keep login
     */
    protected $isKeepLogin;

    /**
     * Current login user
     *
     * @var array
     */
    protected $user;

    /**
     * Whether recover the user session data
     *
     * @var bool
     */
    private $isRecovered = FALSE;

    /**
     * Returns the unique identifier for the identity.
     * The default implementation simply returns {@link username}.
     *
     * @return string the unique identifier for the identity.
     */
    abstract public function getId();

    /**
     * Returns the display name for the identity.
     * The default implementation simply returns {@link username}.
     *
     * @return string the display name for the identity.
     */
    abstract public function getName();

    /**
     * Authenticates a user based on {@link username} and {@link password}.
     * Can get error code by call {@link UserIdentity::getErrorCode()} if fail
     *
     * @param string $username            
     * @param string $password            
     * @param string $ip            
     * @param array $options
     *            extra params such as 'ip'
     *            
     * @return false|User return User array|object if authenticate successfully, otherwise return false.
     */
    abstract protected function authenticate($username, $password, $ip, array $options = array());

    /**
     * Do login after authenticate()
     * Can get error code by call {@link UserIdentity::getErrorCode()} if fail
     *
     * @param string $username            
     * @param string $password            
     * @param string $ip            
     * @return boolean return TRUE if success, otherwise return FALSE
     */
    public function login($username, $password, $ip, $keepLogin = FALSE)
    {
        $this->beforeLogin();
        
        $this->user = $this->authenticate($username, $password, $ip);
        if (! $this->user) {
            return FALSE;
        }
        
        // authenticate success
        $this->username = $username;
        $this->password = $password;
        $this->ip = $ip;
        $this->isKeepLogin = (bool) $keepLogin;
        
        // Save user into session
        $_SESSION[self::SESSION_USER_IDENTITY_ID] = serialize($this->user);
        
        $this->afterLogin();
        
        if ($this->isKeepLogin)
            $this->keepLogin();
        
        return TRUE;
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->beforeLogout();
        
        session_destroy();
        
        $this->afterLogout();
        return TRUE;
    }

    protected function beforeLogin()
    {
        if ($this->isLogin())
            $this->logout();
    }

    protected function afterLogin()
    {}

    protected function beforeLogout()
    {}

    protected function afterLogout()
    {}

    /**
     * Keep the login status
     * If you have this requirement, you can implement this method
     */
    abstract protected function keepLogin();

    /**
     * Whether logged in
     */
    public function isLogin()
    {
        return isset($_SESSION[self::SESSION_USER_IDENTITY_ID]);
    }

    /**
     * Get error code for authorize
     *
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Get error msg for authorize
     *
     * @return string
     */
    public function getErrorMsg()
    {
        return self::$errorMsgs[$this->errorCode];
    }

    /**
     * Recover the session user data
     */
    protected function recoverState()
    {
        if (! $this->isRecovered and $this->isLogin()) {
            $this->user = unserialize($_SESSION[self::SESSION_USER_IDENTITY_ID]);
            $this->isRecovered = TRUE;
        }
    }

    /**
     * Return the current user array|object
     *
     * @return array|object
     */
    public function getCurrentUser()
    {
        $this->recoverState();
        return $this->user;
    }
}