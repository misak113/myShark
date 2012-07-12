<?php //netteCache[01]000429a:2:{s:4:"time";s:21:"0.93491900 1342101361";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:106:"C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark\templates\Homepage\default.latte";i:2;i:1342100223;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"94abcaa released on 2012-02-29";}}}?><?php

// source file: C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark\templates\Homepage\default.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'zqihsqzocw')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lbdfc4518e6c_content')) { function _lbdfc4518e6c_content($_l, $_args) { extract($_args)
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
if (!function_exists($_l->blocks['_header'][] = '_lb7cc0c98017__header')) { function _lb7cc0c98017__header($_l, $_args) { extract($_args); $_control->validateControl('header')
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
if (!function_exists($_l->blocks['_flashes'][] = '_lbbe14c58db6__flashes')) { function _lbbe14c58db6__flashes($_l, $_args) { extract($_args); $_control->validateControl('flashes')
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
if (!function_exists($_l->blocks['_windows'][] = '_lb1e2c4e207e__windows')) { function _lb1e2c4e207e__windows($_l, $_args) { extract($_args); $_control->validateControl('windows')
?>    <div id="windows">
<?php if ($page['loginForm']): Nette\Latte\Macros\CoreMacros::includeTemplate(''._window('login'), get_defined_vars(), $_l->templates['zqihsqzocw'])->render() ;Nette\Latte\Macros\UIMacros::callBlock($_l, 'loginWindow', array('loginForm' => $page['loginForm']) + $template->getParameters()) ;endif ?>
    </div>
<?php
}}

//
// block _page
//
if (!function_exists($_l->blocks['_page'][] = '_lb0c2b53c981__page')) { function _lb0c2b53c981__page($_l, $_args) { extract($_args); $_control->validateControl('page')
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
                                            <div id="content-<?php echo htmlSpecialChars($content['id_content']) ?>" 
												 <?php if (isAllowed('content', 'edit')): ?> data-myshark-params="<?php echo htmlSpecialChars(json_encode($content)) ?>
" <?php endif ?>

												 class="content">
                                                <div class="brace-content <?php echo htmlSpecialChars($content['moduleLabel']) ?>">
<?php Nette\Latte\Macros\CoreMacros::includeTemplate(''.(\Kate\Main\Loader::MODULES_DIR).S.$content['moduleLabel'].S.'default.latte', get_defined_vars(), $_l->templates['zqihsqzocw'])->render() ;$contentBlock =  'contentModule'.$content['moduleLabel'] ;Nette\Latte\Macros\UIMacros::callBlock($_l, $contentBlock, array('moduleContent' => $content['moduleContent'], 'content' => $content) + $template->getParameters()) ?>
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
if (!function_exists($_l->blocks['head'][] = '_lb5a54c13842_head')) { function _lb5a54c13842_head($_l, $_args) { extract($_args)
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


<?php if ($page['cells']): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($page['cells']) as $row): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($row) as $cell): if ($cell['slot']['contents']): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($cell['slot']['contents']) as $content): Nette\Latte\Macros\CoreMacros::includeTemplate(''.(\Kate\Main\Loader::MODULES_DIR).S.$content['moduleLabel'].S.'default.latte', get_defined_vars(), $_l->templates['zqihsqzocw'])->render() ;$headBlock = 'headModule'.$content['moduleLabel'] ;Nette\Latte\Macros\UIMacros::callBlock($_l, $headBlock, array('moduleContent' => $content['moduleContent']) + $template->getParameters()) ?>

<?php call_user_func(reset($_l->blocks['ModuleWindows']), $_l, get_defined_vars()) ; $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;endif ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;endif ;
}}

//
// block ModuleWindows
//
if (!function_exists($_l->blocks['ModuleWindows'][] = '_lb03fd0e214f_ModuleWindows')) { function _lb03fd0e214f_ModuleWindows($_l, $_args) { extract($_args)
;Nette\Latte\Macros\CoreMacros::includeTemplate(''._window('editContent'), get_defined_vars(), $_l->templates['zqihsqzocw'])->render() ;$editWindow = 'editContentWindow_'.$content['id_content'] ;Nette\Latte\Macros\UIMacros::callBlock($_l, $editWindow, $template->getParameters()) ;
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







