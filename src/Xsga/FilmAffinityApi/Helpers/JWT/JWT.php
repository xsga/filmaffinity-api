<?php

/**
 * JWT.
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
namespace Xsga\FilmAffinityApi\Helpers\JWT;

/**
 * Import dependencies.
 */
use DomainException;
use Exception;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;
use Xsga\FilmAffinityApi\Exceptions\JWTException;

/**
 * JWT class.
 */
final class JWT implements JWTInterface
{
    /**
     * Logger.
     *
     * @var LoggerInterface
     *
     * @access private
     */
    private $logger;

    /**
     * Private secret key.
     *
     * @var string
     *
     * @access private
     */
    private $secretKey;

    /**
     * Algorithm.
     *
     * @var string
     *
     * @access private
     */
    private $algorithm = 'HS256';

    /**
     * Token lifetime (in seconds).
     *
     * @var integer
     *
     * @access private
     */
    private $tokenLifetime;

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger    LoggerInterface instance.
     * @param string          $secretKey Secret key.
     * @param integer         $lifetime  Token lifetime in seconds.
     *
     * @access public
     */
    public function __construct(LoggerInterface $logger, string $secretKey, int $lifetime)
    {
        $this->logger        = $logger;
        $this->secretKey     = $secretKey;
        $this->tokenLifetime = $lifetime;
    }

    /**
     * Get JWT token.
     *
     * @param string $userEmail User e-mail.
     *
     * @return string
     *
     * @access public
     */
    public function get(string $userEmail): string
    {
        // Set timestamp.
        $timestamp = time();

        $payload = [
            'iat'  => $timestamp,
            'exp'  => $timestamp + $this->tokenLifetime,
            'user' => $userEmail
        ];

        // Get JWT token.
        return FirebaseJWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    /**
     * Validate JWT token.
     *
     * @param string $token JWT token.
     *
     * @return array
     *
     * @throws JWTException JWT token expired.
     * @throws JWTException JWT token not active.
     * @throws JWTException JWT token signature verification failed.
     * @throws JWTException JWT token is malformed.
     * @throws JWTException JWT token is invalid.
     *
     * @access public
     */
    public function validate(string $token): array
    {
        try {
            $jwtObject = FirebaseJWT::decode($token, new Key($this->secretKey, $this->algorithm));
            $jwtArray  = json_decode(json_encode($jwtObject), true);

            $this->logger->debug('JWT token validated successfully');

            return $jwtArray;
        } catch (ExpiredException $e) {
            $error = 'JWT token expired';
            $this->logger->error($error);
            throw new JWTException($error, 1015);
        } catch (BeforeValidException $e) {
            $error = 'JWT token not active';
            $this->logger->error($error);
            throw new JWTException($error, 1015);
        } catch (SignatureInvalidException $e) {
            $error = 'JWT token signature verification failed';
            $this->logger->error($error);
            throw new JWTException($error, 1015);
        } catch (DomainException $e) {
            $error = 'JWT token is malformed';
            $this->logger->error($error);
            throw new JWTException($error, 1015);
        } catch (UnexpectedValueException $e) {
            $error = 'JWT token is invalid';
            $this->logger->error($error);
            throw new JWTException($error, 1015);
        }//end try
    }

    /**
     * Decode JWT token.
     *
     * @param string $token JWT token.
     *
     * @return array
     *
     * @access public
     */
    public function decode(string $token): array
    {
        try {
            $jwtObject = FirebaseJWT::decode($token, new Key($this->secretKey, $this->algorithm));
            $jwtArray  = json_decode(json_encode($jwtObject), true);

            $this->logger->debug('JWT token decoded successfully');

            return $jwtArray;
        } catch (Exception $e) {
            $this->logger->error('Error decoding JWT token');
            $this->logger->error($e->getMessage());
            return array();
        }//end try
    }
}
