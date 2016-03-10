<?php

/**
 * Access controll validation before controller constructor
 * @author Dave Xie <hhxsv5@sina.com>
 */
class Acl
{

    private $CI;

    private static $whiteListRoutes = [
        'admin/index/login',
        'admin/index/fastLogin',
        'admin/index/verifyCode'
    ];

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function validate($param)
    {
        $uri = (string) $this->CI->uri->segment(1, NULL);
        switch ($uri) {
            case 'admin':
                // check permission for admin
                $request = $this->CI->getRequest();
                $route = $this->CI->uri->ruri_string(); // routed URI string
                                                        
                // verify white list
                if (in_array($route, self::$whiteListRoutes)) {
                    // access public routes
                    return;
                }
                
                // is login ?
                if (! $this->CI->user->isLogin()) {
                    if ($request->isAjaxRequest())
                        $this->CI->responseJson([
                            'code' => 403,
                            'msg' => 'Need login',
                            'data' => NULL
                        ], TRUE);
                    elseif ($request->isCliRequest())
                        exit('Need login');
                    else
                        $this->CI->redirect('admin/index/login');
                }
                
                // TODO: user role is ok?
                // $user = $this->CI->user->getCurrentUser();
                $role = (string) $this->CI->uri->segment(2, NULL);
                if ($role === 'store') {
                    if (! $this->CI->user->isStoreAdmin()) {
                        if ($request->isAjaxRequest())
                            $this->CI->responseJson([
                                'code' => 403,
                                'msg' => 'Insufficient privileges, access forbidden',
                                'data' => NULL
                            ], TRUE);
                        elseif ($request->isCliRequest())
                            exit('Insufficient privileges, access forbidden');
                        else
                            show_error('Insufficient privileges, access forbidden', 403);
                    }
                } elseif ($role === 'platform') {
                    if (! $this->CI->user->isPlatformAdmin()) {
                        if ($request->isAjaxRequest())
                            $this->CI->responseJson([
                                'ok' => FALSE,
                                'msg' => 'Insufficient privileges, access forbidden',
                                'data' => NULL
                            ], TRUE);
                        elseif ($request->isCliRequest())
                            exit('Insufficient privileges, access forbidden');
                        else
                            show_error('Insufficient privileges, access forbidden', 403);
                    }
                }
                break;
            case 'app':
                ; // check permission for app
                break;
            default:
                ;
                break;
        }
    }
}
