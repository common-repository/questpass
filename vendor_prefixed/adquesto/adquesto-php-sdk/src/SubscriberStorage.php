<?php

namespace QuestpassVendor\Adquesto\SDK;

interface SubscriberStorage
{
    public function persist(Subscriber $subscriber);
    public function get();
    public function drop();
}
