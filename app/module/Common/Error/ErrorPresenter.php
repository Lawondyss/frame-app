<?php
/**
 * @author Ladislav Vondráček <lad.von@gmail.com>
 */

namespace App\Module\Common;

use Nette;
use Tracy\ILogger;

class ErrorPresenter extends BasePresenter
{
  public function actionDefault($exception)
  {
    if (!$exception instanceof Nette\Application\BadRequestException) {
      $priority = ILogger::EXCEPTION;
      $this->setView('500');
    } else {
      $priority = ILogger::WARNING;
      $code = $exception->getCode();
      $view = in_array($code, [403, 404, 405, 410]) ? $code : '4xx';
      $this->setView($view);
    }

    if (isset($code) && $code == 404) {
      $this->logger->log($this->getHttpRequest()
                              ->getRemoteAddress(), '404');
    } else {
      $this->logger->log($exception, $priority);
    }
  }
}
