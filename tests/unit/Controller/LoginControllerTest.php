<?php

declare(strict_types=1);

namespace NetAnts\WhatsRabbitLiveChatTest\Controller;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use NetAnts\WhatsRabbitLiveChat\Controller\LoginController;
use NetAnts\WhatsRabbitLiveChat\Service\SettingsService;
use PHPUnit\Framework\TestCase;
use yii\base\Module;

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
}
