<?php

// config/chatbot.php — customize for your LGU

return [
    'org_name'              => env('CHATBOT_ORG_NAME',     'our Local Government Unit'),
    'org_location'          => env('CHATBOT_ORG_LOCATION', 'Philippines'),
    'ai_enabled'            => env('CHATBOT_AI_ENABLED',   true),
    'confidence_threshold'  => 0.30,
    'max_knowledge_context' => 60,
    'max_history_turns'     => 6,
];
