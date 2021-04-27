<?php
/**
 * XsgaUtil.
 *
 * This file contains the XsgaUtil class.
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
namespace xsgaphp\utils;

/**
 * XsgaUtil class.
 */
class XsgaUtil
{

    
    /**
     * Send mail.
     * 
     * @param string $mailTo      E-mail adress to send.
     * @param string $mailCopy    E-mail adress to send a copy.
     * @param string $mailHidden  E-mail adress to send a hidden copy.
     * @param string $mailFrom    E-mail adress from.
     * @param string $nameFrom    Name from.
     * @param string $mailReplyTo E-mail adress to reply.
     * @param string $subject     Subject of the e-mail.
     * @param string $body        Body of the e-mail.
     * 
     * @return boolean
     *
     * @access public
     */
    public static function sendMail(
        $mailTo, 
        $mailCopy, 
        $mailHidden, 
        $mailFrom, 
        $nameFrom, 
        $mailReplyTo, 
        $subject, 
        $body
    )
    {
        // Envío en formato HTML.
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        
        // Nombre y dirección del remitente.
        $headers .= "From: $nameFrom <$mailFrom>\r\n";
        
        // Dirección de respuesta, si queremos que sea distinta que la del remitente.
        $headers .= "Reply-To: $mailReplyTo\r\n";
        
        // Direcciones que recibián copia.
        $headers .= "Cc: $mailCopy\r\n";
        
        // Direcciones que recibirán copia oculta.
        $headers .= "Bcc: $mailHidden\r\n";
        
        // Send mail.
        return mail($mailTo, $subject, $body, $headers);
        
    }//end sendMail()
    
    
    /**
     * Get path to.
     * 
     * @param integer $levelToRoot Number of directories up to root path.
     * @param array   $pathItems   Items from path.
     * 
     * @return string
     * 
     * @access public
     */
    public static function getPathTo($levelToRoot, array $pathItems = array()) : string
    {
        // Initialize path.
        $path = '';
        
        // Validates levelToRoot variable.
        if (!is_numeric($levelToRoot)) {
            $levelToRoot = 0;
        }//end if
        
        // Get path to root.
        for ($i = 0; $i < $levelToRoot; $i++) {
            $path .= DIRECTORY_SEPARATOR.'..';
        }//end for
        
        // Add directory separator into path.
        $path .= DIRECTORY_SEPARATOR;
        
        // Add path items to path.
        if (!empty($pathItems)) {
            foreach ($pathItems as $item) {
                $path .= $item.DIRECTORY_SEPARATOR;
            }//end foreach
        }//end if
        
        return $path;
        
    }//end getPathTo()
    
    
}//end XsgaUtil class
