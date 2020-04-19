<?php
namespace App\Models\Traits;

use Illuminate\Support\Facades\Storage;
use Image;

trait ImageTrait
{

	public function afterInsert(array $data)
	{
		$path = "app/public/{$this->getTable()}/{$this->id}.png";
		if (isset($data['image'])) {
			Image::make($data['image'])->fit(800, 800, function ($constrain) {
				$constrain->aspectRatio();
			})->save(storage_path($path));
		}

		$thumbPath = "app/public/{$this->getTable()}/{$this->id}_thumb.png";
		if (isset($data['image'])) {
			Image::make($data['image'])->fit(150, 150, function ($constrain) {
				$constrain->aspectRatio();
			})->save(storage_path($thumbPath));
		}
	}

	public function afterUpdate(array $data)
	{
		if (isset($data['image']) && $data['image'] !== null) {
			$path = "public/{$this->getTable()}/{$this->id}.png";
			if (Storage::exists($path)) {
				Storage::delete($path);
			}
			$thumbPath = "public/{$this->getTable()}/{$this->id}_thumb.png";
			if (Storage::exists($thumbPath)) {
				Storage::delete($thumbPath);
			}
		}

		$path = "app/public/{$this->getTable()}/{$this->id}.png";
		if (isset($data['image'])) {
			Image::make($data['image'])->fit(800, 800, function ($constrain) {
				$constrain->aspectRatio();
			})->save(storage_path($path));
		}

		$thumbPath = "app/public/{$this->getTable()}/{$this->id}_thumb.png";
		if (isset($data['image'])) {
			Image::make($data['image'])->fit(150, 150, function ($constrain) {
				$constrain->aspectRatio();
			})->save(storage_path($thumbPath));
		}
	}

	public function getFileUrlAttribute()
	{
		$path = "public/{$this->getTable()}/{$this->id}.png";
		if (Storage::exists($path)) {
			return Storage::url($path);
		}
		return null;
	}

	public function getThumbUrlAttribute()
	{
		$path = "public/{$this->getTable()}/{$this->id}_thumb.png";
		if (Storage::exists($path)) {
			return Storage::url($path);
		}
		return null;
	}
}
