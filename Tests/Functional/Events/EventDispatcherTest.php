<?php 
namespace RC\PHPCRRouteEventsBundle\Tests\Functional\Events;

use RC\PHPCRRouteEventsBundle\Events\RouteEvents;
use RC\PHPCRRouteEventsBundle\Tests\Functional\BaseTestCase;

class EventDispatcherTest extends BaseTestCase{
	
	public static function setupBeforeClass(array $options = array(), $routebase = null){
		
		parent::setupBeforeClass(array(), basename(self::ROUTE_ROOT));
		
		$root = self::$dm->find(null, self::ROUTE_ROOT);
		self::$root = $root;
		
		$route = self::createRoute('testroute', $root);
		
		self::$parent = $route;
		

		self::$dm->persist($route);
	
		self::$dm->flush();
	}
	
	protected function setUp(){
		self::$listener = $this->getMock('RC\PHPCRRouteEventsBundle\Tests\Functional\Listener\RouteListenerTestEvent');
		
		self::$dispatcher->addListener(RouteEvents::ROUTE_ADDED, array(self::$listener, 'onRouteAdded'));
		self::$dispatcher->addListener(RouteEvents::ROUTE_POST_MOVE, array(self::$listener, 'onRouteMoved'));
		self::$dispatcher->addListener(RouteEvents::ROUTE_POST_REMOVE, array(self::$listener, 'onRouteRemoved'));
		
		//Disabled events
		self::$dispatcher->addListener(RouteEvents::ROUTE_PRE_EDITED, array(self::$listener, 'onRoutePreEdited'));
		self::$dispatcher->addListener(RouteEvents::ROUTE_EDITED, array(self::$listener, 'onRouteEdited'));
		self::$dispatcher->addListener(RouteEvents::ROUTE_PRE_MOVE, array(self::$listener, 'onRoutePreMoved'));
		
	}
	
	
	public function testRoutePath(){
		$root = self::$dm->find(null, self::ROUTE_ROOT);
		$this->assertEquals('/', $root->getPath());
		
		$root = self::$dm->find(null, self::ROUTE_ROOT);
		$this->assertEquals('/testroute', self::$parent->getPath());
		
	} 
	
	public function testChildRoute(){
		$child = self::createRoute('testchild', self::$parent);
		self::$dm->persist($child);
		self::$dm->flush();
		
		$this->assertEquals('/testroute/testchild', $child->getPath());
	}
	
	public function testDispatchRouteAdded(){
		self::$listener->expects($this->once())
		->method('onRouteAdded');
		
		$child = self::createRoute('onrouteadded', self::$parent);
		self::$dm->persist($child);
		self::$dm->flush();
		
	}
	
	public function testDispatchOnRouteMoved(){
		self::$listener->expects($this->once())
		->method('onRouteMoved');

		$tomove = self::$dm->find(null, '/test/routing/testroute/testchild');
		
		self::$dm->move($tomove, '/test/routing/moving');
		
		self::$dm->flush();
	
	}
	
	public function testDispatchOnRouteRemoved(){
		self::$listener->expects($this->once())
		->method('onRouteRemoved');
	
		$remove = self::$dm->find(null, '/test/routing/moving');
		self::$dm->remove($remove);
	
		self::$dm->flush();
	
	}
	
	
}
