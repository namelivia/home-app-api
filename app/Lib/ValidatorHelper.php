<?php

namespace App\Lib;

class ValidatorHelper
{
    /**
     * Gets the result of the validator operation and returns
     * the resulting validation errors as an array.
     *
     * @param array $errorMessages
     *
     * @return array
     */
    public function parseValidationErrors($errorMessages)
    {
        $validationErrors = [];
        foreach ($errorMessages as $message) {
            foreach ($message as $textMessage) {
                $validationErrors[] = $textMessage;
            }
        }

        return $validationErrors;
    }
}
