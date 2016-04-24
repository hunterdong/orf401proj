<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Artvenue\Notifier;

use App\Artvenue\Mailers\ImageMailer;
use App\Artvenue\Models\Image;
use App\Artvenue\Models\User;

class ImageNotifier extends Notifier
{
    public function __construct(ImageMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function comment(Image $image, User $from, $comment)
    {
        $this->sendNew($image->user_id, $from->id, 'comment', $image->id);

        $to = $image->user;
        $comment = $comment;
        $link = route('image', ['id' => $image->id, 'slug' => $image->slug]);

        $this->mailer->commentMail($to, $from, $comment, $link);
    }

    public function favorite(Image $image, User $from)
    {
        if ($image->user_id !== $from->id) {
            $this->sendNew($image->user_id, $from->id, 'like', $image->id);
        }

        $this->mailer->favoriteMail($image->user, $from, $image);
    }
}
