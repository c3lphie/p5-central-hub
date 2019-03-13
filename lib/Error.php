<?php

/**
 * Prints error and backtrace.
 * Also kills the current operation.
 * This function is only designed for making sure nobody makes any programming errors.
 * It should not be used for errors that can occur in valid code.
 * @param $msg mixed
 */
function Error($msg)
{
    echo "<h1>Internal error occurred</h1><br>";
    echo "<h2>Error: $msg</h2><br><br>";

    echo "<code>";

    debug_print_backtrace();

    echo "</code>";
}