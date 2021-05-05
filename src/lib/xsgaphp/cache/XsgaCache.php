<?php
/**
 * XsgaCache.
 * 
 * A simple file cache.
 * 
 * Based on http://emiliocobos.net/php-cache/
 * 
 * PHP Version 8
 */

/**
 * Namespace.
 */
namespace xsgaphp\cache;

/**
 * XsgaCache class.
 */
class XsgaCache
{
    
    /**
     * Configuration.
     * 
     * @var array
     * 
     * @access public
     */
    public static $config = array(
        'cache_path' => 'cache',
        'expires'    => 180,
    );

    
    /**
     * Lets you configure the cache properly, passing an array:
     *
     * <code>
     * Cache::configure(array(
     *   'expires' => 180,
     *   'cache_path' => 'cache'
     * ));
     * </code>
     * 
     * Or passing a key/val:
     *
     * <code>
     * Cache::configure('expires', 180);
     * </code>
     *
     * @param mixed $key The array with de configuration or the key as string.
     * @param mixed $val The value for the previous key if it was an string.
     * 
     * @return void
     * 
     * @access public
     */
    public static function configure($key, $val = null) : void
    {
        if (is_array($key)) {
            
            foreach ($key as $configName => $configValue) {
                self::$config[$configName] = $configValue;
            }//end foreach
            
        } else {
            
            self::$config[$key] = $val;
            
        }//end if
        
    }//end configure()

    
    /**
     * Get a route to the file associated to that key.
     *
     * @param string $key The parameter key.
     * 
     * @return string The filename of the cache file.
     * 
     * @access private
     */
    private static function getRoute(string $key) : string
    {
        return static::$config['cache_path'].'/'.md5($key).'.cache';
        
    }//end getRoute()

    
    /**
     * Get the data associated with a key.
     *
     * @param string       $key        The parameter key.
     * @param boolean      $raw        Raw.
     * @param integer|null $customTime Custom time.
     * 
     * @return mixed The content you put in, or null if expired or not found.
     * 
     * @access public
     */
    public static function get(string $key, $raw = false, int|null $customTime = null) : mixed
    {
        if (!self::fileExpired($file = self::getRoute($key), $customTime)) {
            
            $content = file_get_contents($file);
            
            return $raw ? $content : unserialize($content);
            
        }//end if

        return null;
        
    }//end get()

    /**
     * Put content into the cache.
     *
     * @param string  $key     The parameter key.
     * @param mixed   $content The the content you want to store.
     * @param boolean $raw     Whether if you want to store raw data or not. 
     *                         If it is true, $content *must* be a string.
     *                         It can be useful for static html caching.
     * 
     * @return boolean Whether if the operation was successful or not.
     * 
     * @access public
     */
    public static function put(string $key, mixed $content, bool $raw = false) : bool
    {
        $destFileName = self::getRoute($key);

        // Use a unique temporary filename to make writes atomic with rewrite.
        $tempFileName = str_replace('.cahe', uniqid('-', true).'.cache', $destFileName);

        $ret = @file_put_contents($tempFileName, $raw ? $content : serialize($content));

        if (!$ret) {
        
            @unlink($tempFileName);
            return false;
        
        }//end if

        return @rename($tempFileName, $destFileName);
        
    }//end put()

    
    /**
     * Delete data from cache.
     *
     * @param string $key The parameter key.
     * 
     * @return boolean True if the data was removed successfully.
     * 
     * @access public
     */
    public static function delete(string $key) : bool
    {
        return @unlink(self::getRoute($key));
        
    }//end delete()

    
    /**
     * Flush all cache.
     *
     * @return boolean Always true.
     * 
     * @access public
     */
    public static function flush() : bool
    {
        $cache_files = glob(self::$config['cache_path'].'/*.cache', GLOB_NOSORT);
        
        foreach ($cache_files as $file) {
            @unlink($file);
        }//end foreach
        
        return true;
    
    }//end flush()
    

    /**
     * Check if a file has expired or not.
     *
     * @param string   $file The rout to the file.
     * @param int|null $time The number of minutes it was set to expire.
     * 
     * @return boolean If the file has expired or not.
     * 
     * @access private
     */
    private static function fileExpired(string $file, int|null $time = null) : bool
    {
        if (!file_exists($file)) {
            return true;
        }//end if
        
        return (time() > (filemtime($file) + 60 * ($time ? $time : self::$config['expires'])));
    
    }//end fileExpired()
    
    
}//end XsgaCache class
