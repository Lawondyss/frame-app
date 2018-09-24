<?php
/**
 * @author Ladislav Vondráček <lad.von@gmail.com>
 */

namespace App\Module\Front;

class HomePresenter extends BasePresenter
{
  /** @var HomeFacade @inject */
  public $facade;


  protected function beforeRender()
  {
    $this->template->title = 'Homepage';
  }
}
