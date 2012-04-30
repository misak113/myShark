<?php //netteCache[01]000429a:2:{s:4:"time";s:21:"0.33935400 1335545332";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:106:"C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark\templates\Homepage\default.latte";i:2;i:1333449052;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"94abcaa released on 2012-02-29";}}}?><?php

// source file: C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark\templates\Homepage\default.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '7ubwz5owij')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lba285691e1e_content')) { function _lba285691e1e_content($_l, $_args) { extract($_args)
?><div class="wrapper-page link--<?php echo htmlSpecialChars($page['page']['link']) ?>">
<div id="<?php echo $_control->getSnippetId('header') ?>"><?php call_user_func(reset($_l->blocks['_header']), $_l, $template->getParameters()) ?>
</div>    
<div id="<?php echo $_control->getSnippetId('flashes') ?>"><?php call_user_func(reset($_l->blocks['_flashes']), $_l, $template->getParameters()) ?>
</div><div id="<?php echo $_control->getSnippetId('windows') ?>"><?php call_user_func(reset($_l->blocks['_windows']), $_l, $template->getParameters()) ?>
</div>    
<div id="<?php echo $_control->getSnippetId('page') ?>"><?php call_user_func(reset($_l->blocks['_page']), $_l, $template->getParameters()) ?>
</div>    
</div>
<?php
}}

//
// block _header
//
if (!function_exists($_l->blocks['_header'][] = '_lbee92e7121a__header')) { function _lbee92e7121a__header($_l, $_args) { extract($_args); $_control->validateControl('header')
;if (count($page['languages']) > 1): ?>
    <div class="languages">
<?php $iterations = 0; foreach ($page['languages'] as $lang): ?>
        <a href="<?php echo htmlSpecialChars($lang['actualPath']) ?>">
                        <?php echo Nette\Templating\Helpers::escapeHtml($lang['imgHtml'], ENT_NOQUOTES) ?>

        </a>
<?php $iterations++; endforeach ?>
    </div>
<?php endif ;
}}

//
// block _flashes
//
if (!function_exists($_l->blocks['_flashes'][] = '_lb6e9ac4a846__flashes')) { function _lb6e9ac4a846__flashes($_l, $_args) { extract($_args); $_control->validateControl('flashes')
?>    <div id="flashes">
<?php $iterations = 0; foreach ($flashes as $flash): ?>	<div class="flash <?php echo htmlSpecialChars($flash->type) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate($flash->message), ENT_NOQUOTES) ?></div>
<?php $iterations++; endforeach ?>
    </div>
<?php
}}

//
// block _windows
//
if (!function_exists($_l->blocks['_windows'][] = '_lb43f4ca099e__windows')) { function _lb43f4ca099e__windows($_l, $_args) { extract($_args); $_control->validateControl('windows')
?>    <div id="windows">
<?php if ($page['loginForm']): Nette\Latte\Macros\CoreMacros::includeTemplate(''._window('login'), get_defined_vars(), $_l->templates['7ubwz5owij'])->render() ;Nette\Latte\Macros\UIMacros::callBlock($_l, 'loginWindow', array('loginForm' => $page['loginForm']) + $template->getParameters()) ;endif ?>
    </div>
<?php
}}

//
// block _page
//
if (!function_exists($_l->blocks['_page'][] = '_lbf3799c4fa5__page')) { function _lbf3799c4fa5__page($_l, $_args) { extract($_args); $_control->validateControl('page')
?>    <section id="page-<?php echo htmlSpecialChars($page['page']['id_page']) ?>" class="page">
        <div class="brace-page">
<?php if ($page['cells']): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($page['cells']) as $row): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($row) as $cell): ?>
            <div class="wrapper-cell">
<div id="<?php echo $_dynSnippetId = $_control->getSnippetId('cell-'.$cell['id_cell']) ?>
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
<?php Nette\Latte\Macros\CoreMacros::includeTemplate(''.(\Kate\Main\Loader::MODULES_DIR).S.$content['moduleLabel'].S.'default.latte', get_defined_vars(), $_l->templates['7ubwz5owij'])->render() ;$contentBlock =  'contentModule'.$content['moduleLabel'] ;Nette\Latte\Macros\UIMacros::callBlock($_l, $contentBlock, array('moduleContent' => $content['moduleContent'], 'content' => $content) + $template->getParameters()) ?>
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
</div>
            </div>
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
if (!function_exists($_l->blocks['head'][] = '_lb00bd091fbe_head')) { function _lb00bd091fbe_head($_l, $_args) { extract($_args)
?><style type="text/css">
    #body {
        width: <?php echo Nette\Templating\Helpers::escapeCss($page['layout']['width']) ?>px;
    }
<?php $iterations = 0; foreach ($page['cells'] as $row): $iterations = 0; foreach ($row as $cell): ?>
    #cell-<?php echo Nette\Templating\Helpers::escapeCss($cell['id_cell']) ?> {
        width: <?php echo Nette\Templating\Helpers::escapeCss($cell['width']?$cell['width'].$cell['width_unit']:'auto') ?>;
        height: <?php echo Nette\Templating\Helpers::escapeCss($cell['height']?$cell['height'].$cell['height_unit']:'auto') ?>;
    }
<?php $iterations++; endforeach ;$iterations++; endforeach ?>
</style>


<?php if ($page['cells']): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($page['cells']) as $row): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($row) as $cell): if ($cell['slot']['contents']): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($cell['slot']['contents']) as $content): Nette\Latte\Macros\CoreMacros::includeTemplate(''.(\Kate\Main\Loader::MODULES_DIR).S.$content['moduleLabel'].S.'default.latte', get_defined_vars(), $_l->templates['7ubwz5owij'])->render() ;$headBlock = 'headModule'.$content['moduleLabel'] ;Nette\Latte\Macros\UIMacros::callBlock($_l, $headBlock, array('moduleContent' => $content['moduleContent']) + $template->getParameters()) ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;endif ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;endif ;
}}

//
// end of blocks
//

// template extending and snippets support

$_l->extends = empty($template->_extended) && isset($_control) && $_control instanceof Nette\Application\UI\Presenter ? $_control->findLayoutTemplateFile() : NULL; $template->_extended = $_extended = TRUE;


if ($_l->extends) {
	ob_start();

} elseif (!empty($_control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($_control, $_l, get_defined_vars());
}

//
// main template
//
if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); } ?>







