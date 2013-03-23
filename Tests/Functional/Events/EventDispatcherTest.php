<?php 
namespace RC\PHPCRRouteEventsBundle\Tests\Functional\Events;

use RC\PHPCRRouteEventsBundle\Events\RouteEvents;
use RC\PHPCRRouteEventsBundle\Events\RouteDataEvent;
use RC\PHPCRRouteEventsBundle\Events\RouteMoveEventsData;
use RC\PHPCRRouteEventsBundle\Events\RouteFlushDataEvent;

use RC\PHPCRRouteEventsBundle\Events\EventDispatcher;
use RC\PHPCRRouteEventsBundle\Tests\Functional\BaseTestCase;

use Doctrine\ODM\PHPCR\Event\LifecycleEventArgs;

use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route;

class EventDispatcherTest extends BaseTestCase{
	
	protected static $root;
	protected static $parent;
	protected static $listener;
	
	const ROUTE_ROOT = '/test/routing';
	
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
		if (!class_exists('Symfony\Component\EventDispatcher\EventDispatcher')) {
			
		}
	
		
		$listener = $this->getMock('RC\PHPCRRouteEventsBundle\Tests\Functional\Events\RouteListener');
		
		self::$dispatcher->addListener(RouteEvents::ROUTE_ADDED, array($listener, 'onRouteAdded'));
		self::$dispatcher->addListener(RouteEvents::ROUTE_POST_MOVE, array($listener, 'onRouteMoved'));
		self::$dispatcher->addListener(RouteEvents::ROUTE_POST_REMOVE, array($listener, 'onRouteRemoved'));
		
		//Disabled events
		self::$dispatcher->addListener(RouteEvents::ROUTE_PRE_EDITED, array($listener, 'onRoutePreEdited'));
		self::$dispatcher->addListener(RouteEvents::ROUTE_EDITED, array($listener, 'onRouteEdited'));
		self::$dispatcher->addListener(RouteEvents::ROUTE_PRE_MOVE, array($listener, 'onRoutePreMoved'));
		
		
		self::$listener = $listener;
	}
	
	protected static function  createRoute($name, $parent){
		$route = new Route;
		$route->setPosition($parent, $name);
		$route->setDefault('id', '0');
		$route->setRequirement('id', '[0-9]+');
		return $route;
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

class RouteListener {

	public function __construct(){

	}


	public function onRouteAdded(RouteDataEvent $event){

	}


	public function onRouteMoved(RouteMoveEventsData $event){

	}

	public function onRouteRemoved(RouteFlushDataEvent $event){

	}

	public function onRoutePreEdited(RouteDataEvent $event){

	}

	public function onRouteEdited(RouteDataEvent $event){

	}


}