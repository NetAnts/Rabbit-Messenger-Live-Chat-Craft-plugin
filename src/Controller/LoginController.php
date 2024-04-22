<?php

declare(strict_types=1);

namespace Rabbit\RabbitMessengerLiveChat\Controller;

use craft\web\Controller;
use GuzzleHttp\Client;
use Rabbit\RabbitMessengerLiveChat\Factory\LiveChatServiceFactory;
use Rabbit\RabbitMessengerLiveChat\Plugin;
use Rabbit\RabbitMessengerLiveChat\Service\SettingsService;
use Whatsrabbit\LiveChatPluginCore\Exception\LiveChatException;
use Whatsrabbit\LiveChatPluginCore\LiveChatService;
use yii\base\Module;
use yii\web\Response;

class LoginController extends Controller
{
    private LiveChatService $liveChatService;
    protected int|bool|array $allowAnonymous = true;

    public function __construct(
        string $id,
        Module $module,
        private SettingsService $settingsService,
        private LiveChatServiceFactory $liveChatServiceFactory,
        array $config = [],
    ) {
        parent::__construct($id, $module, $config);
        $this->liveChatService = $liveChatServiceFactory(LiveChatService::class, $this->settingsService);
    }

    /**
     * @throws LiveChatException
     */
    public function actionGetToken(): Response
    {
        return $this->asJson($this->liveChatService->fetchToken());
    }
}
