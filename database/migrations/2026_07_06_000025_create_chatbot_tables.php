<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Knowledge base ─────────────────────────────────────
        Schema::create('chatbot_knowledge', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->text('answer');
            $table->text('keywords')->default('');
            $table->string('category', 100)->default('general');
            $table->unsignedInteger('usage_count')->default(0);
            $table->timestamps();
        });

        // ── 2. Conversations ──────────────────────────────────────
        Schema::create('chatbot_conversations', function (Blueprint $table) {
            $table->id();
            $table->uuid('session_id')->unique();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();
        });

        // ── 3. Messages ───────────────────────────────────────────
        Schema::create('chatbot_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('session_id')->index();
            $table->string('role', 10);   // 'user' | 'bot'
            $table->text('content');
            $table->boolean('matched')->default(false);
            $table->foreignId('knowledge_id')
                  ->nullable()
                  ->constrained('chatbot_knowledge')
                  ->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });

        // ── 4. Unmatched queries (for training) ───────────────────
        Schema::create('chatbot_unmatched', function (Blueprint $table) {
            $table->id();
            $table->text('query');
            $table->uuid('session_id')->nullable();
            $table->boolean('resolved')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });

        // ── Seed default eGovernance FAQs ─────────────────────────
        DB::table('chatbot_knowledge')->insert([
            [
                'question'    => 'How do I request a Barangay Clearance?',
                'answer'      => "To request a Barangay Clearance in BISIG:\n1) Log in to your BISIG account.\n2) Open Services and choose Barangay Clearance.\n3) Complete the form and upload requirements.\n4) Submit your request.\n5) Track progress using your Reference ID.\nApproved requests can be downloaded or claimed at the Barangay Hall.",
                'keywords'    => 'barangay clearance,clearance,request clearance,requirements,reference id',
                'category'   => 'civil',
                'usage_count' => 0,
                'created_at'  => now(), 'updated_at' => now(),
            ],
            [
                'question'    => 'How do I request a Barangay Indigency Certificate?',
                'answer'      => "To request a Barangay Indigency Certificate in BISIG:\n1) Log in to your account.\n2) Open Services and choose Barangay Indigency.\n3) Fill out the form and upload required documents.\n4) Submit your request.\n5) Track status using your Reference ID.\nYou will be notified when it is approved.",
                'keywords'    => 'indigency,indigency certificate,request indigency,reference id,requirements',
                'category'   => 'indigency',
                'usage_count' => 0,
                'created_at'  => now(), 'updated_at' => now(),
            ],
            [
                'question'    => 'How do I request a Barangay Residency Certificate?',
                'answer'      => "To request a Barangay Residency Certificate in BISIG:\n1) Log in to your account.\n2) Open Services and choose Barangay Residency.\n3) Complete the application details.\n4) Submit and wait for verification.\n5) Track updates using your Reference ID.",
                'keywords'    => 'residency,residency certificate,resident certificate,request residency,reference id',
                'category'   => 'civil',
                'usage_count' => 0,
                'created_at'  => now(), 'updated_at' => now(),
            ],
            [
                'question'    => 'How do I submit an Incident Report?',
                'answer'      => "To submit an Incident Report in BISIG:\n1) Log in to your account.\n2) Open Services and choose Incident Report.\n3) Enter the incident details (date, place, and description).\n4) Upload supporting files if available.\n5) Submit and monitor status updates in Request Status.",
                'keywords'    => 'incident report,blotter,submit incident,file report,reklamo,reference id',
                'category'   => 'blotter',
                'usage_count' => 0,
                'created_at'  => now(), 'updated_at' => now(),
            ],
            [
                'question'    => 'How do I track my request status?',
                'answer'      => "To track your request in BISIG:\n1) Log in to your account.\n2) Open Request Status.\n3) Use your Reference ID to find your application.\n4) Review the latest status update and remarks.\nYou will also receive notifications when status changes.",
                'keywords'    => 'track request,request status,reference id,approved,pending,rejected',
                'category'   => 'tracking',
                'usage_count' => 0,
                'created_at'  => now(), 'updated_at' => now(),
            ],
            [
                'question'    => 'How do I log in or register on BISIG?',
                'answer'      => "To register on BISIG:\n1) Open the BISIG portal.\n2) Click Register and fill out your personal details.\n3) Upload a valid ID for verification.\n4) Submit your registration and wait for approval.\nAfter approval, log in using your account credentials.",
                'keywords'    => 'bisig login,bisig register,account,forgot password,portal access',
                'category'   => 'portal',
                'usage_count' => 0,
                'created_at'  => now(), 'updated_at' => now(),
            ],
            [
                'question'    => 'How do I contact BISIG support?',
                'answer'      => "For BISIG support, use the contact channels listed in the portal or visit Barangay Hall during office hours. Include your Reference ID and issue details so staff can assist you faster.",
                'keywords'    => 'bisig support,contact support,help,reference id,portal issue',
                'category'   => 'general',
                'usage_count' => 0,
                'created_at'  => now(), 'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_unmatched');
        Schema::dropIfExists('chatbot_messages');
        Schema::dropIfExists('chatbot_conversations');
        Schema::dropIfExists('chatbot_knowledge');
    }
};
