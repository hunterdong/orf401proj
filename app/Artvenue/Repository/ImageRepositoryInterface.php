<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Artvenue\Repository;

use App\Artvenue\Models\Image;
use Auth;
use Cache;
use DB;
use File;
use Str;

interface ImageRepositoryInterface
{

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);


    /**
     * @param null $category
     * @param null $timeframe
     * @return mixed
     */
    public function getLatest($category = null, $timeframe = null);


    /**
     * @param null $category
     * @param null $timeframe
     * @return mixed
     */
    public function getFeatured($category = null, $timeframe = null);


    /**
     * @param array $input
     * @param       $id
     * @return mixed
     */
    public function update($input);


    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);


    /**
     * @param      $tag
     * @return mixed
     */
    public function getByTags($tag);


    /**
     * @param $image
     * @return mixed
     */
    public function incrementViews($image);


    /**
     * @param null $category
     * @param null $timeframe
     * @return mixed
     */
    public function mostCommented($category = null, $timeframe = null);


    /**
     * @param null $category
     * @return mixed
     */
    public function popular($category = null, $timeframe = null);


    /**
     * @param null $category
     * @param null $timeframe
     * @return mixed
     */
    public function mostFavorited($category = null, $timeframe = null);


    /**
     * @param null $category
     * @param null $timeframe
     * @return mixed
     */
    public function mostDownloaded($category = null, $timeframe = null);


    /**
     * @param null $category
     * @param null $timeframe
     * @return mixed
     */
    public function mostViewed($category = null, $timeframe = null);


    /**
     * @param      $input
     * @param null $category
     * @return mixed
     */
    public function search($input, $category = null);

    /**
     * @param Image $image
     * @return mixed
     */
    public function findNextImage(Image $image);


    /**
     * @param Image $image
     * @return mixed
     */
    public function findPreviousImage(Image $image);

}