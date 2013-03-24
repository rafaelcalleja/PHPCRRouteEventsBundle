<?php 
namespace RC\PHPCRRouteEventsBundle\Tests\Functional\Events;

use RC\PHPCRRouteEventsBundle\Events\RouteEvents;

use RC\PHPCRRouteEventsBundle\Tests\Functional\BaseTestCase;
use RC\PHPCRRouteEventsBundle\Tests\Functional\Listener\RouteListenerTestEvent;


use Doctrine\ODM\PHPCR\Event\LifecycleEventArgs;

use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route;

class RouteFlushDataEventTest extends BaseTestCase{
	
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
		self::$listener = new RouteListenerTestEvent();
		self::$dispatcher->addListener(RouteEvents::ROUTE_ADDED, array(self::$listener, 'onRouteAdded'));
		self::$dispatcher->addListener(RouteEvents::ROUTE_POST_MOVE, array(self::$listener, 'onRouteMoved'));
	}
	
	
	public function testSource(){
		$child = self::createRoute('imsource', self::$parent);
		self::$dm->persist($child);
		self::$dm->flush();
			
		self::$dm->move($child, '/test/routing/imdest');
		self::$dm->flush();
		
		
		$event = self::$listener->getEvent();
		$this->assertEquals('/test/routing/testroute/imsource', $event->getSource());
	}
	
	
	public function testDest(){
		$child = self::createRoute('imsource2', self::$parent);
		self::$dm->persist($child);
		self::$dm->flush();
			
		self::$dm->move($child, '/test/routing/imdest2');
		self::$dm->flush();
		
		
		$event = self::$listener->getEvent();
		$this->assertEquals('/test/routing/imdest2', $event->getDest());
	
	}
	
	
	
	
}
	
	
	
	
	
