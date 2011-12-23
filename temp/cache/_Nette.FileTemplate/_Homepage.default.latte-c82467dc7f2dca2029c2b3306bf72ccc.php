<?php //netteCache[01]000429a:2:{s:4:"time";s:21:"0.67861800 1323890675";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:106:"C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark\templates\Homepage\default.latte";i:2;i:1323890671;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"f38d86f released on 2011-08-24";}}}?><?php

// source file: C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark\templates\Homepage\default.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'wofo9v83rr')
;//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lbf965ab1795_content')) { function _lbf965ab1795_content($_l, $_args) { extract($_args)
?>
<div class="wrapper-page link--<?php echo htmlSpecialChars($page['page']['link']) ?>">
<div id="<?php echo $control->getSnippetId('header') ?>"><?php call_user_func(reset($_l->blocks['_header']), $_l, $template->getParams()) ?>
</div>    
    
<div id="<?php echo $control->getSnippetId('page') ?>"><?php call_user_func(reset($_l->blocks['_page']), $_l, $template->getParams()) ?>
</div>    
</div>
<?php
}}

//
// block _header
//
if (!function_exists($_l->blocks['_header'][] = '_lb70dd65b4a9__header')) { function _lb70dd65b4a9__header($_l, $_args) { extract($_args); $control->validateControl('header')
;if (count($page['languages']) > 1): ?>
    <div class="languages">
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($page['languages']) as $lang): ?>
        <a href="<?php echo htmlSpecialChars($lang['actualPath']) ?>">
                        <?php echo Nette\Templating\DefaultHelpers::escapeHtml($lang['imgHtml'], ENT_NOQUOTES) ?>

        </a>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
    </div>
<?php endif ?>
	
<?php if ($page['loginForm']): Nette\Latte\Macros\CoreMacros::includeTemplate(''.(\Kate\Main\Loader::WINDOWS_DIR).S.'login.latte', get_defined_vars(), $_l->templates['wofo9v83rr'])->render() ;Nette\Latte\Macros\UIMacros::callBlock($_l, 'loginWindow', array('loginForm' => $page['loginForm']) + $template->getParams()) ;endif ?>
	
<?php
}}

//
// block _page
//
if (!function_exists($_l->blocks['_page'][] = '_lb58e7b9cf84__page')) { function _lb58e7b9cf84__page($_l, $_args) { extract($_args); $control->validateControl('page')
?>
    <section id="page-<?php echo htmlSpecialChars($page['page']['id_page']) ?>" class="page">
        <div class="brace-page">
<?php if ($page['cells']): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($page['cells']) as $row): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($row) as $cell): ?>
            <div class="wrapper-cell">
<div id="<?php echo $_dynSnippetId = $control->getSnippetId('cell-'.$cell['id_cell']) ?>
"><?php ob_start() ?>
                <section id="cell-<?php echo htmlSpecialChars($cell['id_cell']) ?>" class="cell">
                    <div class="brace-cell">
                        <div class="wrapper-slot link--<?php echo htmlSpecialChars($cell['slot']['link']) ?>">
                            <section id="slot-<?php echo htmlSpecialChars($cell['slot']['id_slot']) ?>" class="slot">
                                <div class="brace-slot">
<?php if ($cell['slot']['contents']): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($cell['slot']['contents']) as $content): ?>
                                        <div class="wrapper-content link--<?php echo htmlSpecialChars($content['link']) ?>">
                                            <div id="content-<?php echo htmlSpecialChars($content['id_content']) ?>" class="content">
                                                <div class="brace-content <?php echo htmlSpecialChars($content['moduleLabel']) ?>">
<?php Nette\Latte\Macros\CoreMacros::includeTemplate(''.(\Kate\Main\Loader::MODULES_DIR).S.$content['moduleLabel'].S.'default.latte', get_defined_vars(), $_l->templates['wofo9v83rr'])->render() ;$contentBlock =  'contentModule'.$content['moduleLabel'] ;Nette\Latte\Macros\UIMacros::callBlock($_l, $contentBlock, array('moduleContent' => $content['moduleContent']) + $template->getParams()) ?>
                                                </div>
                                            </div>
                                        </div>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;endif ?>
                                </div>
                            </section>
                        </div>
                    </div>
                </section>
<?php $_dynSnippets[$_dynSnippetId] = ob_get_flush() ?>
</div>            </div>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
            <div class="clear"></div>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;endif ?>
        </div>
    </section>
<?php if (isset($_dynSnippets)) return $_dynSnippets; 
}}

//
// block head
//
if (!function_exists($_l->blocks['head'][] = '_lb76c5d4c5ab_head')) { function _lb76c5d4c5ab_head($_l, $_args) { extract($_args)
?>
<style type="text/css">
    #body {
        width: <?php echo Nette\Templating\DefaultHelpers::escapeCss($page['layout']['width']) ?>px;
    }
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($page['cells']) as $row): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($row) as $cell): ?>
    #cell-<?php echo Nette\Templating\DefaultHelpers::escapeCss($cell['id_cell']) ?> {
        width: <?php echo Nette\Templating\DefaultHelpers::escapeCss($cell['width']?$cell['width'].$cell['width_unit']:'auto') ?>;
        height: <?php echo Nette\Templating\DefaultHelpers::escapeCss($cell['height']?$cell['height'].$cell['height_unit']:'auto') ?>;
    }
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
</style>


<?php if ($page['cells']): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($page['cells']) as $row): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($row) as $cell): if ($cell['slot']['contents']): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($cell['slot']['contents']) as $content): Nette\Latte\Macros\CoreMacros::includeTemplate(''.(\Kate\Main\Loader::MODULES_DIR).S.$content['moduleLabel'].S.'default.latte', get_defined_vars(), $_l->templates['wofo9v83rr'])->render() ;$headBlock = 'headModule'.$content['moduleLabel'] ;Nette\Latte\Macros\UIMacros::callBlock($_l, $headBlock, array('moduleContent' => $content['moduleContent']) + $template->getParams()) ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;endif ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;endif ;
}}

//
// end of blocks
//

// template extending and snippets support

$_l->extends = empty($template->_extends) ? FALSE : $template->_extends; unset($_extends, $template->_extends);


if ($_l->extends) {
	ob_start();
} elseif (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
// ?>







<?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
