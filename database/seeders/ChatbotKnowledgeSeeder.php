<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * BISIG — Barangay Hulo Portal
 * Knowledge base seeder for InfoBot
 *
 * Run: php artisan db:seed --class=ChatbotKnowledgeSeeder
 */
class ChatbotKnowledgeSeeder extends Seeder
{
    public function run(): void
    {
        $now   = now();

        // Remove legacy and unsupported entries so BISIG-only scope is preserved.
        DB::table('chatbot_knowledge')
            ->whereIn('question', [
                'How do I get a business permit?',
                'How do I request a birth certificate?',
                'How do I pay my real property tax?',
                'How do I apply for PhilHealth?',
                'How do I get a senior citizen ID?',
                'How do I get a PWD ID?',
                'How do I apply for a National ID?',
                'How do I get an NBI clearance?',
                'How do I get a First Time Job Seeker Certificate?',
            ])
            ->orWhere('category', 'job_seeker')
            ->delete();

        $items = [

            /* ═══════════════════════════════════════════════════
               BARANGAY INDIGENCY
            ═══════════════════════════════════════════════════ */
            [
                'question' => 'How do I request a Barangay Indigency Certificate?',
                'answer'   => "Steps to request a Barangay Indigency Certificate:\n\n1) Log in to the Barangay Hulo Portal.\n2) Go to Services, then select Barangay Indigency.\n3) Fill out the form carefully.\n4) Upload required documents if any.\n5) Click Submit.\n6) Check updates under Request Status using your Reference ID.\n\nThe certificate is free of charge. Processing is done by barangay staff after submission.",
                'keywords' => 'indigency,indigency certificate,barangay indigency,certificate of indigency,how to get indigency,pano kuha indigency,request indigency,indigency requirements,mahirap,libre,sertipiko ng kahirapan,social welfare,apply indigency',
                'category' => 'indigency',
            ],

            /* ═══════════════════════════════════════════════════
               BARANGAY CLEARANCE
            ═══════════════════════════════════════════════════ */
            [
                'question' => 'How do I request a Barangay Clearance?',
                'answer'   => "Steps to request a Barangay Clearance:\n\n1) Log in to your account on the Barangay Hulo Portal.\n2) Select Services, then Barangay Clearance.\n3) Complete the request form with your personal information.\n4) Follow payment instructions if applicable.\n5) Submit the request.\n6) Track your status under Request Status using your Reference ID.\n\nApproved clearances can be downloaded from the portal or claimed at the Barangay Hall.",
                'keywords' => 'clearance,barangay clearance,how to get clearance,pano kuha clearance,request clearance,clearance requirements,clearance purpose,employment clearance,business clearance,travel clearance,clearance steps,mag-apply clearance,barangay certificate',
                'category' => 'clearance',
            ],

            /* ═══════════════════════════════════════════════════
               INCIDENT REPORT / BLOTTER
            ═══════════════════════════════════════════════════ */
            [
                'question' => 'How do I submit an Incident Report or Blotter?',
                'answer'   => "Steps to submit an Incident Report:\n\n1) Log in to the Barangay Hulo Portal.\n2) Click on Incident Report in the services menu.\n3) Describe the incident — include the date, place, and details of what happened.\n4) Upload evidence such as photos or documents (optional but recommended).\n5) Click Submit.\n6) Barangay officials will review your report and respond.\n\nYou can track the status of your report under Request Status.",
                'keywords' => 'incident report,blotter,file blotter,mag-file ng blotter,reklamo,complaint,insidente,away,pagnanakaw,harassment,submit incident,report incident,blotter report,barangay blotter,how to file blotter',
                'category' => 'blotter',
            ],

            /* ═══════════════════════════════════════════════════
               TRACKING REQUESTS
            ═══════════════════════════════════════════════════ */
            [
                'question' => 'How do I track the status of my request?',
                'answer'   => "Steps to track your request status:\n\n1) Log in to the Barangay Hulo Portal.\n2) Go to Request Status in your account dashboard.\n3) Find your request using your Reference ID.\n4) View the current status:\n   - Pending — received, waiting for review\n   - Under Review — being evaluated by staff\n   - Processing — being prepared\n   - Approved — approved by barangay\n   - Ready for Pickup — available to claim at the hall\n   - Claimed — successfully claimed\n   - Ready for Delivery — being prepared for delivery\n   - Out for Delivery — on its way\n   - Delivered — successfully delivered\n   - Rejected — not approved (check reason)\n\nYou also receive email and SMS notifications for every status update.",
                'keywords' => 'track,tracking,request status,reference ID,status ng request,saan na ang request,pending,approved,rejected,under review,processing,ready pickup,claimed,delivered,how to track,check status,my request',
                'category' => 'tracking',
            ],

            /* ═══════════════════════════════════════════════════
               PORTAL LOGIN / REGISTER
            ═══════════════════════════════════════════════════ */
            [
                'question' => 'How do I log in or register on the Barangay Hulo Portal?',
                'answer'   => "To register on the Barangay Hulo Portal:\n\n1) Visit the Barangay Hulo Portal website.\n2) Click Register or Create Account.\n3) Fill in your personal information: name, address, contact number, and email.\n4) Upload a valid government ID for verification.\n5) Submit and wait for account verification.\n6) Once verified, log in using your email and password.\n\nIf you forgot your password, click Forgot Password on the login page to reset it via email.",
                'keywords' => 'login,log in,register,sign up,create account,forgot password,password reset,how to register,mag-register,account,portal login,BISIG login,portal account,pano mag-login,hindi makapag-login',
                'category' => 'portal',
            ],

            /* ═══════════════════════════════════════════════════
               NOTIFICATIONS
            ═══════════════════════════════════════════════════ */
            [
                'question' => 'How will I know if my request is approved or updated?',
                'answer'   => "The Barangay Hulo Portal sends automatic notifications:\n\n- Email notification — sent to your registered email address\n- SMS notification — sent to your registered mobile number\n\nNotifications are sent when:\n- Your request is received (with your Reference ID)\n- Your request is under review\n- Your request is approved or rejected\n- Your document is ready for pickup or delivery\n\nYou can also log in anytime and check Request Status for real-time updates.",
                'keywords' => 'notification,email,SMS,approved na ba,kailan malalaman,update,status update,how to know,receive notification,di natanggap,walang email,walang SMS,paano malalaman',
                'category' => 'portal',
            ],

            /* ═══════════════════════════════════════════════════
               PORTAL SUPPORT
            ═══════════════════════════════════════════════════ */
            [
                'question' => 'How do I contact support for the Barangay Hulo Portal?',
                'answer'   => "For technical support or concerns with the Barangay Hulo Portal:\n\n- Email: support@barangayhulo.portal\n- Visit the Barangay Hall in person for urgent concerns\n\nOffice Hours: Monday to Friday, 8:00 AM - 5:00 PM\nClosed on weekends and official holidays.\n\nFor portal issues like login problems, missing documents, or incorrect status — email support with your Reference ID and a description of the issue.",
                'keywords' => 'support,help,contact,email support,portal help,technical problem,issue,error,hindi gumagana,hindi makapag-login,portal error,contact barangay,barangay hall,office hours,hotline',
                'category' => 'support',
            ],

            /* ═══════════════════════════════════════════════════
               APPEAL / CANCEL
            ═══════════════════════════════════════════════════ */
            [
                'question' => 'Can I cancel or appeal a rejected request?',
                'answer'   => "Yes, you can appeal a rejected request or cancel a pending one:\n\nTo CANCEL a pending request:\n1) Log in to the portal.\n2) Go to Request Status.\n3) Find your request and click Cancel Request.\n(Only available while status is Pending)\n\nTo APPEAL a rejected request:\n1) Log in and go to Request Status.\n2) Open the rejected request.\n3) Click Appeal and explain the reason.\n4) Upload additional documents if needed.\n5) Submit — barangay staff will re-evaluate.\n\nFor further help, contact: support@barangayhulo.portal",
                'keywords' => 'cancel,appeal,rejected,i-cancel,mag-cancel,bakit na-reject,how to appeal,dispute,request rejected,cancelled,cancel request,appeal request',
                'category' => 'portal',
            ],

            /* ═══════════════════════════════════════════════════
               ABOUT THE PORTAL
            ═══════════════════════════════════════════════════ */
            [
                'question' => 'What is the Barangay Hulo Portal?',
                'answer'   => "The Barangay Hulo Portal (BISIG) is the official online system of Barangay Hulong Duhat, Malabon City.\n\nThrough this portal, residents can:\n- Request Barangay Clearance online\n- Request Indigency Certificates online\n- Request Residency Certificates online\n- Submit Incident Reports\n- Track their request status using a Reference ID\n- Receive email and SMS notifications\n- Download approved documents\n\nThe portal is accessible via desktop and mobile browsers, 24/7 for request submission.",
                'keywords' => 'BISIG,portal,what is BISIG,barangay portal,about portal,barangay hulo portal,e-governance,online system,digital barangay,barangay hulong duhat,malabon',
                'category' => 'general',
            ],

            /* ═══════════════════════════════════════════════════
               DOWNLOAD DOCUMENT
            ═══════════════════════════════════════════════════ */
            [
                'question' => 'How do I download my approved barangay document?',
                'answer'   => "To download your approved document:\n\n1) Log in to the Barangay Hulo Portal.\n2) Go to Request Status.\n3) Find your approved request.\n4) Click Download to save the digital copy.\n\nIf a physical copy is needed, visit the Barangay Hall during office hours (Mon-Fri, 8AM-5PM) to claim it.\n\nEach document includes a unique Reference ID as proof of authenticity.",
                'keywords' => 'download,i-download,get document,download certificate,approved document,physical copy,claim document,paano makuha,how to get my document,download clearance,download indigency',
                'category' => 'portal',
            ],

        ];

        $count = 0;
        foreach ($items as $item) {
            DB::table('chatbot_knowledge')->updateOrInsert(
                ['question' => $item['question']],
                array_merge($item, [
                    'usage_count' => DB::table('chatbot_knowledge')
                        ->where('question', $item['question'])
                        ->value('usage_count') ?? 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
            $count++;
        }

        $this->command->info("✅ {$count} BISIG InfoHulo Assistant knowledge items seeded.");
    }
}