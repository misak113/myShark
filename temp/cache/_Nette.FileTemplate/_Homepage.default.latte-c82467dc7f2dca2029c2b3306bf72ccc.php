<?php //netteCache[01]000429a:2:{s:4:"time";s:21:"0.63051200 1317236977";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:106:"C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark\templates\Homepage\default.latte";i:2;i:1317236975;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"f38d86f released on 2011-08-24";}}}?><?php

// source file: C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark\templates\Homepage\default.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '1m8nurp0zg')
;//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lbad39000dbc_content')) { function _lbad39000dbc_content($_l, $_args) { extract($_args)
?>
<div id="wrapper">
        
<div id="<?php echo $control->getSnippetId('good') ?>"><?php call_user_func(reset($_l->blocks['_good']), $_l, $template->getParams()) ?>
</div>        
        
</div>
<?php
}}

//
// block _good
//
if (!function_exists($_l->blocks['_good'][] = '_lbd5a57e01ed__good')) { function _lbd5a57e01ed__good($_l, $_args) { extract($_args); $control->validateControl('good')
;$iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($page['cells']) as $cell): ?>
<div id="<?php echo $_dynSnippetId = $control->getSnippetId('page_cell_'.$page['page']['id_page'].'_'.$cell['id_cell']) ?>
"><?php ob_start() ?>
                    <?php echo Nette\Templating\DefaultHelpers::escapeHtml(var_export($cell), ENT_NOQUOTES) ?>

<?php $_dynSnippets[$_dynSnippetId] = ob_get_flush() ?>
</div><?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
        <p>good - {}</p>
<?php if (isset($_dynSnippets)) return $_dynSnippets; 
}}

//
// block head
//
if (!function_exists($_l->blocks['head'][] = '_lbe615c7eb9f_head')) { function _lbe615c7eb9f_head($_l, $_args) { extract($_args)
?>
<script>
    $(document).ready(function () {
        return;
        $.ajax({
            url: './',
            dataType: 'text',
            success: function (data) {
                alert(data);
            }
        });
    });
</script>
<?php
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
//
if (!$_l->extends) { call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars()); } ?>


<?php if (!$_l->extends) { call_user_func(reset($_l->blocks['head']), $_l, get_defined_vars()); }  
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
