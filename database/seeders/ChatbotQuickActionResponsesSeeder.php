<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatbotQuickActionResponsesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $indigencyAnswer = "Sure. Here is how to request a Barangay Indigency Certificate:\n\n1) Log in to your Barangay Hulo Portal account.\n2) Go to Services and choose Barangay Indigency.\n3) Fill out the application form completely.\n4) Upload required documents, if requested.\n5) Submit your request and keep your Reference ID for tracking.";
        $clearanceAnswer = "You can request a Barangay Clearance online:\n\n1) Log in to the Barangay Hulo Portal.\n2) Open Services and select Barangay Clearance.\n3) Complete the form and provide required details.\n4) Submit the request and save your Reference ID.\n5) Track updates in Request Status.";
        $incidentAnswer = "To submit an Incident Report:\n\n1) Log in to your account.\n2) Open Incident Report from Services.\n3) Enter complete incident details.\n4) Upload evidence if available.\n5) Submit and monitor updates from the barangay.";
        $trackingAnswer = "You can track your request anytime:\n\n1) Log in to your account.\n2) Open Request Status.\n3) Search using your Reference ID.\n4) Review the latest status update and remarks.";
        $supportAnswer = "For portal support, please contact the barangay support team and include your Reference ID if available. You can also visit the Barangay Hall during office hours for urgent concerns.";
        $readyPickupAnswer = "If your request status is READY TO PICKUP, your document is already available for claiming at the Barangay Hall. Please bring a valid ID and your Reference ID when claiming.";

        $items = [
            [
                'question' => 'I want to request a Barangay Indigency certificate.',
                'answer' => $indigencyAnswer,
                'keywords' => 'quick action,indigency,request indigency,indigency certificate',
                'category' => 'indigency',
            ],
            [
                'question' => 'Paano kumuha ng Barangay Indigency certificate?',
                'answer' => $indigencyAnswer,
                'keywords' => 'quick action,tagalog,indigency,paano kumuha indigency',
                'category' => 'indigency',
            ],
            [
                'question' => 'Pano mag request ng indigency?',
                'answer' => $indigencyAnswer,
                'keywords' => 'quick action,tagalog,indigency,pano request indigency',
                'category' => 'indigency',
            ],
            [
                'question' => 'How to request indigecy certificate?',
                'answer' => $indigencyAnswer,
                'keywords' => 'quick action,typo,indigency,indigecy certificate',
                'category' => 'indigency',
            ],
            [
                'question' => 'How do I get a Barangay Clearance?',
                'answer' => $clearanceAnswer,
                'keywords' => 'quick action,clearance,barangay clearance,request clearance',
                'category' => 'clearance',
            ],
            [
                'question' => 'Paano kumuha ng Barangay Clearance?',
                'answer' => $clearanceAnswer,
                'keywords' => 'quick action,tagalog,clearance,paano kumuha clearance',
                'category' => 'clearance',
            ],
            [
                'question' => 'How do I get a Barangay Clearence?',
                'answer' => $clearanceAnswer,
                'keywords' => 'quick action,typo,clearance,clearence',
                'category' => 'clearance',
            ],
            [
                'question' => 'I want to submit an incident report.',
                'answer' => $incidentAnswer,
                'keywords' => 'quick action,incident report,blotter,file report',
                'category' => 'blotter',
            ],
            [
                'question' => 'Pano mag file ng blotter?',
                'answer' => $incidentAnswer,
                'keywords' => 'quick action,tagalog,blotter,incident report',
                'category' => 'blotter',
            ],
            [
                'question' => 'I want to submit an incdent report.',
                'answer' => $incidentAnswer,
                'keywords' => 'quick action,typo,incident report,incdent',
                'category' => 'blotter',
            ],
            [
                'question' => 'How can I track the status of my request?',
                'answer' => $trackingAnswer,
                'keywords' => 'quick action,track request,request status,reference id',
                'category' => 'tracking',
            ],
            [
                'question' => 'Paano ko ma track ang request ko?',
                'answer' => $trackingAnswer,
                'keywords' => 'quick action,tagalog,track request,reference id',
                'category' => 'tracking',
            ],
            [
                'question' => 'I need help or support with the portal.',
                'answer' => $supportAnswer,
                'keywords' => 'quick action,portal support,help,contact support',
                'category' => 'support',
            ],
            [
                'question' => 'Kailangan ko ng tulong sa portal.',
                'answer' => $supportAnswer,
                'keywords' => 'quick action,tagalog,portal support,tulong',
                'category' => 'support',
            ],
            [
                'question' => 'Is my request ready for pickup?',
                'answer' => $readyPickupAnswer,
                'keywords' => 'quick action,ready pickup,ready to pickup,claim document',
                'category' => 'tracking',
            ],
            [
                'question' => 'Ready na ba for pickup ang request ko?',
                'answer' => $readyPickupAnswer,
                'keywords' => 'quick action,tagalog,ready pickup,claim document',
                'category' => 'tracking',
            ],
            [
                'question' => 'Is my request ready to pick up?',
                'answer' => $readyPickupAnswer,
                'keywords' => 'quick action,ready to pick up,ready pickup,claim document',
                'category' => 'tracking',
            ],
        ];

        $count = 0;

        foreach ($items as $item) {
            DB::table('chatbot_knowledge')->updateOrInsert(
                ['question' => $item['question']],
                [
                    'answer' => $item['answer'],
                    'keywords' => $item['keywords'],
                    'category' => $item['category'],
                    'usage_count' => DB::table('chatbot_knowledge')
                        ->where('question', $item['question'])
                        ->value('usage_count') ?? 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
            $count++;
        }

        $this->command->info("Seeded {$count} quick-action chatbot responses.");
    }
}
