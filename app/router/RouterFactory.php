<?php
/**
 * @author Ladislav Vondráček <lad.von@gmail.com>
 */

namespace App\Router;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\SmartObject;

class RouterFactory
{
  use SmartObject;


  public function create(): RouteList
  {
    $router = new RouteList;

    $router[] = $this->systemRouter();
    $router[] = $this->frontRouter();

    return $router;
  }


  private function systemRouter(): RouteList
  {
    $router = new RouteList('System');

    $router[] = new Route('/system/<presenter>[/<action>[/id]]');

    return $router;
  }


  private function frontRouter(): RouteList
  {
    $router = new RouteList('Front');

    $router[] = new Route('<presenter>[/<action>[/id]]', [
      'presenter' => 'Home',
      'action' => 'default',
      'id' => null,
    ]);

    return $router;
  }
}
