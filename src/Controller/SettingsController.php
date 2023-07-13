<?php

namespace NetAnts\WhatsRabbitLiveChat\Controller;

use craft\web\Controller;
use NetAnts\WhatsRabbitLiveChat\Plugin;
use NetAnts\WhatsRabbitLiveChat\Service\SettingsService;
use NetAnts\WhatsRabbitLiveChat\ValueObject\LiveChatConfig;
use yii\web\Response;


class SettingsController extends Controller
{
    public function __construct(
        $id,
        $module,
        $config,
        private SettingsService $settingsService,
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionSave(): ?Response
    {
        $data = $this->request->getBodyParams();
        $liveChatConfig = LiveChatConfig::createFromRequest($data);



        $saved = $this->settingsService->saveSettings(
            Plugin::getInstance(),
            $liveChatConfig,
        );

        if(!$saved) {
            $response = new Response();
            $response->setStatusCode(500);
            $response->data = [
                'success' => false,
                'mesasge' => 'something went wrong'
            ];
            return $response;
        }

        return $this->redirectToPostedUrl();
    }
}