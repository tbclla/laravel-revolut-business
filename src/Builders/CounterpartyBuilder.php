<?php

namespace tbclla\Revolut\Builders;

class CounterpartyBuilder extends Builder
{
    /**
     * The profile type of a Revolut counterparty
     *
     * @var string
     */
    public $profile_type;

    /**
     * The name of a Revolut counterparty
     *
     * @var string
     */
    public $name;
    
    /**
     * The phone number of a counterparty
     *
     * @var string
     */
    public $phone;

    /**
     * The email address of a counterparty
     *
     * @var string
     */
    public $email;

    /**
     * The first and last name of an external counterparty
     *
     * @var array
     */
    public $individual_name;

    /**
     * The company name of an external counterparty
     *
     * @var string
     */
    public $company_name;

    /**
     * The bank country of an external counterparty
     *
     * @var string
     */
    public $bank_country;

    /**
     * The currency of an external counterparty's account
     *
     * @var string
     */
    public $currency;

    /**
     * The address of an external counterparty
     *
     * @var array
     */
    public $address;

    /**
     * The account number of an external counterparty
     *
     * @var string
     */
    public $account_number;

    /**
     * The routing number of an external counterparty
     *
     * @var string
     */
    public $routing_number;

    /**
     * The sort code of an external counterparty
     *
     * @var string
     */
    public $sort_code;

    /**
     * The International Bank Account Number (IBAN) of an external counterparty
     *
     * @var string
     */
    public $iban;

    /**
     * The Business Identifier Code (BIC) of an external counterparty
     *
     * @var string
     */
    public $bic;

    /**
     * The 'Clave Bancaria Estandarizada' (CLABE) for an external counterparty
     *
     * @var string
     */
    public $clabe;

    /**
     * Create a 'personal' Revolut counterparty
     *
     * @param string $name The full name of the party
     * @param string $phone The phone number of the party in international format, starting with '+'
     * @return self
     */
    public function personal(string $name, string $phone)
    {
        return $this->profileType('personal')
                    ->name($name)
                    ->phone($phone);
    }

    /**
     * Create a 'business' Revolut counterparty
     *
     * @param string $email The email address of the party
     * @return self
     */
    public function business(string $email)
    {
        return $this->profileType('business')->email($email);
    }


    /**
     * Set the profile type of a Revolut counterparty
     *
     * @param string $type
     * @return self
     */
    public function profileType(string $type)
    {
        return $this->setAttribute('profile_type', $type);
    }

    /**
     * Set the name of a 'personal' Revolut counterparty
     *
     * @param string $name
     * @return self
     */
    public function name(string $name)
    {
        return $this->setAttribute('name', $name);
    }

    /**
     * Set the phone number of a 'personal' Revolut counterparty
     *
     * @param string $phone
     * @return self
     */
    public function phone(string $phone)
    {
        return $this->setAttribute('phone', $phone);
    }

    /**
     * Set the email address of a 'business' Revolut counterparty
     *
     * @param string $email
     * @return self
     */
    public function email(string $email)
    {
        return $this->setAttribute('email', $email);
    }

    /**
     * Set the first and last name of an external Revolut counterparty
     *
     * @param string $first The first name
     * @param string $last The last name
     * @return self
     */
    public function individualName(string $first, string $last)
    {
        return $this->setAttribute('individual_name', [
            'first_name' => $first,
            'last_name' => $last,
        ]);
    }

    /**
     * Set the name of the company of an external Revolut counterparty
     *
     * @param string $name The company name
     * @return self
     */
    public function companyName(string $name)
    {
        return $this->setAttribute('company_name', $name);
    }

    /**
     * Set the bank country of an external Revolut counterparty
     *
     * @param string $country The country in 2-letter ISO format
     * @return self
     */
    public function bankCountry(string $country)
    {
        return $this->setAttribute('bank_country', $country);
    }

    /**
     * Set the currency for an external Revolut counterparty's account
     *
     * @param string $currency The currency in 3-leltter ISO format
     * @return self
     */
    public function currency(string $currency)
    {
        return $this->setAttribute('currency', $currency);
    }

    /**
     * Set the first line of the address for an external Revolut counterparty
     *
     * @param string $streetLine1
     * @return self
     */
    public function streetLine1(string $streetLine1)
    {
        return $this->address(['street_line_1' => $streetLine1]);
    }

    /**
     * Set the second line of the address for an external Revolut counterparty
     *
     * @param string $streetLine1
     * @return self
     */
    public function streetLine2(string $streetLine2)
    {
        return $this->address(['street_line_2' => $streetLine2]);
    }

    /**
     * Set the region of an external Revolut counterparty
     *
     * @param string $region
     * @return self
     */
    public function region(string $region)
    {
        return $this->address(['region' => $region]);
    }

    /**
     * Set the city of an external Revolut counterparty
     *
     * @param string $city
     * @return self
     */
    public function city(string $city)
    {
        return $this->address(['city' => $city]);
    }

    /**
     * Set the country of an external Revolut counterparty
     *
     * @param string $country The country in 2-letter ISO format
     * @return self
     */
    public function country(string $country)
    {
        return $this->address(['country' => $country]);
    }

    /**
     * Set the postcode of an external Revolut counterparty
     *
     * @param string $postcode
     * @return self
     */
    public function postcode(string $postcode)
    {
        return $this->address(['postcode' => $postcode]);
    }

    /**
     * Set the address of an external Revolut counterparty
     *
     * @param array $data
     * @return self
     */
    public function address(array $data)
    {
        return $this->setAttribute('address', array_merge($this->address ?? [], $data));
    }

    /**
     * Set the account number of an external Revolut counterparty
     * Required for GBP, USD accounts
     *
     * @param string $accountNumber
     * @return self
     */
    public function accountNumber(string $accountNumber)
    {
        return $this->setAttribute('account_no', $accountNumber);
    }

    /**
     * Set the routing number of an external Revolut counterparty
     * Required for USD accounts
     *
     * @param string $routingNumber
     * @return self
     */
    public function routingNumber(string $routingNumber)
    {
        return $this->setAttribute('routing_number', $routingNumber);
    }

    /**
     * Set the sort code of an external Revolut counterparty
     * Required for USD accounts
     *
     * @param string $sortCode
     * @return self
     */
    public function sortCode(string $sortCode)
    {
        return $this->setAttribute('sort_code', $sortCode);
    }

    /**
     * Set the IBAN number of an external Revolut counterparty
     * Required for IBAN countries
     *
     * @param string $iban
     * @return self
     */
    public function iban(string $iban)
    {
        return $this->setAttribute('iban', $iban);
    }

    /**
     * Set the BIC/SWIFT code of an external Revolut counterparty
     * Required for SWIFT & SWIFT MX
     *
     * @param string $bic
     * @return self
     */
    public function bic(string $bic)
    {
        return $this->setAttribute('bic', $bic);
    }

    /**
     * Set the CLABE number of an external Revolut counterparty
     * Required for SWIFT MX
     *
     * @param string $clabe
     * @return self
     */
    public function clabe(string $clabe)
    {
        return $this->setAttribute('clabe', $clabe);
    }
}
