<?php

namespace QuestpassVendor;

use QuestpassVendor\Adquesto\SDK\CurlHttpClient;
use QuestpassVendor\Adquesto\SDK\InMemoryStorage;
include './vendor/autoload.php';
$content = new \QuestpassVendor\Adquesto\SDK\Content(
    'https://api.adquesto.com/v1/publishers/services/',
    // API url
    'SERVICE-UUID',
    new InMemoryStorage(),
    new CurlHttpClient()
);
$js = $content->javascript(new \QuestpassVendor\Adquesto\SDK\SubscriptionsContextProvider([]));
