<?php
/**
 * @author Ladislav Vondráček <lad.von@gmail.com>
 */

namespace App\Module\Common;

use App\Service\Logger\Logger;
use Nette\Application\UI\Presenter;
use Nette\Utils\Strings;

abstract class BasePresenter extends Presenter
{
  /** @persistent */
  public $backlink;

  /** @var Logger @inject */
  public $logger;


  public function formatTemplateFiles(): array
  {
    $view = $this->getView() . '.latte';

    $paths = [
      $this->getDir('app') . $this->getDir('module') . $this->getDir('presenter') . $this->getDir('template') . $view,
      $this->getDir('app') . $this->getDir('module') . $this->getDir('template') . $view,
      $this->getDir('app') . $this->getDir('common') . $this->getDir('template') . $view,
    ];

    return $paths;
  }


  public function formatLayoutTemplateFiles(): array
  {
    $layout = '@' . ($this->layout ?: 'layout') . '.latte';

    $paths = [
      $this->getDir('app') . $this->getDir('module') . $this->getDir('presenter') . $this->getDir('template') . $layout,
      $this->getDir('app') . $this->getDir('module') . $this->getDir('template') . $layout,
      $this->getDir('app') . $this->getDir('common') . $this->getDir('template') . $layout,
    ];

    return $paths;
  }


  protected function getDir(string $name): string
  {
    switch ($name) {
      // absolute path to application directory
      case 'app':
        return rtrim($this->context->parameters['appDir'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

      // path to directory for actual module
      case 'module':
        $modulePath = dirname(dirname($this->getReflection()->getFileName()));
        return Strings::after($modulePath, DIRECTORY_SEPARATOR, -2) . DIRECTORY_SEPARATOR;

      // directory for presenter
      case 'presenter':
        return Strings::after($this->getName(), ':', -1) . DIRECTORY_SEPARATOR;

      // directory for templates
      case 'template':
        return '_templates' . DIRECTORY_SEPARATOR;

      // directory for common "module", here is base of all functional
      case 'common':
        $modulePath = dirname($this->getDir('module'));

        return $modulePath . DIRECTORY_SEPARATOR . 'Common' . DIRECTORY_SEPARATOR;

      default:
        throw new \InvalidArgumentException(sprintf('Directory of "%s" not set.', $name));
    }
  }
}
