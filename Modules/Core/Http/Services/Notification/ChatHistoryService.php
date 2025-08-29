<?php
namespace Modules\Core\Http\Services\Notification;

use App\Http\Contracts\Notification\ChatHistoryServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Notification\ChatHistory;

class ChatHistoryService extends PsService implements ChatHistoryServiceInterface
{

    public function __construct()
    {}

    public function save($chatHistoryData, $loginUserId)
    {
        DB::beginTransaction();

        try {
            $chatHistory = new ChatHistory();
            $chatHistory->fill($chatHistoryData);
            $chatHistory->added_user_id = $loginUserId;
            $chatHistory->save();

            DB::commit();

            return $chatHistory;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $chatHistoryData, $loginUserId)
    {
        DB::beginTransaction();

        try {
            $chatHistory = $this->get($id);
            $chatHistory->fill($chatHistoryData);
            $chatHistory->updated_user_id = $loginUserId;
            $chatHistory->update();

            DB::commit();

            return $chatHistory;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function get($id = null, $conds = null, $relation = null)
    {
        $chatHistory = ChatHistory::when($id, function ($q, $id) {
                $q->where(ChatHistory::id, $id);
            })
            ->when($conds, function ($q, $conds) {
                $q->where($conds);
            })->when($relation, function ($q, $relation) {
                $q->with($relation);
            })->first();
        return $chatHistory;
    }

    public function getAll($relation = null, $limit = null, $offset = null, $conds = null, $in_conds = null, $condsNotIn = null, $pagPerPage = null, $noPagination = null)
    {
        $chatHistories = ChatHistory::when($relation, function ($q, $relation) {
                    $q->with($relation);
                })
                ->when($limit, function ($query, $limit) {
                    $query->limit($limit);
                })
                ->when($offset, function ($query, $offset) {
                    $query->offset($offset);
                })
                ->when($conds, function ($query, $conds) {
                    $query->where($conds);
                })
                ->when($in_conds, function ($query, $in_conds) {
                    if(isset($in_conds[ChatHistory::buyerUserId]) && !empty($in_conds[ChatHistory::buyerUserId]))
                        $query->whereIn(ChatHistory::buyerUserId, $in_conds[ChatHistory::buyerUserId]);

                    if(isset($in_conds[ChatHistory::sellerUserId]) && !empty($in_conds[ChatHistory::sellerUserId]))
                        $query->whereIn(ChatHistory::sellerUserId, $in_conds[ChatHistory::sellerUserId]);

                    if (isset($in_conds[ChatHistory::itemId]) && !empty($in_conds[ChatHistory::itemId]))
                        $query->whereIn(ChatHistory::itemId, $in_conds[ChatHistory::itemId]);

                })
                ->when($condsNotIn, function ($query, $condsNotIn) {
                    if(isset($condsNotIn[ChatHistory::buyerUserId]) && !empty($condsNotIn[ChatHistory::buyerUserId]))
                        $query->whereNotIn(ChatHistory::buyerUserId, $condsNotIn[ChatHistory::buyerUserId]);

                    if(isset($condsNotIn[ChatHistory::sellerUserId]) && !empty($condsNotIn[ChatHistory::sellerUserId]))
                        $query->whereNotIn(ChatHistory::sellerUserId, $condsNotIn[ChatHistory::sellerUserId]);

                    if (isset($condsNotIn[ChatHistory::itemId]) && !empty($condsNotIn[ChatHistory::itemId]))
                        $query->whereNotIn(ChatHistory::itemId, $condsNotIn[ChatHistory::itemId]);
                })
                ->orderBy(ChatHistory::updatedDate, 'desc');

                if ($pagPerPage) {
                    return $chatHistories->paginate($pagPerPage)->onEachSide(1)->withQueryString();
                } elseif ($noPagination) {
                    return $chatHistories->get();
                }
        return $chatHistories;
    }


}
