<?php

namespace Modules\Core\Http\Services;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\CoreResetCode;

class ResetCodeService extends PsService
{
    public function create($request)
    {
        DB::beginTransaction();
        try {
            $resetCode = new CoreResetCode();

            if (isset($request->user_id) && !empty($request->user_id))
                $resetCode->user_id = $request->user_id;

            if (isset($request->code) && !empty($request->code))
                $resetCode->code = $request->code;

            $resetCode->added_user_id = Auth::user()->id;
            $resetCode->save();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
        $roles = CoreResetCode::latest()->get();
        return $roles;
    }


    public function update($id, $request)
    {

        DB::beginTransaction();
        try {
            $resetCode = $this->getResetCode($id);

            if(isset($request->user_id) && !empty($request->user_id))
                $resetCode->user_id = $request->user_id;

            if (isset($request->code) && !empty($request->code))
                $resetCode->code = $request->code;

            $resetCode->updated_user_id = Auth::user()->id;
            $resetCode->update();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
        $roles = CoreResetCode::latest()->get();
        return $roles;
    }

    public function getResetCode($id = null, $relation = null)
    {
        $resetCode = CoreResetCode::when($id, function ($q, $id) {
                            $q->where($this->subCatIdCol, $id);
                        })
                        ->when($relation, function ($q, $relation) {
                            $q->with($relation);
                        })
                        ->first();
        return $resetCode;
    }
}
