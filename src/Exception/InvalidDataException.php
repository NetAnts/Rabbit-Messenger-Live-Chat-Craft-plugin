<?php

class InvalidDataException extends Exception
{
    public static function becauseOfMissingData(string $key): self {
        return new self(
            message: sprintf(
                'Could not create LiveChatConfig because the following data is missing "%s"',
                $key
            )
        );
    }
}