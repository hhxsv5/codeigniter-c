<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Http request object for codeigniter
 *
 * @author Dave Xie <hhxsv5@sina.com>
 */
class Request
{

    /**
     * Http request method
     *
     * @var string
     */
    const HTTP_METHOD_GET = 'GET';

    const HTTP_METHOD_POST = 'POST';

    const HTTP_METHOD_PUT = 'PUT';

    const HTTP_METHOD_DELETE = 'DELETE';

    /**
     * Http request protocol
     *
     * @var string
     */
    const PROTOCOL_HTTP = 'HTTP';

    const PROTOCOL_HTTPS = 'HTTPS';

    private $CI;

    private $isHttps;

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function getRequestMethod()
    {
        return $this->CI->input->method(TRUE);
    }

    public function getRequestProtocol()
    {
        if ($this->isHttps === null) {
            $https = (string) $this->CI->input->server('HTTPS');
            $forwarded = (string) $this->CI->input->server('HTTP_X_FORWARDED_PROTO');
            $this->isHttps = ! strcasecmp($https, 'on') || ! strcasecmp($forwarded, 'https');
        }
        return $this->isHttps ? self::PROTOCOL_HTTPS : self::PROTOCOL_HTTP;
    }

    public function getUrlReferrer()
    {
        return $this->CI->input->server('HTTP_REFERER');
    }

    public function isHttp()
    {
        return self::PROTOCOL_HTTP === self::getRequestProtocol();
    }

    public function isHttps()
    {
        return self::PROTOCOL_HTTPS === self::getRequestProtocol();
    }

    public function isAjaxRequest()
    {
        return $this->CI->input->is_ajax_request();
    }

    public function isCliRequest()
    {
        return is_cli(); // deprecated is_cli_request()
    }

    public function isGet()
    {
        return self::HTTP_METHOD_GET === $this->getRequestMethod();
    }

    public function isPost()
    {
        return self::HTTP_METHOD_POST === $this->getRequestMethod();
    }

    public function isPut()
    {
        return self::HTTP_METHOD_PUT === $this->getRequestMethod();
    }

    public function isDelete()
    {
        return self::HTTP_METHOD_DELETE === $this->getRequestMethod();
    }

    public function verifyRequestMethod($method = self::HTTP_METHOD_GET)
    {
        $requestMethod = $this->getRequestMethod();
        if ($method === $requestMethod) {
            return TRUE;
        }
        return $protocol === $requestProtocol;
    }

    public function verifyRequestProtocol($protocol = self::PROTOCOL_HTTP)
    {
        $requestProtocol = $this->getRequestMethod();
        return $protocol === $requestProtocol;
    }

    public function getQuery($key, $default = NULL)
    {
        return $this->getParam($key, self::HTTP_METHOD_GET, $default);
    }

    public function getPost($key, $default = NULL)
    {
        return $this->getParam($key, self::HTTP_METHOD_POST, $default);
    }

    public function getCookie($key, $default = NULL)
    {
        $data = $this->CI->input->cookie($key);
        return $data === NULL ? $default : $data;
    }

    public function getParam($key, $method = self::HTTP_METHOD_GET, $default = NULL)
    {
        switch ($method) {
            case self::HTTP_METHOD_GET:
                $data = $this->CI->input->get($key);
                break;
            case self::HTTP_METHOD_POST:
                $data = $this->CI->input->post($key);
                break;
            case self::HTTP_METHOD_PUT:
                $data = $this->CI->input->input_stream($key);
                break;
            case self::HTTP_METHOD_DELETE:
                $data = $this->CI->input->input_stream($key);
                break;
            default:
                return $default;
        }
        if ($data === NULL)
            return $default;
            
            // !!!Fixed: array to string conversion
        if (is_array($data) or is_object($data))
            return $data;
        
        return $data;
    }

    public function getIPAddress()
    {
        return $this->CI->input->ip_address();
    }

    public function getUserAgent()
    {
        return $this->CI->input->user_agent();
    }

    public function getRequestHeader($name)
    {
        return $this->CI->input->get_request_header($name);
    }
}