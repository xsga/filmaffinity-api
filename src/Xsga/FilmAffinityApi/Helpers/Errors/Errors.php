<?php

/**
 * Errors.
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
namespace Xsga\FilmAffinityApi\Helpers\Errors;

/**
 * Import dependencies.
 */
use Psr\Log\LoggerInterface;

/**
 * Class Errors.
 */
final class Errors implements ErrorsInterface
{
    /**
     * Errors data.
     *
     * @var ErrorDto[]
     */
    private array $errors;

    /**
     * Constructor.
     */
    public function __construct(
        private LoggerInterface $logger,
        private string $lang
    ) {
        $this->errors = $this->load();
    }

    /**
     * Loads errors file.
     */
    private function load(): array
    {
        $errorsLocation  = realpath(dirname(__FILE__, 6)) . DIRECTORY_SEPARATOR;
        $errorsLocation .= 'config' . DIRECTORY_SEPARATOR . 'errors.json';

        if (!file_exists($errorsLocation)) {
            $this->logger->error("File \"$errorsLocation\" not found");
            return [];
        }//end if

        // Load file.
        $errors = json_decode(file_get_contents($errorsLocation), true);

        if (empty($errors)) {
            $this->logger->warning("File \"$errorsLocation\" it's empty");
            return [];
        }//end if

        $this->logger->debug("File \"$errorsLocation\" loaded sucessfully");

        $errorsDto = [];

        foreach ($errors as $error) {
            $dto           = new ErrorDto();
            $dto->code     = $error['code'];
            $dto->httpCode = $error['http'];
            $dto->message  = $error['description'][$this->lang];

            $errorsDto[] = $dto;
        }//end foreach

        return $errorsDto;
    }

    /**
     * Get error.
     */
    public function getError(int $code): ErrorDto
    {
        foreach ($this->errors as $error) {
            if ($error->code === $code) {
                return $error;
            }//end if
        }//end foreach

        $this->logger->warning("Error with code \"$code\" not found");

        return new ErrorDto();
    }
}
