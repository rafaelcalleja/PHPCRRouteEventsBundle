<?php 
namespace RC\PHPCRRouteEventsBundle\Tests\Functional\Listener;

use RC\PHPCRRouteEventsBundle\Events\RouteEvents;
use RC\PHPCRRouteEventsBundle\Events\RouteDataEvent;
use RC\PHPCRRouteEventsBundle\Events\RouteMoveEventsData;
use RC\PHPCRRouteEventsBundle\Events\RouteFlushDataEvent;

class RouteListenerTestEvent  {

	protected $last_event;
	public function __construct(){

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


}