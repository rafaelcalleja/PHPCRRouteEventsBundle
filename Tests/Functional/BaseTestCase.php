<?php 
namespace RC\PHPCRRouteEventsBundle\Tests\Functional;

require __DIR__.'/app/AppKernel.php';
use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use RC\PHPCRRouteEventsBundle\Tests\Functional\AppKernel;
use RC\PHPCRRouteEventsBundle\Tests\Functional\Document\DocumentContentTest;

class BaseTestCase extends WebTestCase
{
    /**
     * @var \Doctrine\ODM\PHPCR\DocumentManager
     */
    protected static $dm;
    protected static $kernel;
    protected static $dispatcher;
    
    protected static $root;
    protected static $parent;
    protected static $listener;
    
    const ROUTE_ROOT = '/test/routing';

    protected static function createKernel(array $options = array())
    {
        return new AppKernel(
            isset($options['config']) ? $options['config'] : 'default.yml'
        );
    }

    /**
     * careful: the kernel is shut down after the first test, if you need the
     * kernel, recreate it.
     *
     * @param array  $options   passed to self:.createKernel
     * @param string $routebase base name for routes under /test to use
     */
    public static function setupBeforeClass(array $options = array(), $routebase = null)
    {
        self::$kernel = self::createKernel($options);
        self::$kernel->init();
        self::$kernel->boot();

        self::$dm = self::$kernel->getContainer()->get('doctrine_phpcr.odm.document_manager');
        self::$dispatcher = self::$kernel->getContainer()->get('event_dispatcher');
        
        if (null == $routebase) {
            return;
        }

        $session = self::$kernel->getContainer()->get('doctrine_phpcr.session');
        if ($session->nodeExists("/test/$routebase")) {
            $session->getNode("/test/$routebase")->remove();
        }
        if (! $session->nodeExists('/test')) {
            $session->getRootNode()->addNode('test', 'nt:unstructured');
        }
        $session->save();

        $root = self::$dm->find(null, '/test');
        $route = new Route;
        $route->setPosition($root, $routebase);
        self::$dm->persist($route);
        self::$dm->flush();
    }
    
    protected static function  createRoute($name, $parent, $locale = 'es'){
    	$route = new Route;
    	$route->setPosition($parent, $name);
    	$route->setDefault('_locale', $locale);
    	return $route;
    }
    
    protected static function  createContent($name, $title){
    	$content = new DocumentContentTest();
    	$content->setNodename($name);
    	$content->setParent(self::$root);
    	$content->setTitle($title);
    	return $content;
    }
    
}