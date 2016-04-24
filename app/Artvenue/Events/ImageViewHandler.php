<?php

namespace App\Artvenue\Events;

use App\Artvenue\Repository\ImageRepositoryInterface;
use App\Events\Event;
use Illuminate\Session\Store;
use Illuminate\Queue\SerializesModels;


class ImageViewHandler extends Event
{
    use SerializesModels;

    /**
     * @param ImageRepositoryInterface $images
     * @param Store $session
     */
    public function __construct(ImageRepositoryInterface $images, Store $session)
    {
        $this->session = $session;
        $this->images = $images;
    }

    /**
     * @param $image
     */
    public function handle($image)
    {
        if (!$this->hasViewedTrick($image)) {
            $image = $this->images->incrementViews($image);
            $this->storeViewedTrick($image);
        }
    }

    /**
     * @param $image
     * @return bool
     */
    protected function hasViewedTrick($image)
    {
        return array_key_exists($image->id, $this->getViewedTricks());
    }

    /**
     * Get the users viewed trick from the session.
     *
     * @return array
     */
    protected function getViewedTricks()
    {
        return $this->session->get('viewed_images', []);
    }

    /**
     * @param $image
     */
    protected function storeViewedTrick($image)
    {

        $key = 'viewed_images.' . $image->id;

        $this->session->put($key, time());
    }
}