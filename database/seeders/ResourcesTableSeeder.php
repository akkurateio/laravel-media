<?php

namespace Akkurate\LaravelMedia\Database\Seeders;

use Illuminate\Database\Seeder;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Akkurate\LaravelMedia\Models\Resource;

class ResourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $files = Storage::disk('local')->files('to-import/resources');
        foreach ($files as $i => $file) {
            if($file != 'to-import/resources/.DS_Store') {
                echo $file . "\n";
                $this->add($file);
            }
        }
    }

    protected function add($file) {
        // Give the image a unique filename
        $filename = 'img-' . uniqid();

        $filePath = storage_path() . '/app/' . $file;
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);;

        // Check if resource already exists by comparing filename and file size
        if (Resource::where('md5', md5_file($filePath))->count()) {
            return;
        }

        $img = Image::make($filePath);
        $img->fit(300);

        // Create new Resource
        $resource = Resource::create([
            'name' => pathinfo($file, PATHINFO_FILENAME),
            'alt' => basename($file),
            'legend' => basename($file),
            'user_id' => 1,
            'account_id' => 1,
            'md5' => md5_file($filePath),
            'thumb' => 'data:image/' . $extension . ';base64,' . base64_encode($img->stream())
        ]);

        // Associate Media to the Resource
        $resource
            ->addMediaFromDisk($file, 'local')
            ->usingFileName($filename . '.' . $extension)
            ->preservingOriginal()
            ->toMediaCollection();

        $resource->update([
            'media_id' => $resource->getFirstMedia()->id,
        ]);
    }
}
