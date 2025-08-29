<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Role;
use Modules\Core\Entities\Authorization\RolePermission;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('psx_roles', function (Blueprint $table) {
            $table->after("status", function ($table){
                $table->tinyInteger("can_access_admin_panel")->default(0);
            });
        });

        $role = Role::where("id", 1)->first();
        try{
            if(!empty($role)) {
                $role->can_access_admin_panel = 1;
                $role->update();

                $user = User::where("id", 1)->first();
                $user->role_id = 1;
                $user->update();

                $rolePermissions = RolePermission::where("role_id", 2)->get();
                $rolePermissionIds = $rolePermissions->pluck("id");
                RolePermission::destroy($rolePermissionIds);
            }
        }catch(Exception $_) {}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_roles', function (Blueprint $table) {
            $table->dropColumn(["can_access_admin_panel"]);
        });
    }
};
