<?php

namespace App\Http\Controllers\Admin;

class JaszczflixController
{
    public function index()
    {
        try {
            $downloadedDirectories = \File::directories('/home/share/Movies/');
        } catch (\Exception $e) {
            $downloadedDirectories = [];
            \Log::warning("not found directory");
        }

        $filenames = [];
        foreach ($downloadedDirectories as $downloadedDirectory) {
            $files = \File::allFiles($downloadedDirectory);
            /** @var \SplFileInfo $file */
            foreach ($files as $file) {
                if (str_contains($file, 'mkv') ||
                    str_contains($file, 'avi') ||
                    str_contains($file, 'mp4') ||
                    str_contains($file, 'mov') ||
                    str_contains($file, 'mpg') ) {
                    // file is a movie
                    $filenames[] = str_replace('/home/share/Movies/', 'http://home:8182/', $file->getRealPath());
                }
            }
        }

        return \Response::view('jaszczflix/index', ['moviePaths' => $filenames]);
    }

    public function getMovie()
    {
        return \Response::view();
    }
}
