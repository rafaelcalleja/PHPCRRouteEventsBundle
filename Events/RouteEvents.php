<?php
namespace RC\PHPCRRouteEventsBundle\Events;

final class RouteEvents{
	/**
	 * El evento «rc.route.added» es lanzado cada vez que se crea una ruta phpcr
	 * en el sistema.
	 *
	 * El escucha del evento recibe una
	 * instancia de RC\PHPCRRouteEventsBundle\Events\RouteDataEvent.
	 *
	 * @var string
	 */
	const ROUTE_ADDED = 'rc.route.added';
	
	/**
	 * El evento «rc.route.edited» es lanzado cada vez que se edita una ruta phpcr
	 * en el sistema.
	 *
	 * El escucha del evento recibe una
	 * instancia de RC\PHPCRRouteEventsBundle\Events\RouteDataEvent.
	 *
	 * @var string
	 */
	const ROUTE_EDITED = 'rc.route.edited';
	
	/**
	 * El evento «rc.route.edited» es lanzado cada vez que se inicia la edicion de una ruta phpcr
	 * en el sistema.
	 *
	 * El escucha del evento recibe una
	 * instancia de RC\PHPCRRouteEventsBundle\Events\RouteDataEvent.
	 *
	 * @var string
	 */
	const ROUTE_PRE_EDITED = 'rc.route.pre.edited';
	
	/**
	 * El evento «rc.route.edited» es lanzado cada vez que se inicia la edicion de una ruta phpcr
	 * en el sistema.
	 *
	 * El escucha del evento recibe una
	 * instancia de RC\PHPCRRouteEventsBundle\Events\RouteDataEvent.
	 *
	 * @var string
	 */
	const ROUTE_POST_MOVE = 'rc.route.post.move';
	
	/**
	 * El evento «rc.route.edited» es lanzado cada vez que se inicia la edicion de una ruta phpcr
	 * en el sistema.
	 *
	 * El escucha del evento recibe una
	 * instancia de RC\PHPCRRouteEventsBundle\Events\RouteDataEvent.
	 *
	 * @var string
	 */
	const ROUTE_PRE_MOVE = 'rc.route.pre.move';
	
	/**
	 * El evento «rc.route.post.remove» es lanzado cada vez que se ha eliminado una ruta phpcr
	 * en el sistema.
	 *
	 * El escucha del evento recibe una
	 * instancia de RC\PHPCRRouteEventsBundle\Events\RouteFlushDataEvent.
	 *
	 * @var string
	 */
	const ROUTE_POST_REMOVE = 'rc.route.post.remove';
}