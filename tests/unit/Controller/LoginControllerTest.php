<?php

declare(strict_types=1);

namespace NetAnts\WhatsRabbitLiveChatTest\Controller;

use Mockery;
use Mockery\MockInterface;
use NetAnts\WhatsRabbitLiveChat\Controller\LoginController;
use NetAnts\WhatsRabbitLiveChat\Service\SettingsService;
use PHPUnit\Framework\TestCase;
use yii\base\Module;

class LoginControllerTest extends TestCase
{
    private SettingsService|MockInterface $settingsService;
    private LoginController $loginController;
    protected function setUp(): void
    {
        $this->markTestSkipped('Will be fixed in the near future because Plugin can not be instantiated');
        $id = 'settingsController';
        $module = Mockery::mock(Module::class);
        $config = [];
        $this->settingsService = Mockery::mock(SettingsService::class);
        $this->loginController = new LoginController($id, $module, $this->settingsService, $config);
    }

    public function testCanCreate(): void
    {
        $this->assertInstanceOf(LoginController::class, $this->loginController);
    }
}
