<?php

declare(strict_types=1);

namespace Rabbit\RabbitMessengerLiveChat\Controller;

use craft\web\Controller;
use GuzzleHttp\Client;
use Rabbit\RabbitMessengerLiveChat\Factory\LiveChatServiceFactory;
use Rabbit\RabbitMessengerLiveChat\Plugin;
use Rabbit\RabbitMessengerLiveChat\Service\SettingsService;
use Rabbit\LiveChatPluginCore\Exception\LiveChatException;
use Rabbit\LiveChatPluginCore\LiveChatService;
use yii\base\Module;
use yii\web\Response;

class LoginController extends Controller
{
    private LiveChatService $liveChatService;
    protected int|bool|array $allowAnonymous = true;
    public $enableCsrfValidation = false;

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
        $this->requirePostRequest();
        return $this->asJson($this->liveChatService->fetchToken());
    }
}
