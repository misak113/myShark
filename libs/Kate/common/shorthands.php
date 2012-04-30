<?php

/**
 * Třída pro naloadování tohoto souboru :)
 */
class shorthands {};

/**
 * Zkrácená fce pro překládání
 * Translator::get()->translate();
 * @param string $message vstupní text
 * @return string přeložený text
 */
function _t($message) {
    return Kate\Helper\Translator::get()->translate($message);
}

/**
 * Debugovací fce pro interaktivní debugování
 * Lepsi nez var_dump
 * @param mixed $message jakýkoli objekt k lognutí
 */
function _d($message) {
    Nette\Diagnostics\Debugger::barDump($message);
    Kate\Helper\LogService::realtimeDebug($message);
}

/** 
 * Returns an instance of __ for OO-style calls
 * 
 */
function __($item=null) {
  $__ = new Kate\External\__;
  if(func_num_args() > 0) $__->_wrapped = $item;
  return $__;
}

/**
 * Vrátí zda je aktuální uživatel oprávněn činit zvolenou akci
 * @param string $type typ
 * @param operace $operation operace
 * @return boolean je oprávněn?
 */
function isAllowed($type, $operation) {
    return \Kate\Main\Loader::get()->getUserModel()->getUser()->isAllowed($type, $operation);
}


function getHtmlIconHref($iconName, $alt, $action, $param = '', $namespace = 'general', $addClasses = false, $href = false) {
    return \Kate\Helper\ImagePrinter::get()->getHtmlIconHref($iconName, $alt, $action, $param, $namespace, $addClasses, $href);
}

function _window($name) {
    return \Kate\Main\Loader::getWindowTemplatePath($name);
}

function _control($type, $value, $options = false) {
	return Kate\Helper\FormHelper::control($type, $value, $options);
}
?>
