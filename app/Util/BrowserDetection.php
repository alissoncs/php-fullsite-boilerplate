<?php

namespace App\Util;

class BrowserDetection
{
  public function detect()
  {
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
      return 'ie';
    elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== false)
      return 'edge';
    elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false)
      return 'ie';
    elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false)
      return 'firefox';
    elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false)
      return 'chrome';
    elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false)
      return 'opera-mini';
    elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false)
      return 'opera';
    elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== false)
      return 'safari';
    else
      return 'other';
  }
}
