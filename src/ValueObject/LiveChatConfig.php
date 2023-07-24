<?php

declare(strict_types=1);

namespace NetAnts\WhatsRabbitLiveChat\ValueObject;

use NetAnts\WhatsRabbitLiveChat\Exception\InvalidDataException;

class LiveChatConfig
{
    private const REQUIRED_KEYS = [
        'apiKey',
        'apiSecret',
        'avatarAssetId',
        'title',
        'description',
        'whatsAppUrl',
    ];

    private function __construct(
        public string $apiKey,
        public string $apiSecret,
        public array $avatarAssetId,
        public string $title,
        public string $description,
        public string $whatsAppUrl,
        public string $loginUrl,
    ) {
    }

    /**
     * @throws InvalidDataException
     */
    public static function createFromRequest(array $data): self
    {
        foreach (self::REQUIRED_KEYS as $key) {
            if (
                !array_key_exists($key, $data) ||
                empty($data[$key])
            ) {
                throw InvalidDataException::becauseOfMissingData($key);
            }
        }

        return new self(
            $data['apiKey'],
            $data['apiSecret'],
            $data['avatarAssetId'],
            $data['title'],
            $data['description'],
            $data['whatsAppUrl'],
            '/actions/whatsrabbit-live-chat/login/get-token'
        );
    }
}
