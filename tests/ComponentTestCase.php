<?php

namespace Tests;

use Blade\Blade;
use PHPUnit\Framework\TestCase;

abstract class ComponentTestCase extends TestCase
{

    private array $createdFiles = [];

    protected Blade $blade;

    protected function setup(): void
    {
        parent::setUp();

        if (!is_dir($this->getTempDirectory())) {
            mkdir($this->getTempDirectory());
        }

        if (!is_dir($this->getCacheDirectory())) {
            mkdir($this->getCacheDirectory());
        }

        $this->blade = new Blade($this->getTempDirectory(), $this->getCacheDirectory());

        $this->blade->addAnonymousComponentPath(
            __DIR__ . '/../views/components',
            'bootstrap'
        );
    }


    public function tearDown(): void
    {
        $this->deleteDirectory($this->getCacheDirectory());
        $this->deleteDirectory($this->getTempDirectory());

        parent::tearDown();
    }

    protected function deleteDirectory(string $directory): void
    {
        $directory = rtrim($directory, '/');
        $files = glob("$directory/*");

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            } elseif (is_dir($file)) {
                $this->deleteDirectory($file);
            }
        }

        is_dir($directory) && rmdir($directory);
    }

    protected function cleanUpGeneratedFiles(): void
    {
        $files = array_merge(
            $this->createdFiles,
            glob("{$this->blade->config->cachePath}/*.php")
        );

        foreach ($files as $file) {
            if (!file_exists($file)) {
                continue;
            }
            unlink($file);
        }
    }

    protected function getTempDirectory(): string
    {
        static $directory;

        if (is_null($directory)) {
            $directory = __DIR__ . '/tmp';
            sys_get_temp_dir();
        }



        return $directory;
    }

    /**
     * Undocumented function
     *
     * @param string $template
     * @return string
     */
    protected function createTemporaryBladeFile(string $template, string $name = '', $directory = ''): string
    {
        if (empty($name)) {
            $name = hash('sha256', $template);
        }

        if (str_contains($name, '.')) {
            $name = str_replace('.', '/', $name);
        }


        $file = sprintf(
            "%s/%s.blade.php",
            $directory ? $directory : $this->getTempDirectory(),
            $name
        );

        $directory = pathinfo($file, PATHINFO_DIRNAME);
        $this->recursivelyCreateDirectory($directory);

        if (file_put_contents($file, $template) === false) {
            throw new \Exception('Could not create temporary file');
        }

        $this->createdFiles[] = $file;

        return $name;
    }

    protected function recursivelyCreateDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            mkdir(
                directory: $directory,
                recursive: true
            );
        }
    }


    public function render($template, $data = [])
    {
        $name = $this->createTemporaryBladeFile(template: $template);

        return (string) $this->blade->render($name, $data);
    }

    public function createComponent(string $name, string $template, string $namespace = '')
    {
        $directory = $this->getTempDirectory() . "/components";

        if (strlen($namespace) > 0) {
            $directory = $this->getNamespaceDir($namespace);
        }

        $name = $this->createTemporaryBladeFile(
            $template,
            $name,
            $directory
        );
    }

    public function getNamespaceDir(string $namespace): string
    {
        return sprintf("%s/namespaces/%s", $this->getTempDirectory(), $namespace);
    }

    protected function removeIndentation(string $input): string
    {
        return preg_replace('/\s+/', ' ', trim($input));
    }

    protected function getCacheDirectory(): string
    {
        return $this->getTempDirectory() . '/.cache';
    }
}
