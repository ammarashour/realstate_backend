<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Feedback;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Favourite;
use Illuminate\Contracts\Support\Renderable;
use Modules\Core\Http\Services\FeedbackService;

class FeedbackController extends Controller
{

    const parentPath = "favourite/";
    const indexPath = self::parentPath."Index";
    const createPath = self::parentPath."Create";
    const editPath = self::parentPath."Edit";
    const indexRoute = "favourite.index";
    const createRoute = "favourite.create";
    const editRoute = "favourite.edit";

    protected $feedbackService, $successFlag, $dangerFlag, $warningFlag;

    public function __construct(FeedbackService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;
        $this->warningFlag = Constants::warning;
    }

    public function favouriteIndex()
    {
        $dataArr = $this->feedbackService->index();
        return renderView(self::indexPath, $dataArr);
    }
}
