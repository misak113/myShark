<?php

namespace Kate\KinqModules\Hook;
use Nette
,	SplObjectStorage
,	Nette\Callback
,	Nette\InvalidStateException
,	Nette\InvalidArgumentException
,	kinq;


class HookContainer extends Nette\Object implements \Kate\KinqModules\Hook\IHookContainer
{
	private $registry = array();
	
	/** @var Nette\DI\IContainer */
	private $context;
	
	private $presenter;

	protected $modules = array();

	protected $options = array(
		'binder' => null,
		'args' => null
	);

	public function __construct(Nette\DI\IContainer $context, array $modules)
	{
	    $this->context = $context;
		$this->loadModules($modules);
	}

	protected function loadModules($modules) {
	    foreach ($modules as $moduleName) {
		if (!preg_match('~Module$~', $moduleName)) {
		    $moduleName = $moduleName.'Module';
		}
		try {
		    $module = new $moduleName($this);
		    $module->setupHooks();
		    $module->extendRouter($this->context->application->getRouter());
		    $this->modules[] = $module;
		} catch (Exception $e) {
		    _d('Module "'.$moduleName.'" doesn\'t exists.');
		}
	    }
	}


	public function bind($event, $callback, $args = array()) {
		if (!isset($this->registry[$event])) $this->registry[$event] = self::buildStorage();

		if (is_string($callback)) $callback = new Callback($callback);

		if(get_class($callback) !== 'Nette\Callback') throw new InvalidArgumentException("Callback '$callback' is not Nette\callback or string");

		if (!$callback->isCallable()) throw new InvalidStateException("Callback '$callback' is not callable.");

		$options = array(
			'caller' => null,
			'args' => $args
		);
		list(, $options['caller']) = debug_backtrace(false);
		$this->registry[$event]->attach($callback, $options);
	}



	/**
	 *
	 * @return void
	 */
	public function unbind($object, $event = null) {

		//unbind from one event
		if ($event && isset($this->registry[$event])) return $this->registry[$event]->detach($object);

		//unbind from all events
		foreach ($this->registry as $event) {
			$event->detach($object);
		}
	}
	
	
	
	public function notify($event, array $options = array()) {
		//list(, $caller) = debug_backtrace(false);

		if (!isset($this->registry[$event])) return new SplObjectStorage();
		foreach ($this->registry[$event] as $object) {
		    call_user_func_array($object, $options);
		}
	}


	protected static function buildStorage() {
		return new SplObjectStorage();
	}
}