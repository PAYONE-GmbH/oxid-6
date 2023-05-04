<?php
/**
 * PAYONE OXID Connector is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PAYONE OXID Connector is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with PAYONE OXID Connector.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.payone.de
 * @copyright (C) Payone GmbH
 * @version   OXID eShop CE
 */

$sLangName = "Dutch";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = array(
    'charset' => 'ISO-8859-15',
    'FCPO_IBAN_INVALID' => 'Please enter a valid IBAN.',
    'FCPO_BIC_INVALID' => 'Please enter a valid BIC.',
    'FCPO_BLZ_INVALID' => 'Please enter a valid bank identification number.',
    'FCPO_KTONR_INVALID' => 'Please enter a valid bank account number.',
    'FCPO_ERROR' => 'An error occurred: <br>',
    'FCPO_ERROR_BLOCKED' => 'Bankdata invalid.',
    'FCPO_CC_NUMBER_INVALID' => 'Please enter a valid credit card number.',
    'FCPO_CC_DATE_INVALID' => 'Please enter a valid date of expiry.',
    'FCPO_CC_CVC2_INVALID' => 'Please enter a valid check digit.',
    'fcpo_so_ktonr' => 'bank account number',
    'fcpo_so_blz' => 'bank identification number',
    'FCPO_MANIPULATION' => 'suspicion of fraudulent manipulation',
    'FCPO_REMARK_APPOINTED_MISSING' => 'The shop did not receive the APPOINTED transactionstatus. Please check this payment carefully!',
    'FCPO_THANKYOU_APPOINTED_ERROR' => 'An error occured during the payment-process. We will check the order and contact you if necessary.',
    'FCPO_CARDSEQUENCENUMBER' => 'card sequence number',
    'FCPO_ONLINE_UEBERWEISUNG_TYPE' => 'typ',
    'FCPO_BANKGROUPTYPE' => 'bank group',
    'FCPO_BANKACCOUNTHOLDER' => 'account holder',
    'FCPO_VOUCHER' => 'voucher',
    'FCPO_DISCOUNT' => 'discount',
    'FCPO_WRAPPING' => "Gift wrapping",
    'FCPO_GIFTCARD' => "Greeting card",
    'FCPO_SURCHARGE' => 'Surcharge',
    'FCPO_DEDUCTION' => 'Deduction',
    'FCPO_PAYMENTTYPE' => "Type of Payment",
    'FCPO_SHIPPINGCOST' => "Shipping cost",

    'FCPO_BANK_COUNTRY' => 'Bank country',
    'FCPO_BANK_GER_OLD' => 'or pay as usual with your known account data <br> (only for German accounts).',
    'FCPO_BANK_IBAN' => 'IBAN',
    'FCPO_BANK_BIC' => 'BIC',
    'FCPO_BANK_CODE' => 'Bank code',
    'FCPO_PAYOLUTION_PLEASE SELECT' => 'Please select ...',
    'FCPO_BANK_ACCOUNT_NUMBER' => 'Account number',
    'FCPO_BANK_ACCOUNT_HOLDER' => 'Account holder',
    'FCPO_CREDITCARD' => "Credit card",
    'FCPO_CREDITCARD_CHOOSE' => "Please select",
    'FCPO_CARD_VISA' => "Visa",
    'FCPO_CARD_MASTERCARD' => "Mastercard",
    'FCPO_NUMBER' => "Number",
    'FCPO_FIRSTNAME' => "Firstname",
    'FCPO_LASTNAME' => "Lastname",
    'FCPO_BANK_ACCOUNT_HOLDER_2' => "Account holder",
    'FCPO_IF_DEFERENT_FROM_BILLING_ADDRESS' => "If different from Billing Address.",
    'FCPO_VALID_UNTIL' => "Valid until",
    'FCPO_CARD_SECURITY_CODE' => "CVV2 or CVC2 security code",
    'FCPO_CARD_SECURITY_CODE_DESCRIPTION' => "This check digit is printed in reverse italic on the back side of your credit card right above the signature panel.",
    'FCPO_TYPE_OF_PAYMENT' => "Type of Payment",
    'FCPO_MIN_ORDER_PRICE' => "Minimum order price",
    'FCPO_PREVIOUS_STEP' => "Previous Step",
    'FCPO_CONTINUE_TO_NEXT_STEP' => "Continue to Next Step",
    'FCPO_PAYMENT_INFORMATION' => "Payment Information",
    'FCPO_PAGE_CHECKOUT_PAYMENT_EMPTY_TEXT' => '<p>Currently we have no shipping method set up for this country.</p> <p>We are aiming to find a possible delivery method and we will inform you as soon as possible via e-mail about the result, including further information about delivery costs.</p>',

    'FCPO_EMAIL_BANK_DETAILS' => 'Bank details',
    'FCPO_EMAIL_BANK' => 'Bank',
    'FCPO_EMAIL_ROUTINGNUMBER' => 'Bank Code',
    'FCPO_EMAIL_ACCOUNTNUMBER' => 'Account No.',
    'FCPO_EMAIL_BIC' => 'BIC',
    'FCPO_EMAIL_IBAN' => 'IBAN',

    'FCPO_KLV_CONFIRM' => 'I agree to the store terms.',
    'FCPO_KLV_TELEPHONENUMBER' => 'Phone',
    'FCPO_KLV_TELEPHONENUMBER_INVALID' => 'Please enter a valid telephone-number.',
    'FCPO_KLV_BIRTHDAY' => 'Date of birth',
    'FCPO_KLV_BIRTHDAY_INVALID' => 'Please enter a valid date of birth.',
    'FCPO_KLV_ADDINFO' => 'Additional info',
    'FCPO_KLV_ADDINFO_INVALID' => 'Field must not be empty.',
    'FCPO_KLV_ADDINFO_DEL' => 'Additional info del. address',
    'FCPO_KLV_SAL' => 'Title',
    'FCPO_KLV_PERSONALID' => 'Personal identity number',
    'FCPO_KLV_PERSONALID_INVALID' => 'Field must not be empty.',
    'FCPO_KLV_INFO_NEEDED' => 'Some more information is needed.',
    'FCPO_KLV_CONFIRMATION_MISSING' => 'You have to agree to the terms of the store.',

    'FCPO_KLS_CHOOSE_CAMPAIGN' => 'Please select a campaign',
    'FCPO_KLS_CAMPAIGN_INVALID' => 'You have to select a campaign.',
    'FCPO_KLS_NO_CAMPAIGN' => 'There are no installment-campaigns for your current combination of delivery-country, language and currency.<br>Please select another payment-type.',

    'FCPO_ORDER_MANDATE_HEADER' => 'SEPA direct debit',
    'FCPO_ORDER_MANDATE_INFOTEXT' => 'In order to debit the SEPA direct debit, we need a SEPA mandate from you.',
    'FCPO_ORDER_MANDATE_CHECKBOX' => 'I accept the mandate<br>(electronic transmission)',
    'FCPO_ORDER_MANDATE_ERROR' => 'You have to accept the SEPA mandate.',

    'FCPO_THANKYOU_PDF_LINK' => 'Your SEPA-mandate as PDF',
    'FCPO_MANAGEMANDATE_ERROR' => 'A problem occurred. Please check the data you entered or choose another payment-type.',

    'FCPO_PAYPALEXPRESS_USER_SECURITY_ERROR' => 'Please log in to your shop account and go through the PayPal Express checkout again. The PayPal-deliveryaddress did not match the address of your shop-account.',

    'FCPO_CC_IFRAME_HEADER' => 'Payment with creditcard',
    'FCPO_OR' => 'or',
    'FCPO_PAYOLUTION_USTID' => 'Tax Identification Number',
    'FCPO_PAYOLUTION_PHONE' => 'Fon',
    'FCPO_PAYOLUTION_BIRTHDATE' => 'Birthdate',
    'FCPO_PAYOLUTION_PRECHECK_FAILED' => 'Transaction has been delcined by financing-service. Please achoose another payment method.',
    'FCPO_PAYOLUTION_YEAR' => 'Year',
    'FCPO_PAYOLUTION_MONTH' => 'Month',
    'FCPO_PAYOLUTION_DAY' => 'Day',
    'FCPO_PAYOLUTION_AGREEMENT_PART_1' => 'Ik aanvaard de overdracht van de benodigde gegevens voor de afwikkeling van %s en een controle van mijn identiteit en kredietwaardigheid.<br>Deze',
    'FCPO_PAYOLUTION_AGREEMENT_PART_1_FCPOPO_DEBITNOTE'             => 'de debetbetaling',
    'FCPO_PAYOLUTION_AGREEMENT_PART_1_FCPOPO_INSTALLMENT'           => 'de termijnbetaling',
    'FCPO_PAYOLUTION_AGREEMENT_PART_1_FCPOPO_BILL'                  => 'de aankoop op factuur',
    'FCPO_PAYOLUTION_AGREEMENT_PART_2' => 'kan ik op elk gewenst moment met onmiddellijke ingang herroepen.',
    'FCPO_PAYOLUTION_AGREE' => 'inwilliging',
    'FCPO_PAYOLUTION_EMAIL_CLEARING' => 'Payolution Reference code:',
    'FCPO_PAYOLUTION_NOT_AGREED' => 'You did not agree to consent.',
    'FCPO_PAYOLUTION_SEPA_NOT_AGREED' => 'You did not grant SEPA direct debit mandate.',
    'FCPO_PAYOLUTION_PHONE_MISSING' => 'Telephone number is required for this payment.',
    'FCPO_PAYOLUTION_SEPA_AGREEMENT_PART_1' => 'Herewith I give ',
    'FCPO_PAYOLUTION_SEPA_AGREE' => 'SEPA direct debit mandate',
    'FCPO_PAYOLUTION_ACCOUNTHOLDER' => 'Account owner',
    'FCPO_BANK_IBAN' => 'IBAN',
    'FCPO_BANK_BIC' => 'BIC',
    'FCPO_PAYOLUTION_BIRTHDATE_INVALID' => 'Your date of birth was not entered correctly',
    'FCPO_PAYOLUTION_BANKDATA_INCOMPLETE' => 'Your entered account data is not complete.',
    'FCPO_PAYOLUTION_CHECK_INSTALLMENT_AVAILABILITY' => 'Check availability',
    'FCPO_PAYOLUTION_INSTALLMENT_SELECTION' => 'Installment selection',
    'FCPO_PAYOLUTION_SELECT_INSTALLMENT' => 'Please choose your installment selection',
    'FCPO_PAYOLUTION_INSTALLMENT_SUMMARY_AND_ACCOUNT' => 'Overview and acoount information',
    'FCPO_PAYOLUTION_PLEASE_CHECK_AVAILABLILITY' => 'Please check availibility of installment options first.',
    'FCPO_PAYOLUTION_INSTALLMENT_PER_MONTH' => 'per Month',
    'FCPO_PAYOLUTION_INSTALLMENT_RATES' => 'Rates',
    'FCPO_PAYOLUTION_INSTALLMENT_RATE' => 'Rate',
    'FCPO_PAYOLUTION_INSTALLMENT_MONTHLY_RATES' => 'Monthly installments',
    'FCPO_PAYOLUTION_INSTALLMENT_INTEREST_RATE' => 'Interest',
    'FCPO_PAYOLUTION_INSTALLMENT_EFF_INTEREST_RATE' => 'effective insterest',
    'FCPO_PAYOLUTION_INSTALLMENT_DUE_AT' => 'due at',
    'FCPO_PAYOLUTION_INSTALLMENT_DOWNLOAD_DRAFT' => 'Download contract draft',
    'FCPO_PAYOLUTION_INSTALLMENTS_NUMBER' => 'Amount of possible installments',
    'FCPO_PAYOLUTION_INSTALLMENT_FINANCING_AMOUNT' => 'Amount',
    'FCPO_PAYOLUTION_INSTALLMENT_FINANCING_SUM' => 'Total',
    'FCPO_PAYOLUTION_INSTALLMENT_NOT_YET_SELECTED' => 'Please choose',
    'FCPO_PAYOLUTION_NO_INSTALLMENT_SELECTED' => 'You did not choose an installment.',
    'FCPO_PAYOLUTION_BIRTHDATE_INVALID' => 'Your date of birth was not entered correctly',
    'FCPO_RATEPAY_BIRTHDATE' => 'Date of birth',

    'FCPO_CC_HOSTED_ERROR_CARDTYPE'                 => 'Please select a cardtype',
    'FCPO_CC_HOSTED_ERROR_CVC'                      => 'Please check CVC',
    'FCPO_CC_HOSTED_ERROR_INCOMPLETE'               => 'Input incomplete',

    'FCPO_CONFIG_GROUP_APPLE_PAY'                      => "Apple Pay",
    'FCPO_HELP_APPLE_PAY_MERCHANT_ID'                  => "Merchant ID",
    'FCPO_APPLE_PAY_MERCHANT_ID'                       => "Merchant ID",
    'FCPO_HELP_APPLE_PAY_CERTIFICATE'                  => "The name of the certificate is taken from the text field. It can be changed to any wished name. If empty when saving uploaded file, it will by default take the file\'s initial name.<br/>The text field can also be used to name an existing certificate file on the server, without uploading a new file.",
    'FCPO_APPLE_PAY_CERTIFICATE'                       => "Merchant Identification Certificate file",
    'FCPO_APPLE_PAY_CONFIG_CERTIFICATE_MISSING'        => "The current configured certificate file doesn't exist.<br/>Apple Pay cannot be used as payment method.",
    'FCPO_HELP_APPLE_PAY_KEY'                          => "This is a multiple field. You can upload a file containing the key, or type the content of the key directly into the text area below. Priority goes to uploaded file if both options are used !<br/><br/>
    The smaller text field is used to name the destination file, or path to an existing key file on server.<br/>
    - If empty while saving uploaded file, it will take the uploaded file's initial name.<br/>
    - If empty while saving direct input, it will take the default name 'merchant_id.key'.<br/>
    - If empty while not doing any of those, it will take an empty value.",
    'FCPO_APPLE_PAY_KEY'                               => "Certificate private key",
    'FCPO_HELP_APPLE_PAY_PASSWORD'                     => "Certificate key password",
    'FCPO_APPLE_PAY_PASSWORD'                          => "Certificate key password",
    'FCPO_APPLE_PAY_CREDITCARD'                        => "Allowed credit cards to use with Apple Pay",
    'FCPO_APPLE_PAY_CREATE_SESSION_ERROR'              => "Error while establishing connection to Apple Pay service.",
    'FCPO_APPLE_PAY_CREATE_SESSION_ERROR_CARDS'        => "No valid credit cards type is allowed in the configuration. Apple Pay session cannot be initialized.",
);

/*
[{oxmultilang ident="GENERAL_YOUWANTTODELETE"}]
*/
