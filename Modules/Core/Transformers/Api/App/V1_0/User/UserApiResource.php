<?php

namespace Modules\Core\Transformers\Api\App\V1_0\User;

use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use Modules\Core\Entities\Item;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\UserInfo;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\User\Rating;
use Modules\Core\Entities\User\BlockUser;
use Modules\Core\Entities\User\FollowUser;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Http\Facades\SystemConfigFacade;
use Modules\Core\Transformers\Api\App\V1_0\User\UserInfoApiResource;

class UserApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => checkAndGetValue($this, 'id'),
            'user_is_sys_admin' => checkAndGetValue($this, 'user_is_sys_admin'),
            'email_verified_at' => checkAndGetValue($this, 'email_verified_at'),
            'facebook_id' => checkAndGetValue($this, 'facebook_id'),
            'google_id' => checkAndGetValue($this, 'google_id'),
            'phone_id' => checkAndGetValue($this, 'phone_id'),
            'apple_id' => checkAndGetValue($this, 'apple_id'),
            'name' => checkAndGetValue($this, 'name'),
            'username' => checkAndGetValue($this, 'username'),
            'email' => checkAndGetValue($this, 'email'),
            'user_phone' => checkAndGetValue($this, 'user_phone'),
            'user_address' => checkAndGetValue($this, 'user_address'),
            'user_about_me' => checkAndGetValue($this, 'user_about_me'),
            'user_cover_photo' => checkAndGetValue($this, 'user_cover_photo'),
            'role_id' => checkAndGetValue($this, 'role_id'),
            'status' => checkAndGetValue($this, 'status'),
            'is_banned' => checkAndGetValue($this, 'is_banned'),
            'has_code' => $this->hasCode(),
            'need_verify' => $this->needVerify(),
            'hasPassword' => $this->hasPassword(),
            'overall_rating' => checkAndGetValue($this, 'overall_rating'),
            'is_show_email' => checkAndGetValue($this, 'is_show_email'),
            'is_show_phone' => checkAndGetValue($this, 'is_show_phone'),
            'is_shop_admin' => checkAndGetValue($this, 'is_shop_admin'),
            'is_city_admin' => checkAndGetValue($this, 'is_city_admin'),
            'user_lat' => checkAndGetValue($this, 'user_lat'),
            'user_lng' => checkAndGetValue($this, 'user_lng'),
            'added_date' => checkAndGetValue($this, 'added_date'),
            'added_date_timestamp' => checkAndGetValue($this, 'added_date_timestamp'),
            'verify_types' => checkAndGetValue($this, 'verify_types'),
            "remaining_post" => $this->getRemainingPostCount(),
            "post_count" => $this->getPostCountLimit(),
            "active_item_count" => $this->getActiveItemCount(),
            "rating_count" => $this->getRatingCount(),
            "rating_details" => $this->getRatingPercent(),
            "follower_count" => $this->getFollowerCount(),
            "following_count" => $this->getFollowingCount(),
            "is_followed" => $this->isFollowed($request->login_user_id),
            "is_blocked" => $this->isBlocked($request->login_user_id),
            "is_verify_blue_mark" => $this->isVerifyBlueMark(),
            "userRelation" => UserInfoApiResource::collection($this->userRelation ?? []),
            "added_date_str" => $this->getAddedDateStr(),
            "is_empty_object" => checkAndGetValue($this, 'id', "1"),

        ];
    }

    ///////////////////////////////////////////////////////////////////////
    /// Private Functions
    ///////////////////////////////////////////////////////////////////////
    private function getRatingPercent()
    {
        if(empty(checkAndGetValue($this, 'id'))){
            return "";
        }

        $totalRating = Rating::where(['to_user_id' => $this->id])->get();
        $totalRatingCount = $totalRating->count();
        $totalRatingSum = $totalRating->sum('rating');

        $ratingCounts = DB::table('psx_ratings')
            ->select('rating', DB::raw('count(*) as count'))
            ->where('to_user_id', $this->id)
            ->groupBy('rating')
            ->pluck('count', 'rating');

        // Define all possible ratings
        $allRatings = collect([1, 2, 3, 4, 5]);

        // Ensure all ratings (1-5) are included, setting missing ones to 0
        $fullRatingCounts = $allRatings->mapWithKeys(function ($rating) use ($ratingCounts) {
            return [$rating => $ratingCounts->get($rating, 0)];
        });

        $ratingStr = new \stdClass();
        $ratingStr->one_star_count = (string)$fullRatingCounts[1];
        $ratingStr->one_star_percent = $totalRatingCount == 0 ? "0" : number_format((float) ((100 / $totalRatingCount) * $fullRatingCounts[1]), 1, '.', '');
        $ratingStr->two_star_count = (string)$fullRatingCounts[2];
        $ratingStr->two_star_percent = $totalRatingCount == 0 ? "0" : number_format((float) ((100 / $totalRatingCount) * $fullRatingCounts[2]), 1, '.', '');
        $ratingStr->three_star_count = (string)$fullRatingCounts[3];
        $ratingStr->three_star_percent = $totalRatingCount == 0 ? "0" : number_format((float) ((100 / $totalRatingCount) * $fullRatingCounts[3]), 1, '.', '');
        $ratingStr->four_star_count = (string)$fullRatingCounts[4];
        $ratingStr->four_star_percent = $totalRatingCount == 0 ? "0" : number_format((float) ((100 / $totalRatingCount) * $fullRatingCounts[4]), 1, '.', '');
        $ratingStr->five_star_count = (string)$fullRatingCounts[5];
        $ratingStr->five_star_percent = $totalRatingCount == 0 ? "0" : number_format((float) ((100 / $totalRatingCount) * $fullRatingCounts[5]), 1, '.', '');
        $ratingStr->total_rating_count = (string)$totalRatingCount;
        $ratingStr->total_rating_value = $totalRatingCount == 0 ? "0" : number_format((float) ($totalRatingSum / $totalRatingCount), 1, '.', '');

        return $ratingStr;
    }

    private function getAddedDateStr()
    {
        if(empty($this->added_date)){
            return "";
        }
        return $this->added_date->diffForHumans();
    }

    private function getRatingCount()
    {
        if(empty($this->id)){
            return "0";
        }
        return (string)Rating::where(['to_user_id' => $this->id])->count();
    }

    private function hasPassword()
    {
        return empty($this->password) ? "false" : "true";
    }

    private function needVerify()
    {
        $backendSettingService = app()->make(BackendSettingServiceInterface::class);
        $backendSetting = $backendSettingService->get();
        if($backendSetting->email_verification_enabled == 1 && isset($this->code) && $this->code != '' && $this->code != null){
            return "1";
        }

        return "0";
    }

    private function hasCode()
    {
        return empty($this->code) ? "0" : "1";
    }

    private function getFollowerCount()
    {
        if(empty($this->id)){
            return "0";
        }

        return (string)FollowUser::where(['followed_user_id' => $this->id])->count();
    }

    private function getFollowingCount()
    {
        if(empty($this->id)){
            return "0";
        }

        return (string)FollowUser::where(['user_id' => $this->id])->count();
    }

    private function isBlocked($loginUserId)
    {
        if(empty($this->id)){
            return "0";
        }

        $conds = [
            'from_block_user_id' => $loginUserId,
            'to_block_user_id' => $this->id,
        ];
        $count = BlockUser::where($conds)->count();

        return $count > 0 ? "1" : "0";
    }

    private function isFollowed($loginUserId)
    {
        if(empty($this->id)){
            return "0";
        }

        $conds = [
            'user_id' => $loginUserId,
            'followed_user_id' => $this->id,
        ];
        $count = FollowUser::where($conds)->count();

        return $count > 0 ? "1" : "0";
    }

    private function isVerifyBlueMark()
    {
        if(empty($this->id)){
            return "0";
        }

        $conds = [
            'user_id' => $this->id,
            'core_keys_id' => Constants::usrIsVerifyBlueMark
        ];

        $userInfo = UserInfo::where($conds)->first();

        return $userInfo ? $userInfo->value : "0";
    }

    private function getRemainingPostCount()
    {
        if(empty($this->id)){
            return "0";
        }

        $conds = [
            'user_id' => $this->id,
            'core_keys_id' => Constants::usrRemainingPost
        ];

        $userInfo = UserInfo::where($conds)->first();

        return !empty($userInfo->value) ? $userInfo->value : "0";
    }

    private function getActiveItemCount()
    {
        if(empty($this->id)){
            return "0";
        }

        $conds = [
            'added_user_id' => $this->id,
            'status' => Constants::publish
        ];

        $count = Item::where($conds)->count();

        return $count > 0 ? (string)$count : "0";
    }

    private function getPostCountLimit()
    {
        $roleId = checkAndGetValue($this, 'role_id');
        $systemConfig = SystemConfigFacade::get();
        if($systemConfig->is_paid_app == 1 &&  $roleId == Constants::normalUserRoleId){
            return __('limited');
        }

        return __('unlimited');
    }
}
