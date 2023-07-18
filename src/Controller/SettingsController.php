<?php

declare(strict_types=1);

namespace NetAnts\WhatsRabbitLiveChat\Controller;

use Craft;
use craft\web\Controller;
use NetAnts\WhatsRabbitLiveChat\Exception\InvalidDataException;
use NetAnts\WhatsRabbitLiveChat\Plugin;
use NetAnts\WhatsRabbitLiveChat\Service\SettingsService;
use NetAnts\WhatsRabbitLiveChat\ValueObject\LiveChatConfig;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;
use yii\base\Module;
use yii\web\Response;

class SettingsController extends Controller
{
    public function __construct(
        string $id,
        Module $module,
        private SettingsService $settingsService,
        private Craft $craft,
        array $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * @throws InvalidConfigException
     * @throws BadRequestHttpException
     */
    public function actionSave(): ?Response
    {
        $data = $this->request->getBodyParams();

        try {
            $liveChatConfig = LiveChatConfig::createFromRequest($data);
        } catch (InvalidDataException $e) {
            $this->craft::$app->session->setError('Something went wrong while creating config' . $e->getMessage());
            return $this->redirectToPostedUrl();
        }

        $saved = $this->settingsService->saveSettings(
            Plugin::getInstance(),
            $liveChatConfig,
        );

        if (!$saved) {
            $this->craft::$app->session->setError('Something went wrong while saving the plugin settings');
            return $this->redirectToPostedUrl();
        }

        $this->craft::$app->session->setSuccess('Plugin settings updated');
        return $this->redirectToPostedUrl();
    }
}
