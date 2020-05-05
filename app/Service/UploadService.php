<?php
namespace App\Service;
use Slim\Http\UploadedFile;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Gd\Imagine;

class UploadService {

  private $folder = 'public/upload';

  public function __construct(string $folder = null){
    $this->folder = $folder ? $folder : $this->folder;
  }

  public function createFile(UploadedFile $file, $outputName) {
    $ds = DIRECTORY_SEPARATOR;

    $folder = $_ENV['UPLOAD_FOLDER'];
    if (empty($env)) $folder = 'public/upload';
    $folder = ROOT . $ds . $folder;

    if (!is_dir($folder)) {
      mkdir($folder);
    }
    if (!is_dir($folder . $ds . 'downloads')) {
      mkdir($folder . $ds . 'downloads');
    }

    $full = $folder . $ds . 'downloads' . $ds .$outputName;

    $file->moveTo($full);

    return $full;
  }

  public function createImageWithThumbnail(UploadedFile $file, $outputName) {
    $ds = DIRECTORY_SEPARATOR;

    $folder = $_ENV['UPLOAD_FOLDER'];
    if (empty($env)) $folder = 'public/upload';
    $folder = ROOT . $ds . $folder;

    if (!is_dir($folder)) {
      mkdir($folder);
    }
    if (!is_dir($folder . $ds . 'thumbnails')) {
      mkdir($folder . $ds . 'thumbnails');
    }

    $full = $folder . $ds . $outputName;
    $thumbnail = $folder . $ds . 'thumbnails' . $ds . $outputName;

    $file->moveTo($full);

    // thumbnail
    $size = new Box(90, 90);
    $mode = ImageInterface::THUMBNAIL_OUTBOUND;
    $imagine = new Imagine();
    
    $imagine->open($full)
    ->thumbnail($size, $mode)
    ->save($thumbnail);

    // fulll

    $size = new Box(1920, 1200);
    $mode = ImageInterface::THUMBNAIL_INSET;
    $imagine = new Imagine();

    $imageFull = $imagine->open($full);

    $width = $imageFull->getSize()->getWidth();
    $height = $imageFull->getSize()->getHeight();

    $ratio = $width / $height;
    if ($width > 1920) {
      $imageFull->resize(new Box(1920, 1920 / $ratio))->save($full);
    }

    return $imageFull;
  }

  public function rename($oldName, $newName) {

  }

  public function generateRandomName(string $extension, string $prefix = '') {
    return $prefix . sprintf('%s.%0.8s', bin2hex(random_bytes(8)), $extension);
  }

  public function accepted(UploadedFile $file, $extension) {
    if ($extension == 'image') {
      $mime = $file->getClientMediaType();
      if ($mime == 'image/jpeg' || $mime == 'image/png' || $mime == 'image/png') {
        return true;
      }
    } else if ($extension == 'file'){
      $mime = $file->getClientMediaType();
      // TODO verificar extensão
      return true;
    }

    return false;
  }
}
