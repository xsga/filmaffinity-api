<?php
/**
 * XsgaRestResponse.
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
 * XsgaRestResponse class.
 */
class XsgaRestResponse
{

    /**
     * Raw content.
     * 
     * @var string
     * 
     * @access private
     */
    private $raw;

    /**
     * HTTP headers.
     * 
     * @var array
     * 
     * @access private
     */
    private $headers = array();

    /**
     * Petition content.
     * 
     * @var string
     * 
     * @access private
     */
    private $body;

    /**
     * Petition errors.
     * 
     * @var array
     * 
     * @access private
     */
    private $errors = array();

    /**
     * Petition info.
     * 
     * @var array
     * 
     * @access private
     */
    private $info = array();


    /**
     * Constructor.
     *
     * @param array $response Response.
     * 
     * @access public
     */
    public function __construct(array $response)
    {
        $this->raw  = $response['response'];
        $this->info = $response['info'];

        // Separate body a from a header.
        if (isset($response['options'][CURLOPT_HEADER]) && $response['options'][CURLOPT_HEADER]) {
            
            list($headers, $this->body) = explode("\r\n\r\n", $response['response'], 2);
            
            // Parse headers.
            $this->parseHeaders($headers);
            
        } else {
            $this->body = $response['response'];
        }//end if
        
    }//end _construct()


    /**
     * Return raw response.
     *
     * @return string|null
     * 
     * @access public
     */
    public function getRaw() : string|null
    {
        return $this->raw;
        
    }//end getRaw()


    /**
     * Return response headers.
     *
     * @return string|null
     * 
     * @access public
     */
    public function getHeaders() : string|null
    {
        return $this->headers;
        
    }//end getHeaders()


    /**
     * Return response headers.
     *
     * @param string      $name    Name.
     * @param string|null $default Default.
     * 
     * @return string|null
     * 
     * @access public
     */
    public function getHeader(string $name, string|null $default = null) : string|null
    {
        return array_key_exists($name, $this->headers) ? $this->headers[$name] : $default;
        
    }//end getHeader()


    /**
     * Return response body.
     *
     * @return string|null
     * 
     * @access public
     */
    public function getBody() : string|null
    {
        return $this->body;
        
    }//end getBody()


    /**
     * Set errors.
     *
     * @param array $errors Errors.
     * 
     * @return void
     * 
     * @access public
     */
    public function setError($errors) : void
    {
        $this->errors = $errors;
        
    }//end setErrors()


    /**
     * Return request errors.
     *
     * @return string|null
     * 
     * @access public
     */
    public function getErrors() : string|null
    {
        return $this->errors;
        
    }//end getErrors()


    /**
     * Return request errors.
     *
     * @return integer
     * 
     * @access public
     */
    public function getCode() : int
    {
        return $this->info['http_code'];
        
    }//end getCode()


    /**
     * Get access for properties.
     *
     * @param string $name   Name.
     * @param array  $params Parameters.
     * 
     * @return string|null
     * 
     * @access public
     */
    public function __call(string $name, array $params) : string|null
    {
        $name = strtolower(str_replace('get', '', $name));
        
        if (array_key_exists($name, $this->info)) {
            return $this->info[$name];
        }//end if
        
        return null;
        
    }//end __call()


    /**
     * Parse headers.
     * 
     * @param string $headers Headers.
     * 
     * @return void
     * 
     * @access private
     */
    private function parseHeaders(string $headers)
    {
        $exploded = explode("\r\n", $headers);
        
        foreach ($exploded as $headerString) {
            
            if (strpos($headerString, ':') !== false) {
                
                list($key, $val)           = explode(':', $headerString, 2);
                $this->headers[trim($key)] = trim($val);
                
            }//end if
            
        }//end foreach
        
    }//end parseHeaders()


}//end XsgaRestResponse class
