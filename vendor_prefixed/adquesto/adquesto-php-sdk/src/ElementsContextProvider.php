<?php

namespace QuestpassVendor\Adquesto\SDK;

class ElementsContextProvider implements ContextProvider
{
    protected $mainQuestId;
    protected $reminderQuestId;
    protected $isDraft;
    protected $hasActiveCampaigns;
    public function __construct($mainQuestId = null, $reminderQuestId = null, $isDraft = \false, $hasActiveCampaigns = \true)
    {
        $this->mainQuestId = $mainQuestId ? $mainQuestId : $this->generateId('q-');
        $this->reminderQuestId = $reminderQuestId ? $reminderQuestId : $this->generateId('rq-');
        $this->isDraft = $isDraft;
        $this->hasActiveCampaigns = $hasActiveCampaigns;
    }
    protected function generateId($prefix = null)
    {
        return \uniqid($prefix);
    }
    public function mainQuestId()
    {
        return $this->mainQuestId;
    }
    public function reminderQuestId()
    {
        return $this->reminderQuestId;
    }
    public function values()
    {
        $hasActiveCampaigns = $this->hasActiveCampaigns;
        if (\is_callable($hasActiveCampaigns)) {
            $hasActiveCampaigns = $hasActiveCampaigns();
        }
        return array('__MAIN_QUEST_ID__' => $this->mainQuestId, '__REMINDER_QUEST_ID__' => $this->reminderQuestId, '__IS_PUBLISHED__' => $this->isDraft == \false, '__HAS_ACTIVE_CAMPAIGNS__' => $hasActiveCampaigns);
    }
}
