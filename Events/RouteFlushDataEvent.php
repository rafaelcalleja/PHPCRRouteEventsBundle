<?php 
namespace RC\PHPCRRouteEventsBundle\Events;

use Doctrine\ODM\PHPCR\Event\MoveEventArgs;
use Symfony\Component\EventDispatcher\Event;
use Doctrine\ODM\PHPCR\Event\PostFlushEventArgs;

class RouteFlushDataEvent extends Event
{
	protected $dm;
	protected $removed;
	protected $updated;
	protected $new;
	protected $uow;
	

	public function __construct(PostFlushEventArgs $event){
		$this->dm = $event->getDocumentManager();
		$this->uow = $this->dm->getUnitOfWork();
		
		$this->removed = $this->setRemoved();
		$this->updated = $this->setUpdated();
		$this->new = $this->setNew();
	}
	
	

	public function setRemoved(){
		   $removes = $this->uow->getScheduledRemovals();
		   var_dump(count($removes));
		   $this->removed = array();
		   foreach ($removes as $document) {
		   		var_dump('llego', get_class($removes));
   	            if($document instanceof \Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route ){
   	            	$this->removed[] = $document;
   	            }
   	        }
		   

//         $scheduledInserts = $uow->getScheduledInserts();
//         $scheduledUpdates = $uow->getScheduledUpdates();
//         $updates = array_merge($scheduledInserts, $scheduledUpdates);

//         foreach ($updates as $document) {
//             if ($this->getArm()->isAutoRouteable($document)) {
//                 $route = $this->getArm()->updateAutoRouteForDocument($document);
//                 $uow->computeSingleDocumentChangeSet($route);
//             }
//         }

//         $removes = $uow->getScheduledRemovals();

//         foreach ($removes as $document) {
//             if ($this->getArm()->isAutoRouteable($document)) {
//                 $routes = $this->getArm()->fetchAutoRoutesForDocument($document);
//                 foreach ($routes as $route) {
//                     $uow->scheduleRemove($route);
//                 }
//             }
//         }
	}
	
	public function setUpdated(){
		
	}

	public function setNew(){
		
	}
	
	
	public function getRemoved(){
		return $this->removed;
	}
	
	public function getUpdated(){
		
	}
	
	public function getNew(){
		
	}
}