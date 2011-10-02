<?php //netteCache[01]000419a:2:{s:4:"time";s:21:"0.19043100 1317589591";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:97:"C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark\templates\@layout.latte";i:2;i:1317589589;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"f38d86f released on 2011-08-24";}}}?><?php

// source file: C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark\templates\@layout.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '4w1hh3q1m9')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <meta name="description" content="Nette Framework web application skeleton" />
<?php if (isset($robots)): ?>
        <meta name="robots" content="<?php echo htmlSpecialChars($robots) ?>" />
<?php endif ?>

        <title><?php echo Nette\Templating\DefaultHelpers::escapeHtml($title, ENT_NOQUOTES) ?></title>

<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($styles) as $style): ?>
        <link rel="stylesheet" media="<?php echo htmlSpecialChars($style[1]) ?>" href="<?php echo htmlSpecialChars($style[0]) ?>
" type="<?php echo htmlSpecialChars($style[2]) ?>" />
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
        
        <link rel="shortcut icon" href="<?php echo htmlSpecialChars($basePath) ?>/favicon.ico" type="image/x-icon" />
        <link rel="icon" href="<?php echo htmlSpecialChars($basePath) ?>/favicon.ico" type="image/vnd.microsoft.icon" />

<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($scripts) as $script): ?>
        <script type="text/javascript" src="<?php echo htmlSpecialChars($script) ?>"></script>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
        
<?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'head', $template->getParams()) ?>
    </head>

    <body>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($flashes) as $flash): ?>
        <div class="flash <?php echo htmlSpecialChars($flash->type) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($flash->message, ENT_NOQUOTES) ?></div>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
        <div id="body">
<?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'content', $template->getParams()) ?>
        </div>    
    </body>
</html>
