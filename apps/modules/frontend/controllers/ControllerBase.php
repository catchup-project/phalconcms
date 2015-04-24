<?php
namespace Multiple\Frontend\Controllers;
use Phalcon\Mvc\Controller;
class ControllerBase extends Controller{
	/**
	 * @param $uri
	 * @return mixed
	 */
	protected function forward($uri)
	{
		$uriParts = explode('/', $uri);
		return $this->dispatcher->forward(
				array(
						'module'=>'frontend',
						'controller' => $uriParts[0],
						'action' => $uriParts[1]
				)
		);
	}
}