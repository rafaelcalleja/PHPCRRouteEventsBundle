<?php 
namespace RC\PHPCRRouteEventsBundle\Events;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\ODM\PHPCR\Event\LifecycleEventArgs;

class RouteDataEvent extends Event
{
	protected $document, $dm, $clone, $uow;
	

	public function __construct($event){
		$this->document =  $event->getDocument();
		$this->dm = $event->getDocumentManager();
		$this->uow = $this->dm->getUnitOfWork();
		$this->clone = $this->dm->create($this->dm->getPhpcrSession(), $this->dm->getConfiguration(), $this->dm->getEventManager());
		$this->clone->setLocaleChooserStrategy($this->dm->getLocaleChooserStrategy());
	}
	
	public function cloneDocumentManager(){
		return $this->clone;
	}
	
	public function getDocumentManager(){
		return $this->dm;
	}
	
	public function getLocale(){
		return $this->document->getDefault('_locale');
	}
	
	public function getPath(){
		return $this->document->getPattern();
		
	}
	
	public function getId(){
		return $this->document->getId();
	}
	
	public function getLabel(){
		if((method_exists($this->document->getRouteContent(), 'getId'))){
			$translation = $this->dm->findTranslation(get_class($this->document->getRouteContent()), $this->document->getRouteContent()->getId(), $this->getLocale());
			if(method_exists($translation, 'getTitle')) {
				return $translation->getTitle();
			}
		}
		
		return false;
	}
	
	public function persist($document){
		$this->dm->persist($document);
		$this->uow->computeSingleDocumentChangeSet($document);
		
	}
	
	public function flush($document){
		//$this->uow->computeChangeSets();
		//$this->uow->commit($document);
		$this->uow->initializeObject($document);
		
		//$this->uow->computeChangeSet($this->dm->getClassMetadata($document), $document);
		$this->persist($document);
		//$this->uow->refresh($document);
	}
	
	
	public function getDocument(){
		return $this->document;
	}
}