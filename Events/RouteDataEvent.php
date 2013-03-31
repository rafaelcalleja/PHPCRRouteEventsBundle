<?php 
namespace RC\PHPCRRouteEventsBundle\Events;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\ODM\PHPCR\Event\LifecycleEventArgs;

class RouteDataEvent extends Event
{
	protected $document, $dm, $clone, $uow;
	protected $contentId = false;
	

	public function __construct($event){
		$this->document =  $event->getDocument();
		$this->dm = $event->getDocumentManager();
		$this->uow = $this->dm->getUnitOfWork();
		$this->clone = $this->dm->create($this->dm->getPhpcrSession(), $this->dm->getConfiguration(), $this->dm->getEventManager());
		$this->clone->setLocaleChooserStrategy($this->dm->getLocaleChooserStrategy());
		$this->setId($event);
	}
	
	protected function setId($event){
		if($this->document->getRouteContent()){
			$this->dm->refresh($this->document->getRouteContent());
			$class = $this->dm->getClassMetadata(get_class($this->document->getRouteContent()));
			$this->contentId = 'get'.ucfirst(current($class->getIdentifier())); 
		}
	}
	
	
	public function getPosition(){
		return array_search($this->document->getName(), $this->dm->getPhpcrSession()->getNode($this->document->getParent()->getId())->getNodeNames()->getArrayCopy() );
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
		
		if($this->contentId){
			$translation = $this->dm->findTranslation(get_class($this->document->getRouteContent()), $this->document->getRouteContent()->{$this->contentId}(), $this->getLocale());
			if(method_exists($translation, 'getTitle')) {
				return $translation->getTitle();
			}elseif(method_exists($translation, 'getName')) {
				return $translation->getName();
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