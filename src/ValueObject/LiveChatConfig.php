<?php

declare(strict_types=1);

namespace Rabbit\RabbitMessengerLiveChat\ValueObject;

use Rabbit\RabbitMessengerLiveChat\db\Settings;
use Rabbit\RabbitMessengerLiveChat\Exception\InvalidDataException;

class LiveChatConfig
{
    private const REQUIRED_KEYS = [
        'avatarAssetId',
        'description',
        'whatsAppUrl',
    ];

    private function __construct(
        public int $avatarAssetId,
        public string $description,
        public string $whatsAppUrl,
        public bool $desktopExpanded,
        public bool $showInformationForm,
        public int $starterPopupTimer,
        public bool $enabled,
        public string $loginUrl,
        public string $position,
        public string $zIndex,
        public string $left,
        public string $right,
        public string $bottom,
        public string $top,
        public string $margin,
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
            (int)$data['avatarAssetId'],
            $data['description'],
            $data['whatsAppUrl'],
            (bool)$data['desktopExpanded'],
            (bool)$data['showInformationForm'],
            (int)$data['starterPopupTimer'],
            (bool)$data['enabled'],
            '/actions/rabbit-messenger-live-chat/login/get-token',
            $data['position'],
            $data['zIndex'],
            $data['left'],
            $data['right'],
            $data['bottom'],
            $data['top'],
            $data['margin'],
        );
    }

    public static function createFromDatabase(Settings $settings)
    {
        return new self(
            $settings->avatar_asset_id,
            $settings->description,
            $settings->whatsapp_url,
            (bool)$settings->desktop_expanded,
            (bool)$settings->show_information_form,
            (int)$settings->starter_popup_timer,
            (bool)$settings->enabled,
            '/actions/rabbit-messenger-live-chat/login/get-token',
            $settings->position,
            $settings->z_index,
            $settings->left,
            $settings->right,
            $settings->bottom,
            $settings->top,
            $settings->margin
        );
    }
}
