<?php

declare(strict_types=1);

namespace NetAnts\WhatsRabbitLiveChatTest\Controller;

use Craft;
use craft\test\TestSetup;
use craft\web\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use NetAnts\WhatsRabbitLiveChat\Controller\SettingsController;
use NetAnts\WhatsRabbitLiveChat\Service\SettingsService;
use PHPUnit\Framework\TestCase;
use yii\base\Module;
use yii\web\Response;

class SettingsControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private SettingsService|MockInterface $settingsService;
    private Craft | MockInterface $craft;
    private SettingsController $controller;

    protected function setUp(): void
    {
        $id = 'settingsController';
        $module = Mockery::mock(Module::class);
        $config = [];
        $this->craft = Mockery::mock(Craft::class);
        $this->settingsService = Mockery::mock(SettingsService::class);
        $this->controller = new SettingsController($id, $module, $this->settingsService, $this->craft, $config);
    }


    public function testSavingAction(): void
    {
        $request = Mockery::mock(Request::class);
        $request->expects('getBodyParams')->andReturn([
            'apiKey' => 'some-api-key',
            'apiSecret' => 'some-api-secret',
            'title' => 'Some title',
            'description' => 'Some description',
            'avatarAssetId' => ['some-avatar-id'],
            'whatsAppUrl' => 'https://wa.me',
            'loginUrl' => '',
        ]);
        $request->expects('getValidatedBodyParam')->andReturn(null);
        $request->expects('getPathInfo')->andReturn('/api');
        $this->settingsService->expects('saveSettings')->withAnyArgs()->andReturn(true);
        $this->controller->request = $request;
        $response = $this->controller->actionSave();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(302, $response->getStatusCode());
        $this->assertTrue($response->getIsRedirection());
    }

    public function testActionSaveFails(): void
    {
        $request = Mockery::mock(Request::class);
        $request->expects('getBodyParams')->andReturn([
            'apiKey' => 'some-api-key',
            'apiSecret' => 'some-api-secret',
            'title' => 'Some title',
            'description' => 'Some description',
            'avatarAssetId' => ['some-avatar-id'],
            'whatsAppUrl' => 'https://wa.me',
            'loginUrl' => '',
        ]);
        $request->expects('getValidatedBodyParam')->andReturn(null);
        $request->expects('getPathInfo')->andReturn('/api');
        $this->settingsService->expects('saveSettings')->withAnyArgs()->andReturn(false);
        $this->controller->request = $request;
        $response = $this->controller->actionSave();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(
            'Something went wrong while saving the plugin settings',
            $this->craft::$app->session->getError()
        );
    }

    public function testActionSaveButLiveChatConfigCannotBeCreated(): void
    {
        $request = Mockery::mock(Request::class);
        $request->expects('getBodyParams')->andReturn([
            'apiKey' => 'some-api-key',
            'apiSecret' => 'some-api-secret',
            'title' => 'Some title',
            'description' => 'Some description',
            'avatarAssetId' => ['some-avatar-id'],
            'loginUrl' => '',
        ]);
        $request->expects('getValidatedBodyParam')->andReturn(null);
        $request->expects('getPathInfo')->andReturn('/api');
        $this->controller->request = $request;
        $response = $this->controller->actionSave();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(
            'Something went wrong while creating configCould not create LiveChatConfig because the following data is missing "whatsAppUrl"',
            $this->craft::$app->session->getError()
        );
    }
}
