<?php

    $i = 1;
    while ( $i <= 100 ) {

        if (( $i % 3 == 0 ) && ( $i % 5 == 0 )) {
            echo "foobar";
        }
        elseif ( $i % 3 == 0 ) {
            echo "foo";
        }
        elseif ( $i % 5 == 0) {
            echo "bar";
        }
        else {
            echo $i;
        }

        if ($i < 100) {
            echo ", ";
        }
        $i++;
    }



?>