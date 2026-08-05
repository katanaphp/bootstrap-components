<?php

namespace Tests;

use Symfony\Component\DomCrawler\Crawler;

class AlertTest extends ComponentTestCase
{
    public function testDefault(): void
    {
        $alert = $this->getAlert('<x-bootstrap::alert>Slot content</x-bootstrap::alert>');

        $this->assertSame('alert', $alert->attr('role'));
        $this->assertStringContainsString('alert-primary', $alert->attr('class'));
        $this->assertSame('Slot content', $alert->text());
    }

    public function testVariantAttribute(): void
    {
        $alert = $this->getAlert('<x-bootstrap::alert variant="secondary"/>');

        $this->assertStringContainsString('alert-secondary', $alert->attr('class'));
    }

    public function testDefaultLink(): void
    {
        $link = $this->getCrawler(
            '.alert-link',
            $this->render('<x-bootstrap::alert.link />')
        );

        $this->assertSame('#', $link->attr('href'));
    }

    public function testLinkSlotAndLabel(): void
    {
        $link = $this->getCrawler(
            '.alert-link',
            $this->render('<x-bootstrap::alert.link label="Link text" />')
        );

        $this->assertSame('Link text', $link->text());


        $link = $this->getCrawler(
            '.alert-link',
            $this->render('<x-bootstrap::alert.link>Link slot</x-bootstrap::alert.link>')
        );

        $this->assertSame('Link slot', $link->text());
    }

    public function testLabelHasPriorityOverSlot(): void
    {
        $link = $this->getCrawler(
            '.alert-link',
            $this->render('<x-bootstrap::alert.link label="Link label">Link slot</x-bootstrap::alert.link>')
        );

        $this->assertSame('Link label', $link->text());
    }

    public function testDefaultHeadingLevelIs3(): void
    {
        $heading = $this->getCrawler(
            '.alert-heading',
            $this->render('<x-bootstrap::alert.heading />')
        );

        $this->assertSame('h3', $heading->nodeName());
    }

    public function testLevelChangesTag(): void
    {
        $heading = $this->getCrawler(
            '.alert-heading',
            $this->render('<x-bootstrap::alert.heading level="6" />')
        );

        $this->assertSame('h6', $heading->nodeName());
    }

    public function testHeadingAttributeAndSlot(): void
    {
        $heading = $this->getCrawler(
            '.alert-heading',
            $this->render('<x-bootstrap::alert.heading heading="Heading content"/>')
        );

        $this->assertSame('Heading content', $heading->text());

        $heading = $this->getCrawler(
            '.alert-heading',
            $this->render('<x-bootstrap::alert.heading> Slot heading content </x-bootstrap::alert.heading>')
        );

        $this->assertSame('Slot heading content', $heading->text());
    }

    public function testHeadingAttributeHasPriorityOverSlot(): void
    {
        $heading = $this->getCrawler(
            '.alert-heading',
            $this->render('<x-bootstrap::alert.heading heading="Heading content">Slot content</x-bootstrap::alert.heading>')
        );

        $this->assertSame('Heading content', $heading->text());
    }

    public function testDismissibleAttribute(): void
    {
        $alert = $this->getAlert('<x-bootstrap::alert dismissible/>');

        $this->assertStringContainsString('alert-dismissible', $alert->attr('class'));

        $this->assertSame(
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
            $alert->filter('.btn-close')->outerHtml()
        );
    }

    protected function getAlert(string $template): Crawler
    {
        return (new Crawler($this->render($template)))->filter('.alert')->first();
    }

    protected function getCrawler(string $selector, string $html): Crawler
    {
        return (new Crawler($this->render($html)))->filter($selector)->first();
    }
}
