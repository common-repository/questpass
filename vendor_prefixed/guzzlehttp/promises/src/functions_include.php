<?php

namespace QuestpassVendor;

// Don't redefine the functions if included multiple times.
if (!\function_exists('QuestpassVendor\\GuzzleHttp\\Promise\\promise_for')) {
    require __DIR__ . '/functions.php';
}
