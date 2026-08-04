<?php

namespace Tests;

use Symfony\Component\DomCrawler\Crawler;

class ProgressTest extends ComponentTestCase
{
    public function testDefaultProgressBar(): void
    {
        $progress = $this->getProgress("<x-bootstrap::progress />");

        $this->assertSame('div', $progress->nodeName());
        $this->assertSame('progressbar', $progress->attr('role'));

        $this->assertCount(1, $progress->children());
        $this->assertCount(1, $progress->children('.progress-bar'));
    }

    public function testLabelAttribute(): void
    {
        $progress = $this->getProgress("<x-bootstrap::progress />");

        $this->assertNull($progress->attr('aria-label'));

        $progress = $this->getProgress("<x-bootstrap::progress label='File transfer' />");

        $this->assertSame('File transfer', $progress->attr('aria-label'));
    }


    public function testDefaultValueIs25(): void
    {
        $progress = $this->getProgress("<x-bootstrap::progress />");
        $filler = $progress->children('.progress-bar');

        $this->assertSame('25', $progress->attr('aria-valuenow'));
        $this->assertSame('width: 25%', $filler->attr('style'));
    }

    public function testDefaultMinValueIsZero(): void
    {
        $progress = $this->getProgress("<x-bootstrap::progress />");

        $this->assertSame('0', $progress->attr('aria-valuemin'));
    }


    public function testDefaultMaxValueIs100(): void
    {
        $progress = $this->getProgress("<x-bootstrap::progress />");

        $this->assertSame('100', $progress->attr('aria-valuemax'));
    }

    public function testFillIsBasedOnValue(): void
    {
        $progress = $this->getProgress('<x-bootstrap::progress value="2" max="3"/>');
        $filler = $progress->filter('.progress-bar');

        $this->assertSame('width: 66%', $filler->attr('style'));
    }

    public function testProgressBarLabel(): void
    {
        $progress = $this->getProgress(
            '<x-bootstrap::progress>Progressing...</x-bootstrap::progress>'
        );

        $this->assertSame(
            'Progressing...',
            $progress->filter('.progress-bar')->text()
        );
    }

    public function testColorUsesBackground(): void
    {
        $progress = $this->getProgress(
            '<x-bootstrap::progress color="secondary"/>'
        );

        $this->assertStringContainsString(
            'bg-secondary',
            $progress->filter('.progress-bar')->attr('class')
        );
    }

    public function testColorsSwitchesToTextWhenSlotHasContent(): void
    {
        $progress = $this->getProgress(
            '<x-bootstrap::progress color="secondary">Some text</x-bootstrap::progress>'
        );

        $this->assertStringContainsString(
            'text-bg-secondary',
            $progress->filter('.progress-bar')->attr('class')
        );
    }

    public function testStripedAttribute(): void
    {

        $progress = $this->getProgress('<x-bootstrap::progress />');

        $this->assertCount(0, $progress->filter('.progress-bar-striped'));

        $progress = $this->getProgress('<x-bootstrap::progress striped />');

        $this->assertStringContainsString(
            'progress-bar-striped',
            $progress->filter('.progress-bar')->attr('class')
        );
    }

    public function testAnimatedAttribute(): void
    {
        $progress = $this->getProgress('<x-bootstrap::progress />');

        $this->assertCount(0, $progress->filter('.progress-bar-animated'));

        $progress = $this->getProgress('<x-bootstrap::progress animated />');

        $this->assertStringContainsString(
            'progress-bar-animated',
            $progress->filter('.progress-bar')->attr('class')
        );
    }
    protected function getProgress(string $template): Crawler
    {
        return (new Crawler($this->render($template)))->filter('.progress')->first();
    }
}
