<?php //netteCache[01]000419a:2:{s:4:"time";s:21:"0.50254600 1335545332";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:97:"C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark\templates\@layout.latte";i:2;i:1329332179;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"94abcaa released on 2012-02-29";}}}?><?php

// source file: C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark\templates\@layout.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '1xymjl4qlx')
;
// prolog Nette\Latte\Macros\UIMacros

// snippets support
if (!empty($_control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($_control, $_l, get_defined_vars());
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
<?php if (isset($robots)): ?>        <meta name="robots" content="<?php echo htmlSpecialChars($robots) ?>" />
<?php endif ?>

        <title><?php echo Nette\Templating\Helpers::escapeHtml($title, ENT_NOQUOTES) ?></title>

<?php if (isset($styles)): $iterations = 0; foreach ($styles as $style): ?>        <link rel="stylesheet" media="<?php echo htmlSpecialChars($style[1]) ?>
" href="<?php echo htmlSpecialChars($style[0]) ?>" type="<?php echo htmlSpecialChars($style[2]) ?>" />
<?php $iterations++; endforeach ;endif ?>
        
        <link rel="shortcut icon" href="<?php echo htmlSpecialChars($basePath) ?>/favicon.ico" type="image/x-icon" />
        <link rel="icon" href="<?php echo htmlSpecialChars($basePath) ?>/favicon.ico" type="image/vnd.microsoft.icon" />

<?php if (isset($scripts)): $iterations = 0; foreach ($scripts as $script): ?>        <script type="text/javascript" src="<?php echo htmlSpecialChars($script) ?>"></script>
<?php $iterations++; endforeach ;endif ;if (isset($jsVariables)): ?>
        <script type="text/javascript">
<?php $iterations = 0; foreach ($jsVariables as $var => $value): ?>
				<?php echo $var ?> = '<?php echo $value ?>';
<?php $iterations++; endforeach ?>
		</script>
<?php endif ?>
        
<?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'head', $template->getParameters()) ?>
    </head>

    <body>
<?php if (isset($setting['loadingBox'])): ?>        <div id="loading-box"><div class="loading"></div></div>
<?php endif ?>
        <div id="body">
<?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'content', $template->getParameters()) ?>
        </div>    
    </body>
</html>
