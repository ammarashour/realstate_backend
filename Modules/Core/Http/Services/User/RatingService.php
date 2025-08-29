<?php

namespace Modules\Core\Http\Services\User;

use App\Http\Contracts\Authorization\PushNotificationTokenServiceInterface;
use App\Http\Contracts\Notification\FirebaseCloudMessagingServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\User\Rating;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use App\Http\Contracts\User\UserServiceInterface;
use App\Http\Contracts\User\RatingServiceInterface;

class RatingService extends PsService implements RatingServiceInterface
{
    public function __construct(protected UserServiceInterface $userService,
        protected PushNotificationTokenServiceInterface $pushNotificationTokenService,
        protected FirebaseCloudMessagingServiceInterface $firebaseCloudMessagingService)
    {

    }
    public function save($ratingData)
    {
        DB::beginTransaction();
        try {
            $rating = $this->saveRating($ratingData);

            if($ratingData['type'] == Constants::ratingUserType) {
                $userId = $ratingData['to_user_id'];
                $ratingCount = $this->getUserRatingCount($userId);
                $userData = $this->prepareUserData($ratingCount);
                $this->userService->update($userId, $userData, null, []);
            }
            DB::commit();

            $this->sendNoti($ratingData);

            return $rating;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($id, $ratingData)
    {
        DB::beginTransaction();
        try {
            $rating = $this->updateRating($id, $ratingData);

            if($ratingData['type'] == Constants::ratingUserType) {
                $userId = $ratingData['to_user_id'];
                $ratingCount = $this->getUserRatingCount($userId);
                $userData = $this->prepareUserData($ratingCount);
                $this->userService->update($userId, $userData, null, []);
            }
            DB::commit();

            $this->sendNoti($ratingData);

            return $rating;
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function get($id = null, $conds = null, $relation = null)
    {
        return Rating::when($id, function($query, $id){
                $query->where(Rating::id, $id);
            })
            ->when($conds, function($query, $conds){
                $query->where($conds);
            })
            ->when($relation, function($query, $relation){
                $query->with($relation);
            })
            ->first();
    }

    public function getAll($conds = null, $relation = null, $limit = null, $offset = null)
    {
        return Rating::when($conds, function($query, $conds){
                $query->when(isset($conds['from_user_id']), function($query) use($conds){
                    $query->where(Rating::fromUserId, $conds['from_user_id']);
                })->when(isset($conds['to_user_id']), function($query) use($conds){
                    $query->where(Rating::toUserId, $conds['to_user_id']);
                })->when(isset($conds['type']), function($query) use($conds){
                    $query->where(Rating::type, $conds['type']);
                });
            })
            ->when($relation, function($query, $relation){
                $query->with($relation);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->latest()->get();
    }

    ///////////////////////////////////////////////////////////////
    /// Private Functions
    ///////////////////////////////////////////////////////////////

    ///------------------------------------------------------------
    /// Data Preparation
    ///------------------------------------------------------------
    private function prepareUserData($ratingCount)
    {
        return [
            'overall_rating' => $ratingCount
        ];
    }

    private function prepareTokenConds($userId)
    {
        return [
            'user_id' => $userId,
        ];
    }

    private function prepareFcmData($ratingData)
    {
        return [
            'message' => $ratingData['title'],
            'rating' => $ratingData['rating'],
            'flag' => Constants::reviewNotiFlag,
            'review_user_id' => $ratingData['from_user_id'],
        ];
    }

    ///------------------------------------------------------------
    /// Database
    ///------------------------------------------------------------
    private function saveRating($ratingData)
    {
        $rating = new Rating();
        $rating->fill($ratingData);
        $rating->added_user_id = Auth::id();
        $rating->save();

        return $rating;
    }

    private function updateRating($id, $ratingData)
    {
        $rating = $this->get($id);
        $rating->updated_user_id = Auth::id();
        $rating->update($ratingData);

        return $rating;
    }

    ///------------------------------------------------------------
    /// Other
    ///------------------------------------------------------------
    private function getUserRatingCount($userId)
    {
        $conds = [
            'to_user_id' => $userId
        ];
        $totalRating = $this->getAll($conds);
        $ratingCount = $totalRating->count();
        $ratingSum = $totalRating->sum(Rating::rating);
        if($ratingCount > 0){
            $ratingCount = number_format((float) ($ratingSum / $ratingCount), 1, '.', '');
        }

        return $ratingCount;
    }

    private function sendNoti($ratingData)
    {
        // send noti
        $toUser = $this->userService->get($ratingData['to_user_id']);
        $fromUser = $this->userService->get($ratingData['from_user_id']);

        $tokenConds = $this->prepareTokenConds($ratingData['to_user_id']);
        $notiTokens = $this->pushNotificationTokenService->getAll(conds: $tokenConds, noPagination: Constants::yes);

        $deviceIds = $notiTokens->pluck('device_token')->toArray();
        $platformNames = $notiTokens->pluck('platform_name')->toArray();

        $data = $this->prepareFcmData($ratingData);

        foreach ($deviceIds as $deviceId) {
            $this->firebaseCloudMessagingService->sendAndroidFcm($deviceId, $data, $platformNames);
        }

        // send mail
        $message = __('rating__receive_rating_from') ." ". $fromUser->name;
        sendMail($toUser->email, $toUser->name, $ratingData['title'], null, $message);
    }
}
