<?php

declare(strict_types=1);

namespace NetAnts\WhatsRabbitLiveChatTest\Controller;

use DateTimeImmutable;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use NetAnts\WhatsRabbitLiveChat\Controller\LoginController;
use NetAnts\WhatsRabbitLiveChat\Service\SettingsService;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Whatsrabbit\LiveChatPluginCore\LiveChatService;
use Whatsrabbit\LiveChatPluginCore\ValueObject\AuthenticationResponse;
use yii\base\Module;
use yii\web\Response;

class LoginControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private SettingsService|MockInterface $settingsService;
    private LoginController $loginController;
    protected function setUp(): void
    {
        $id = 'settingsController';
        $module = Mockery::mock(Module::class);
        $config = [];
        $this->settingsService = Mockery::mock(SettingsService::class);
        $this->settingsService->pluginRepoUrl = 'bla';
        $this->loginController = new LoginController($id, $module, $this->settingsService, $config);
    }

    public function testCanCreate(): void
    {
        $this->assertInstanceOf(LoginController::class, $this->loginController);
    }

    public function testActionGetToken(): void
    {
        // Given
        $token = new AuthenticationResponse(
            'externalId',
            'token',
            'refreshToken',
            new DateTimeImmutable(),
        );
        $liveChatProperty = new ReflectionProperty($this->loginController, 'liveChatService');
        $liveChatService = Mockery::mock(LiveChatService::class);
        $liveChatProperty->setAccessible(true);
        $liveChatProperty->setValue($this->loginController, $liveChatService);
        $liveChatService->expects('fetchToken')->andReturn($token);

        // When
        $result = $this->loginController->actionGetToken();

        // Then
        $this->assertInstanceOf(Response::class, $result);
        $this->assertSame($this->loginController->asJson($token), $result);
    }
}
