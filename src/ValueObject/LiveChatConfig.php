<?php

namespace NetAnts\WhatsRabbitLiveChat\ValueObject;

use InvalidDataException;

class LiveChatConfig
{
    private const REQUIRED_KEYS = [
        'apiKey',
        'apiSecret',
        'title',
        'description',
    ];

    private function __construct(
        public readonly string $apiKey,
        public readonly string $apiSecret,
        public readonly string $avatarUrl,
        public readonly string $title,
        public readonly string $description,
        public readonly string $whatsAppUrl,
        public readonly string $loginUrl,
    ) {
    }

    /**
     * @throws InvalidDataException
     */
    public static function createFromRequest(array $data): self {
        foreach (self::REQUIRED_KEYS as $key) {
            if(
                !array_key_exists($key, $data) ||
                !isset($key, $data)
            ) {
                throw InvalidDataException::becauseOfMissingData($key);
            }
        }

        return new self(
            $data['apiKey'],
            $data['apiSecret'],
            $data['avatarUrl'],
            $data['title'],
            $data['description'],
            $data['whatsAppUrl'],
            $data['loginUrl']
        );
    }
}