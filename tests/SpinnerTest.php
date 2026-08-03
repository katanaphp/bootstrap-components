<?php

namespace Tests;

use Symfony\Component\DomCrawler\Crawler;

class SpinnerTest extends ComponentTestCase
{
    public function testDefaultSpinnerIsBorder(): void
    {
        $crawler = new Crawler($this->render('<x-bootstrap::spinner />'));

        $element = $crawler->filter('span')->first();

        $this->assertStringContainsString('spinner-border', $element->attr('class'));
    }

    public function testDefaultVariantIsBorder(): void
    {

        $this->assertStringContainsString(
            "spinner-border",
            $this->getSpinner("<x-bootstrap::spinner/>")->attr('class')
        );

        $this->assertStringContainsString(
            "spinner-grow",
            $this->getSpinner("<x-bootstrap::spinner variant='grow' />")->attr('class')
        );
    }

    public function testDefaultColorIsPrimary(): void
    {
        $this->assertStringContainsString(
            'text-primary',
            $this->getSpinner('<x-bootstrap::spinner />')->attr('class')
        );
    }

    public function testColorAttribute(): void
    {
        $spinner = $this->getSpinner("<x-bootstrap::spinner color='secondary' />");

        $this->assertStringContainsString(
            'text-secondary',
            $spinner->attr('class')
        );
    }

    public function testSupportCustomAttributes(): void
    {
        $spinner = $this->getSpinner("<x-bootstrap::spinner data-progress='10' />");

        $this->assertNotNull($spinner->attr('data-progress'));
        $this->assertSame('10', $spinner->attr('data-progress'));
    }

    public function testHasRoleAttribute(): void
    {
        $spinner = $this->getSpinner("<x-bootstrap::spinner />");

        $this->assertNotNull($spinner->attr('role'));
        $this->assertSame('status', $spinner->attr('role'));
    }

    public function testEmptyRoleRemovesAttribute(): void
    {
        $spinner = $this->getSpinner("<x-bootstrap::spinner role='' />");

        $this->assertNull($spinner->attr('role'));
    }

    public function testDefaultScreenReaderLabel(): void
    {
        $spinner = $this->getSpinner('<x-bootstrap::spinner/>');

        $label = $spinner->children('span');

        $this->assertCount(1, $label);
        $this->assertSame('visually-hidden', $label->attr('class'));
    }

    public function testScreenReaderLabelProp(): void
    {
        $spinner = $this->getSpinner('<x-bootstrap::spinner label="Downloading" />');
        $label = $spinner->children('span');

        $this->assertNull($spinner->attr('label'));
        $this->assertNull($label->attr('label'));

        $this->assertSame('Downloading', $label->text());
    }

    public function testEmptyLabelRemovesIt(): void
    {
        $spinner = $this->getSpinner('<x-bootstrap::spinner label="" />');

        $this->assertCount(0, $spinner->children('span'));
    }

    public function testSmallAttributeAddsClass(): void
    {
        $spinner = $this->getSpinner('<x-bootstrap::spinner small/>');

        $this->assertStringContainsString(
            'spinner-border-sm',
            $spinner->attr('class')
        );

        $spinner = $this->getSpinner('<x-bootstrap::spinner variant="grow" small/>');

        $this->assertStringContainsString(
            'spinner-grow-sm',
            $spinner->attr('class')
        );
    }

    protected function getSpinner(string $template): Crawler
    {
        // return

        return (new Crawler($this->render($template)))->filter('span')->first();
    }
}
