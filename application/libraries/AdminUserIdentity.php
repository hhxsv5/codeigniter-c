<?php
defined('BASEPATH') or exit('No direct script access allowed');

// include AbstractUserIdentity.php
get_instance()->load->file(APPPATH . 'libraries/AbstractUserIdentity.php');

/**
 * Admin user identity for admin
 *
 * @author Dave Xie <hhxsv5@sina.com>
 */
class AdminUserIdentity extends AbstractUserIdentity
{

    private $CI;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->library('session');
        $this->CI->load->model('admin_user');
    }

    /**
     *
     * Implement parent method
     *
     * @see AbstractUserIdentity::authenticate()
     */
    protected function authenticate($username, $password, $ip, array $options = array())
    {
        $user = $this->CI->admin_user->getAdminUserByAccount($username);
        if (! $user) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
            return FALSE;
        }
        
        if (! password_verify($password, $user['password'])) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
            return FALSE;
        }
        
        if (! $user['is_enable']) {
            $this->errorCode = self::ERROR_USER_DISABLED;
            return FALSE;
        }
        
        // other verifications: ip check...
        
        return $user;
    }

    /**
     *
     * Implement parent method
     *
     * @see AbstractUserIdentity::getId()
     */
    public function getId()
    {
        $user = $this->getCurrentUser();
        return $user && isset($user['admin_user_id']) ? $user['admin_user_id'] : 0;
    }

    /**
     *
     * Implement parent method
     *
     * @see AbstractUserIdentity::getName()
     */
    public function getName()
    {
        $user = $this->getCurrentUser();
        return $user && isset($user['account']) ? $user['account'] : 'guest';
    }

    /**
     * Override AbstractUserIdentity::afterLogin()
     *
     * @see AbstractUserIdentity::afterLogin()
     */
    protected function afterLogin()
    {
        // increase login times
        ++ $this->user['login_times'];
        
        // Update last login
        $this->CI->admin_user->updateLoginUser($this->ip, $this->user['login_times'], $this->user['admin_user_id']);
    }

    /**
     *
     * Override AbstractUserIdentity::afterLogin()
     *
     * @see AbstractUserIdentity::keepLogin()
     */
    protected function keepLogin()
    {
        // TODO: keep status into cookie
    }

    /**
     * 是否是平台管理员
     *
     * @return boolean
     */
    public function isPlatformAdmin()
    {
        $user = $this->getCurrentUser();
        $this->CI->load->model('admin_role');
        return $user && $user['admin_role_id'] == Admin_role::ROLE_PLATFORM_ADMIN;
    }

    /**
     * 是否是商家
     *
     * @return boolean
     */
    public function isStoreAdmin()
    {
        $user = $this->getCurrentUser();
        $this->CI->load->model('admin_role');
        return $user && $user['admin_role_id'] == Admin_role::ROLE_STORE_ADMIN;
    }

    /**
     * 获取商家ID
     *
     * @return int
     */
    public function getStoreId()
    {
        $user = $this->getCurrentUser();
        return $user ? $user['store_id'] : 0;
    }
}