<?php

namespace Tests;

use DOMDocument;
use Symfony\Component\DomCrawler\Crawler;

class BadgeTest extends ComponentTestCase
{
    public function testRendersSpan(): void
    {
        $crawler = new Crawler($this->render("<x-bootstrap::badge/>"));

        $this->assertCount(1, $crawler->filter('span'));
    }

    public function testVariant(): void
    {
        $crawler = new Crawler($this->render("<x-bootstrap::badge variant='primary'/>"));

        $this->assertStringContainsString(
            "text-bg-primary",
            $crawler->filter('span')->attr('class')
        );
    }

    public function testSlot(): void
    {
        $crawler = new Crawler($this->render("<x-bootstrap::badge variant='primary'>Slot content</x-bootstrap::badge>"));

        $this->assertSame(
            "Slot content",
            $crawler->filter('span')->innerText()
        );
    }

    public function testPillFlag(): void
    {
        $crawler = new Crawler($this->render("<x-bootstrap::badge pill>Slot content</x-bootstrap::badge>"));

        $this->assertStringContainsString(
            "rounded-pill",
            $crawler->filter('span')->attr('class')
        );
    }
}
