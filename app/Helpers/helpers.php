<?php

function getDayOfDate(string $date)
{
    return date('N', strtotime($date));
}
