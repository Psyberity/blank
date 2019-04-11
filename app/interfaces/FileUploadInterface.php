<?php
namespace App\Interfaces;

use Phalcon\Http\Request;

interface FileUploadInterface
{
    public function upload(Request $request):bool;
    public function getUploadedName():string;
}