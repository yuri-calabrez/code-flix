<?php


namespace CodeFlix\Media;


trait SeriePaths
{
    use VideoStorages;

    public function getThumbFolderStorageAttribute()
    {
        return "series/{$this->id}";
    }

    public function getThumbRelativeAttribute()
    {
        return "{$this->thumb_folder_storage}/{$this->thumb}";
    }

    public function getThumbPathAttribute()
    {
        return $this->getAbsolutePath($this->getStorage(), $this->thumb_relative);
    }

    public function getThumbSmallRelativeAttribute()
    {
        list($name, $extension) = explode('.', $this->thumb);
        return "{$this->thumb_folder_storage}/{$name}_small.{$extension}";
    }

    public function getThumbSmallPathAttribute()
    {
        return $this->getAbsolutePath($this->getStorage(), $this->thumb_small_relative);
    }

    public function getThumbAssetAttribute()
    {
        return route('admin.series.thumb-asset', ['serie' => $this->id]);
    }

    public function getThumbSmallAssetAttribute()
    {
        return route('admin.series.thumb-small-asset', ['serie' => $this->id]);
    }
}