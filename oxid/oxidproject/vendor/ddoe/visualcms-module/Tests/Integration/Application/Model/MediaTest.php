<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\VisualCmsModule\Tests\Integration\Application\Model;

use OxidEsales\EshopCommunity\Core\Registry;
use OxidEsales\VisualCmsModule\Application\Model\Media;
use PHPUnit\Framework\TestCase;

class MediaTest extends TestCase
{
    private const ALTERNATIVE_IMAGE_DIRECTORY_PARAMETER = 'ddoeVisualCmsAlternativeImageDirectory';
    private const FIXTURE_FILE = 'file.jpg';
    private const FULL_MEDIA_PATH = '/out/pictures/ddmedia/';
    private const SHORT_MEDIA_PATH = '/ddmedia/';

    protected function setUp(): void
    {
        parent::setUp();

        $this->resetConfigParams();
    }

    public function testGetMediaPathWithNoAlternativeImageDirectory(): void
    {
        $mediaPath = (new Media())->getMediaPath(self::FIXTURE_FILE);

        $this->assertStringContainsString(self::FULL_MEDIA_PATH, $mediaPath);
    }

    public function testGetMediaPathWithAlternativeImageDirectory(): void
    {
        $externalUrl = 'https://some-cdn-url.com';
        Registry::getConfig()->setConfigParam(self::ALTERNATIVE_IMAGE_DIRECTORY_PARAMETER, $externalUrl);

        $mediaPath = (new Media())->getMediaPath(self::FIXTURE_FILE);

        $this->assertStringStartsWith($externalUrl, $mediaPath);
        $this->assertStringNotContainsString(self::FULL_MEDIA_PATH, $mediaPath);
        $this->assertStringContainsString(self::SHORT_MEDIA_PATH, $mediaPath);
    }

    public function testGetMediaUrlWithNoAlternativeImageDirectory(): void
    {
        $shopUrl = Registry::getConfig()->getConfigParam('sShopDir');
        Registry::getConfig()->setConfigParam('sShopDir', __DIR__ . '/../../../fixtures/');

        $mediaUrl = (new Media())->getMediaUrl(self::FIXTURE_FILE);
        Registry::getConfig()->setConfigParam('sShopDir', $shopUrl);

        $this->assertStringContainsString(self::FULL_MEDIA_PATH, $mediaUrl);
    }

    public function testGetMediaUrlWithAlternativeImageDirectory(): void
    {
        $externalUrl = 'https://some-cdn-url.com';
        Registry::getConfig()->setConfigParam(self::ALTERNATIVE_IMAGE_DIRECTORY_PARAMETER, $externalUrl);

        $mediaUrl = (new Media())->getMediaUrl(self::FIXTURE_FILE);

        $this->assertStringStartsWith($externalUrl, $mediaUrl);
        $this->assertStringNotContainsString(self::FULL_MEDIA_PATH, $mediaUrl);
        $this->assertStringContainsString(self::SHORT_MEDIA_PATH, $mediaUrl);
    }

    private function resetConfigParams(): void
    {
        Registry::getConfig()->setConfigParam(self::ALTERNATIVE_IMAGE_DIRECTORY_PARAMETER, null);
    }
}
