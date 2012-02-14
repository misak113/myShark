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
    Kate\Helper\LogService::realtimeDebug($message);
}

?>
