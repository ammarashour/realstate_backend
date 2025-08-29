<?php

namespace Modules\Core\Imports;

use App\Http\Contracts\Localization\MobileLanguageStringServiceInterface;
use Illuminate\Support\Collection;
use Modules\Core\Entities\Localization\MobileLanguage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Modules\Core\Http\Facades\MobileLanguageFacade;

class MobileLanguageStringImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function __construct(
        protected MobileLanguageStringServiceInterface $mobileLanguageStringService,
        protected MobileLanguage $targetLanguage,
    ) {}

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        

        $toLanguages = MobileLanguageFacade::getAll();

        // may be this skip will not necessary in the future
        // so, skip the first row if the key is 'key_key' which is the header of the file
        $firstRow = $rows->first();
        $rows = $rows->skip(isset($firstRow['key']) && $firstRow['key'] == 'key_key' ? 1 : 0);

        if(count($rows->toArray()) > 0){

            // save or update the rows using mobileLanguageStringService
            $this->mobileLanguageStringService->importLanguageStrings(
                toLanguages: $toLanguages,
                langStrings: $rows->toArray(),
                targetLanguage: $this->targetLanguage
            );

            $languages = MobileLanguageFacade::getAll();
            foreach($languages as $language) {
                $this->mobileLanguageStringService->updateCode($language->id);
            }
        }
    }

    /**
     * Validation
     */
    public function rules(): array
    {
        return [
            'key' => 'required',
            "value" => "required",
        ];
    }
}
