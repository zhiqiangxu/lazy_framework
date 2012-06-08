<?php

function get_default_db()
{
    global $DB_URL, $DB_USER, $DB_PASS;
    return new DB($DB_URL, $DB_USER, $DB_PASS);
}


