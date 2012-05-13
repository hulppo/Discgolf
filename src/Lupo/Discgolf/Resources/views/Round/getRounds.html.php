<pre>
<?php
foreach ($rounds as $round) {
    echo "TIMESTAMP: " . $round->getTimestamp()->format('c') . "\n";
}
?></pre>