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
        string $mailTo, 
        string $mailCopy, 
        string $mailHidden, 
        string $mailFrom, 
        string $nameFrom, 
        string $mailReplyTo, 
        string $subject, 
        string $body
    ) : bool
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
     * @param string|array $pathItems Items from root path.
     * 
     * @return string
     * 
     * @access public
     */
    public static function getPathTo(string|array $pathItems) : string
    {
        // Initialize path.
        $path = $_ENV['APP_ROOT'];
        
        // Add path items to path.
        if (is_array($pathItems)) {
            foreach ($pathItems as $item) {
                $path .= $item.DIRECTORY_SEPARATOR;
            }//end foreach
        } else {
            $path .= $pathItems.DIRECTORY_SEPARATOR;
        }//end if
        
        return $path;
        
    }//end getPathTo()
    
    
}//end XsgaUtil class
