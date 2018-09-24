<?php
/**
 * @author Ladislav Vondráček <lad.von@gmail.com>
 */

namespace App\Module\System;

use Nette\Configurator;
use Nette\Utils\DateTime;
use Tracy\Debugger;

class DebugPresenter extends BasePresenter
{
  public function actionEnable()
  {
    $date = DateTime::from('+30 days');

    $this->getHttpResponse()->setCookie(Debugger::COOKIE_SECRET, 'enable-debugger', $date);
    $this->getHttpResponse()->setCookie(Configurator::COOKIE_SECRET, 'enable-debugger', $date);

    echo 'Debug cookie set to ', $date->format('Y-m-d'), ' for you current IP ', $this->getHttpRequest()->getRemoteAddress();

    $this->terminate();
  }


  public function actionDisable()
  {
    $this->getHttpResponse()->deleteCookie(Debugger::COOKIE_SECRET);
    $this->getHttpResponse()->deleteCookie(Configurator::COOKIE_SECRET);

    echo 'Debug cookie unset';

    $this->terminate();
  }
}
