<?php

namespace App\Utils;

use App\Enums\CountryEnum;
use App\Enums\StatusEnum;
use App\Enums\ValidatorMessageEnum;
use App\Enums\DeliveryMethodEnum;
use App\Enums\PaymentMethodEnum;
use Dotenv\Validator;

class ValidatorUtil {
    
    public static function emailValidationRules() {
        return ['email' => ['required', 'string', 'email', 'regex:/^.+[@].+[.].+$/', 'max:255', 'unique:users,email']];
    }

        /////////////////////////////
       //                         //
      // General Rules           //
     //                         //
    /////////////////////////////

    public static function getFullNameValidationRules() {
        $atLeastTwoWordsRegex = 'regex:/^.*[^ ]+[ ][^ ]+.*$/';
        return ['required', 'string', 'max:255', $atLeastTwoWordsRegex];
    }

    public static function getFirstNameValidationRules() {
        return ['required', 'string', 'min:1', 'max:50'];
    }

    public static function getLastNameValidationRules() {
        return ['required', 'string', 'min:1', 'max:50'];
    }

    public static function getEmailValidationRules() {
        return ['required', 'string', 'email', 'regex:/^.+[@].+[.].+$/', 'max:255'];
    }

    public static function getPhoneNumberValidationRules() {
        return ['required', 'regex:/^[+]?[0-9]{9,13}$/'];
    }

    public static function getCountryValidationRules() {
        $countries = implode(',', CountryEnum::valueArray());
        return ['required', 'string', 'in:' . $countries];
    }

    public static function getCityValidationRules() {
        return ['required', 'string', 'min:1'];
    }

    public static function getStreetNumberValidationRules() {
        return ['required', 'string', 'min:1'];
    }

    public static function getZipCodeValidationRules() {
        return ['required', 'string', 'min:1'];
    }

    public static function getStatusValidationRules() {
        $statusArray = StatusEnum::valueArray();
        unset($statusArray[0]);

        return ['required', 'in:' . implode(',', $statusArray)];
    }

        /////////////////////////////
       //                         //
      // General Rule Messages   //
     //                         //
    /////////////////////////////

    public static function getFullNameValidationMessages($fieldName, $translation) {
        return [
            $fieldName . '.required' => 'A(z) ' . $translation . ValidatorMessageEnum::REQUIRED,
            $fieldName . '.string' => 'A(z) ' . $translation . ValidatorMessageEnum::STRING,
            $fieldName . '.max' => 'A(z) ' . $translation . ValidatorMessageEnum::STRING_MAX,
            $fieldName . '.regex' => 'A(z) ' . $translation . ' legalább két szóból állhat.'
        ];
    }

    public static function getFirstNameValidationMessages($fieldName, $translation) {
        return [
            $fieldName . '.required' => 'A(z) ' . $translation . ValidatorMessageEnum::REQUIRED,
            $fieldName . '.string' => 'A(z) ' . $translation . ValidatorMessageEnum::STRING,
            $fieldName . '.min' => 'A(z) ' . $translation . ValidatorMessageEnum::STRING_MIN,
            $fieldName . '.max' => 'A(z) ' . $translation . ValidatorMessageEnum::STRING_MAX,
        ];
    }

    public static function getLastNameValidationMessages($fieldName, $translation) {
        return [
            $fieldName . '.required' => 'A(z) ' . $translation . ValidatorMessageEnum::REQUIRED,
            $fieldName . '.string' => 'A(z) ' . $translation . ValidatorMessageEnum::STRING,
            $fieldName . '.min' => 'A(z) ' . $translation . ValidatorMessageEnum::STRING_MIN,
            $fieldName . 'max' => 'A(z) ' . $translation . ValidatorMessageEnum::STRING_MAX,
        ];
    }

    public static function getEmailValidationMessages($fieldName, $translation) {
        return [
            $fieldName . '.required' => 'A(z) ' . $translation . ValidatorMessageEnum::REQUIRED,
            $fieldName . '.string' => 'A(z) ' . $translation . ValidatorMessageEnum::STRING,
            $fieldName . '.email' => 'A(z) ' . $translation . ValidatorMessageEnum::EMAIL,
            $fieldName . '.max' => 'A(z) ' . $translation . ValidatorMessageEnum::STRING_MAX,
            $fieldName . '.unique' => 'A(z) ' . $translation . ValidatorMessageEnum::UNIQUE,
            $fieldName . '.regex' => 'A(z) ' . $translation . ValidatorMessageEnum::REGEX,
        ];
    }

    public static function getPhoneNumberValidationMessages($fieldName, $translation) {
        return [
            $fieldName . '.required' => 'A(z) ' . $translation . ValidatorMessageEnum::REQUIRED,
            $fieldName . '.regex' => 'A(z) ' . $translation . ValidatorMessageEnum::REGEX,
        ];
    }

    public static function getCountryValidationMessages($fieldName, $translation) {
        return [
            $fieldName . '.required' => 'A(z) ' . $translation . ValidatorMessageEnum::REQUIRED,
            $fieldName . '.string' => 'A(z) ' . $translation . ValidatorMessageEnum::REGEX,
            $fieldName . '.in' => 'A(z) ' . $translation . ValidatorMessageEnum::IN,
        ];
    }

    public static function getCityValidationMessages($fieldName, $translation) {
        return [
            $fieldName . '.required' => 'A(z) ' . $translation . ValidatorMessageEnum::REQUIRED,
            $fieldName . '.string' => 'A(z) ' . $translation . ValidatorMessageEnum::REGEX,
            $fieldName . '.min' => 'A(z) ' . $translation . ValidatorMessageEnum::STRING_MIN,
        ];
    }

    public static function getStreetNumberValidationMessages($fieldName, $translation) {
        return [
            $fieldName . '.required' => 'A(z) ' . $translation . ValidatorMessageEnum::REQUIRED,
            $fieldName . '.string' => 'A(z) ' . $translation . ValidatorMessageEnum::REGEX,
            $fieldName . '.min' => 'A(z) ' . $translation . ValidatorMessageEnum::STRING_MIN,
        ];
    }

    public static function getZipCodeValidationMessages($fieldName, $translation) {
        return [
            $fieldName . '.required' => 'A(z) ' . $translation . ValidatorMessageEnum::REQUIRED,
            $fieldName . '.string' => 'A(z) ' . $translation . ValidatorMessageEnum::REGEX,
            $fieldName . '.min' => 'A(z) ' . $translation . ValidatorMessageEnum::STRING_MIN,
        ];
    }

    public static function getStatusValidationMessages($fieldName, $translation) {
        return [
            $fieldName . '.required' => 'A(z) ' . $translation . ValidatorMessageEnum::REQUIRED,
            $fieldName . '.in' => 'A(z) ' . $translation . ValidatorMessageEnum::IN,
        ];
    }

        /////////////////////////////
       //                         //
      // RegisterController      //
     //                         //
    /////////////////////////////

    public static function getPasswordValidationRulesForRegistration() {
        return ['required', 'string', 'min:8', 'confirmed'];
    }

    public static function getEmailValidationRulesForRegistration() {
        $rules = ValidatorUtil::getEmailValidationRules();
        $rules[] = 'unique:users,email';
        return $rules;
    }

    public static function getPasswordValidationMessagesForRegistration($fieldName, $translation) {
        return [
            $fieldName . '.required' => 'A(z) ' . $translation . ValidatorMessageEnum::REQUIRED,
            $fieldName . '.string' => 'A(z) ' . $translation . ValidatorMessageEnum::STRING,
            $fieldName . '.min' => 'A(z) ' . $translation . ValidatorMessageEnum::STRING_MIN,
            $fieldName . '.confirmed' => 'A(z) ' . $translation . ValidatorMessageEnum::CONFIRMED,
        ];
    }

    public static function getPasswordConfirmationValidationMessagesForRegistration($fieldName, $translation) {
        return [
            $fieldName . '.required' => 'A(z) ' . $translation . ValidatorMessageEnum::REQUIRED
        ];
    }

    public static function getRegistrationValidationRules()
    {
        return [
            'name' => ValidatorUtil::getFullNameValidationRules(),
            'email' => ValidatorUtil::getEmailValidationRulesForRegistration(),
            'password' => ValidatorUtil::getPasswordValidationRulesForRegistration(),
            'password_confirmation' => ['required'],
        ];
    }

    public static function getRegistrationValidationMessages() {
        return array_merge(
            ValidatorUtil::getFullNameValidationMessages('name', 'teljes név '),
            ValidatorUtil::getEmailValidationMessages('email', 'email cím '),
            ValidatorUtil::getPasswordValidationMessagesForRegistration('password', 'jelszó '),
            ValidatorUtil::getPasswordConfirmationValidationMessagesForRegistration('password_confirmation', 'jelszó újra ')
        );
    }

        /////////////////////////////
       //                         //
      // CartController          //
     //                         //
    /////////////////////////////

    public static function getStoreValidationRulesForCart($product) {
        $stockIdsSring = implode(',', $product->stocks()->pluck('id')->toArray());
        return [
            'color-size' => ['required', 'in:' . $stockIdsSring],
            'amount' => ['required' , 'numeric', 'min:1']
        ];
    }

    public static function getStoreValidationMessagesForCart() {
        return [
            'color-size.required' => 'Válassz egy szín-méret kombinációt',
            'color-size.in' => 'A termékből nem érhető el ilyen szín-méret kombináció',
            'amount.required' => 'A darabszám' . ValidatorMessageEnum::REQUIRED,
            'amount.numeric' => 'A darabszám ' . ValidatorMessageEnum::NUMERIC,
            'amount.min' => 'A darabszám ' . ValidatorMessageEnum::NUMERIC_MIN,
        ];
    }

    public static function getUpdateValidationRulesForCart($fieldName) {
        return [
            $fieldName => ['required', 'numeric']
        ];
    }

    public static function getUpdateValidationMessagesForCart($fieldName) {
        return [
            $fieldName . '.required' => 'A mező megadása kötelező',
            $fieldName . '.numeric' => 'A mezőnek számnak kell lennie',
        ];
    }

        /////////////////////////////
       //                         //
      // CategoryController      //
     //                         //
    /////////////////////////////

    public static function getValidationRulesForCategories() {
        return [
            'name' => ['required', 'string', 'min:3', 'max:30'],
            'parent_category_id' => ['required', 'not_in:0', 'exists:categories,id'],
            'cover_image' => ['required', 'image', 'max:1024'],
        ];
    }

    public static function getValidationMessagesForCategories() {
        return [
            'name.required' => 'A kategória neve' . ValidatorMessageEnum::REQUIRED,
            'name.string' => 'A kategória neve' . ValidatorMessageEnum::STRING,
            'name.min' => 'A kategória neve' . ValidatorMessageEnum::STRING_MIN,
            'name.max' => 'A kategória neve' . ValidatorMessageEnum::STRING_MAX,
            'parent_category_id.required' => 'A kategória' . ValidatorMessageEnum::REQUIRED,
            'parent_category_id.not_in' => 'Válassz egy kategóriát',
            'parent_category_id.exists' => 'A választott kategória' . ValidatorMessageEnum::EXISTS,
            'cover_image.required' => 'Fájl feltöltése kötelező',
            'cover_image.image' => 'A feltöltött fájl' . ValidatorMessageEnum::IMAGE,
            'cover_image.max' => 'A feltöltött fájl' . ValidatorMessageEnum::MAX_SIZE,
        ];
    }

        /////////////////////////////
       //                         //
      // FavoriteController      //
     //                         //
    /////////////////////////////

        /////////////////////////////
       //                         //
      // OrderController         //
     //                         //
    /////////////////////////////

    public static function getStoreValidationRulesForOrder() {
        return ['customer_message' => ['max:300']];
    }
    
    public static function getStoreValidationMessagesForOrder() {
        return [
            'customer_message.max' => 'A mejegyzés' . ValidatorMessageEnum::STRING_MAX
        ];
    }

    public static function getUpdateValidationRulesForOrder() {
        return ['status' => ValidatorUtil::getStatusValidationRules()];
    }

    public static function getUpdateValidationMessagesForOrder() {
        return ValidatorUtil::getStatusValidationMessages('status', 'státusz');
    }
    
    public static function getUpdateDataValidationRules($differentBillingAddress, $deliveryMethodIsPersonal) {
        $rules = null;
        if($deliveryMethodIsPersonal) {
            $rules = ValidatorUtil::getUpdateDataValidationDeliveryPersonalMethodRules();
        } else {
            $rules = ValidatorUtil::getUpdateDataValidationDeliveryRules();
        }

        if($differentBillingAddress) {
            $rules = array_merge($rules, ValidatorUtil::updateDataValidationBillingRules());
        }
        return $rules;
    }

    public static function getUpdateMethodsValidationRulesForOrder() {
        return [
            'delivery-methods' => 'required|string|in:' . implode(',', DeliveryMethodEnum::valueArray()),
            'payment-methods' => 'required|string|in:' . implode(',', PaymentMethodEnum::valueArray())
        ];
    }

    public static function getUpdateMethodsValidationMessagesForOrder() {
        return [
            'delivery-methods.required' => 'A szállítási mód kötelező',
            'delivery-methods.string' => 'A szállítási mód helytelen',
            'delivery-methods.in' => 'A szállítási mód nem létezik',
            'payment-methods.required' => 'A fizetési mód kötelező',
            'payment-methods.string' => 'A fizetési mód helytelen',
            'payment-methods.in' => 'A fizetési mód nem létezik',
        ];
    }

    public static function getUpdateDataValidationMessages() {
        return array_merge(
            ValidatorUtil::getFirstNameValidationMessages('delivery_first_name', 'keresztnév '),
            ValidatorUtil::getLastNameValidationMessages('delivery_last_name', 'vezetéknév '),
            ValidatorUtil::getPhoneNumberValidationMessages('phone_number', 'telefonszám '),
            ValidatorUtil::getCountryValidationMessages('delivery_country', 'ország '),
            ValidatorUtil::getCityValidationMessages('delivery_city', 'város '),
            ValidatorUtil::getStreetNumberValidationMessages('delivery_street_number', 'utca és házszám '),
            ValidatorUtil::getZipCodeValidationMessages('delivery_zip_code', 'irányítószám '),
            ValidatorUtil::getFirstNameValidationMessages('billing_first_name', 'keresztnév '),
            ValidatorUtil::getLastNameValidationMessages('billing_last_name', 'vezetéknév '),
            ValidatorUtil::getCountryValidationMessages('billing_country', 'ország '),
            ValidatorUtil::getCityValidationMessages('billing_city', 'város '),
            ValidatorUtil::getStreetNumberValidationMessages('billing_street_number', 'utca és házszám '),
            ValidatorUtil::getZipCodeValidationMessages('billing_zip_code', 'irányítószám '),
        );
    }

    public static function getUpdateDataValidationDeliveryPersonalMethodRules() {
        return [
            'delivery_first_name' => ValidatorUtil::getFirstNameValidationRules(),
            'delivery_last_name' => ValidatorUtil::getLastNameValidationRules(),
            'phone_number' => ValidatorUtil::getPhoneNumberValidationRules(),
        ];
    }

    public static function getUpdateDataValidationDeliveryRules() {
        return [
            'delivery_first_name' => ValidatorUtil::getFirstNameValidationRules(),
            'delivery_last_name' => ValidatorUtil::getLastNameValidationRules(),
            'phone_number' => ValidatorUtil::getPhoneNumberValidationRules(),
            'delivery_country' => ValidatorUtil::getCountryValidationRules(),
            'delivery_city' => ValidatorUtil::getCityValidationRules(),
            'delivery_street_number' => ValidatorUtil::getStreetNumberValidationRules(),
            'delivery_zip_code' => ValidatorUtil::getZipCodeValidationRules(),
        ];
    }

    public static function updateDataValidationBillingRules() {
        return [
            'billing_first_name' => ValidatorUtil::getFirstNameValidationRules(),
            'billing_last_name' => ValidatorUtil::getLastNameValidationRules(),
            'billing_country' => ValidatorUtil::getCountryValidationRules(),
            'billing_city' => ValidatorUtil::getCityValidationRules(),
            'billing_street_number' => ValidatorUtil::getStreetNumberValidationRules(),
            'billing_zip_code' => ValidatorUtil::getZipCodeValidationRules(),
        ];
    }

        /////////////////////////////
       //                         //
      // ProductController       //
     //                         //
    /////////////////////////////

    /**
     * @param int $stockFieldsCounter
     * @param int $imagesCounter
     * 
     * @return array
     */
    public static function getStoreValidationRulesForProduct($stockFieldsCounter, $imagesCounter) {
        return array_merge(
            ValidatorUtil::getBaseValidationRulesForProduct($stockFieldsCounter, $imagesCounter),
            ['image_fields_counter' => ['required', 'numeric', 'min:1']]
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param int $stockFieldsCounter
     * @param int $imagesCounter
     * @param int $storedImagesCounter
     * 
     * @return array
     */
    public static function getUpdateValidationRulesForProduct($request, $stockFieldsCounter, $imagesCounter, $storedImagesCounter) {
        return array_merge(
            ValidatorUtil::getBaseValidationRulesForProduct($stockFieldsCounter, $imagesCounter), 
            ValidatorUtil::getRemoveImagesValidationRules($request, $storedImagesCounter)
        );
    }
    
    /**
     * @param int $stockFieldsCounter
     * @param int $imagesCounter
     * 
     * @return array
     */
    public static function getStoreValidationMessagesForProduct($stockFieldsCounter, $imagesCounter) {
        return array_merge(
            ValidatorUtil::getBaseValidationMessagesForProduct($stockFieldsCounter, $imagesCounter),
            ['image_fields_counter.required' => 'Legalább egy képnek kell lennie.',
                'image_fields_counter.min' => 'Legalább egy képnek kell lennie.',
                'image_fields_counter.numeric' => 'A mezőnek számnak kell lennie.',]
        );
    }

    public static function getUpdateValidationMessagesForProduct($request, $stockFieldsCounter, $imagesCounter, $storedImagesCounter) {
        return array_merge(
            ValidatorUtil::getBaseValidationMessagesForProduct($stockFieldsCounter, $imagesCounter),
            ValidatorUtil::getRemoveImagesValidationMessages($request, $storedImagesCounter)
        );
    }

    /**
     * @param int $stockFieldsCounter
     * @param int $imagesCounter
     * 
     * @return array
     */
    public static function getBaseValidationRulesForProduct($stockFieldsCounter, $imagesCounter) {
        $rules = [
            'name' => ['required', 'string', 'max:30'],
            'description' => ['required', 'string', 'max:1000', 'min:3'],
            'price' => ['required', 'numeric', 'min:1'],
            'category_id' => ['required', 'not_in:0', 'exists:categories,id'],
            'stock_fields_counter' => ['required', 'numeric', 'min:1'],
            'image_fields_counter' => ['required', 'numeric'],
        ];

        return array_merge(
            $rules, 
            ValidatorUtil::getStockFieldsValidationRules($stockFieldsCounter),
            ValidatorUtil::getImagesValidationRules($imagesCounter)
        );
    }

    /**
     * @param int $stockFieldsCounter
     * @param int $imagesCounter
     * 
     * @return array
     */
    public static function getBaseValidationMessagesForProduct($stockFieldsCounter, $imagesCounter) {
        $messages = [
            'name.string' => 'A név' . ValidatorMessageEnum::STRING,
            'name.required' => 'A név' . ValidatorMessageEnum::REQUIRED,
            'name.max' => 'A név' . ValidatorMessageEnum::STRING_MAX,
            'description.string' => 'A leírás' . ValidatorMessageEnum::STRING,
            'description.required' => 'A leírás' . ValidatorMessageEnum::REQUIRED,
            'description.max' => 'A leírás' . ValidatorMessageEnum::STRING_MAX,
            'description.min' => 'A leírás' . ValidatorMessageEnum::STRING_MIN,
            'price.required' => 'Az ár' . ValidatorMessageEnum::REQUIRED,
            'price.numeric' => 'Az ár' . ValidatorMessageEnum::NUMERIC,
            'price.min' => 'Az ár' . ValidatorMessageEnum::NUMERIC_MIN,
            'category_id.required' => 'A kategória' . ValidatorMessageEnum::REQUIRED,
            'category_id.not_in' => 'Válassz egy kategóriát',
            'category_id.exists' => 'A választott kategória' . ValidatorMessageEnum::EXISTS,
            'stock_fields_counter.required' => '',
            'stock_fields_counter.numerc' => '',
            'stock_fields_counter.min' => 'Legalább egy készletnek kell lennie.',
            'image_fields_counter.required' => '',
            'image_fields_counter.numerc' => '',
            'image_fields_counter.min' => 'Legalább egy képnek kell lennie.',
        ];

        return array_merge(
            $messages, 
            ValidatorUtil::getStockFieldsValidationMessages($stockFieldsCounter),
            ValidatorUtil::getImagesValidationMessages($imagesCounter)
        );  
    }

    /**
     * Get validation rules for stock fields if counter is number.
     * 
     * @param mixed $stockFieldsCounter
     */
    private static function getStockFieldsValidationRules($stockFieldsCounter) {
        $rules = [];
        if(ctype_digit($stockFieldsCounter)) {
            for($i = 0; $i < $stockFieldsCounter; ++$i) {
                $rules = array_merge($rules, ValidatorUtil::getValidationRulesForStockField($i));
            }
        }

        return $rules;
    }

    /**
     * Get validation rules for image checkboxes if counter is number.
     * 
     * @param mixed $imagesCounter
     */
    private static function getImagesValidationRules($imagesCounter) {
        $rules = [];
        if(ctype_digit($imagesCounter)) {
            for($i = 0; $i < $imagesCounter; ++$i) {
                $fieldName = 'image_' . $i;
                $rules = array_merge($rules, ValidatorUtil::getValidationRulesForImage($fieldName));
            }
        }

        return $rules;
    }

    /**
     * Get validation rules for image removing checkboxes if request has field.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param mixed $storedImagesCounter
     */
    public static function getRemoveImagesValidationRules($request, $storedImagesCounter)
    {
        $rules = [];
        for($i = 0; $i < $storedImagesCounter; ++$i) {
            $fieldName = 'removeStoredProductImageCheckBox_' . $i;
            if($request->has($fieldName)) {
                $rules = array_merge($rules, ValidatorUtil::getValidationRulesForRemoveImage($fieldName));
            }
        }

        return $rules;
    }

     /**
     * Get validation messages for stock fields if counter is number.
     * 
     * @param mixed $stockFieldsCounter
     */
    private static function getStockFieldsValidationMessages($stockFieldsCounter) {
        $messages = [];
        if(ctype_digit($stockFieldsCounter)) {
            for($i = 0; $i < $stockFieldsCounter; ++$i) {
                $messages = array_merge($messages, ValidatorUtil::getValidationMessagesForStockField($i));
            }
        }

        return $messages;
    }

    /**
     * Get validation messages for image checkboxes if counter is number.
     * 
     * @param mixed $imagesCounter
     */
    private static function getImagesValidationMessages($imagesCounter) {
        $messages = [];
        if(ctype_digit($imagesCounter)) {
            for($i = 0; $i < $imagesCounter; ++$i) {
                $fieldName = 'image_' . $i;
                $messages = array_merge($messages, ValidatorUtil::getValidationMessagesForImage($fieldName));
            }
        }

        return $messages;
    }

    /**
     * Get validation messages for image removing checkboxes if request has them.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param mixed $storedImagesCounter
     */
    public static function getRemoveImagesValidationMessages($request, $storedImagesCounter)
    {
        $messages = [];
        for($i = 0; $i < $storedImagesCounter; ++$i) {
            $fieldName = 'removeStoredProductImageCheckBox_' . $i;
            if($request->has($fieldName)) {
                $messages = ValidatorUtil::getValidationMessagesForRemoveImage($fieldName);
            }
        }

        return $messages;
    }

    /**
     * Get validation rules for one stock field with given index.
     * 
     * @param int $index
     * @return array
     */
    private static function getValidationRulesForStockField($index) {
        return [
            'stock_color_' . $index => ['required', 'string'],
            'stock_size_' . $index  => ['required', 'string'],
            'stock_in_stock_' . $index  => ['required', 'numeric', 'min:1'],
        ];
    }

    /**
     * Get validation rules for one image with given fieldname.
     * 
     * @param string $fieldName
     * @return array
     */
    public static function getValidationRulesForImage($fieldName) {
        return [$fieldName => ['required', 'image', 'max:1024']];
    }

    /**
     * Get validation rules for remove stored image checkbox.
     * 
     * @param int $fieldName
     * @return array
     */
    public static function getValidationRulesForRemoveImage($fieldName) {
        return [$fieldName => ['accepted']];
    }

    /**
     * Get validation messages for one stock field with given index.
     * 
     * @param int $index
     * @return array
     */
    private static function getValidationMessagesForStockField($index) {
        return [
            'stock_color_' . $index . '.required' => 'A szín' . ValidatorMessageEnum::REQUIRED,
            'stock_color_' . $index . '.string' => 'A szín' . ValidatorMessageEnum::STRING,
            'stock_size_' . $index . '.required' => 'A méret' . ValidatorMessageEnum::REQUIRED,
            'stock_size_' . $index . '.string' => 'A méret' . ValidatorMessageEnum::STRING,
            'stock_in_stock_' . $index . '.required' => 'A darabszám' . ValidatorMessageEnum::REQUIRED,
            'stock_in_stock_' . $index . '.numeric' => 'A darabszám' . ValidatorMessageEnum::NUMERIC,
            'stock_in_stock_' . $index . '.min' => 'A darabszám' . ValidatorMessageEnum::NUMERIC_MIN
        ];
    }

    /**
     * Get validation messages for one image with given index.
     * 
     * @param int $index
     * @return array
     */
    public static function getValidationMessagesForImage($fieldName) {
        return [
            $fieldName . '.required' => 'Fájl feltöltése kötelező',
            $fieldName . '.image' =>  'A feltöltött fájl' . ValidatorMessageEnum::IMAGE,
            $fieldName . '.max' => 'A feltöltött fájl' . ValidatorMessageEnum::MAX_SIZE,
        ];
    }

    /**
     * Get validation messages for remove stored image checkbox.
     * 
     * @param int $fieldName
     * @return array
     */
    public static function getValidationMessagesForRemoveImage($fieldName)
    {
        return [
            $fieldName . 'accepted' => 'A mezőt el kell fogadni.'
        ];
    }

    /**
     * Determine if color and size fields are unique together.
     * 
     * @param array $validated
     * @param int $stockFieldsCounter
     * @return boolean
     */
    public static function areStockFieldsUniques($validated, int $stockFieldsCounter) {
        for($i = 0; $i < $stockFieldsCounter; ++$i) {
            for($j = 0; $j < $stockFieldsCounter; ++$j) {
                if($i != $j && $validated['stock_color_' . $i] == $validated['stock_color_' . $j] 
                        && $validated['stock_size_' . $i] == $validated['stock_size_' . $j]) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Determines if product has at least one image after adding and deleting images.
     * 
     * @param int $imagesCounter
     * @param \App\Models\Product $product
     * @param  \Illuminate\Http\Request  $request
     * @return boolean
     */
    public static function afterOperationsHasImage($imagesCounter, $product, $request) {
        $productImagesCount = count($product->images);
        $deleteImagesCounter = 0;

        for ($i=0; $i < $productImagesCount; ++$i) { 
            $checkBoxInd = 'removeStoredProductImageCheckBox_' . $i;
            if($request->has($checkBoxInd)) {
                ++$deleteImagesCounter;
            }
        }

        return $imagesCounter + $productImagesCount - $deleteImagesCounter > 0;
    }
    
        /////////////////////////////
       //                         //
      // UserController          //
     //                         //
    /////////////////////////////

    /**
     * Get validation rules for changing password.
     */
    public static function getChangePasswordValidationRulesForUser() {
        return [
            'old_password' => ['required', 'password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
            'new_password_confirmation' => ['required']
        ];
    }

    /**
     * Get validation messages for changing password.
     */
    public static function getChangePasswordValidationMessagesForUser($translations) {
        return [
            'old_password.required' => 'A(z) ' . $translations['old'] . ValidatorMessageEnum::REQUIRED,
            'old_password.password' => ValidatorMessageEnum::PASSWORD,
            'new_password.required' => 'A(z) ' .  $translations['new'] . ValidatorMessageEnum::REQUIRED,
            'new_password.string' => 'A(z) ' .  $translations['new'] . ValidatorMessageEnum::STRING,
            'new_password.confirmed' => 'A(z) ' .  $translations['new'] . ValidatorMessageEnum::CONFIRMED,
            'new_password.min' => 'A(z) ' .  $translations['new'] . ValidatorMessageEnum::STRING_MIN,
            'new_password_confirmation.required' => 'A(z) ' .  $translations['new_confirmation'] . ValidatorMessageEnum::REQUIRED,
        ];
    }

}