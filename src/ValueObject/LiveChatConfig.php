<?php

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
        public readonly string $apiKey,
        public readonly string $apiSecret,
        public readonly string $avatarAssetId,
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
                empty($data[$key])
            ) {
                throw InvalidDataException::becauseOfMissingData($key);
            }
        }

        return new self(
            $data['apiKey'],
            $data['apiSecret'],
            $data['avatarAssetId'][0],
            $data['title'],
            $data['description'],
            $data['whatsAppUrl'],
            'someUrl', /* TODO will be fixed in  LCWP-103 */
        );
    }
}