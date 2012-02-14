<?php //netteCache[01]000419a:2:{s:4:"time";s:21:"0.49060800 1329148409";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:97:"C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark\templates\@layout.latte";i:2;i:1329148314;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"f38d86f released on 2011-08-24";}}}?><?php

// source file: C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark\templates\@layout.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '1r98rdvlxk')
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
<html lang="<?php if (isset($page['page']['activeLanguage']['shortcut'])): echo htmlSpecialChars($page['page']['activeLanguage']['shortcut']) ;endif ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <meta name="description" content="Nette Framework web application skeleton" />
<?php if (isset($robots)): ?>
        <meta name="robots" content="<?php echo htmlSpecialChars($robots) ?>" />
<?php endif ?>

        <title><?php echo Nette\Templating\DefaultHelpers::escapeHtml($title, ENT_NOQUOTES) ?></title>

<?php if (isset($styles)): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($styles) as $style): ?>
        <link rel="stylesheet" media="<?php echo htmlSpecialChars($style[1]) ?>" href="<?php echo htmlSpecialChars($style[0]) ?>
" type="<?php echo htmlSpecialChars($style[2]) ?>" />
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;endif ?>
        
        <link rel="shortcut icon" href="<?php echo htmlSpecialChars($basePath) ?>/favicon.ico" type="image/x-icon" />
        <link rel="icon" href="<?php echo htmlSpecialChars($basePath) ?>/favicon.ico" type="image/vnd.microsoft.icon" />

<?php if (isset($scripts)): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($scripts) as $script): ?>
        <script type="text/javascript" src="<?php echo htmlSpecialChars($script) ?>"></script>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;endif ;if (isset($jsVariables)): ?>
        <script type="text/javascript">
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($jsVariables) as $var => $value): ?>
				<?php echo $var ?> = '<?php echo $value ?>';
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
		</script>
<?php endif ?>
        
<?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'head', $template->getParams()) ?>
    </head>

    <body>
<?php if (isset($setting['loadingBox'])): ?>
        <div id="loading-box"><div class="loading"></div></div>
<?php endif ?>
        <div id="body">
<?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'content', $template->getParams()) ?>
        </div>    
    </body>
</html>
