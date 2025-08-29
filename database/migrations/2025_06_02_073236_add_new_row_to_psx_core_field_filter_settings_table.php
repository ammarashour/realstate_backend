<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Project;
use Modules\Core\Entities\Table;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Vendor\Vendor;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $vendorTable = Table::where('name', 'Vendor')->first();
        if (!$vendorTable) {
            throw new Exception("Vendor record is missing");
        }

        $project = Project::first();
        if (!$project) {
            throw new Exception("Project record is missing");
        }

        $lastCoreFieldInserted = CoreField::where(CoreField::moduleName, Constants::vendor)->orderBy(CoreField::ordering, 'desc')->first();
        $coreField = CoreField::create([
            CoreField::moduleName => Constants::vendor,
            CoreField::fieldName => Vendor::isUnlimited,
            CoreField::dataType => 'Boolean',
            CoreField::tableId => $vendorTable->id,
            CoreField::baseModuleName => "ps-" . Constants::vendor,
            CoreField::projectId => $project->id,
            CoreField::projectName => $project->project_name,
            CoreField::isCoreField => 1,
            CoreField::isDelete => 0,
            CoreField::enable => 1,
            CoreField::mandatory => 0,
            CoreField::isShowSorting => 1,
            CoreField::isShowInFilter => 0,
            CoreField::ordering => $lastCoreFieldInserted->ordering + 1,
            CoreField::isIncludeInHideShow => 1,
            CoreField::isShow => 1,
            CoreField::permissionForEnableDisable => 0,
            CoreField::permissionForDelete => 0,
            CoreField::permissionForMandatory => 0,
            CoreField::addedDate => now(),
            CoreField::addedUserId => 1
        ]);

        $coreField->update([
            CoreField::labelName => "core_key_{$coreField->id}_name",
            CoreField::placeholder => "core_key_{$coreField->id}_placeholder"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {}
};
