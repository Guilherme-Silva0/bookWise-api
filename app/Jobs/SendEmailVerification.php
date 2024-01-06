<?php

namespace App\Jobs;

use App\Mail\EmailVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailVerification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly object $user,
        public readonly string $lang
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        app()->setLocale($this->lang);
        $confirmationToken = hash_hmac('sha256', $this->user->id, env('SECRET_KEY'));
        Mail::to($this->user->email)->send(new EmailVerification($this->user, $confirmationToken));
    }
}
