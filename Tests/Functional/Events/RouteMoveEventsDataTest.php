<?php 
namespace RC\PHPCRRouteEventsBundle\Tests\Functional\Events;

use RC\PHPCRRouteEventsBundle\Events\RouteEvents;

use RC\PHPCRRouteEventsBundle\Tests\Functional\BaseTestCase;
use RC\PHPCRRouteEventsBundle\Tests\Functional\Listener\RouteListenerTestEvent;


use Doctrine\ODM\PHPCR\Event\LifecycleEventArgs;

use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route;

class RouteMoveEventsDataTest extends BaseTestCase{
	
	
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
		self::$dispatcher->addListener(RouteEvents::ROUTE_POST_REMOVE, array(self::$listener, 'onRouteRemoved'));
	}
	
	
	public function testTotalGetRemoved(){
		$child = self::createRoute('delete1', self::$parent);
		self::$dm->persist($child);
		self::$dm->flush();
			
		self::$dm->remove($child);
		self::$dm->flush();
		
		$event = self::$listener->getEvent();
		$total = count($event->getRemoved());
		
		$this->assertEquals(1, $total);
		
		$toremove = array();
		for($x=2;$x<=6;$x++){
			$child = self::createRoute('delete'.$x, self::$parent);
			self::$dm->persist($child);
			$toremove[] = $child;
		}
		self::$dm->flush();
		
		foreach($toremove as $d){
			self::$dm->remove($d);
		}
		
		self::$dm->flush();
		$event = self::$listener->getEvent();
		
		$total = count($event->getRemoved());
		$this->assertEquals(count($toremove), $total);
		
	}
	
}





