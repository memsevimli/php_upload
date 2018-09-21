<?php

    $i = 1;
    while ( $i <= 100 ) {

        if (( $i % 3 == 0 ) && ( $i % 5 == 0 )) {
            fwrite(STDOUT, "foobar");
        }
        elseif ( $i % 3 == 0 ) {
            fwrite(STDOUT, "foo");
        }
        elseif ( $i % 5 == 0) {
            fwrite(STDOUT, "bar");
        }
        else {
            fwrite(STDOUT, $i);
        }

        if ($i < 100) {
            fwrite(STDOUT, ", ");
        }
        $i++;
    }

?>