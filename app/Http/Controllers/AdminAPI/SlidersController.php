<?php

namespace App\Http\Controllers\AdminAPI;

use App\Http\Requests\Sliders\SlidersCreatingRequest;
use App\Http\Requests\Sliders\SlidersUpdattingRequest;
use App\Http\Resources\SlidersResource;
use App\Models\Sliders;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SlidersController extends AdminAPIBaseController
{
    // model instance
    private $slidersModel;

    /**
     * SlidersController constructor.
     * @param Sliders $slidersModel
     * @created 2020-02-21 LongTHK
     */
    public function __construct(Sliders $slidersModel)
    {
        // call parent constructor
        parent::__construct();

        // create model instance
        $this->slidersModel = $slidersModel;
    }

    /**
     * Get sliders list
     * @return AnonymousResourceCollection
     * @created 2020-02-21 LongTHK
     */
    public function getList()
    {
        // get current page
        $currentPage = request()->currentPage;

        // return collection
        return SlidersResource::collection($this->slidersModel->getSlidersList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->slidersModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Creata new slider
     * @param SlidersCreatingRequest $request
     * @return AnonymousResourceCollection
     * @created 2020-02-21 LongTHK
     */
    public function create(SlidersCreatingRequest $request)
    {
        // create new slider
        $this->slidersModel->createNewSlider($request->sliderName, $request->sliderDescription, $request->sliderItemsList);

        // return collection
        return SlidersResource::collection($this->slidersModel->getSlidersList($request->currentPage))->additional([
            'pagination' => [
                'currentPage' => $request->currentPage,
                'totalPage' => $this->slidersModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Delete slider
     * @return AnonymousResourceCollection
     * @created 2020-02-21 LongTHK
     */
    public function delete()
    {
        // get slider id
        $sliderId = request()->sliderId;
        // get current page
        $currentPage = request()->currentPage;

        // get slider info
        $sliderInfo = $this->slidersModel->getSliderInfo($sliderId);
        // delete slider
        $sliderInfo->delete();

        // return collection
        return SlidersResource::collection($this->slidersModel->getSlidersList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->slidersModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Delete image from items list
     * @return AnonymousResourceCollection
     * @created 2020-02-21 LongTHK
     */
    public function deleteImageItem()
    {
        // get slider id
        $sliderId = request()->sliderId;
        // get image index
        $imageIndex = request()->imageIndex;
        // get current page
        $currentPage = request()->currentPage;

        // get slider info
        $sliderInfo = $this->slidersModel->getSliderInfo($sliderId);
        // get items list
        $itemsList = $sliderInfo->items;
        // get image item
        $imageItem = $itemsList[$imageIndex];

        // remove item at index
        array_splice($itemsList, $imageIndex, 1);
        $sliderInfo->items = $itemsList;
        $sliderInfo->save();

        // return collection
        return SlidersResource::collection($this->slidersModel->getSlidersList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->slidersModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Change image item redirect link
     * @return AnonymousResourceCollection
     * @created 2020-02-21 LongTHK
     */
    public function changeImageRedirectLink()
    {
        // get slider id
        $sliderId = request()->sliderId;
        // get image index
        $imageIndex = request()->imageIndex;
        // get image redirect link
        $redirectLink = request()->redirectLink;
        // get current page
        $currentPage = request()->currentPage;

        // get slider info
        $sliderInfo = $this->slidersModel->getSliderInfo($sliderId);
        // get items list
        $itemsList = $sliderInfo->items;

        // set new redirect link
        $itemsList[$imageIndex]['imageRedirect'] = $redirectLink;
        $sliderInfo->items = $itemsList;
        $sliderInfo->save();

        // return collection
        return SlidersResource::collection($this->slidersModel->getSlidersList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->slidersModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Change image item title
     * @return AnonymousResourceCollection
     * @created 2020-02-21 LongTHK
     */
    public function changeImageTitle()
    {
        // get slider id
        $sliderId = request()->sliderId;
        // get image index
        $imageIndex = request()->imageIndex;
        // get image title
        $title = request()->title;
        // get current page
        $currentPage = request()->currentPage;

        // get slider info
        $sliderInfo = $this->slidersModel->getSliderInfo($sliderId);
        // get items list
        $itemsList = $sliderInfo->items;

        // set new title
        $itemsList[$imageIndex]['imageTitle'] = $title;
        $sliderInfo->items = $itemsList;
        $sliderInfo->save();

        // return collection
        return SlidersResource::collection($this->slidersModel->getSlidersList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->slidersModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Change image item description
     * @return AnonymousResourceCollection
     * @created 2020-02-21 LongTHK
     */
    public function changeImageDescription()
    {
        // get slider id
        $sliderId = request()->sliderId;
        // get image index
        $imageIndex = request()->imageIndex;
        // get image description
        $description = request()->description;
        // get current page
        $currentPage = request()->currentPage;

        // get slider info
        $sliderInfo = $this->slidersModel->getSliderInfo($sliderId);
        // get items list
        $itemsList = $sliderInfo->items;

        // set new title
        $itemsList[$imageIndex]['imageDescription'] = $description;
        $sliderInfo->items = $itemsList;
        $sliderInfo->save();

        // return collection
        return SlidersResource::collection($this->slidersModel->getSlidersList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->slidersModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Get slider info
     * @return SlidersResource
     * @created 2020-02-21 LongTHK
     */
    public function info()
    {
        return SlidersResource::make($this->slidersModel->getSliderInfo(request()->sliderId));
    }

    /**
     * Update slider
     * @param SlidersUpdattingRequest $request
     * @return AnonymousResourceCollection
     * @created 2020-02-21 LongTHK
     */
    public function update(SlidersUpdattingRequest $request)
    {
        // get slider id
        $sliderId = $request->sliderId;
        // get slider info
        $sliderInfo = $this->slidersModel->getSliderInfo($sliderId);
        // get itemsList list
        $newItemsList = $request->sliderItemsList;

        // add new image to slider
        $currentSliderItemsList = $sliderInfo->items;
        if (!empty($newItemsList)) {
            $currentSliderItemsList = array_merge($currentSliderItemsList, $newItemsList);
        }

        // update slider
        $sliderInfo->name = $request->sliderName;
        $sliderInfo->description = $request->sliderDescription;
        $sliderInfo->items = $currentSliderItemsList;
        $sliderInfo->save();


        // return collection
        return SlidersResource::collection($this->slidersModel->getSlidersList($request->currentPage))->additional([
            'pagination' => [
                'currentPage' => $request->currentPage,
                'totalPage' => $this->slidersModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Set default slider
     * @return AnonymousResourceCollection
     * @created 2020-03-02 LongTHK
     */
    public function setDefaultSlider()
    {
        // update all slider "default" field
        $this->slidersModel->query()->update(['is_default' => 0]);

        // update current slider
        $currentSlider = $this->slidersModel->getSliderInfo(request()->sliderId);
        $currentSlider->is_default = 1;
        $currentSlider->save();

        // return collection
        return SlidersResource::collection($this->slidersModel->getSlidersList(request()->currentPage))->additional([
            'pagination' => [
                'currentPage' => request()->currentPage,
                'totalPage' => $this->slidersModel->getTotalPage()
            ]
        ]);
    }
}
