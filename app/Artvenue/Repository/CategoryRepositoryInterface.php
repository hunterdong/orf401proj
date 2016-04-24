<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Artvenue\Repository;

use App\Artvenue\Repository\ImageRepository;

interface CategoryRepositoryInterface
{

    /**
     * @param $slug
     * @return mixed
     */
    public function getBySlug($slug);
}