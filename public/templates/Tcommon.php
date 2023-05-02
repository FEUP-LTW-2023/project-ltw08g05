<?php
	function output_header(){
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<title>Document</title>';
        echo '</head>';
        echo '<body>';
        echo '<header>';
        // any additional header content goes here
        echo '</header>';
        echo '<nav>';
        // any additional navigation content goes here
        echo '</nav>';
        echo '<main>';
    }

    function output_footer(){
        echo '</main>';
        echo '<aside>';
        // any additional aside content goes here
        echo '</aside>';
        echo '<footer>';
        // any additional footer content goes here
        echo '</footer>';
        echo '</body>';
        echo '</html>';
    }
?>