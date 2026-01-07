<?php

namespace Osd\L4lHelpers\Mail\Mail;

use Illuminate\Mail\Mailable;

class TestMail extends Mailable
{
    public function build()
    {
        return $this->subject('Mail configuration test')
            ->html('<H1>Mail configuration test <span style="color: lawngreen">Success</span></H1>');
    }
}
