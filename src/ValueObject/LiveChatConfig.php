<?php

declare(strict_types=1);

namespace NetAnts\WhatsRabbitLiveChat\ValueObject;

use NetAnts\WhatsRabbitLiveChat\Exception\InvalidDataException;

class LiveChatConfig
{
    private const REQUIRED_KEYS = [
        'avatarAssetId',
        'title',
        'description',
        'whatsAppUrl',
    ];

    private function __construct(
        public int $avatarAssetId,
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

            (int)$data['avatarAssetId'][0],
            $data['title'],
            $data['description'],
            $data['whatsAppUrl'],
            '/actions/whatsrabbit-live-chat/login/get-token'
        );
    }

    public static function createFromDatabase($settings)
    {
        return new self(

            $settings->avatar_asset_id,
            $settings->title,
            $settings->description,
            $settings->whatsapp_url,
            '/actions/whatsrabbit-live-chat/login/get-token'

        );
    }
}
