<?php 
namespace RC\PHPCRRouteEventsBundle\Tests\Functional\Events;

use RC\PHPCRRouteEventsBundle\Tests\Functional\BaseTestCase;
use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route;

class EventDispatcherTest extends BaseTestCase{
	
	const ROUTE_ROOT = '/test/routing';
	
	protected function createRoute(){
		
		$route = new Route;
		$root = $this->getDm()->find(null, $this->ROUTE_ROOT);
		
		$route->setRouteContent($root); 
		$route->setPosition($root, 'testroute');
		$route->setDefault('x', 'y');
		$route->setRequirement('testreq', 'testregex');
		$route->setOptions(array('test' => 'value'));
		$route->setOption('another', 'value2');
		
		
		

	}
	
	protected function persistRoute($route){
		
		$this->getDm()->persist($route);
		$this->getDm()->flush();
		
		$this->assertEquals('/testroute', $route->getPattern());
		
	}
	
	public function testRouteAdded(){

		$route = $this->persistRoute($this->createRoute());
		
	} 
	
}