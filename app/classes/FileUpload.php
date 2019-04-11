<?php
namespace App\Classes;

use Phalcon\Http\Request;

class FileUpload
{
    private $field;
    private $filename;
    private $uploadDirPath;
    private $uploadedName;

    public function __construct(string $field, string $filename, string $uploadDirPath)
    {
        $this->field = $field;
        $this->filename = $filename;
        if (mb_substr($uploadDirPath, mb_strlen($uploadDirPath) - 1) == '/') {
            $this->uploadDirPath = $uploadDirPath;
        } else {
            $this->uploadDirPath = $uploadDirPath . '/';
        }
    }

    public function upload(Request $request):bool
    {
        $files = $request->getUploadedFiles();
        if (empty($files)) return false;
        foreach ($files as $file) {
            if ($file->isUploadedFile()) {
                if ($this->field == $file->getKey()) {
                    $extension = $file->getExtension();
                    if (empty($extension)) {
                        throw new ExtensionException('Отсутствует расширение файла: ' . $this->field);
                    }
                    $this->uploadedName = $this->filename . '.' . $extension;
                    $file->moveTo($this->uploadDirPath . $this->uploadedName);
                    return true;
                }
            }
        }
        return false;
    }

    public function getUploadedName():string
    {
        return $this->uploadedName;
    }

}