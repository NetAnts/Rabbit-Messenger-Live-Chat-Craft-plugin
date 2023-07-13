<?php

namespace NetAnts\WhatsRabbitLiveChat\Service;

use Craft;
use craft\elements\Asset;

class AssetService
{
    public function __construct(
        private readonly Craft $craft,
    )
    {
    }

    public function createAsset(string $fileName): Asset
    {
        $tmpPath = $this->craft::$app->getPath()->getTempPath();
        $volume = $this->craft::$app->getVolumes()->getVolumeByHandle('local');
        $storagePath = sprintf("%swhatsrabbit-live-chat", $this->craft::$app->path->getStoragePath());
        $asset = new Asset();
        $asset->tempFilePath = sprintf(
            "%s%s",
            $tmpPath,
            $fileName
        );
        $asset->filename = $fileName;
        $asset->title = $fileName;
        $asset->avoidFilenameConflicts = true;
        $asset->setScenario(Asset::SCENARIO_CREATE);
        $asset->folderPath = $storagePath;
        $asset->volumeId = $volume->id;
        $asset->folderId;


        $success = $this->craft::$app->getElements()->saveElement($asset);

        if(!$success) {
            throw new \Exception();
        }

        return $asset;
    }
}