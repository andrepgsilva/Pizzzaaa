<?php

namespace App\Finance;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money as Money;

/**
 * Handle money operations and conversions
 */
class MoneyHandler
{
    protected $price;
    protected $currency;
    protected $locale;
    
    public function __construct($price, $currency = 'EUR', $locale = 'en_US')
    {
        $this->price = $price;
        $this->currency = new Currency('EUR');
        $this->locale = $locale;
    }

    /**
     * Convert integer to money format
     *   
     * @return string
     **/
    public function format()
    {
        $money = new Money($this->price, $this->currency);
        
        // Define currencies standard
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter($this->locale, \NumberFormatter::CURRENCY);

        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }
}
