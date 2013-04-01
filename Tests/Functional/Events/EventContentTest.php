<?php 
namespace RC\PHPCRRouteEventsBundle\Tests\Functional\Events;

use RC\PHPCRRouteEventsBundle\Events\RouteEvents;

use RC\PHPCRRouteEventsBundle\Tests\Functional\BaseTestCase;
use RC\PHPCRRouteEventsBundle\Tests\Functional\Listener\RouteListenerTestEvent;


use Doctrine\ODM\PHPCR\Event\LifecycleEventArgs;

use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route;

class EventContentTest extends BaseTestCase{
	
	
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
		self::$listener = new RouteListenerComputingEventTest2();
		self::$dispatcher->addListener(RouteEvents::ROUTE_ADDED, array(self::$listener, 'onRouteAdded'));
		self::$dispatcher->addListener(RouteEvents::ROUTE_POST_MOVE, array(self::$listener, 'onRouteMoved'));
		self::$dispatcher->addListener(RouteEvents::ROUTE_POST_REMOVE, array(self::$listener, 'onRouteRemoved'));
	}
	
	
	public function testMultiLangContent(){
		
		$child = self::createRoute('multilang', self::$parent);
		$content = self::createContent('multilangEs', 'multilangTitleEs');
		self::$dm->persist($content);
		
		self::$dm->bindTranslation($content, 'es');
		self::$dm->persist($content);
		
		$content->setTitle('multilangTitleEn');
		
		self::$dm->bindTranslation($content, 'en');
		self::$dm->persist($content);
		
		$child->setRouteContent($content);
		self::$dm->persist($child);
		
		self::$dm->flush();
		
		$spanish = self::$dm->findTranslation(get_class($content), $content->getId(), 'es')->getTitle();
		$english = self::$dm->findTranslation(get_class($content), $content->getId(), 'en')->getTitle();
		
		$this->assertEquals($spanish, 'multilangTitleEs');
		$this->assertEquals($english, 'multilangTitleEn');
		
		
	}
	
	public function testCurrPosition(){
		$child = self::createRoute('position1', self::$parent);
		self::$dm->persist($child);
		self::$dm->flush();
		
		$event = self::$listener->getEvent();
		$this->assertEquals($event->getPosition(), 1);
		
		$child2 = self::createRoute('position2', self::$parent);
		self::$dm->persist($child2);
		self::$dm->flush();
		
		$event = self::$listener->getEvent();
		$this->assertEquals($event->getPosition(), 2);
		
		
	}
	
		
	
	public function testLabelLangonMove(){
		$child = self::$dm->find(null, '/test/routing/testroute/multilang');
		
		self::$dm->move($child, '/test/routing/testroute/multilangdest');
		self::$dm->flush();
		
		$event = self::$listener->getEvent();
		$this->assertEquals($event->getLabel(), 'multilangTitleEs');
		
		$child->setDefault('_locale', 'en');
		$this->assertEquals($event->getLabel(), 'multilangTitleEn');
		
		
	}
	
	
}

use RC\PHPCRRouteEventsBundle\Events\RouteDataEvent;
use RC\PHPCRRouteEventsBundle\Events\RouteMoveEventsData;
use RC\PHPCRRouteEventsBundle\Events\RouteFlushDataEvent;

class RouteListenerComputingEventTest2  {

	protected $last_event;
	protected $dm, $content;
	
	public function __construct(){

	}

	public function setDm($value){
		$this->dm = $value;
	}
	
	public function setContent($value){
		$this->content = $value;
	}

	public function onRouteAdded(RouteDataEvent $event){

		$this->last_event =  $event;
	}
	
	public function onRouteMoved(RouteMoveEventsData $event){
		$this->last_event =  $event;
	}
	
	public function onRouteRemoved(RouteFlushDataEvent $event){
		
		$this->last_event =  $event;
	}
	
	public function onRoutePreEdited(RouteDataEvent $event){
		$this->last_event =  $event;
	}

	public function onRouteEdited(RouteDataEvent $event){
		$this->last_event =  $event;
	}

	public function getEvent(){
		return $this->last_event;
	}

	public function setContinue($value){
		$this->continue = $value;
	}
	
	public function getContinue(){
		return $this->continue;
	}

}





