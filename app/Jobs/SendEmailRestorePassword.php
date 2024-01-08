<?php

namespace App\Jobs;

use App\Mail\RestorePasswordEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailRestorePassword implements ShouldQueue
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
        $token = hash_hmac('sha256', $this->user->email, env('SECRET_KEY'));
        Mail::to($this->user->email)->send(new RestorePasswordEmail($this->user, $token));
    }
}
