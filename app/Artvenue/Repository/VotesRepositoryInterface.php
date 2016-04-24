<?php

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Artvenue\Repository;

use App\Artvenue\Models\Image;

interface VotesRepositoryInterface
{

    /**
     * @param Image $post
     * @return mixed
     */
    public function vote(Image $post);
}