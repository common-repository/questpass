<?php

namespace QuestpassVendor\Adquesto\SDK;

interface JavascriptStorage
{
    public function get();
    public function set($contents);
    public function valid();
}
