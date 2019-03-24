<?php
namespace Modules\Admin\Config;

use Phalcon\Mvc\Router\Group;

class AdminRouters extends Group
{
	public function __construct($lang = 'ru')
    {
		$this->setPaths([
			'module' => 'admin',
			'namespace' => 'Modules\Admin\Controllers'
		]);

		$this->add('/', ['controller' => 'index', 'action' => 'index', 'lang' => $lang]);
		$this->add('/:controller', ['controller' => 1, 'action' => 'index', 'lang' => $lang]);
		$this->add('/:controller/:action', ['controller' => 1, 'action' => 2, 'lang' => $lang]);
		$this->add('/:controller/:action/:params', ['controller' => 1, 'action' => 2, 'params' => 3, 'lang' => $lang]);
	}
}