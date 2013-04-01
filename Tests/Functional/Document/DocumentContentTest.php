<?php 
namespace RC\PHPCRRouteEventsBundle\Tests\Functional\Document;

use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;


/**
 * @PHPCRODM\Document(translator="attribute" , referenceable=true)
 */

class DocumentContentTest extends Generic {

	/** @PHPCRODM\String(translated=true) */
	protected $title;
	
	/** @PHPCRODM\Locale*/
	protected $locale;
	
	public function __construct(){

	}

	
	public function getTitle(){
		return $this->title;
	}
	
	public function setTitle($value){
		$this->title = $value;
	}
	
	public function getLocale(){
		return $this->locale;
	}
	
	public function setLocale($locale){
		$this->locale = $locale;
	}
	

}
