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
	
	public function __construct(){

	}

	
	public function getTitle(){
		return $this->title;
	}
	
	public function setTitle($value){
		$this->title = $value;
	}
	
	/** @PHPCRODM\Locale*/
	protected $locale;
	

}
