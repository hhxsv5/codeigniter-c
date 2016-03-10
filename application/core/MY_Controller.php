<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Base controller
 *
 * @property Request $request
 * @property AdminUserIdentity $user Authorized session user
 * @author Dave Xie <hhxsv5@sina.com>
 */
class MY_Controller extends CI_Controller
{

    /**
     * The folder of page layout
     *
     * @var string
     */
    const LAYOUT_FOLDER = 'layouts';

    /**
     * The title of page
     *
     * @var stirng
     */
    private $pageTitle = '';

    /**
     * The description of page meta
     *
     * @var stirng
     */
    private $pageDescription = '';

    /**
     * The keywords of page meta
     *
     * @var stirng
     */
    private $pageKeywords = '';

    /**
     * The css files array
     *
     * @var array
     */
    protected $cssFiles = array();

    /**
     * The javascript files array
     *
     * @var array
     */
    protected $scriptFiles = array();

    /**
     * The layout of page default 'admin_common_layout'
     *
     * @var string
     */
    protected $layout = 'admin_common_layout';

    public function __construct()
    {
        parent::__construct();
        
        $this->load->library('request');
        // $this->load->library('adminUserIdentity', [], 'user');
        $this->load->helper('url');
    }

    /**
     * Get the request object for current action
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Response JSON string to client side
     *
     * @param mixed $data            
     * @param boolean $exit            
     */
    public function responseJson($data, $exit = FALSE)
    {
        $this->output->output_json($data, $exit);
    }

    /**
     * Header Redirect
     *
     * Header redirect in two flavors
     * For very fine grained control over headers, you could use the Output
     * Library's set_header() function.
     *
     * @param string $uri
     *            URL
     * @param string $method
     *            Redirect method
     *            'auto', 'location' or 'refresh'
     * @param int $code
     *            HTTP Response status code Default 302
     * @return void
     */
    public function redirect($url = '', $method = 'auto', $code = 302)
    {
        redirect($url, $method, $code);
    }

    /**
     * Renders a view with a layout.
     *
     * @param string $view
     *            name of the view to be rendered.
     * @param array $data
     *            data to be extracted into PHP variables and made available to the view script
     * @param boolean $return
     *            whether the rendering result should be returned instead of being displayed to end users.
     * @return string|object the rendering result. View content string if $return is set to TRUE, otherwise CI_Loader instance (method chaining)
     */
    protected function render($view, array $data = array(), $return = false)
    {
        $data['_ci_content'] = $view;
        $data['_ci_title'] = $this->pageTitle;
        $data['_ci_description'] = $this->pageDescription;
        $data['_ci_keywords'] = $this->pageKeywords;
        $data['_ci_css'] = $this->cssFiles;
        $data['_ci_js'] = $this->scriptFiles;
        if (! $this->layout)
            return $this->renderWithoutLayout($view, $data, $return);
        return $this->load->view(static::LAYOUT_FOLDER . DIRECTORY_SEPARATOR . $this->layout, $data, $return);
    }

    /**
     * Renders a view without layout.
     *
     * @param string $view
     *            name of the view to be rendered.
     * @param array $data
     *            data to be extracted into PHP variables and made available to the view script
     * @param boolean $return
     *            whether the rendering result should be returned instead of being displayed to end users.
     * @return string|object the rendering result. View content string if $return is set to TRUE, otherwise CI_Loader instance (method chaining)
     */
    protected function renderWithoutLayout($view, array $data = array(), $return = false)
    {
        $data['_ci_content'] = $view;
        $data['_ci_title'] = $this->pageTitle;
        $data['_ci_description'] = $this->pageDescription;
        $data['_ci_keywords'] = $this->pageKeywords;
        $data['_ci_css'] = $this->cssFiles;
        $data['_ci_js'] = $this->scriptFiles;
        return $this->load->view($view, $data, $return);
    }

    /**
     * Registers a CSS file
     *
     * @param array|string $url
     *            URL of the CSS file
     * @param string $media
     *            media that the CSS file should be applied to. If 'all', it means all media types.
     */
    public function registerCssFile($url, $media = 'all')
    {
        if (is_array($url))
            foreach ($url as $u)
                $this->cssFiles[$u] = $media;
        else
            $this->cssFiles[$url] = $media;
    }

    /**
     * Registers a javascript file.
     *
     * @param array|string $url
     *            URL of the javascript file
     */
    public function registerScriptFile($url)
    {
        if (is_array($url))
            foreach ($url as $u)
                $this->scriptFiles[$u] = $u;
        else
            $this->scriptFiles[$url] = $url;
    }

    /**
     * Get the page title
     *
     * @return string the page title.
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * Set the page title
     *
     * @param string $value            
     */
    public function setPageTitle($title)
    {
        $this->pageTitle = $title;
    }

    /**
     * Get the page description
     *
     * @return string the page description.
     */
    public function getPageDescription()
    {
        return $this->pageDescription;
    }

    /**
     * Set the page description
     *
     * @param string $description            
     */
    public function setPageDescription($description)
    {
        $this->pageDescription = $description;
    }

    /**
     * Get the page keywords
     *
     * @return string the page keywords.
     */
    public function getPageKeywords()
    {
        return $this->pageKeywords;
    }

    /**
     * Set the page keywords
     *
     * @param string $keywords            
     */
    public function setPageKeywords($keywords)
    {
        $this->pageKeywords = $keywords;
    }

    /**
     * Get the layout
     *
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Set the layout
     *
     * @param string $layout            
     */
    public function setLayout($layout)
    {
        $this->layout = trim($layout);
    }

    /**
     * Constructs a URL.
     *
     * @param string $route
     *            the controller and the action (e.g. article/read)
     * @param array $params
     *            list of GET parameters (name=>value). Both the name and value will be URL-encoded.
     *            If the name is '#', the corresponding value will be treated as an anchor
     *            and will be appended at the end of the URL.
     * @param bool $ssl
     *            wheather uses ssl protocol
     * @param string $argSeparator
     *            the token separating name-value pairs in the URL. Defaults to '&'.
     * @return string the constructed URL
     */
    public function createUrl($route, array $params = array(), $ssl = NULL, $argSeparator = '&')
    {
        $ssl === NULL and $ssl = $this->request->isHttps();
        $route = trim(trim($route), '/');
        $alias = $route === '' ? FALSE : array_search($route, $this->router->routes); // Fixed: root path matches 404_override
        if ($alias !== FALSE and ! preg_match('/[\(\[\]\)\\\+\^\$:\{\}\*=]/', $alias))
            $route = $alias; // 路由别名支持
        
        foreach ($params as $i => $param)
            if ($param === null)
                $params[$i] = '';
        
        if (isset($params['#'])) {
            $anchor = '#' . $params['#'];
            unset($params['#']);
        } else
            $anchor = '';
        
        $params = http_build_query($params, NULL, $argSeparator, PHP_QUERY_RFC3986);
        
        if ($route === '/')
            $route = ''; // Fixed: root path
        $url = site_url($route, $ssl ? 'https' : 'http');
        if (strlen($params) > 0) {
            $pos = strpos($url, '?');
            if ($pos === FALSE)
                $url .= '?' . $params;
            elseif ($pos === strlen($url) - 1)
                $url .= $params;
            else
                $url .= $argSeparator . $params;
        }
        $url .= $anchor;
        
        return $url;
    }

    /**
     * Show 404 error
     */
    public function show404()
    {
        if ($this->request->isAjaxRequest()) {
            $this->responseJson([
                'code' => 404,
                'msg' => '404 NOT FOUND',
                'data' => NULL
            ], TRUE);
        } else
            show_404();
    }
}
