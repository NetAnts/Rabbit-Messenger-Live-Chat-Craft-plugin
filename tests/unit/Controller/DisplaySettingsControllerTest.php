<?php

declare(strict_types=1);

namespace Rabbit\RabbitMessengerLiveChatTest\Controller;

use Craft;
use craft\test\TestSetup;
use craft\web\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use Rabbit\RabbitMessengerLiveChat\Controller\DisplaySettingsController;
use Rabbit\RabbitMessengerLiveChat\Exception\InvalidDataException;
use Rabbit\RabbitMessengerLiveChat\Model\DisplaySettings;
use Rabbit\RabbitMessengerLiveChat\Service\SettingsService;
use Rabbit\RabbitMessengerLiveChat\ValueObject\LiveChatConfig;
use PHPUnit\Framework\TestCase;
use yii\base\Module;
use yii\web\Response;

class DisplaySettingsControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private SettingsService|MockInterface $settingsService;
    private Craft | MockInterface $craft;
    private DisplaySettingsController $controller;

    protected function setUp(): void
    {
        $id = 'displaySettingsController';
        $module = Mockery::mock(Module::class);
        $config = [];
        $this->craft = Mockery::mock(Craft::class);
        $this->settingsService = Mockery::mock(SettingsService::class);
        $this->settingsService->expects('getSettings')->andReturn(LiveChatConfig::createFromRequest([
            'avatarAssetId' => 'some-asset-id',
            'description' => 'some-description',
            'whatsAppUrl' => 'some-url',
            'desktopExpanded' => true,
            'showInformationForm' => true,
            'starterPopupTimer' => 25,
            'enabled' => true,
            'position' => 'fixed',
            'zIndex' => '10',
            'left' => 'inherit',
            'right' => '0',
            'bottom' => '0',
            'top' => 'inherit',
            'margin' => '20px',
        ]));
        $this->controller = new DisplaySettingsController($id, $module, $this->settingsService, $this->craft, $config);
    }


    public function testSavingAction(): void
    {
        $request = Mockery::mock(Request::class);
        $request->expects('getBodyParams')->andReturn([
            'apiKey' => 'some-api-key',
            'apiSecret' => 'some-api-secret',
            'description' => 'Some description',
            'avatarAssetId' => ['some-avatar-id'],
            'whatsAppUrl' => 'https://wa.me',
            'desktopExpanded' => true,
            'showInformationForm' => true,
            'starterPopupTimer' => 25,
            'enabled' => true,
            'position' => 'fixed',
            'zIndex' => '10',
            'left' => 'inherit',
            'right' => '0',
            'bottom' => '0',
            'top' => 'inherit',
            'margin' => '20px',
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
            'description' => 'Some description',
            'avatarAssetId' => ['some-avatar-id'],
            'whatsAppUrl' => 'https://wa.me',
            'desktopExpanded' => true,
            'showInformationForm' => true,
            'starterPopupTimer' => 25,
            'enabled' => true,
            'position' => 'fixed',
            'zIndex' => '10',
            'left' => 'inherit',
            'right' => '0',
            'bottom' => '0',
            'top' => 'inherit',
            'margin' => '20px',
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

    public function testModelValidationError()
    {
        $request = Mockery::mock(Request::class);
        $request->expects('getBodyParams')->andReturn([
            'description' => null,
            'avatarAssetId' => ['some-avatar-id'],
            'whatsAppUrl' => 'https://wa.me',
            'desktopExpanded' => true,
            'showInformationForm' => true,
            'starterPopupTimer' => 25,
            'enabled' => true,
            'position' => 'fixed',
            'zIndex' => '10',
            'left' => 'inherit',
            'right' => '0',
            'bottom' => '0',
            'top' => 'inherit',
            'margin' => '20px',
        ]);
        $request->expects('getAcceptsJson')->andReturnTrue();
        $this->controller->request = $request;
        $response = $this->controller->actionSave();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(400, $response->getStatusCode());
    }

    public function testActionSaveButLiveChatConfigCannotBeCreated(): void
    {
        $request = Mockery::mock(Request::class);
        $request->expects('getBodyParams')->andReturn([
            'apiKey' => 'some-api-key',
            'apiSecret' => 'some-api-secret',
            'description' => 'Some description',
            'avatarAssetId' => ['some-avatar-id'],
            'whatsAppUrl' => true,
            'desktopExpanded' => true,
            'showInformationForm' => true,
            'starterPopupTimer' => 25,
            'enabled' => 'true',
            'position' => 'fixed',
            'zIndex' => '10',
            'left' => 'inherit',
            'right' => '0',
            'bottom' => '0',
            'top' => 'inherit',
            'margin' => '20px',
        ]);
        $request->expects('getValidatedBodyParam')->andReturn(null);
        $request->expects('getPathInfo')->andReturn('/api');
        $this->controller->request = $request;
        $this->controller->actionSave();
        $this->assertStringStartsWith(
            'Something went wrong while creating config: Rabbit\RabbitMessengerLiveChat\ValueObject\LiveChatConfig::__construct(): ' .
            'Argument #3 ($whatsAppUrl) must be of type string',
            $this->craft::$app->session->getError()
        );
    }

    public function testActionEdit(): void
    {
        $response = $this->controller->actionEdit();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testActionEditWithSettingsFromRoute(): void
    {
        $displaySettings = new DisplaySettings([
            'description' => 'Some description',
            'avatarAssetId' => 0,
            'whatsAppUrl' => 'https://wa.me',
            'enabled' => false,
            ]);
        $response = $this->controller->actionEdit($displaySettings);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }
}
