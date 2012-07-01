<?php foreach ($view['assetic']->javascripts(
    array('@LupoDiscgolf/Resources/public/js/*')) as $url): ?>
<script type="text/javascript" src="<?php echo $view->escape($url) ?>"></script>
<?php endforeach; ?>
<pre>
<?php
foreach ($courses as $course) {
    echo $course->getName() . "\n";
}
?></pre>