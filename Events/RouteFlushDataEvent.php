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
	protected $document;
	

	public function __construct($event){
		$this->dm = $event->getDocumentManager();
		$this->uow = $this->dm->getUnitOfWork();
		
		$this->setRemoved();
// 		$this->updated = $this->setUpdated();
// 		$this->new = $this->setNew();
	}
	public function getDocumentManager(){
		return $this->dm;
	}
	
	public function setRemoved(){
		$removes = $this->uow->getScheduledRemovals();
		$this->removed = array();
		foreach ($removes as $document) {
			if($document instanceof \Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route ){
   	        	$this->removed[] = $document;
			}
		}
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
	
	public function insert($document){
		$this->uow->scheduleInsert($document);
		//$this->uow->computeSingleDocumentChangeSet($document);
	}
	
	public function persist($document){
		$this->dm->persist($document);
		$this->uow->computeSingleDocumentChangeSet($document);
	}
	
	public function flush($document){
		$this->uow->initializeObject($document);
		$this->persist($document);
	}
}