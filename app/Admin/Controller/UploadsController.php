<?php

namespace App\Admin\Controller;

use App\Admin\Controller\BaseController;

class UploadsController extends BaseController
{
  public function create($request, $response, $args)
  {
    $files = $request->getUploadedFiles();

    if (!$files || !isset($files['file'])) {
      return $response->withJson(['detail' => 'Arquivo não enviado'], 422);
    }

    $file = $files['file'];

    $service = $this->container->get('service.upload');

    $image = null;

    $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
    $outputName = $service->generateRandomName($extension);
    
    $valids = ['png', 'jpg', 'jpeg'];
    if (!in_array($extension, $valids)) {
      return $response->withJson(['detail' => 'Extensão inválida: ' . implode(',', $valids)], 422);
    }

    try {
      $image = $service->createImageWithThumbnail($file, $outputName);
      return $response->withJson([
        'full' => $outputName,
        'thumbnail' => $outputName,
  
        'width' => $image->getSize()->getWidth(),
        'height' => $image->getSize()->getHeight(),
        'aspect_ratio' => $image->getSize()->getWidth() / $image->getSize()->getHeight(),
  
      ], 201);
    } catch(\Exception $e) {
      return $response->withJson(['detail' => $e->getMessage()], 500);
    }
  }

  public function createdDownloadable($request, $response, $args) {
    $files = $request->getUploadedFiles();

    if (!$files || !isset($files['file'])) {
      return $response->withJson(['detail' => 'Arquivo não enviado'], 422);
    }

    $file = $files['file'];

    $service = $this->container->get('service.upload');

    $image = null;

    $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
    $outputName = $file->getClientFilename();

    $valids = ['pdf', 'xlsx', 'docx', 'doc', 'xls', 'csv', 'mp4', 'mp3'];
    if (!in_array($extension, $valids)) {
      return $response->withJson(['detail' => 'Extensão inválida: ' . implode(',', $valids)], 422);
    }

    try {
      $service->createFile($file, $outputName);
      
      return $response->withJson([
        'full' => $outputName,
        'name' => $outputName,
        'extension' => $extension,
        'mime_type' => $file->getClientMediaType(),
      ], 201);
    } catch(\Exception $e) {
      return $response->withJson(['detail' => $e->getMessage()], 500);
    }
  }
}
