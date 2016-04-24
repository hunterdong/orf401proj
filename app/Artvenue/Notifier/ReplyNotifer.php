<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Artvenue\Notifier;

use App\Artvenue\Mailers\ImageMailer;
use App\Artvenue\Models\Image;
use App\Artvenue\Models\User;

class ReplyNotifer extends Notifier
{

    public function __construct(ImageMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function replyNotice(User $to, User $from, Image $on, $reply, $sendEmail = false)
    {
        $this->sendNew($to->id, $from->id, 'reply', $on->id);
        if ($sendEmail === true) {
            $this->mailer->replyMail($to, $from, $on, $reply);
        }
    }
}