<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryData extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_method', 'payment_method', 
        'delivery_cost', 'payment_cost', 'phone_number', 
        'email', 'delivery_address_id', 'billing_address_id', 
    ];

    public function deliveryAddress() {
        return $this->belongsTo(Address::class, 'delivery_address_id');
    }

    public function billingAddress() {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function addressesEqual() {
        if(is_null($this->deliveryAddress) || is_null($this->billingAddress)) {
            return true;
        }

        return $this->deliveryAddress->first_name == $this->billingAddress->first_name
            && $this->deliveryAddress->last_name == $this->billingAddress->last_name
            && $this->deliveryAddress->country == $this->billingAddress->country
            && $this->deliveryAddress->city == $this->billingAddress->city
            && $this->deliveryAddress->street_number == $this->billingAddress->street_number
            && $this->deliveryAddress->zip_code == $this->billingAddress->zip_code;
    }

    public function setDeliveryAddress($data) {
        $addressData = [
            'first_name' => $data['delivery_first_name'],
            'last_name' => $data['delivery_last_name'],
            'country' => $data['delivery_country'],
            'city' => $data['delivery_city'],
            'street_number' => $data['delivery_street_number'],
            'zip_code' => $data['delivery_zip_code'],
        ];

        if(is_null($this->deliveryAddress)) {
            $address = new Address($addressData);
            $address->save();
            $this->delivery_address_id = $address->id;
        } else {
            $this->deliveryAddress->update($addressData);
        }
    }
    
    /**
     * Set billing address according to checkbox checked or not.
     * 
     * @param boolean $differentBillingAddress
     * @param array $data
     * @return void
     */
    public function setBillingAddress($differentBillingAddress, $data) {
        if($differentBillingAddress) {
            if(is_null($this->billing_address_id)) {
                $address = new Address($this->getBillingAddressDataFromData($data));
                $address->save();
                $this->billing_address_id = $address->id;
            } else {
                $this->billingAddress->update($this->getBillingAddressDataFromData($data));
            }
        } else {
            if(is_null($this->billing_address_id)) {
                $address = new Address($this->getDeliveryAddressDataFromData($data));
                $address->save();
                $this->billing_address_id = $address->id;
            } else {
                $this->billingAddress->update($this->getDeliveryAddressDataFromData($data));
            }
        }
    }

    /**
     * Get delivery address data array from data.
     * @param array $data
     * @return array
     */
    public function getDeliveryAddressDataFromData(array $data) {
        return $this->getSpecifiedPrefixAddressDataFromData($data, 'delivery');
    }

    /**
     * Get billing address data array from data.
     * @param array $data
     * @return array
     */
    public function getBillingAddressDataFromData(array $data) {
        return $this->getSpecifiedPrefixAddressDataFromData($data, 'billing');
    }

    /**
     * Get address data array from data with given prefix.
     * @param array $data
     * @param string $prefix
     * @return array
     */
    private function getSpecifiedPrefixAddressDataFromData(array $data, string $prefix) {
        return [
            'first_name' => $data[$prefix . '_first_name'],
            'last_name' => $data[$prefix . '_last_name'],
            'country' => $data[$prefix . '_country'],
            'city' => $data[$prefix . '_city'],
            'street_number' => $data[$prefix . '_street_number'],
            'zip_code' => $data[$prefix . '_zip_code'],
        ];
    }
}
