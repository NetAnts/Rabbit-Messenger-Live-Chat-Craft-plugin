<?php

declare(strict_types=1);

namespace NetAnts\WhatsRabbitLiveChat\Controller;

use craft\web\Controller;
use GuzzleHttp\Client;
use NetAnts\WhatsRabbitLiveChat\Plugin;
use NetAnts\WhatsRabbitLiveChat\Service\SettingsService;
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
        array $config = [],
    ) {
        $settings = Plugin::getInstance()->getSettings();
        $this->liveChatService = new LiveChatService(
            $settings['apiKey'],
            $settings['apiSecret'],
            new Client(),
            $this->settingsService->pluginRepoUrl,
        );
        parent::__construct($id, $module, $config);
    }

    /**
     * @throws LiveChatException
     */
    public function actionGetToken(): Response
    {
        return $this->asJson($this->liveChatService->fetchToken());
    }
}
