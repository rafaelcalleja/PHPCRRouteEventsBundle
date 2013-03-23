<?php 
namespace RC\PHPCRRouteEventsBundle\Tests\Functional\Events;

use RC\PHPCRRouteEventsBundle\Events\RouteEvents;

use RC\PHPCRRouteEventsBundle\Events\EventDispatcher;
use RC\PHPCRRouteEventsBundle\Tests\Functional\BaseTestCase;
use RC\PHPCRRouteEventsBundle\Tests\Functional\Document\DocumentContentTest;
use RC\PHPCRRouteEventsBundle\Tests\Functional\Listener\RouteListenerTestEvent;


use Doctrine\ODM\PHPCR\Event\LifecycleEventArgs;

use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route;

class RouteDataEventTest extends BaseTestCase{
	
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
		self::$listener = new RouteListenerTestEvent();
		self::$dispatcher->addListener(RouteEvents::ROUTE_ADDED, array(self::$listener, 'onRouteAdded'));
	}
	
	protected static function  createRoute($name, $parent, $locale = 'es'){
		$route = new Route;
		$route->setPosition($parent, $name);
		$route->setDefault('_locale', $locale);
		return $route;
	}
	
	protected static function  createContent($name, $title){
		$content = new DocumentContentTest();
		$content->setNodename($name);
		$content->setParent(self::$root);
		$content->setTitle($title);
		
		return $content;
	}
	
		
	
	
	public function testGetLocale(){
		$child = self::createRoute('getlocale', self::$parent);
		self::$dm->persist($child);
		self::$dm->flush();
		
		$event = self::$listener->getEvent();
		$this->assertEquals('es', $event->getLocale());
	}
	
	
	public function testGetPath(){
		$child = self::createRoute('testpattern', self::$parent);
		self::$dm->persist($child);
		self::$dm->flush();
		
		$event = self::$listener->getEvent();
		$this->assertEquals('/testroute/testpattern', $event->getDocument()->getPattern());
	
	}
	
	public function testGetId(){
		
		$child = self::createRoute('testgetid', self::$parent);
		self::$dm->persist($child);
		self::$dm->flush();
		
		$event = self::$listener->getEvent();
		$this->assertEquals('/test/routing/testroute/testgetid', $event->getDocument()->getId());
		
	}
	
 	public function testGetLabel(){
 		$child = self::createRoute('testgetlabel', self::$parent);
 		
 		$content = $this->createContent('contentlabel', 'testing route');
 		$child->setRouteContent($content);
 		
 		self::$dm->persist($content);
 		self::$dm->persist($child);
 		self::$dm->flush();
 		
 		$event = self::$listener->getEvent();
 		
 		$this->assertEquals('testing route', $event->getLabel());
 	}
 	
 	public function testGetLabelOtherLang(){
 		
 		$child = self::createRoute('testgetlabelEN', self::$parent, 'en');
 			
 		$content = $this->createContent('contentlabelEN', '');
 		self::$dm->persist($content);
 		
 		$content->setTitle('testing route english');
 		self::$dm->bindTranslation($content, 'en');
 		self::$dm->persist($content);
 		
 		$content->setTitle('title spanish');
 		self::$dm->bindTranslation($content, 'es');
 		self::$dm->persist($content);
 		
 		$child->setRouteContent($content);
 		self::$dm->persist($child);
 		self::$dm->flush();
 			
 		$event = self::$listener->getEvent();
 			
 		$this->assertEquals('testing route english', $event->getLabel());
 		
 	}
	
	
	public function testGetDocument(){
		$child = self::createRoute('testgetdoc', self::$parent);
		self::$dm->persist($child);
		self::$dm->flush();
		
		$event = self::$listener->getEvent();
		$hash = spl_object_hash($event->getDocument());
		$this->assertEquals(spl_object_hash($child), $hash);
	}
	
	
	
}



