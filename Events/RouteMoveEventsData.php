<?php 
namespace RC\PHPCRRouteEventsBundle\Events;

use Doctrine\ODM\PHPCR\Event\MoveEventArgs;
use RC\PHPCRRouteEventsBundle\Events\RouteDataEvent;

class RouteMoveEventsData extends RouteDataEvent
{
	protected $source , $dest = false;
	

	public function __construct(MoveEventArgs $event){
		parent::__construct($event);
		$this->source = $event->getSourcePath();
		$this->dest = $event->getTargetPath();
	}
	
	

	public function getSource(){
		return $this->source;
	}
	
	public function getDest(){
		return $this->dest;
	}
}