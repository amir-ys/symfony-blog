<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class FileUploader
{
    public function __construct(
        private KernelInterface  $kernel,
        private SluggerInterface $slugger,
        private Filesystem       $filesystem
    )
    {
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            throw new $e;
        }

        return $fileName;
    }

    public function removePreviousUpload($fileName): bool
    {
        $fileFullPath = $this->kernel->getProjectDir() . "/public/{$this->getTargetDirectory()}/" . $fileName;
        if ($this->filesystem->exists($fileFullPath)) {
            $this->filesystem->remove($fileFullPath);
            return true;
        }
        return false;
    }

    public function getTargetDirectory(): string
    {
        return 'images';
    }
}
