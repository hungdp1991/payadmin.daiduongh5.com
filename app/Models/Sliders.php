<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sliders extends Model
{
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'sliders';
    protected $primaryKey = 'id';
    protected $casts = [
        'items' => 'Array',
        'is_default' => 'Boolean'
    ];
    protected $perPage = 10;

    /**
     * Create new slider
     * @param $sliderName
     * @param $sliderDescription
     * @param $sliderItemsList
     * @created 2020-02-21 LongTHK
     */
    public function createNewSlider($sliderName, $sliderDescription, $sliderItemsList)
    {
        // new instance
        $newSlider = new Sliders();

        // set slider info
        $newSlider->name = $sliderName;
        $newSlider->description = $sliderDescription;
        $newSlider->items = $sliderItemsList;

        // save
        $newSlider->save();
    }

    /**
     * Get sliders depend on page
     * @param $currentPage
     * @return mixed
     * @created 2020-02-21 LongTHK
     */
    public function getSlidersList($currentPage = 1)
    {
        return $this->orderBy('name')
            ->skip(($currentPage - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();
    }

    /**
     * Get total page after paginate
     * @return float
     * @created 2020-02-21 LongTHK
     */
    public function getTotalPage()
    {
        return ceil($this->count() / $this->perPage);
    }

    /**
     * Get slider info
     * @param $sliderId
     * @return mixed
     * @created 2020-02-21 LongTHK
     */
    public function getSliderInfo($sliderId)
    {
        return $this->find($sliderId);
    }
}
