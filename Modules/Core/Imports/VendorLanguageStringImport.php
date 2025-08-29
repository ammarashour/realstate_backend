<?php

namespace Modules\Core\Imports;

use App\Config\ps_constant;
use App\Enums\Language\JsonGenerationOption;
use App\Http\Contracts\Localization\VendorLanguageStringServiceInterface;
use Illuminate\Support\Collection;
use Modules\Core\Entities\Localization\Language;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Modules\Core\Entities\Localization\VendorLanguageString;
use Modules\Core\Http\Facades\LanguageFacade;

class VendorLanguageStringImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    
    public function __construct(
        protected VendorLanguageStringServiceInterface $vendorLanguageStringService,
        protected Language $targetLanguage,
        protected JsonGenerationOption $jsonGenerationOption = JsonGenerationOption::NO_GENERATE
    ) {}


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        $toLanguages = LanguageFacade::getAll();

        // may be this skip will not necessary in the future
        // so, skip the first row if the key is 'key_key' which is the header of the file
        $firstRow = $rows->first();
        $rows = $rows->skip(isset($firstRow['key']) && $firstRow['key'] == 'key_key' ? 1 : 0);

        if(count($rows->toArray()) > 0){

            // save or update the rows using beLanguageStringService
            $keys = $this->vendorLanguageStringService->importLanguageStrings(
                toLanguages: $toLanguages,
                langStrings: $rows->toArray(),
                targetLanguage: $this->targetLanguage
            );

            // generate json files
            switch ($this->jsonGenerationOption) {
                case JsonGenerationOption::NO_GENERATE:
                    break;
                case JsonGenerationOption::TARGET_FILE_ONLY:
                    $this->vendorLanguageStringService->generateJsonFilesWithLanguageKeys(
                        $keys, 
                        [$this->targetLanguage]
                    );
                    break;
                case JsonGenerationOption::ALL_LANGUAGE_FILES:
                    
                    $this->vendorLanguageStringService->generateJsonFilesWithLanguageKeys(
                        $keys, 
                        $toLanguages
                    );
                    break;
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
