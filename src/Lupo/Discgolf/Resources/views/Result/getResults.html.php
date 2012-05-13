<pre>
<?php
foreach ($results as $result) {
    echo $result->getPlayer()->getName() . ": " . $result->getThrows() . "\n";
}
?></pre>