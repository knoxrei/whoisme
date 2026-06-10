<?php
$content = file_get_contents('test_compiled.php');
$tokens = token_get_all($content);
$ifs = 0;
foreach ($tokens as $line => $token) {
    if (is_array($token)) {
        if ($token[0] === T_IF) {
            $ifs++;
            echo "IF at line " . $token[2] . "\n";
        } elseif ($token[0] === T_ENDIF) {
            $ifs--;
            echo "ENDIF at line " . $token[2] . "\n";
        }
    }
}
echo "Total unclosed IFs: $ifs\n";
