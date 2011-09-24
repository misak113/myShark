<?php //netteCache[01]000429a:2:{s:4:"time";s:21:"0.42359900 1316897145";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:106:"C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark\templates\Homepage\default.latte";i:2;i:1316894009;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"f38d86f released on 2011-08-24";}}}?><?php

// source file: C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark\templates\Homepage\default.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '13kuqze0ng')
;//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lb0c5363017c_content')) { function _lb0c5363017c_content($_l, $_args) { extract($_args)
?>
<div id="wrapper">
        
<div id="<?php echo $control->getSnippetId('good') ?>"><?php call_user_func(reset($_l->blocks['_good']), $_l, $template->getParams()) ?>
</div>
<div id="<?php echo $control->getSnippetId('bad') ?>"><?php call_user_func(reset($_l->blocks['_bad']), $_l, $template->getParams()) ?>
</div>        
</div>
<?php
}}

//
// block _good
//
if (!function_exists($_l->blocks['_good'][] = '_lb7ff66c24a3__good')) { function _lb7ff66c24a3__good($_l, $_args) { extract($_args); $control->validateControl('good')
?>
        <p>good - <?php echo Nette\Templating\DefaultHelpers::escapeHtml($anyVariable, ENT_NOQUOTES) ?></p>
<?php
}}

//
// block _bad
//
if (!function_exists($_l->blocks['_bad'][] = '_lb90d1906c68__bad')) { function _lb90d1906c68__bad($_l, $_args) { extract($_args); $control->validateControl('bad')
?>
        <p>bad - <?php echo Nette\Templating\DefaultHelpers::escapeHtml($anyVariable, ENT_NOQUOTES) ?></p>
<?php
}}

//
// block head
//
if (!function_exists($_l->blocks['head'][] = '_lbf145616e33_head')) { function _lbf145616e33_head($_l, $_args) { extract($_args)
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
