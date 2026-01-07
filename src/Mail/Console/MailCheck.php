<?php

namespace Osd\L4lHelpers\Mail\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Osd\L4lHelpers\Mail\Mail\TestMail;


class MailCheck extends Command
{

    protected $signature = 'l4lhelpers:mail:check {email?}';

    protected $description = 'Check connection to mail service and if introduce send email test';


    /**
     * @return int
     */
    public function handle(): int
    {
        try {

            $this->checkConnection();
            $this->sendTestMail();

        }catch (\RuntimeException $exception){
            $this->error($exception->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }


    /**
     * @return void
     */
    private function checkConnection(): void
    {
        try {

            $mailer = config('mail.default');

            if ($mailer !== 'smtp') {
                $this->error('Mail not is possible check connection please
                use argument email to test and revise the inbox');
            }

            \Osd\L4lHelpers\Mail::checkSMTP();
            $this->info('The connection connection is working');

        }catch (\RuntimeException $e) {

            throw new \RuntimeException($e->getMessage());
        }
    }


    /**
     * @return void
     */
    private function sendTestMail(): void
    {
        if ($this->argument('email')) {

            if (!filter_var($this->argument('email'), FILTER_VALIDATE_EMAIL)) {

                throw new \RuntimeException('Invalid email address');
            }

            try {
                Mail::to($this->argument('email'))->send(new TestMail());

                $this->info('Mail sent correctly to ' . $this->argument('email'));

            }catch (\Exception $e) {
                throw new \RuntimeException($e->getMessage());
            }
        }
    }
}
