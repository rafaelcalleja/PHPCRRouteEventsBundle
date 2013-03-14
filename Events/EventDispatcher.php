<?php
namespace RC\PHPCRRouteEventsBundle\Events;

use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ODM\PHPCR\Event\LifecycleEventArgs;
use Doctrine\ODM\PHPCR\Event\MoveEventArgs;

use RC\PHPCRRouteEventsBundle\Events\RouteDataEvent;
use RC\PHPCRRouteEventsBundle\Events\RouteMoveEventsData;
use RC\PHPCRRouteEventsBundle\Events\RouteEvents;
use RC\PHPCRRouteEventsBundle\Events\RouteFlushDataEvent;
use Doctrine\ODM\PHPCR\Event\PostFlushEventArgs;
use Doctrine\ODM\PHPCR\Event\OnFlushEventArgs;
use Doctrine\ODM\PHPCR\Event\PreFlushEventArgs;

class EventDispatcher
{
	private $dm;
	private $dispatcher;
	
	function __construct(DocumentManager $dm, $dispatcher){
		$this->dm =$dm;
		$this->dispatcher = $dispatcher;
	}
	

	public function postPersist(LifecycleEventArgs $event){
		$document = $event->getDocument(); 
		
		if( $document instanceof \Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route){
			$newevent = new RouteDataEvent($event);
			$this->dispatcher->dispatch(RouteEvents::ROUTE_ADDED, $newevent);
		}
	}
	
	public function postMove(MoveEventArgs $event){
		
		$document = $event->getDocument();
		
		if( $document instanceof \Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route){
			$newevent = new RouteMoveEventsData($event);
			$this->dispatcher->dispatch(RouteEvents::ROUTE_POST_MOVE, $newevent);
		}
	}	
	
	public function preUpdate(LifecycleEventArgs $event){
		$document = $event->getDocument();
		
		if( $document instanceof \Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route){
			$newevent = new RouteDataEvent($event);
			$this->dispatcher->dispatch(RouteEvents::ROUTE_PRE_EDITED, $newevent);
		}
	}
	
	public function postUpdate(LifecycleEventArgs $event){
		$document = $event->getDocument();
		if( $document instanceof \Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route){
			$newevent = new RouteDataEvent($event);
			$this->dispatcher->dispatch(RouteEvents::ROUTE_EDITED, $newevent);
		}
	
	}
	
	public function postFlush(PostFlushEventArgs $event){
// 		$newevent = new RouteFlushDataEvent($event);
// 		$this->dispatcher->dispatch(RouteEvents::ROUTE_POST_REMOVE, $newevent);
			
	}
	
	public function postRemove(LifecycleEventArgs $event){
// 		$document = $event->getDocument();
// 		if( $document instanceof \Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route){
// 			$event->getDocumentManager()->initializeObject($document);
// 			$newevent = new RouteDataEvent($event, $document);
// 			$this->dispatcher->dispatch(RouteEvents::ROUTE_POST_REMOVE, $newevent);
// 		}
			
	}
	
	public function onFlush(OnFlushEventArgs $event){
		$newevent = new RouteFlushDataEvent($event);
		$this->dispatcher->dispatch(RouteEvents::ROUTE_POST_REMOVE, $newevent);		
	}
	
	
}