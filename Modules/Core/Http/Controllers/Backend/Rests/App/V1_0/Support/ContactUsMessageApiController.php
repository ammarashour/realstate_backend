<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Support;


use Illuminate\Http\Request;
use App\Http\Controllers\PsApiController;
use App\Http\Contracts\Support\ContactUsMessageServiceInterface;
use Modules\Core\Constants\Constants;
use Modules\Core\Transformers\Api\App\V1_0\Support\ContactUsMessageApiResource;
use Illuminate\Contracts\Translation\Translator;
use Modules\Core\Http\Requests\Support\StoreContactUsMessageRequest;
use Modules\Core\Http\Services\AboutService;
use Modules\Core\Transformers\Api\App\V1_0\Support\GetInTouchApiResource as SupportGetInTouchApiResource;

class ContactUsMessageApiController extends PsApiController
{
    public function __construct(protected Translator $translator,
    protected ContactUsMessageServiceInterface $contactService,
    protected AboutService $aboutService,)
    {
        parent::__construct();
    }
    //saving contact us message from api
    public function contact(StoreContactUsMessageRequest $request)
    {
        $validatedData = $request->validated();

        $contact = $this->contactService->save($validatedData);

        $contactNameStr = __('contact_us__api_name');
        $contactEmailStr = __('contact_us__api_email');
        $contactPhoneStr = __('contact_us__api_phone');
        $contactMessageStr = __('contact_us__api_message');

        // start send email to admin
        $msg = $contact->contact_message;

        if (!sendContactMail($contact->contact_name, $contact->contact_email, $contact->contact_phone, $msg, $contactNameStr, $contactEmailStr, $contactPhoneStr, $contactMessageStr)) {
            return  ['error' => __('contact__email_not_sent', [], $request->language_symbol), 'status' =>  Constants::internalServerErrorStatusCode];
        }

        $contactUs = new ContactUsMessageApiResource($contact);
        return responseDataApi($contactUs);
    }

    public function getInTouchForContact(Request $request)
    {
        $contact = new SupportGetInTouchApiResource($this->aboutService->getAbout());
        return responseDataApi($contact);
    }
}
