<?php
/**
 * XsgaRestClient.
 * 
 * Based on "dHttp - http client based cURL" by Askar Fuzaylov <tkdforever@gmail.com>
 * 
 * PHP Version 8
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace xsgaphp\rest;

/**
 * Import dependencies.
 */
use xsgaphp\exceptions\XsgaException;

/**
 * XsgaRestClient class.
 */
class XsgaRestClient
{

    /**
     * Default configuration.
     * 
     * @var array
     * 
     * @access private
     */
    private $default = array(
                        CURLOPT_ENCODING       => 'utf-8',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => false,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_USERAGENT      => 'PHP dHttp/Client 1.3'
                       );

    /**
     * Options.
     * 
     * @var array
     * 
     * @access private
     */
    private $options = array();


    /**
     * Construct.
     *
     * @param string $url     URL.
     * @param array  $options Options.
     *
     * @throws \RuntimeException
     *
     * @access public
     */
    public function __construct(string $url = null, array $options = array())
    {
        if (!extension_loaded('curl')) {
            throw new \RuntimeException('The PHP cURL extension must be installed to use XsgaRestClient');
        }//end if

        // Force IPv4, since this class isn't yet compatible with IPv6.
        if (static::v('features') && CURLOPT_IPRESOLVE) {
            $this->addOptions(array(CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4));
        }//end if

        // Merge with default options.
        $this->addOptions($options);

        // Set URL.
        $this->setUrl($url);

    }//end __construct()


    /**
     * Set URL.
     *
     * @param string $url URL.
     * 
     * @return XsgaRestClient
     * 
     * @access public
     */
    public function setUrl(string $url) : XsgaRestClient
    {
        if ($url !== null) {
            $this->options[CURLOPT_URL] = $this->prepareUrl($url);
        }//end if

        return $this;

    }//end setUrl()


    /**
     * Set user agent.
     *
     * @param string $agent User agent.
     * 
     * @return XsgaRestClient
     * 
     * @access public
     */
    public function setUserAgent(string $agent) : XsgaRestClient
    {
        $this->options[CURLOPT_USERAGENT] = $agent;
        
        return $this;
        
    }//end setUserAgent()


    /**
     * Set cookies.
     *
     * @param string $cookie Cookie.
     * 
     * @return XsgaRestClient
     * 
     * @access public
     */
    public function setCookie(string $cookie) : XsgaRestClient
    {
        $this->options[CURLOPT_COOKIEFILE] = $cookie;
        $this->options[CURLOPT_COOKIEJAR]  = $cookie;
        
        return $this;
        
    }//end setCookie()


    /**
     * Set referer.
     *
     * @param string $referer Referer.
     * 
     * @return XsgaRestClient
     * 
     * @access public
     */
    public function setReferer(string $referer) : XsgaRestClient
    {
        $this->options[CURLOPT_REFERER] = $referer;
        
        return $this;
        
    }//end setReferer()


    /**
     * The maximum amount of HTTP redirections to follow.
     *
     * @param integer $redirects Maximun amount redirects.
     * 
     * @return XsgaRestClient
     * 
     * @access public
     */
    public function setMaxRedirects(int $redirects) : XsgaRestClient
    {
        $this->options[CURLOPT_MAXREDIRS] = $redirects;
        
        return $this;
        
    }//end setMaxRedirects()


    /**
     * The maximum number of seconds to allow cURL functions to execute.
     *
     * @param integer $timeout Timeout.
     * 
     * @return XsgaRestClient
     * 
     * @access public
     */
    public function setTimeout(int $timeout) : XsgaRestClient
    {
        $this->options[CURLOPT_TIMEOUT] = $timeout;
        
        return $this;
        
    }//end setTimeout()


    /**
     * The number of seconds to wait while trying to connect.
     *
     * @param integer $timeout Timeout.
     * 
     * @return XsgaRestClient
     * 
     * @access public
     */
    public function setConnectionTimeout(int $timeout) : XsgaRestClient
    {
        $this->options[CURLOPT_CONNECTTIMEOUT] = $timeout;
        
        return $this;
        
    }//end setConnectionTimeout()


    /**
     * Include the header in the output.
     *
     * @param boolean $show Include header flag.
     * 
     * @return XsgaRestClient
     * 
     * @access public
     */
    public function showHeaders(bool $show) : XsgaRestClient
    {
        $this->options[CURLOPT_HEADER] = $show;
        
        return $this;
        
    }//end showHeaders()


    /**
     * Add options.
     *
     * @param array $params Options.
     * 
     * @return XsgaRestClient
     * 
     * @access public
     */
    public function addOptions(array $params) : XsgaRestClient
    {
        if (count($this->options) === 0) {
            $this->options = $this->default;
        }//end if

        foreach ($params as $key => $val) {
            $this->options[$key] = $val;
        }//end foreach

        return $this;
        
    }//end addOptions()


    /**
     * Send post request.
     *
     * @param array $fields  Fields.
     * @param array $options Options.
     * 
     * @return XsgaRestResponse
     * 
     * @access public
     */
    public function post(array $fields = array(), array $options = array()) : XsgaRestResponse
    {
        $this->addOptions($options + array(
                                        CURLOPT_POST       => true,
                                        CURLOPT_POSTFIELDS => $fields
                                      )
                          );
        
        return $this->exec();
        
    }//end post()


    /**
     * Send put request.
     *
     * @param array $fields  Fields.
     * @param array $options Options.
     * 
     * @return XsgaRestResponse
     * 
     * @access public
     */
    public function put(array $fields = array(), array $options = array()) : XsgaRestResponse
    {
        $this->addOptions($options + array(
                                        CURLOPT_CUSTOMREQUEST => 'PUT',
                                        CURLOPT_POSTFIELDS    => is_array($fields) ? http_build_query($fields) : $fields
                                     )
                          );
        
        return $this->exec();
        
    }//end put()


    /**
     * Send get request.
     *
     * @param array $options Options.
     * 
     * @return XsgaRestResponse
     * 
     * @access public
     */
    public function get(array $options = array()) : XsgaRestResponse
    {
        $this->addOptions($options);
        
        return $this->exec();
        
    }//end get()


    /**
     * Send delete request.
     *
     * @param array $options Options.
     * 
     * @return XsgaRestResponse
     * 
     * @access public
     */
    public function delete(array $options = array()) : XsgaRestResponse
    {
        return $this->get($options + array(CURLOPT_CUSTOMREQUEST => 'DELETE'));
        
    }//end delete()


    /**
     * Send multithreaded queries.
     *
     * @param array $handlers Handlers.
     *
     * @return array
     *
     * @throws XsgaException
     * 
     * @access public
     */
    public function multi(array $handlers) : array
    {
        // Create the multiple cURL handle.
        $mc        = curl_multi_init();
        $resources = array();

        foreach ($handlers as $item) {

            if (!($item instanceof XsgaRestClient)) {
                throw new XsgaException('Handler should be object instance of XsgaRestClient');
            }//end if

            $res = $item->init();

            curl_multi_add_handle($mc, $res);
            
            $resources[] = $res;
            
        }//end foreach

        $running = null;
        
        do {
            usleep(100);
            curl_multi_exec($mc, $running);
        } while ($running > 0);

        $result = array();
        
        foreach ($resources as $item) {
            $resp = new XsgaRestResponse(array(
                'response' => curl_multi_getcontent($item),
                'options'  => $this->options,
                'info'     => curl_getinfo($item)
            ));

            $errno = curl_errno($item);
            
            if ($errno) {
                $resp->setError([curl_errno($item) => curl_error($item)]);
            }//end if

            $result[] = $resp;
            
            curl_multi_remove_handle($mc, $item);
            
        }//end foreach

        curl_multi_close($mc);
        
        return $result;

    }//end multi()


    /**
     * Execute the query.
     *
     * @return XsgaRestResponse
     * 
     * @access private
     */
    private function exec() : XsgaRestResponse
    {
        $ch = $this->init();
        
        // Collect response data.
        $response = new XsgaRestResponse(array(
            'response' => curl_exec($ch),
            'options'  => $this->options,
            'info'     => curl_getinfo($ch)
        ));

        $errno = curl_errno($ch);
        
        if ($errno) {
            $response->setError(array($errno => curl_error($ch)));
        }//end if
        
        curl_close($ch);

        return $response;
        
    }//end exec()


    /**
     * Initialize curl.
     *
     * @return \CurlHandle
     * 
     * @access public
     */
    public function init() : \CurlHandle
    {
        $ch = curl_init();
        
        // The initial parameters.
        $this->setCurlOptions($ch, $this->options);
        
        return $ch;
        
    }//end init()


    /**
     * Set curl options.
     *
     * @param \CurlHandle $ch      Resource.
     * @param array       $options Options.
     * 
     * @return void
     * 
     * @access private
     */
    private function setCurlOptions(\CurlHandle &$ch, array $options)
    {
        curl_setopt_array($ch, $options);
        
    }//end setCurlOptions()


    /**
     * Reset options.
     *
     * @return XsgaRestClient
     * 
     * @access public
     */
    public function reset() : XsgaRestClient
    {
        $this->options = array();
        
        return $this;
        
    }//end delete()


    /**
     * Generate url.
     *
     * @param string|array $url Url.
     * 
     * @return string
     * 
     * @access public
     */
    public function prepareUrl(string|array $url) : string
    {
        if (is_array($url) && !empty($url)) {
            
            $newUrl = $url[0];

            if (isset($url[1]) && is_array($url[1])) {
                $newUrl = '?'.http_build_query($url[1]);
            }//end if
            
        } else {
            $newUrl = $url;
        }//end if

        return $newUrl;
        
    }//end prepareUrl()


    /**
     * Return curl information.
     *
     * @param string $type Type.
     * 
     * @return string|null
     * 
     * @access public
     */
    public static function v(string $type = 'version') : string|null
    {
        $info = curl_version();
        
        return array_key_exists($type, $info) ? $info[$type] : null;
        
    }//end v()


}//end XsgaRestClient class
