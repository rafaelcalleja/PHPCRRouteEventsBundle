<?php 
namespace RC\PHPCRRouteEventsBundle\Tests\Functional\Events;

use RC\PHPCRRouteEventsBundle\Events\RouteEvents;

use RC\PHPCRRouteEventsBundle\Tests\Functional\BaseTestCase;
use RC\PHPCRRouteEventsBundle\Tests\Functional\Listener\RouteListenerTestEvent;


use Doctrine\ODM\PHPCR\Event\LifecycleEventArgs;

use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route;

class EventComputingTest extends BaseTestCase{
	
	
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
		self::$listener = new RouteListenerComputingEventTest();
		self::$dispatcher->addListener(RouteEvents::ROUTE_ADDED, array(self::$listener, 'onRouteAdded'));
		self::$dispatcher->addListener(RouteEvents::ROUTE_ADDED, array(self::$listener, 'onRouteAddedDM'));
		self::$dispatcher->addListener(RouteEvents::ROUTE_POST_MOVE, array(self::$listener, 'onRouteMoved'));
		self::$dispatcher->addListener(RouteEvents::ROUTE_POST_MOVE, array(self::$listener, 'onRouteMoved2'));
		self::$dispatcher->addListener(RouteEvents::ROUTE_POST_REMOVE, array(self::$listener, 'onRouteRemoved'));
	}
	
	
	public function testUsingDMDuringChangesAdded(){
		
		self::$listener->setContinue(true);
		self::$listener->setDm(self::$dm);
		self::$listener->setContent($this->createContent('contentlabelEN', ''));
		
		
		$counter = 0;
		while(self::$listener->getContinue()){
			$child = self::createRoute('compute'.$counter, self::$parent);
			self::$dm->persist($child);
			self::$dm->flush();
			$counter++;
		}
			
		$event = self::$listener->getEvent();
		$this->assertEquals(self::$dm->find(null, '/test/routing/contentlabelEN')->getTitle(), 'testcomputing');
		
			
		$event = self::$listener->getEvent();
		$this->assertEquals(self::$dm->find(null, '/test/routing/contentlabelEN2')->getTitle(), 'testcomputing2');
		
	}
	
	public function testUsingDMDuringChangesMoved(){
	
		self::$listener->setContinue(true);
		self::$listener->setDm(self::$dm);
		self::$listener->setContent($this->createContent('contentlabelMovingEN', ''));
	
	
		$counter = 0;
		while(self::$listener->getContinue()){
			$child = self::createRoute('computeOnMove'.$counter, self::$parent);
			self::$dm->persist($child);
			self::$dm->flush($child);
			
			self::$dm->move($child, '/test/routing/moved'.$counter);
			self::$dm->flush($child);
			
			$counter++;
		}
		
		$event = self::$listener->getEvent();
		$this->assertEquals(self::$dm->find(null, '/test/routing/contentlabelMovingEN')->getTitle(), 'testMoving1Listener');
	
			
 		$event = self::$listener->getEvent();
 		$this->assertEquals(self::$dm->find(null, '/test/routing/contentlabelMovingEN2')->getTitle(), 'testMoving2Listener');
	
	}
	
}

use RC\PHPCRRouteEventsBundle\Events\RouteDataEvent;
use RC\PHPCRRouteEventsBundle\Events\RouteMoveEventsData;
use RC\PHPCRRouteEventsBundle\Events\RouteFlushDataEvent;

class RouteListenerComputingEventTest  {

	protected $last_event;
	protected $continue;
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
		
		$content = clone $this->content; 
		$content->setTitle('testcomputing');
		$event->persist($content);
		$event->flush($content);
		$this->continue = false;
		$this->last_event =  $event;
	}
	
	public function onRouteAddedDM(RouteDataEvent $event){
	
 		//$this->dm = $event->cloneDocumentManager();

 			
 		$content = clone $this->content;
 		$content->setTitle('testcomputing');
 		
		//fix no managed documents for the cloned manager
		//not work for multiples listeners
 		//$root = $this->dm->find(null, $content->getParent()->getId());
 		//$content->setParent($root);
 		$content->setNodename('contentlabelEN2');
		$content->setTitle('testcomputing2');
		

		$event->persist($content);
		$event->flush($content);
		
 		//$this->dm->persist($this->content);
 		//$this->dm->flush();
		$this->continue = false;
		$this->last_event =  $event;
	}


	public function onRouteMoved(RouteMoveEventsData $event){
		$content = clone $this->content;
		$content->setTitle('testMoving1Listener');
			
			
		$event->persist($content);
		$event->flush($content);
		$this->continue = false;
		
		$this->last_event =  $event;
	}
	
	public function onRouteMoved2(RouteMoveEventsData $event){
		$content = clone $this->content;
		$content->setTitle('testMoving2Listener');
		$content->setNodename('contentlabelMovingEN2');
			
			
		$event->persist($content);
		$event->flush($content);
		$this->continue = false;
	
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





