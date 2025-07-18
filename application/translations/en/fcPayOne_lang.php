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
 
$sLangName  = "Deutsch";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = array(
'charset'                                       => 'ISO-8859-15',
'FCPO_LOCALE'                                   => 'en_US',
'FCPO_IBAN_INVALID'                             => 'Please enter a valid IBAN.',
'FCPO_BIC_INVALID'                              => 'Please enter a valid BIC.',
'FCPO_BLZ_INVALID'                              => 'Please enter a valid bank identification number.',
'FCPO_KTONR_INVALID'                            => 'Please enter a valid bank account number.',
'FCPO_ERROR'                                    => 'An error occurred: <br>',
'FCPO_PAY_ERROR_REDIRECT'                       => 'Your payment was rejected by your payment provider! Please choose another payment mean',
'FCPO_ERROR_BLOCKED'                            => 'Bankdata invalid.',
'FCPO_CC_NUMBER_INVALID'                        => 'Please enter a valid credit card number.',
'FCPO_CC_DATE_INVALID'                          => 'Please enter a valid date of expiry.',
'FCPO_CC_CVC2_INVALID'                          => 'Please enter a valid check digit.',
'FCPO_CC_CARDHOLDER'                            => "Cardholder",
'FCPO_CC_CARDHOLDER_HELPTEXT'                   => "Cardholder as printed on card",
'FCPO_CC_CARDHOLDER_INVALID'                    => "Only A-Z, ÄÖÜ,ß and - are valid",
'fcpo_so_ktonr'                                 => 'Bank account number',
'fcpo_so_blz'                                   => 'Bank identification number',
'FCPO_BANK_GER_OLD'                             => 'or pay by using your usual bank account number and bank code<br>(only supported for german accounts).',
'FCPO_MANIPULATION'                             => 'suspicion of fraudulent manipulation',
'FCPO_REMARK_APPOINTED_MISSING'                 => 'The shop did not receive the APPOINTED transactionstatus. Please check this payment carefully!',
'FCPO_THANKYOU_APPOINTED_ERROR'                 => 'An error occured during the payment-process. We will check the order and contact you if necessary.',
'FCPO_CARDSEQUENCENUMBER'                       => 'card sequence number',
'FCPO_ONLINE_UEBERWEISUNG_TYPE'                 => 'type',
'FCPO_BANKGROUPTYPE'                            => 'Bank group',
'FCPO_BANKACCOUNTHOLDER'                        => 'Account holder',
'FCPO_VOUCHER'                                  => 'voucher',
'FCPO_DISCOUNT'                                 => 'discount',
'FCPO_WRAPPING'                                 => "Gift wrapping",
'FCPO_GIFTCARD'                                 => "Greeting card",
'FCPO_SURCHARGE'                                => 'Surcharge',
'FCPO_DEDUCTION'                                => 'Deduction',
'FCPO_PAYMENTTYPE'                              => "Type of Payment:",
'FCPO_SHIPPINGCOST'                             => "Shipping cost",
    
'FCPO_BANK_COUNTRY'                             => 'Bank country',
'FCPO_BANK_IBAN'                                => 'IBAN',
'FCPO_BANK_BIC'                                 => 'BIC',
'FCPO_BANK_CODE'                                => 'Bank code',
'FCPO_BANK_ACCOUNT_NUMBER'                      => 'Account number',
'FCPO_BANK_ACCOUNT_HOLDER'                      => 'Account holder',
'FCPO_CREDITCARD'                               => "Credit card:",
'FCPO_CREDITCARD_CHOOSE' => "Please select",
'FCPO_CARD_VISA'                                => "Visa",
'FCPO_CARD_MASTERCARD'                          => "Mastercard",
'FCPO_NUMBER'                                   => "Number",
'FCPO_FIRSTNAME'                                => "Firstname",
'FCPO_LASTNAME'                                 => "Lastname",
'FCPO_BANK_ACCOUNT_HOLDER_2'                    => "Account holder",
'FCPO_IF_DEFERENT_FROM_BILLING_ADDRESS'         => "If different from Billing Address.",
'FCPO_VALID_UNTIL'                              => "Valid until",
'FCPO_YEAR'                                     => 'Year',
'FCPO_MONTH'                                    => 'Month',
'FCPO_DAY'                                      => 'Day',
'FCPO_CARD_SECURITY_CODE'                       => "CVV2 or CVC2 security code",
'FCPO_CARD_SECURITY_CODE_DESCRIPTION'           => "This check digit is printed in reverse italic on the back side of your credit card right above the signature panel.",
'FCPO_TYPE_OF_PAYMENT'                          => "Type of Payment",
'FCPO_MIN_ORDER_PRICE'                          => "Minimum order price",
'FCPO_PREVIOUS_STEP'                            => "Previous Step",
'FCPO_CONTINUE_TO_NEXT_STEP'                    => "Continue to Next Step",
'FCPO_PAYMENT_INFORMATION'                      => "Payment Information",
'FCPO_PAGE_CHECKOUT_PAYMENT_EMPTY_TEXT'         => '<p>Currently we have no shipping method set up for this country.</p> <p>We are aiming to find a possible delivery method and we will inform you as soon as possible via e-mail about the result, including further information about delivery costs.</p>',

'FCPO_EMAIL_BANK_DETAILS'                       => 'Bank details',
'FCPO_EMAIL_BANK'                               => 'Bank',
'FCPO_EMAIL_ROUTINGNUMBER'                      => 'Bank Code',
'FCPO_EMAIL_ACCOUNTNUMBER'                      => 'Account No.',
'FCPO_EMAIL_BIC'                                => 'BIC',
'FCPO_EMAIL_IBAN'                               => 'IBAN',

'FCPO_KLV_CONFIRM'                              => 'I agree to the store terms.',
'FCPO_KLV_TELEPHONENUMBER'                      => 'Phone',
'FCPO_KLV_TELEPHONENUMBER_INVALID'              => 'Please enter a valid telephone-number.',
'FCPO_KLV_BIRTHDAY'                             => 'Date of birth',
'FCPO_KLV_BIRTHDAY_INVALID'                     => 'Please enter a valid date of birth.',
'FCPO_KLV_ADDINFO'                              => 'Additional info',
'FCPO_KLV_ADDINFO_INVALID'                      => 'Field must not be empty.',
'FCPO_KLV_ADDINFO_DEL'                          => 'Additional info del. address',
'FCPO_KLV_SAL'                                  => 'Title',
'FCPO_KLV_PERSONALID'                           => 'Personal identity number',
'FCPO_KLV_PERSONALID_INVALID'                   => 'Field must not be empty.',
'FCPO_KLV_INFO_NEEDED'                          => 'Some more information is needed.',
'FCPO_KLV_CONFIRMATION_MISSING'                 => 'You have to agree to the terms of the store.',

'FCPO_KLS_CHOOSE_CAMPAIGN'                      => 'Please select a campaign',
'FCPO_KLS_CAMPAIGN_INVALID'                     => 'You have to select a campaign.',
'FCPO_KLS_NO_CAMPAIGN'                          => 'There are no installment-campaigns for your current combination of delivery-country, language and currency.<br>Please select another payment-type.',

'FCPO_ORDER_MANDATE_HEADER'                     => 'SEPA direct debit',
'FCPO_ORDER_MANDATE_INFOTEXT'                   => 'In order to debit the SEPA direct debit, we need a SEPA mandate from you.',
'FCPO_ORDER_MANDATE_CHECKBOX'                   => 'I accept the mandate<br>(electronic transmission)',
'FCPO_ORDER_MANDATE_ERROR'                      => 'You have to accept the SEPA mandate.',

'FCPO_THANKYOU_PDF_LINK'                        => 'Your SEPA-mandate as PDF',
'FCPO_MANAGEMANDATE_ERROR'                      => 'A problem occurred. Please check the data you entered or choose another payment-type.',
    
'FCPO_PAYPALEXPRESS_USER_SECURITY_ERROR'        => 'Please log in to your shop account and go through the PayPal Express checkout again. The PayPal-deliveryaddress did not match the address of your shop-account.',

'FCPO_YAPITAL_HEADER'                           => 'Payment with Yapital',
'FCPO_YAPITAL_TEXT'                             => 'There are 2 possibilities to pay with Yapital. Either you open the Yapital-app, select payment from the app-menu and scan the QR-code or you click the orange LOG IN button and log in on the next site, using your Yapital-login-data. After a successful transaction you will be redirected back to the shop.<br><br>Every transaction is handled in realtime with your Yapital-account. Your bankdata is not transmitted by Yapital, only the necessary transactiondata.<br><br>If you pay with the QR-code please <b>DON\'T</b> click on the links in the window! You will be redirected automatically.',
    
'FCPO_CC_IFRAME_HEADER'                         => 'Payment with creditcard',
'FCPO_OR'                                       => 'or',
'FCPO_PAYOLUTION_USTID'                         => 'Tax Identification Number',
'FCPO_PAYOLUTION_PHONE'                         => 'Fon',
'FCPO_PAYOLUTION_BIRTHDATE'                     => 'Birthdate',
'FCPO_PAYOLUTION_PRECHECK_FAILED'               => 'Transaction has been declined by financing-service. Please choose another payment method.',
'FCPO_PAYOLUTION_YEAR'                          => 'Year',
'FCPO_PAYOLUTION_MONTH'                         => 'Monht',
'FCPO_PAYOLUTION_DAY'                           => 'Day',
'FCPO_PAYOLUTION_PLEASE SELECT'                 => 'Please choose...',
'FCPO_PAYOLUTION_BIRTHDATE_INVALID'             => 'Your birthdate has been entered incorrect.',
'FCPO_PAYOLUTION_AGREEMENT_PART_1'              => 'I confirm transmission of my personal data, which is needed for processing %s, identity- and boni-check.<br>My',
'FCPO_PAYOLUTION_AGREEMENT_PART_1_FCPOPO_DEBITNOTE'             => 'debitnote payment',
'FCPO_PAYOLUTION_AGREEMENT_PART_1_FCPOPO_INSTALLMENT'           => 'installment payment',
'FCPO_PAYOLUTION_AGREEMENT_PART_1_FCPOPO_BILL'                  => 'bill payment',
'FCPO_PAYOLUTION_AGREEMENT_PART_2'              => 'can be cancelled by writing any time later.',
'FCPO_PAYOLUTION_AGREE'                         => 'consent',
'FCPO_PAYOLUTION_EMAIL_CLEARING'                => 'Unzer Reference code:',
'FCPO_PAYOLUTION_NOT_AGREED'                    => 'You did not agree to consent.',
'FCPO_PAYOLUTION_SEPA_NOT_AGREED'               => 'You did not grant SEPA direct debit mandate.',
'FCPO_PAYOLUTION_PHONE_MISSING' => 'Telephone number is required for this payment.',
'FCPO_PAYOLUTION_SEPA_AGREEMENT_PART_1'         => 'Herewith I give ',
'FCPO_PAYOLUTION_SEPA_AGREE'                    => 'SEPA direct debit mandate',
'FCPO_PAYOLUTION_ACCOUNTHOLDER'                 => 'Account owner',
'FCPO_PAYOLUTION_BANKDATA_INCOMPLETE'           => 'Your entered account data is not complete.',
'FCPO_PAYOLUTION_CHECK_INSTALLMENT_AVAILABILITY'=> 'Check availability',
'FCPO_PAYOLUTION_INSTALLMENT_SELECTION'         => 'Installment selection',
'FCPO_PAYOLUTION_SELECT_INSTALLMENT'            => 'Please choose your installment selection',
'FCPO_PAYOLUTION_INSTALLMENT_SUMMARY_AND_ACCOUNT'=> 'Overview and acoount information',
'FCPO_PAYOLUTION_PLEASE_CHECK_AVAILABLILITY'    => 'Please check availibility of installment options first.',
'FCPO_PAYOLUTION_INSTALLMENT_PER_MONTH'         => 'per Month',
'FCPO_PAYOLUTION_INSTALLMENT_RATES'             => 'Rates',
'FCPO_PAYOLUTION_INSTALLMENT_RATE'              => 'Rate',
'FCPO_PAYOLUTION_INSTALLMENT_MONTHLY_RATES'     => 'Monthly installments',
'FCPO_PAYOLUTION_INSTALLMENT_INTEREST_RATE'     => 'Interest',
'FCPO_PAYOLUTION_INSTALLMENT_EFF_INTEREST_RATE' => 'effective insterest',
'FCPO_PAYOLUTION_INSTALLMENT_DUE_AT'            => 'due at',
'FCPO_PAYOLUTION_INSTALLMENT_DOWNLOAD_DRAFT'    => 'Download contract draft',
'FCPO_PAYOLUTION_INSTALLMENTS_NUMBER'           => 'Amount of possible installments',
'FCPO_PAYOLUTION_INSTALLMENT_FINANCING_AMOUNT'  => 'Amount',
'FCPO_PAYOLUTION_INSTALLMENT_FINANCING_SUM'     => 'Total',
'FCPO_PAYOLUTION_INSTALLMENT_NOT_YET_SELECTED'  => 'Please choose',
'FCPO_PAYOLUTION_NO_INSTALLMENT_SELECTED'       => 'You did not choose an installment.',
'FCPO_RATEPAY_FON'                              => 'Phone',
'FCPO_RATEPAY_BIRTHDATE'                        => 'Birthday',
'FCPO_RATEPAY_USTID'                            => 'Vat ID',
'FCPO_RATEPAY_NO_USTID'                         => 'Please enter your Vat ID to place an order as a company',
'FCPO_RATEPAY_NO_SUFFICIENT_DATA'               => 'Some personal information seems to be missing. Please fill out all required fields',
'FCPO_RATEPAY_ADD_TERMS1'                       => 'With clicking on "Submit order" you agree to the ',
'FCPO_RATEPAY_ADD_TERMS2'                       => 'terms of payment of our payment partner ',
'FCPO_RATEPAY_ADD_TERMS3'                       => 'as well as to the performance of a ',
'FCPO_RATEPAY_ADD_TERMS4'                       => 'risk check by our payment partner ',
'FCPO_RATEPAY_ADD_TERMS5'                       => '',
'FCPO_AMAZON_SELECT_ADDRESS'                    => 'Choose Shipping-Address from Amazon-Addressbook',
'FCPO_AMAZON_SELECT_PAYMENT'                    => 'Choose Payment from Amazon-Wallet',
'FCPO_AMAZON_LOGOFF'                            => 'Stop AmazonPay and go back to standard checkout',
'FCPO_AMAZON_PROBLEM'                           => 'There is a problem',
'FCPO_AMAZON_NO_SHIPPING_TO_COUNTRY'            => 'There is no shipping possibility for your selected delivery country. Please click <a href="index.php?cl=user" style="color:green;">here</a> for returning to address selection.',
'FCPO_AMAZON_THANKYOU_MESSAGE'                  => 'Your transaction with Amazon Pay is currently beeing validated. Please be aware that we wil inform you shortly as needed.',
'FCPO_AMAZON_ERROR_TRANSACTION_TIMED_OUT'       => 'Sorry, your transaction with Amazon Pay was not successful. Please choose another payment method.',
'FCPO_AMAZON_ERROR_INVALID_PAYMENT_METHOD'      => 'Please choose another payment method.',
'FCPO_AMAZON_ERROR_REJECTED'                    => 'Sorry, your transaction with Amazon Pay was not successful. Please choose another payment method.',
'FCPO_AMAZON_ERROR_PROCESSING_FAILURE'          => 'Please choose another payment method.',
'FCPO_AMAZON_ERROR_BUYER_EQUALS_SELLER'         => 'Please choose another payment method.',
'FCPO_AMAZON_ERROR_PAYMENT_NOT_ALLOWED'         => 'Please choose another payment method.',
'FCPO_AMAZON_ERROR_PAYMENT_PLAN_NOT_SET'        => 'Please choose payment method.',
'FCPO_AMAZON_ERROR_SHIPPING_ADDRESS_NOT_SET'    => 'Please choose an address',
'FCPO_AMAZON_ERROR_900'                         => 'Please choose another payment method.',
'FCPO_SECINVOICE_BIRTHDATE'                     => 'Please enter your birthday',
'FCPO_SECINVOICE_BIRTHDATE_B2B'                 => 'Date of birth of the subscriber',
'FCPO_SECINVOICE_USTID'                         => 'Tax Identification Number',
'FCPO_SECINVOICE_NO_COMPANY'                    => 'Not a company? Click <a href="index.php?cl=account_user" style="color:green;">here</a> for changing your address.',
'FCPO_NOT_ADULT'                                => 'Due to your age you are not allowed to use this payment. Please select another payment method',
'FCPO_BIRTHDATE_INVALID'                        => 'Your birthdate has been entered incorrect.',
'FCPO_RATEPAY_AGREE'                            => '<p>Within the order process, we will be sending your data to Ratepay GmbH for the purpose of verifying your identity and creditworthiness as well as the performance of the contract. The <a href="//ratepay.com/legal/" title="Legal - Ratepay" target="_blank">Additional Terms and Conditions and Data Protection Notice of Ratepay GmbH</a> apply.</p>',
'FCPO_RATEPAY_NOT_AGREED'                       => 'You did not agree to send your data to Ratepay.',
'FCPO_RATEPAY_SEPA_NOT_AGREED'                  => 'You did not authorized Ratepay to collect payments from your account.',
'FCPO_RATEPAY_SEPA_AGREE'                       => '<p>I hereby authorise Ratepay GmbH to collect payments from my account by direct debit. At the same time, I authorise my bank to debit my account in accordance with the instructions from Ratepay GmbH.</p><p>Note: As part of my rights, I am entitled to a refund from my ibank under the terms and conditions of my agreement with my bank.	A refund must be claimed within 8 weeks starting from the date on which my account was debited. My rights are explained in a statement that I can obtain from my bank.</p>',
'FCPO_RATEPAY_ACCOUNTHOLDER'                    => 'Accountholder',
'FCPO_RATEPAY_MANDATE_IDENTIFICATION'           => '<p>Ratepay GmbH - Ritterstraße 12-14 - 10969 Berlin, Germany<br> Creditor ID: DE39RPY00000568463<br> Mandate Reference: WILL BE COMMUNICATED SEPARATELY</p>',
'FCPO_CC_HOSTED_ERROR_CARDTYPE'                 => 'Please select a cardtype',
'FCPO_CC_HOSTED_ERROR_CVC'                      => 'Please check CVC',
'FCPO_CC_HOSTED_ERROR_INCOMPLETE'               => 'Input incomplete',
'FCPO_EMAIL_CLEARING_SUBJECT'                   => 'Your clearingdata for ordernumber ',
'FCPO_EMAIL_CLEARING_BODY_WELCOME'              => "Hello %NAME% %SURNAME%,\nfor clearing your invoice, please use the following data:\n\n",
'FCPO_EMAIL_CLEARING_BODY_THANKYOU'             => 'Thank you, your %SHOPNAME%-Team',
'FCPO_EMAIL_USAGE'                              => 'Usage',

'FCPO_KLARNA_COMBINED_DATA_AGREEMENT'           => 'I agree, sending my personal data to Klarna GmbH for the purpose of processing the payment.',
'FCPO_KLARNA_NOT_AGREED'                        => 'You have to agree to submit your data to pay with klarna.',
'FCPO_KLARNA_NO_AUTHORIZATION'                  => 'An unexpected error occurred.',

'FCPO_BNPL_TNC_DATAPROTECTION_NOTICE'           => 'By placing this order, I agree to the <span style="color: black; font-weight: bold; text-decoration: underline"><a href="https://legal.paylater.payone.com/en/terms-of-payment.html" target="_blank" rel="noreferrer noopener">supplementary payment terms</a></span> and the performance of a risk assessment for the selected payment method. I am aware of the <span style="color: black; font-weight: bold; text-decoration: underline"><a href="https://legal.paylater.payone.com/en/data-protection-payments.html" target="_blank" rel="noreferrer noopener">supplementary data protection notice</a></span>.',
'FCPO_BNPL_FON'                                 => 'Phone',
'FCPO_BNPL_FON_B2B'                             => 'Phone number of the subscriber',
'FCPO_BNPL_IBAN'                                => 'IBAN',
'FCPO_BNPL_SECINSTALLMENT_UNAVAILABLE'          => 'Safe installment is not available for your current basket. Please choose another payment method.',
'FCPO_BNPL_SECINSTALLMENT_SELECTION'            => 'Desired installment plan',
'FCPO_BNPL_SECINSTALLMENT_OVW_TITLE'                => 'Overview',
'FCPO_BNPL_SECINSTALLMENT_OVW_NBRATES'              => 'Number of payments',
'FCPO_BNPL_SECINSTALLMENT_OVW_TOTALFINANCING'       => 'Financing amount',
'FCPO_BNPL_SECINSTALLMENT_OVW_TOTALAMOUNT'          => 'Total amount',
'FCPO_BNPL_SECINSTALLMENT_OVW_INTEREST'             => 'Interest rate',
'FCPO_BNPL_SECINSTALLMENT_OVW_EFFECTIVEINTEREST'    => 'Effective interest rate',
'FCPO_BNPL_SECINSTALLMENT_OVW_MONTHLYRATE'          => 'Monthly amount',
'FCPO_BNPL_SECINSTALLMENT_OVW_DL_CREDINFO'          => '&gt;&nbsp;Download Installment information',
'FCPO_BNPL_SECINSTALLMENT_PLAN_INVALID'             => 'Please select the desired installment plan',
'FCPO_BNPL_USTID'                                   => 'Tax Identification Number',
'FCPO_BNPL_NO_COMPANY'                              => 'Not a company? Click <a href="index.php?cl=account_user" style="color:green;">here</a> for changing your address.',

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

'PAYONE Alipay' => 'PAYONE Alipay',
'PAYONE Amazon Pay' => 'PAYONE Amazon Pay',
'PAYONE Apple Pay' => 'PAYONE Apple Pay',
'PAYONE Bancontact' => 'PAYONE Bancontact',
'PAYONE eps Überweisung' => 'PAYONE eps',
'PAYONE Gesicherter Rechnungskauf' => 'PAYONE Secure Invoice',
'PAYONE Gesicherter Rechnungskauf (neu)' => 'PAYONE Secured Invoice',
'PAYONE Gesicherter Ratenkauf' => 'PAYONE Secured Installment',
'PAYONE Gesicherte Lastschrift' => 'PAYONE Secured Direct Debit',
'PAYONE iDEAL' => 'PAYONE iDEAL',
'PAYONE Klarna Ratenkauf' => 'PAYONE Klarna Ratenkauf',
'PAYONE Klarna Rechnung' => 'PAYONE Klarna Rechnung',
'PAYONE Klarna Sofort bezahlen' => 'PAYONE Klarna Sofort bezahlen',
'PAYONE Kreditkarte' => 'PAYONE Credit Card',
'PAYONE Lastschrift' => 'PAYONE Direct Debit',
'PAYONE Nachnahme' => 'PAYONE Cash on Delivery',
'PAYONE PayPal Express' => 'PAYONE PayPal Express',
'PAYONE PayPal' => 'PAYONE PayPal',
'PAYONE PayPal Express V2' => 'PAYONE PayPal Express V2',
'PAYONE PayPal V2' => 'PAYONE PayPal V2',
'PAYONE PostFinance Card' => 'PAYONE PostFinance Card',
'PAYONE PostFinance E-Finance' => 'PAYONE PostFinance E-Finance',
'PAYONE Przelewy24' => 'PAYONE Przelewy24',
'PAYONE Ratepay Rechnungskauf' => 'PAYONE Ratepay Open Invoice',
'PAYONE Ratepay Lastschrift' => 'PAYONE Ratepay Direct Debit',
'PAYONE Ratepay Ratenkauf' => 'PAYONE Ratepay Installments',
'PAYONE Rechnungskauf' => 'PAYONE Invoice',
'PAYONE Sofort Überweisung' => 'PAYONE Sofort',
'PAYONE Unzer Lastschrift' => 'PAYONE Unzer Lastschrift',
'PAYONE Unzer Ratenkauf' => 'PAYONE Unzer Ratenkauf',
'PAYONE Unzer Rechnungskauf' => 'PAYONE Unzer Rechnungskauf',
'PAYONE Vorkasse' => 'PAYONE prepayment',
'PAYONE WeChat Pay' => 'PAYONE WeChat Pay',
'FCPO_KLARNA' => 'PAYONE Klarna Payments',

'FCPO_RATEPAY_RUNTIME_TITLE' => 'Duration',
'FCPO_RATEPAY_RUNTIME_DESCRIPTION' => 'Number of monthly installments',
'FCPO_RATEPAY_RATE_TITLE' => 'Installment amount',
'FCPO_RATEPAY_RATE_DESCRIPTION' => 'Amount of the monthly installments',
'FCPO_RATEPAY_RATE_CALCULATE' => 'Calculate rate',
'FCPO_RATEPAY_CALCULATION_INTRO_PART1' => 'In the following you can decide how you want to pay the installments. ',
'FCPO_RATEPAY_CALCULATION_INTRO_PART2' => 'Conveniently determine the number of installments and thus <b> the duration </b> of the installment payment ',
'FCPO_RATEPAY_CALCULATION_INTRO_PART3' => 'or simply determine the desired <b>monthly installment amount.</b>',

'FCPO_RATEPAY_CALCULATION_DETAILS_TITLE' => 'Personal rate calculation',
'FCPO_RATEPAY_CALCULATION_DETAILS_CODE_TRANSLATION_603' => 'The desired installment corresponds to the given conditions.',
'FCPO_RATEPAY_CALCULATION_DETAILS_CODE_TRANSLATION_671' => 'The last installment was lower than allowed. Duration and/or installment amount have been adjusted.',
'FCPO_RATEPAY_CALCULATION_DETAILS_CODE_TRANSLATION_688' => 'The installment was lower than allowed for long-term installment plans. The duration has been adjusted.',
'FCPO_RATEPAY_CALCULATION_DETAILS_CODE_TRANSLATION_689' => 'The installment was lower than allowed for short term installment plans. The duration has been adjusted.',
'FCPO_RATEPAY_CALCULATION_DETAILS_CODE_TRANSLATION_695' => 'The installment is too high for the minimum available duration. The installment amount has been reduced.',
'FCPO_RATEPAY_CALCULATION_DETAILS_CODE_TRANSLATION_696' => 'The requestes installment amount is too low. It has been increased.',
'FCPO_RATEPAY_CALCULATION_DETAILS_CODE_TRANSLATION_697' => 'No corresponding duration is available for the selected installment amount. The installment amount has been adjusted.',
'FCPO_RATEPAY_CALCULATION_DETAILS_CODE_TRANSLATION_698' => 'The installment was too low for the maximum available duration. The installment amount has been increased.',
'FCPO_RATEPAY_CALCULATION_DETAILS_CODE_TRANSLATION_699' => 'The installment is too high for the minimum available duration. The installment amount has been reduced.',
'FCPO_RATEPAY_CALCULATION_DETAILS_CODE_TRANSLATION_RATE_INCREASED' => 'The requested installment amount was not available and got increased.',
'FCPO_RATEPAY_CALCULATION_DETAILS_CODE_TRANSLATION_RATE_REDUCED' => 'The requested installment amount was not available and got reduced.',
'FCPO_RATEPAY_CALCULATION_DETAILS_EXAMPLE' => 'The rate calculation can differ from the rate plan',

'FCPO_RATEPAY_CALCULATION_DETAILS_SHOW' => 'Show Details',
'FCPO_RATEPAY_CALCULATION_DETAILS_HIDE' => 'Hide Details',

'FCPO_RATEPAY_CALCULATION_DETAILS_PRICE_LABEL' => 'Value of goods',
'FCPO_RATEPAY_CALCULATION_DETAILS_PRICE_DESC' => 'Sum of all items in your shopping cart, including shipping costs etc.',
'FCPO_RATEPAY_CALCULATION_DETAILS_SERVICE_CHARGE_LABEL' => 'Service charge',
'FCPO_RATEPAY_CALCULATION_DETAILS_SERVICE_CHARGE_DESC' => 'One-time processing fee for installments per order.',
'FCPO_RATEPAY_CALCULATION_EFFECTIVE_RATE_LABEL' => 'Effective rate',
'FCPO_RATEPAY_CALCULATION_EFFECTIVE_RATE_DESC' => 'Total cost of the loan as an annual percentage.',
'FCPO_RATEPAY_CALCULATION_DEBIT_RATE_LABEL' => 'Interest rate per month',
'FCPO_RATEPAY_CALCULATION_DEBIT_RATE_DESC' => 'Periodic percentage, applied to the loan drawn.',
'FCPO_RATEPAY_CALCULATION_INTEREST_AMOUNT_LABEL' => 'Interest amount',
'FCPO_RATEPAY_CALCULATION_INTEREST_AMOUNT_DESC' => 'Concrete interests amount',
'FCPO_RATEPAY_CALCULATION_DURATION_MONTH_LABEL' => ' monthly installments &agrave;',
'FCPO_RATEPAY_CALCULATION_DURATION_MONTH_DESC' => 'Partial amount due monthly',
'FCPO_RATEPAY_CALCULATION_LAST_RATE_LABEL' => 'plus a last installment &agrave;',
'FCPO_RATEPAY_CALCULATION_LAST_RATE_DESC' => 'Partial amount due in the last month',
'FCPO_RATEPAY_CALCULATION_TOTAL_AMOUNT_LABEL' => 'Total amount',
'FCPO_RATEPAY_CALCULATION_TOTAL_AMOUNT_DESC' => 'Sum of the amounts to be paid by the buyer from the value of the goods, contract conclusion fee and interest.',

'FCPO_RATEPAY_INSTALLMENT_TYPE_TRANSFER_TITLE' => 'Installment by bank transfer',
'FCPO_RATEPAY_INSTALLMENT_TYPE_DEBIT_TITLE' => 'Installment by debit',
'FCPO_RATEPAY_INSTALLMENT_SWITCH_TO_TRANSFER_LINK' => 'I would like to make the installment payments myself and not pay by direct debit',
'FCPO_RATEPAY_INSTALLMENT_SWITCH_TO_DEBIT_LINK' => 'I would like to conveniently pay the installments by direct debit',
);

/*
[{oxmultilang ident="GENERAL_YOUWANTTODELETE"}]
*/
