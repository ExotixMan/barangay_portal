<?php

namespace App\Services;

use RuntimeException;

class DocumentPdfService
{
    public function convertDocxToPdf(string $docxPath, string $pdfPath): void
    {
        if (! is_file($docxPath)) {
            throw new RuntimeException('The Word document to convert was not found.');
        }

        $outputDir = dirname($pdfPath);
        if (! is_dir($outputDir) && ! mkdir($outputDir, 0755, true) && ! is_dir($outputDir)) {
            throw new RuntimeException('Unable to create the PDF output directory.');
        }

        $binary = $this->resolveBinary();
        if ($binary === null) {
            throw new RuntimeException(
                'LibreOffice is not installed or cannot be found. On Render, install libreoffice in the Docker image or set LIBREOFFICE_BINARY to the soffice path.'
            );
        }

        $profileDir = $outputDir . '/.libreoffice-' . bin2hex(random_bytes(6));
        if (! mkdir($profileDir, 0700, true) && ! is_dir($profileDir)) {
            throw new RuntimeException('Unable to create a temporary LibreOffice profile directory.');
        }

        $sourceBase = pathinfo($docxPath, PATHINFO_FILENAME) . '.pdf';
        $convertedPath = $outputDir . DIRECTORY_SEPARATOR . $sourceBase;

        $command = sprintf(
            '%s --headless --nologo --nodefault --nolockcheck --nofirststartwizard -env:UserInstallation=%s --convert-to pdf --outdir %s %s 2>&1',
            escapeshellarg($binary),
            escapeshellarg('file://' . str_replace(DIRECTORY_SEPARATOR, '/', $profileDir)),
            escapeshellarg($outputDir),
            escapeshellarg($docxPath)
        );

        $output = [];
        $exitCode = 1;

        try {
            exec($command, $output, $exitCode);
        } finally {
            $this->removeDirectory($profileDir);
        }

        if ($exitCode !== 0 || ! is_file($convertedPath) || filesize($convertedPath) === 0) {
            $details = trim(implode("\n", $output));
            throw new RuntimeException('LibreOffice could not convert the document to PDF.' . ($details !== '' ? ' ' . $details : ''));
        }

        if ($convertedPath !== $pdfPath) {
            if (is_file($pdfPath)) {
                @unlink($pdfPath);
            }
            if (! @rename($convertedPath, $pdfPath)) {
                if (! @copy($convertedPath, $pdfPath)) {
                    throw new RuntimeException('PDF was created but could not be moved to its final filename.');
                }
                @unlink($convertedPath);
            }
        }
    }

    private function resolveBinary(): ?string
    {
        $configured = trim((string) env('LIBREOFFICE_BINARY', ''));
        $candidates = array_values(array_filter([
            $configured,
            '/usr/bin/soffice',
            '/usr/local/bin/soffice',
            '/usr/bin/libreoffice',
            '/usr/local/bin/libreoffice',
        ]));

        foreach ($candidates as $candidate) {
            if (is_file($candidate) && is_executable($candidate)) {
                return $candidate;
            }
        }

        foreach (['soffice', 'libreoffice'] as $name) {
            $result = trim((string) shell_exec('command -v ' . escapeshellarg($name) . ' 2>/dev/null'));
            if ($result !== '' && is_executable($result)) {
                return $result;
            }
        }

        return null;
    }

    private function removeDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            return;
        }

        foreach (array_diff(scandir($directory) ?: [], ['.', '..']) as $item) {
            $path = $directory . DIRECTORY_SEPARATOR . $item;
            is_dir($path) ? $this->removeDirectory($path) : @unlink($path);
        }

        @rmdir($directory);
    }
}
