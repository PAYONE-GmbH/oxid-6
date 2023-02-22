function fcpoGetSelectedPaymentMethod() {
    var oForm = fcpoGetPaymentForm();
    if(oForm && oForm.paymentid) {
        if(oForm.paymentid.length) {
            for(var i = 0;i < oForm.paymentid.length; i++) {
                if(oForm.paymentid[i].checked == true) {
                    return oForm.paymentid[i].value;
                }
            }
        } else {
            return oForm.paymentid.value;
        }
    }
    return false;
}

function fcpoGetPaymentForm() {
    if(document.order) {
        if(document.order[0].nodeName != 'FORM' && document.order.paymentid) {
            return document.order;
        } else {
            for(var i = 0; i < document.order.length; i++) {
                if(document.order[i].paymentid) {
                    return document.order[i];
                }
            }
        }
    }
    return false;
}

function fcpoGetOperationMode(sType) {
    var sSelectedPaymentOperationMode = 'fcpo_mode_' + fcpoGetSelectedPaymentMethod();
    if(sType != '') {
        sSelectedPaymentOperationMode += '_' + sType;
    }
    var oForm = fcpoGetPaymentForm();
    console.log(sSelectedPaymentOperationMode);
    return oForm[sSelectedPaymentOperationMode].value;
}

function fcCheckType(element) {
    if(fcpoGetCreditcardType() == 'U') {
        document.getElementById('fcpo_kkcsn_row').style.display = 'table-row';
    } else {
        document.getElementById('fcpo_kkcsn_row').style.display = 'none';
    }
}

function fcCheckDebitCountry() {
    var oForm = fcpoGetPaymentForm();
    if(fcpoGetElvCountry() == 'DE') {
        if(document.getElementById('fcpo_elv_ktonr_info')) {
            document.getElementById('fcpo_elv_ktonr_info').style.display = '';
        }
        if(document.getElementById('fcpo_elv_ktonr')) {
            document.getElementById('fcpo_elv_ktonr').style.display = '';
        }
        if(document.getElementById('fcpo_elv_blz')) {
            document.getElementById('fcpo_elv_blz').style.display = '';
        }
    } else {
        if(document.getElementById('fcpo_elv_ktonr_info')) {
            document.getElementById('fcpo_elv_ktonr_info').style.display = 'none';
        }
        if(document.getElementById('fcpo_elv_ktonr')) {
            document.getElementById('fcpo_elv_ktonr').style.display = 'none';
            oForm['dynvalue[fcpo_elv_ktonr]'].value = '';
        }
        if(document.getElementById('fcpo_elv_blz')) {
            document.getElementById('fcpo_elv_blz').style.display = 'none';
            oForm['dynvalue[fcpo_elv_blz]'].value = '';
        }
    }
    fcHandleDebitInputs();
}

function fcpoResetErrorContainers() {
    if(document.getElementById('fcpo_cc_number_invalid')) {
        document.getElementById('fcpo_cc_number_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_cc_date_invalid')) {
        document.getElementById('fcpo_cc_date_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_cc_cvc2_invalid')) {
        document.getElementById('fcpo_cc_cvc2_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_cc_cardholder_invalid')) {
        document.getElementById('fcpo_cc_cardholder_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_cc_error')) {
        document.getElementById('fcpo_cc_error').style.display = '';
    }
    if(document.getElementById('fcpo_cc_error_content')) {
        document.getElementById('fcpo_cc_error_content').innerHTML = '';
    }
    if(document.getElementById('fcpo_elv_error_blocked')) {
        document.getElementById('fcpo_elv_error_blocked').style.display = '';
    }
    if(document.getElementById('fcpo_elv_iban_invalid')) {
        document.getElementById('fcpo_elv_iban_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_elv_bic_invalid')) {
        document.getElementById('fcpo_elv_bic_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_elv_blz_invalid')) {
        document.getElementById('fcpo_elv_blz_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_elv_ktonr_invalid')) {
        document.getElementById('fcpo_elv_ktonr_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_elv_error')) {
        document.getElementById('fcpo_elv_error').style.display = '';
    }
    if(document.getElementById('fcpo_elv_error_content')) {
        document.getElementById('fcpo_elv_error_content').innerHTML = '';
    }
    if(document.getElementById('fcpo_ou_iban_invalid')) {
        document.getElementById('fcpo_ou_iban_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_ou_bic_invalid')) {
        document.getElementById('fcpo_ou_bic_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_ou_blz_invalid')) {
        document.getElementById('fcpo_ou_blz_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_ou_ktonr_invalid')) {
        document.getElementById('fcpo_ou_ktonr_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_ou_error')) {
        document.getElementById('fcpo_ou_error').style.display = '';
    }
    if(document.getElementById('fcpo_ou_error_content')) {
        document.getElementById('fcpo_ou_error_content').innerHTML = '';
    }
    if(document.getElementById('fcpo_klv_fon_invalid')) {
        document.getElementById('fcpo_klv_fon_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_klv_birthday_invalid')) {
        document.getElementById('fcpo_klv_birthday_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_klv_addinfo_invalid')) {
        document.getElementById('fcpo_klv_addinfo_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_klv_del_addinfo_invalid')) {
        document.getElementById('fcpo_klv_del_addinfo_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_klv_personalid_invalid')) {
        document.getElementById('fcpo_klv_personalid_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_klv_confirmation_missing')) {
        document.getElementById('fcpo_klv_confirmation_missing').style.display = '';
    }
    if(document.getElementById('fcpo_kls_fon_invalid')) {
        document.getElementById('fcpo_kls_fon_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_kls_birthday_invalid')) {
        document.getElementById('fcpo_kls_birthday_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_kls_addinfo_invalid')) {
        document.getElementById('fcpo_kls_addinfo_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_kls_del_addinfo_invalid')) {
        document.getElementById('fcpo_kls_del_addinfo_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_kls_personalid_invalid')) {
        document.getElementById('fcpo_kls_personalid_invalid').style.display = '';
    }
    if(document.getElementById('fcpo_kls_confirmation_missing')) {
        document.getElementById('fcpo_kls_confirmation_missing').style.display = '';
    }
    if(document.getElementById('fcpopl_secinstallment_iban_invalid')) {
        document.getElementById('fcpopl_secinstallment_iban_invalid').style.display = '';
    }
    if(document.getElementById('fcpopl_secdebitnote_iban_invalid')) {
        document.getElementById('fcpopl_secdebitnote_iban_invalid').style.display = '';
    }
}

function fcpoGetCreditcardType() {
    var oForm = fcpoGetPaymentForm();
    if(oForm["dynvalue[fcpo_kktype]"].nodeName == 'INPUT') {
        sCreditcardType = oForm["dynvalue[fcpo_kktype]"].value;
    } else {
        sCreditcardType = oForm["dynvalue[fcpo_kktype]"].options[oForm["dynvalue[fcpo_kktype]"].selectedIndex].value;
    }
    return sCreditcardType;
}

function fcpoGetCardExpireDate() {
    var oForm = fcpoGetPaymentForm();
    if(oForm["dynvalue[fcpo_kkyear]"].nodeName == 'INPUT') {
        sDate = oForm["dynvalue[fcpo_kkyear]"].value.substr(2,2) + oForm["dynvalue[fcpo_kkmonth]"].value;
    } else {
        sDate = oForm["dynvalue[fcpo_kkyear]"].options[oForm["dynvalue[fcpo_kkyear]"].selectedIndex].innerHTML.substr(2,2) + oForm["dynvalue[fcpo_kkmonth]"].options[oForm["dynvalue[fcpo_kkmonth]"].selectedIndex].innerHTML;
    }
    return sDate;
}

function fcpoStartCCRequest() {
    fcpoResetErrorContainers();
    var oForm = fcpoGetPaymentForm();
    oForm["dynvalue[fcpo_kknumber]"].value = fcpoGetCleanedNumber(oForm["dynvalue[fcpo_kknumber]"].value);
    if(oForm["dynvalue[fcpo_kknumber]"].value == '') {
        document.getElementById('fcpo_cc_number_invalid').style.display = 'block';
        return false;
    }

    oForm["dynvalue[fcpo_kkpruef]"].value = fcpoGetCleanedNumber(oForm["dynvalue[fcpo_kkpruef]"].value);
    if(oForm["dynvalue[fcpo_kkpruef]"].value == '' || oForm["dynvalue[fcpo_kkpruef]"].value.length < 3) {
        document.getElementById('fcpo_cc_cvc2_invalid').style.display = 'block';
        return false;
    }

    var sKKType = fcpoGetCreditcardType();

    var sMode = fcpoGetOperationMode(sKKType);

    var data = {
        mid : oForm.fcpo_mid.value,
        portalid : oForm.fcpo_portalid.value,
        mode : sMode,
        request : 'creditcardcheck',
        responsetype : 'JSON',
        hash : oForm["fcpo_hashcc_" + sKKType].value,
        encoding : oForm.fcpo_encoding.value,
        aid : oForm.fcpo_aid.value,
        cardpan : oForm["dynvalue[fcpo_kknumber]"].value,
        cardtype : sKKType,
        cardexpiredate : fcpoGetCardExpireDate(),
        cardcvc2 : oForm["dynvalue[fcpo_kkpruef]"].value,
        storecarddata : 'yes',
        language : oForm.fcpo_tpllang.value,
        integrator_name : 'oxid',
        integrator_version : oForm.fcpo_integratorver.value,
        solution_name : 'fatchip',
        solution_version : oForm.fcpo_integratorextver.value

    };
    if(sKKType == 'U') {
        data.cardsequencenumber = oForm["dynvalue[fcpo_kkcsn]"].value;
    }
    var options = {
        return_type : 'object',
        callback_function_name : 'fcpoProcessPayoneResponseCC'
    };

    var request = new PayoneRequest(data, options);
    request.checkAndStore();
    return false;
}

function fcpoValidateCardholder(e) {
    var error = false;
    var cardholder = document.getElementById('fcpo_cc_cardholder').value;
    var cardholderLabel = document.getElementById('fcpo_cc_cardholder_label');
    var cardholderReg = new RegExp(/^[A-Za-z \-äöüÄÖÜß]{1,50}$/);
    if (cardholderReg.test(cardholder)) {
        document.getElementById('fcpo_cc_cardholder_invalid').style.display = '';
        cardholderLabel.classList.remove("text-danger");
        cardholderLabel.classList.remove("cardholder-error");
    } else {
        error = true;
        document.getElementById('fcpo_cc_cardholder_invalid').style.display = 'block';
        cardholderLabel.classList.add("text-danger");
        cardholderLabel.classList.add("cardholder-error");
    }
    return error;
}

function fcpoGetCleanedNumber(dirtyNumber) {
    var cleanedNumber = '';
    var tmpChar;
    for (i = 0; i < dirtyNumber.length; i++) {
        tmpChar = dirtyNumber.charAt(i);
        if (tmpChar != ' ' && !isNaN(tmpChar)) {
            cleanedNumber = cleanedNumber + tmpChar;
        }
    }
    return cleanedNumber;
}

function fcpoGetCleanedNumberIBAN(sDirtyNumber) {
    var sCleanedNumber = '';
    var sTmpChar;
    for (i = 0; i < sDirtyNumber.length; i++) {
        sTmpChar = sDirtyNumber.charAt(i);
        if (sTmpChar != ' ' && (!isNaN(sTmpChar) || /^[A-Za-z]/.test(sTmpChar))) {
            if(/^[a-z]/.test(sTmpChar)) {
                sTmpChar = sTmpChar.toUpperCase();
            }
            sCleanedNumber = sCleanedNumber + sTmpChar;
        }
    }
    return sCleanedNumber;
}

function fcpoGetElvCountry() {
    var oForm = fcpoGetPaymentForm();
    var sElvCountry = 'DE';
    if (oForm["dynvalue[fcpo_elv_country]"].length > 0) {
        if(oForm["dynvalue[fcpo_elv_country]"].nodeName == 'INPUT') {
            sElvCountry = oForm["dynvalue[fcpo_elv_country]"].value;
        } else {
            sElvCountry = oForm["dynvalue[fcpo_elv_country]"].options[oForm["dynvalue[fcpo_elv_country]"].selectedIndex].value;
        }
    }
    return sElvCountry;
}

function fcpoValidateBNPLIban(method) {
    fcpoResetErrorContainers();
    var oForm = fcpoGetPaymentForm();

    if(oForm['dynvalue[' + method + '_iban]']) {
        oForm['dynvalue[' + method + '_iban]'].value = fcpoGetCleanedNumberIBAN(oForm['dynvalue[' + method + '_iban]'].value);
    }

    if(oForm['dynvalue[' + method + '_iban]'].value == '') {
        document.getElementById(method + '_iban_invalid').style.display = 'block';
        return false;
    }

    return true;
}

function fcpoStartELVRequest() {
    fcpoResetErrorContainers();
    var oForm = fcpoGetPaymentForm();

    if(oForm['dynvalue[fcpo_elv_blz]']) {
        oForm['dynvalue[fcpo_elv_blz]'].value = fcpoGetCleanedNumber(oForm['dynvalue[fcpo_elv_blz]'].value);
    }
    if(oForm['dynvalue[fcpo_elv_ktonr]']) {
        oForm['dynvalue[fcpo_elv_ktonr]'].value = fcpoGetCleanedNumber(oForm['dynvalue[fcpo_elv_ktonr]'].value);
    }
    if(oForm['dynvalue[fcpo_elv_iban]']) {
        oForm['dynvalue[fcpo_elv_iban]'].value = fcpoGetCleanedNumberIBAN(oForm['dynvalue[fcpo_elv_iban]'].value);
    }
    if(oForm['dynvalue[fcpo_elv_bic]']) {
        oForm['dynvalue[fcpo_elv_bic]'].value = fcpoGetCleanedNumberIBAN(oForm['dynvalue[fcpo_elv_bic]'].value);
    }

    if(oForm['dynvalue[fcpo_payolution_iban]']) {
        oForm['dynvalue[fcpo_payolution_iban]'].value = fcpoGetCleanedNumberIBAN(oForm['dynvalue[fcpo_payolution_iban]'].value);
    }
    if(oForm['dynvalue[fcpo_payolution_bic]']) {
        oForm['dynvalue[fcpo_payolution_bic]'].value = fcpoGetCleanedNumberIBAN(oForm['dynvalue[fcpo_payolution_bic]'].value);
    }

    if(oForm['dynvalue[fcpo_elv_iban]'].value == '' && (!oForm['dynvalue[fcpo_elv_bic]'] || oForm['dynvalue[fcpo_elv_bic]'].value == '') && (!oForm['dynvalue[fcpo_elv_blz]'] || oForm['dynvalue[fcpo_elv_blz]'].value == '') && (!oForm['dynvalue[fcpo_elv_ktonr]'] || oForm['dynvalue[fcpo_elv_ktonr]'].value == '')) {
        document.getElementById('fcpo_elv_iban_invalid').style.display = 'block';
        return false;
    }

    if(oForm['dynvalue[fcpo_payolution_iban]'] && oForm['dynvalue[fcpo_payolution_bic]']) {
        if (oForm['dynvalue[fcpo_payolution_iban]'].value == '' && oForm['dynvalue[fcpo_payolution_bic]'].value == '') {
            document.getElementById('fcpo_payolution_iban_invalid').style.display = 'block';
            return false;
        }
    }

    var blIsGermany = false;
    if(oForm['dynvalue[fcpo_elv_blz]'] && oForm['dynvalue[fcpo_elv_ktonr]'] && fcpoGetElvCountry() == 'DE') {
        blIsGermany = true;
    }

    if(blIsGermany == true && oForm['dynvalue[fcpo_elv_iban]'].value == '') {
        if(oForm['dynvalue[fcpo_elv_ktonr]'].value == '' || oForm['dynvalue[fcpo_elv_ktonr]'].value.length > 10) {
            document.getElementById('fcpo_elv_ktonr_invalid').style.display = 'block';
            return false;
        }

        if(oForm['dynvalue[fcpo_elv_blz]'].value == '' || oForm['dynvalue[fcpo_elv_blz]'].value.length != 8) {
            document.getElementById('fcpo_elv_blz_invalid').style.display = 'block';
            return false;
        }
    }
    else {
        if(oForm['dynvalue[fcpo_elv_iban]'].value == '' || oForm['dynvalue[fcpo_elv_iban]'].value.length > 34) {
            document.getElementById('fcpo_elv_iban_invalid').style.display = 'block';
            return false;
        }
        if(oForm['dynvalue[fcpo_elv_bic]'] && (oForm['dynvalue[fcpo_elv_bic]'].value == '' || oForm['dynvalue[fcpo_elv_bic]'].value.length > 11)) {
            document.getElementById('fcpo_elv_bic_invalid').style.display = 'block';
            return false;
        }
    }

    if(oForm.fcpo_checktype && oForm.fcpo_checktype.value == '-1') {
        oForm.submit();
        return false;
    }

    var sMode = fcpoGetOperationMode('');
    var data = {
        mid : oForm.fcpo_mid.value,
        portalid : oForm.fcpo_portalid.value,
        mode : sMode,
        request : 'bankaccountcheck',
        responsetype : 'JSON',
        hash : oForm.fcpo_hashelvWith.value,
        encoding : oForm.fcpo_encoding.value,
        aid : oForm.fcpo_aid.value,
        checktype : oForm.fcpo_checktype.value,
        language : oForm.fcpo_tpllang.value,
        integrator_name : 'oxid',
        integrator_version : oForm.fcpo_integratorver.value,
        solution_name : 'fatchip',
        solution_version : oForm.fcpo_integratorextver.value,
    };

    if(oForm['dynvalue[fcpo_elv_iban]'].value != '' && (!oForm['dynvalue[fcpo_elv_bic]'] || oForm['dynvalue[fcpo_elv_bic]'].value != '')) {
        data.iban = oForm['dynvalue[fcpo_elv_iban]'].value;
        if(oForm['dynvalue[fcpo_elv_bic]']) data.bic = oForm['dynvalue[fcpo_elv_bic]'].value;
    } else {
        data.bankcountry = fcpoGetElvCountry();
        data.bankaccount = oForm['dynvalue[fcpo_elv_ktonr]'].value;
        data.bankcode = oForm['dynvalue[fcpo_elv_blz]'].value;
    }

    var options = {
        return_type : 'object',
        callback_function_name : 'fcpoProcessPayoneResponseELV'
    };

    var request = new PayoneRequest(data, options);
    request.checkAndStore();

    return false;
}

function fcCheckPaymentSelection() {
    var sCheckedValue = fcpoGetSelectedPaymentMethod();
    if(sCheckedValue != false) {
        var oForm = fcpoGetPaymentForm();
        if(sCheckedValue == 'fcpocreditcard' && oForm.fcpo_cc_type.value == 'ajax') {
            return fcpoStartCCRequest();
        } else if(sCheckedValue == 'fcpodebitnote') {
            return fcpoStartELVRequest(true);
        } else if(sCheckedValue == 'fcpopl_secinstallment' || sCheckedValue == 'fcpopl_secdebitnote') {
            return fcpoValidateBNPLIban(sCheckedValue);
        }
    }
    return true;
}

function fcpoProcessPayoneResponseELV(response) {
    if(response.get('status') != 'VALID') {
        if(response.get('errorcode') == '1083') {
            document.getElementById('fcpo_elv_ktonr_invalid').style.display = 'block';
        } else if(response.get('errorcode') == '1084' || response.get('errorcode') == '884') {
            document.getElementById('fcpo_elv_blz_invalid').style.display = 'block';
        } else if(response.get('status') == 'BLOCKED') {
            document.getElementById('fcpo_elv_error_blocked').style.display = 'block';
        } else {
            document.getElementById('fcpo_elv_error_content').innerHTML = '"'+response.get('customermessage')+'"';
            document.getElementById('fcpo_elv_error').style.display = 'block';
        }
    } else {
        var oForm = fcpoGetPaymentForm();
        oForm.submit();
    }
}

function fcpoProcessPayoneResponseCC(response) {
    var cardholderError = fcpoValidateCardholder();
    if (cardholderError) {
        return false;
    }
    if(response.get('status') == 'VALID') {
        var oForm = fcpoGetPaymentForm();
        oForm["dynvalue[fcpo_pseudocardpan]"].value = response.get('pseudocardpan');
        oForm["dynvalue[fcpo_ccmode]"].value = fcpoGetOperationMode(fcpoGetCreditcardType());
        oForm["dynvalue[fcpo_kknumber]"].value = response.get('truncatedcardpan');
        oForm["dynvalue[fcpo_kkpruef]"].value = 'xxx';
        oForm.submit();
    } else if(response.get('status') != 'VALID') {
        if(response.get('errorcode') == '1078' || response.get('errorcode') == '877') {
            document.getElementById('fcpo_cc_number_invalid').style.display = 'block';
        } else if(response.get('errorcode') == '1079') {
            document.getElementById('fcpo_cc_cvc2_invalid').style.display = 'block';
        } else if(response.get('errorcode') == '33') {
            document.getElementById('fcpo_cc_date_invalid').style.display = 'block';
        } else {
            document.getElementById('fcpo_cc_error_content').innerHTML = '"'+response.get('customermessage')+'"';
            document.getElementById('fcpo_cc_error').style.display = 'block';
        }
    }
}

function fcHandleDebitInputs(sDebitBICMandatory) {
    if (typeof(sDebitBICMandatory) == undefined) {
        sDebitBICMandatory = 'true';
    }
    fcHandleDebitInputsTypeIban();
    fcHandleDebitInputsTypeBlz();
}
function fcEnableDebitInputsTypeIban() {
    var oForm = fcpoGetPaymentForm();
    if (oForm['dynvalue[fcpo_elv_iban]'] && oForm['dynvalue[fcpo_elv_bic]'] ) {
        oForm['dynvalue[fcpo_elv_iban]'].disabled = false;
        oForm['dynvalue[fcpo_elv_iban]'].style.backgroundColor = "";
        oForm['dynvalue[fcpo_elv_bic]'].disabled = false;
        oForm['dynvalue[fcpo_elv_bic]'].style.backgroundColor = "";
    }
}
function fcEnableDebitInputsTypeBlz() {
    var oForm = fcpoGetPaymentForm();
    if (oForm['dynvalue[fcpo_elv_ktonr]'] && oForm['dynvalue[fcpo_elv_blz]'] ) {
        oForm['dynvalue[fcpo_elv_ktonr]'].disabled = false;
        oForm['dynvalue[fcpo_elv_ktonr]'].style.backgroundColor = "";
        oForm['dynvalue[fcpo_elv_blz]'].disabled = false;
        oForm['dynvalue[fcpo_elv_blz]'].style.backgroundColor = "";
    }
}
function fcDisableDebitInputsTypeIban() {
    var oForm = fcpoGetPaymentForm();
    if (oForm['dynvalue[fcpo_elv_iban]'] && oForm['dynvalue[fcpo_elv_bic]'] ) {
        oForm['dynvalue[fcpo_elv_iban]'].disabled = true;
        oForm['dynvalue[fcpo_elv_iban]'].style.backgroundColor = "#EEE";
        oForm['dynvalue[fcpo_elv_bic]'].disabled = true;
        oForm['dynvalue[fcpo_elv_bic]'].style.backgroundColor = "#EEE";
    }
}
function fcDisableDebitInputsTypeBlz() {
    var oForm = fcpoGetPaymentForm();
    if (oForm['dynvalue[fcpo_elv_ktonr]'] && oForm['dynvalue[fcpo_elv_blz]'] ) {
        oForm['dynvalue[fcpo_elv_ktonr]'].disabled = true;
        oForm['dynvalue[fcpo_elv_ktonr]'].style.backgroundColor = "#EEE";
        oForm['dynvalue[fcpo_elv_blz]'].disabled = true;
        oForm['dynvalue[fcpo_elv_blz]'].style.backgroundColor = "#EEE";
    }
}
function fcHandleDebitInputsTypeIban() {
    var oForm = fcpoGetPaymentForm();
    if((oForm['dynvalue[fcpo_elv_bic]'] && oForm['dynvalue[fcpo_elv_iban]'])) {
        if(fcpoGetElvCountry() == 'DE'
            && (oForm['dynvalue[fcpo_elv_iban]'].value != '' || oForm['dynvalue[fcpo_elv_bic]'].value != '' )
        ) {
            fcEnableDebitInputsTypeIban();
            fcDisableDebitInputsTypeBlz();
        } else {
            fcEnableDebitInputsTypeIban();
        }
    }
}

function fcHandleDebitInputsTypeBlz() {
    var oForm = fcpoGetPaymentForm();
    if((oForm['dynvalue[fcpo_elv_ktonr]'] && oForm['dynvalue[fcpo_elv_blz]'])) {
        if(fcpoGetElvCountry() == 'DE'
            && (oForm['dynvalue[fcpo_elv_ktonr]'].value != '' || oForm['dynvalue[fcpo_elv_blz]'].value != '')
        ) {
            fcEnableDebitInputsTypeBlz();
            fcDisableDebitInputsTypeIban();
        } else {
            fcEnableDebitInputsTypeBlz();
        }
    }
}

function fcpoHandleMandateCheckbox(oCheckbox) {
    if(document.getElementById('fcpoMandateCheckboxTop')) {
        document.getElementById('fcpoMandateCheckboxTop').value = oCheckbox.checked;
    } else {
        if(document.getElementById('orderConfirmAgbTop')) {
            var oInput = document.createElement("input");
            oInput.setAttribute("type", "hidden");
            oInput.setAttribute("name", "fcpoMandateCheckbox");
            oInput.setAttribute("id", "fcpoMandateCheckboxTop");
            oInput.setAttribute("value", oCheckbox.checked);
            document.getElementById("orderConfirmAgbTop").appendChild(oInput);
        }
    }
    if(document.getElementById('fcpoMandateCheckboxBottom')) {
        document.getElementById('fcpoMandateCheckboxBottom').value = oCheckbox.checked;
    } else {
        if(document.getElementById('orderConfirmAgbBottom')) {
            var oInput = document.createElement("input");
            oInput.setAttribute("type", "hidden");
            oInput.setAttribute("name", "fcpoMandateCheckbox");
            oInput.setAttribute("id", "fcpoMandateCheckboxBottom");
            oInput.setAttribute("value", oCheckbox.checked);
            document.getElementById("orderConfirmAgbBottom").appendChild(oInput);
        }
    }
}

/**
 * Creates payone form input fields and appends it at the form end
 *
 * @param   {type} oForm
 * @param   {type} sName
 * @param   {type} sValue
 * @returns {undefined}
 */
function fcSetPayoneInput(oForm, sName, sValue) {
    var sInput = '<input type="hidden" name="'+sName+'" value="'+sValue+'" />';
    oForm.insertAdjacentHTML('beforeend', sInput);
}

/**
 * Sets payone form input fields
 *
 * @param   {type} oForm
 * @returns {undefined}
 */
function fcSetPayoneInputFields(oForm) {
    for(var sInput in oFcPayoneData.inputs) {
        var sInputName = sInput;
        var sInputValue = oFcPayoneData.inputs[sInput];
        if (sInput.indexOf('dynvalue') != -1 ) {
            sInputName = sInputName.replace('dynvalue_', '');
            sInputName = 'dynvalue['+sInputName+']';
        }
        fcSetPayoneInput(oForm, sInputName, sInputValue);
    }
}

/**
 * Triggers session start call via ajax
 *
 * @param void
 */
$('#fcpo_klarna_combined_agreed, #klarna_payment_selector').change(
    function() {
        var payment_id = $('#klarna_payment_selector').children("option:selected").val();
        var oForm = fcpoGetPaymentForm();

        var klarna_combined_agreed = $('[id="fcpo_klarna_combined_agreed"]');
        if (klarna_combined_agreed.length > 0 && klarna_combined_agreed[0].checked == false) {
            klarna_combined_agreed[0].innerHTML = '';

            if ($('[id="klarna_combined_js_inject"]').length > 0 && $('[id="klarna_combined_js_inject"]')[0].innerHTML !== '') {
                location.reload();
            }
            return;
        } else {
            if (typeof(oForm['dynvalue[fcpo_klarna_birthday][year]']) !== 'undefined') {
                var birthday = oForm['dynvalue[fcpo_klarna_birthday][year]'].value + '-'+ oForm['dynvalue[fcpo_klarna_birthday][month]'].value + '-' + oForm['dynvalue[fcpo_klarna_birthday][day]'].value;
            }
            if (typeof(oForm['dynvalue[fcpo_klarna_telephone]']) !== 'undefined') {
                var telephone = oForm['dynvalue[fcpo_klarna_telephone]'].value;
            }
            if (typeof(oForm['dynvalue[fcpo_klarna_personalid]']) !== 'undefined') {
                var personalid = oForm['dynvalue[fcpo_klarna_personalid]'].value;
            }
        }

        let payment_category_list = {
            "fcpoklarna_invoice" : "pay_later",
            "fcpoklarna_directdebit" : "direct_debit",
            "fcpoklarna_installments" : "pay_over_time"
        };

        var payment_category = payment_category_list[payment_id];

        var formParams = '{' +
            '"payment_container_id":"klarna_widget_combined_container", ' +
            '"payment_category":"' + payment_category + '",' +
            '"birthday":"' + birthday + '",' +
            '"personalid":"' + personalid + '",' +
            '"telephone":"' + telephone + '"' +
        '}';

        $.ajax(
            {
                url: payoneAjaxControllerUrl,
                method: 'POST',
                type: 'POST',
                dataType: 'text',
                data: {
                    paymentid: payment_id,
                    action: "start_session",
                    params: formParams,
                    birthday: birthday
                },
                success: function(Response) {
                    var klarnaWidgetCombinedContainer = $('[id="klarna_widget_combined_container"]');
                    if (klarnaWidgetCombinedContainer.length > 0) {
                        klarnaWidgetCombinedContainer[0].innerHTML = '';
                    }
                    $('#klarna_combined_js_inject').empty().html(Response);
                    var paymentKlarnaCombined = $('[id="payment_klarna_combined"]');
                    if (paymentKlarnaCombined.length > 0) {
                        paymentKlarnaCombined[0].value = payment_id;
                    }
                },
                error: function () {
                    location.reload();
                }
            }
        );
    }
);


// >>> APPLE PAY

function fcpoPayWithApplePay(amount, country, currency, networks, checkoutForm) {
    var session = new ApplePaySession(3, {
        countryCode: country,
        currencyCode: currency,
        supportedNetworks: networks,
        merchantCapabilities: ['supports3DS', 'supportsDebit', 'supportsCredit'],
        total: { label: 'PAYONE Apple Pay', amount: amount }
    });

    session.onvalidatemerchant = function(evt) {
        var validationUrl = evt.validationURL;

        $.ajax({
            url: payoneAjaxControllerUrl,
            method: 'POST',
            type: 'POST',
            data: {
                paymentid: 'fcpo_apple_pay',
                action: "fcpoapl_create_session",
                params: JSON.stringify({"validationUrl": validationUrl})
            },
            success: function(response) {
                var data = JSON.parse(response);
                if ('SUCCESS' !== data.status) {
                    alert(data.status + ' : ' + data.message + '\n(' + data.errorDetails +')');
                    return
                }
                session.completeMerchantValidation(data.merchantSession);
            },
            error: function(response) {
                var data = JSON.parse(response);
                alert(data.status + ' ' + response.status + ' : ' + data.message);
            }
        });
    };

    session.onpaymentauthorized = function(evt) {
        var token = evt.payment.token;

        $.ajax({
            url: payoneAjaxControllerUrl,
            method: 'POST',
            type: 'POST',
            data: {
                paymentid: 'fcpo_apple_pay',
                action: "fcpoapl_payment",
                params: JSON.stringify({"token": token})
            },
            success: function(response) {
                var data = JSON.parse(response);

                if ('SUCCESS' !== data.status) {
                    session.completePayment({
                        status: ApplePaySession.STATUS_FAILURE,
                        errors: [data.message]
                    });
                    return false;
                }

                session.completePayment({
                    status: ApplePaySession.STATUS_SUCCESS,
                    errors: []
                });

                checkoutForm.submit();
                return true;
            },
            error: function(response) {
                var data = JSON.parse(response);
                session.completePayment({
                    status: ApplePaySession.STATUS_FAILURE,
                    errors: [data.message]
                });

                return false;
            }
        });
    };

    session.begin()
}

function fcpoAplCheckDevice() {
    var allowedDevice = 0;
    if (window.ApplePaySession) {
        var canMakePayments = ApplePaySession.canMakePayments();
        if (canMakePayments) {
            allowedDevice = 1;
        }
    }

    $.ajax({
        url: payoneAjaxControllerUrl,
        method: 'POST',
        type: 'POST',
        data: {
            paymentid: 'fcpo_apple_pay',
            action: 'fcpoapl_register_device',
            params: JSON.stringify({"allowed": allowedDevice})
        },
        success: fcpoAplCheckDeviceSuccess,
        error: fcpoAplCheckDeviceFailure
    });
}
function fcpoAplCheckDeviceSuccess (response) {
    var responseData = JSON.parse(response);

    if ('SUCCESS' !== responseData.status) {
        alert("Bad response\n" + responseData.message);
    }
}
function fcpoAplCheckDeviceFailure(response) {
    var responseData = JSON.parse(response);
    alert("Failure : Call failed\n" + responseData.message);
}

function fcpoGetAplOrderInfo (placeOrderButtonForm) {
    $.ajax({
        url: payoneAjaxControllerUrl,
        method: 'POST',
        type: 'POST',
        data: {
            paymentid: 'fcpo_apple_pay',
            action: 'fcpoapl_get_order_info',
            params: '{}'
        },
        success: function(response) {
            var responseData = JSON.parse(response);

            if ('SUCCESS' !== responseData.status) {
                alert("Bad response\n" + responseData.message);
                return false;
            }

            var info = responseData.info;
            if (info.isApl) {
                placeOrderButtonForm.on('submit', function(e){
                    e.preventDefault();

                    if (info.supportedNetworks.length < 1) {
                      alert(info.errorMessage);
                      return false;
                    }

                    fcpoPayWithApplePay(
                        info.amount,
                        info.country,
                        info.currency,
                        info.supportedNetworks,
                        this
                    );
                });
            }

            return true;
        },
        error: function(response) {
            var responseData = JSON.parse(response);
            alert("Failure : Call failed\n" + responseData.message);
            return false;
        }
    });
}

// APPLE PAY <<<


// >>>> RATEPAY INSTALLMENT

function fcpoRatepayRateCalculatorAction(sMode, sPaymentMethodId, iMonth) {
    var oForm = fcpoGetPaymentForm();
    var sPaymentMethodOxid = oForm['dynvalue[fcporp_installment_profileid]'].value;
    var iInstallmentRate = oForm['dynvalue[fcporp_installment_rate_value]'].value;

    var sFormParams = '{' +
        '"sPaymentMethodOxid":"' + sPaymentMethodOxid + '" , ' +
        '"sMode":"' + sMode + '"';

    if (sMode === 'runtime') {
        sFormParams += ', "iMonth":' + iMonth
    } else {
        sFormParams += ', "iInstallment":' + iInstallmentRate
    }

    sFormParams += '}';

    $.ajax({
        url: payoneAjaxControllerUrl,
        method: 'POST',
        type: 'POST',
        data: {
            paymentid: sPaymentMethodId,
            action: 'fcporp_calculation',
            params: sFormParams
        },
        success: function (response) {
            var oCalculationDetailsContainer = document.getElementById('fcporp_installment_calculation_details');
            oCalculationDetailsContainer.innerHTML = response;

            document.getElementById('fcporp_installment_sepa_container').style.display = 'block';
        }
    });
}
function fcpoMouseOver(mouseoverString) {
    document.getElementById(mouseoverString).style.display = 'block';
}
function fcpoMouseOut(mouseoverString) {
    document.getElementById(mouseoverString).style.display = 'none';
}
function fcpoRpChangeDetails(paymentMethod) {
    if (document.getElementById(paymentMethod + '_rp-show-installment-plan-details').style.display === 'none') {
        document.getElementById(paymentMethod + '_rp-show-installment-plan-details').style.display = 'block';
        document.getElementById(paymentMethod + '_rp-hide-installment-plan-details').style.display = 'none';
        document.getElementById(paymentMethod + '_rp-installment-plan-details').style.display = 'none';
        document.getElementById(paymentMethod + '_rp-installment-plan-no-details').style.display = 'block';
    } else {
        document.getElementById(paymentMethod + '_rp-hide-installment-plan-details').style.display = 'block';
        document.getElementById(paymentMethod + '_rp-show-installment-plan-details').style.display = 'none';
        document.getElementById(paymentMethod + '_rp-show-installment-plan-details').style.display = 'none';
        document.getElementById(paymentMethod + '_rp-installment-plan-details').style.display = 'block';
        document.getElementById(paymentMethod + '_rp-installment-plan-no-details').style.display = 'none';
    }
}
function fcpoChangeInstallmentPaymentType(payment, paymentMethod) {
    if (payment == 28) {
        document.getElementById(paymentMethod + '_iban').value = '';
        document.getElementById(paymentMethod + '_sepa_container').style.display = 'none';
        document.getElementById(paymentMethod + '_rp-switch-payment-type-direct-debit').style.display = 'block';
        document.getElementById(paymentMethod + '_paymentFirstday').value = 2;
        document.getElementById(paymentMethod + '_settlement_type').value = 'banktransfer';
    } else {
        document.getElementById(paymentMethod + '_sepa_container').style.display = 'block';
        document.getElementById(paymentMethod + '_rp-switch-payment-type-direct-debit').style.display = 'none';
        document.getElementById(paymentMethod + '_paymentFirstday').value = 28;
        document.getElementById(paymentMethod + '_settlement_type').value = 'debit';
    }
}

// RATEPAY INSTALLMENT <<<<


// >>>> BNPL INSTALLMENT

function fcpoSelectBNPLInstallmentPlan(iIndex) {
    var oRadio = document.getElementById('bnplPlan_' + iIndex);
    if (oRadio) {
        oRadio.checked = true;
    }

    var oDetailsList = document.getElementsByClassName('bnpl_installment_overview');
    for (var i = 0 ; i < oDetailsList.length ; i++) {
        var oElement = oDetailsList[i];

        if (oElement.id === 'bnpl_installment_overview_' + iIndex) {
            oElement.style.display = 'block';
        } else {
            oElement.style.display = 'none';
        }
    }
}

// BNPL INSTALLMENT <<<<


/**
 * Triggers precheck for payolution installment via ajax
 *
 * @param void
 */
var fcpoPayolutionInstallmentCheckAvailability = $('[id="payolution_installment_check_availability"]');
if (fcpoPayolutionInstallmentCheckAvailability.length > 0) {
    fcpoPayolutionInstallmentCheckAvailability[0].addEventListener('click',
        function () {
            // trigger loading animation and disable button
            var payolutionInstallmentCalculationSelection = $('[id="payolution_installment_calculation_selection"]');
            if (payolutionInstallmentCalculationSelection.length > 0) {
                payolutionInstallmentCalculationSelection[0].innerHTML = '<div id="payolution_center_animation"><img src="modules/fc/fcpayone/out/img/ajax-loader.gif"</div>';
            }
            var payolutionInstallmentCheckAvailability = $('[id="payolution_installment_check_availability"]');
            if (payolutionInstallmentCheckAvailability.length > 0) {
                payolutionInstallmentCheckAvailability[0].setAttribute('disabled', true);
            }
            // collect data from form to pass it through to controller
            var formParams = '{';
            $('[name^="dynvalue"]').each(
                function (key, value) {
                    var formType = this.getAttribute('type');
                    var rawName = this.getAttribute("name");

                    var regExp = /\[([^)]+)\]/;
                    var matches = regExp.exec(rawName);
                    if (matches === null) {
                        return true;
                    }

                    var nameInBrackets = matches[1];
                    if (key > 0 && formParams != '{') {
                        formParams += ', ';
                    }

                    if (formType == 'checkbox') {
                        var inputValue = '';
                        if (this.checked) {
                            inputValue = this.value;
                        }
                    }
                    else {
                        var inputValue = this.value;
                    }
                    formParams += '"' + nameInBrackets + '":"' + inputValue + '"';
                }
            );
            formParams += '}';

            $.ajax(
                {
                    url: payoneAjaxControllerUrl,
                    method: 'POST',
                    type: 'POST',
                    dataType: 'text',
                    data: {paymentid: "fcpopo_installment", action: "precheck", params: formParams},
                    success: function (Response) {
                        if ($('[id="payolution_installment_calculation_selection"]').length > 0) {
                            $('[id="payolution_installment_calculation_selection"]')[0].innerHTML = Response;
                        }

                        if ($('[id="payolution_installment_check_availability"]').length > 0) {
                            $('[id="payolution_installment_check_availability"]')[0].removeAttribute('disabled');
                        }

                        var numberOfInstallments = 0;
                        if ($('[id="payolution_no_installments"]').length > 0) {
                            numberOfInstallments = $('[id="payolution_no_installments"]')[0].value
                        }
                        if ($('[id="payolution_sum_number_installments"]').length > 0) {
                            $('[id="payolution_sum_number_installments"]')[0].innerHTML = numberOfInstallments;
                        }
                        $('input[name=payolution_installment_selection]').bind('change', function () {
                            // selected interest data will be set into summary box
                            var selectedInstallmentIndex = $('input[name=payolution_installment_selection]:checked').val();
                            // disable all installment details and enable selected
                            for (i = 1; i <= numberOfInstallments; i++) {
                                var element = $('[id="payolution_rates_details_' + i + '"]');
                                if (element.length > 0) {
                                    element[0].classList.remove('payolution_rates_visible');
                                    element[0].classList.add('payolution_rates_invisible');
                                }
                            }
                            element = $('[id="payolution_rates_details_' + selectedInstallmentIndex + '"]');
                            if (element.length > 0) {
                                element[0].classList.add('payolution_rates_visible');
                                element[0].classList.remove('payolution_rates_invisible');
                            }
                            // set needed values to foreseen fields
                            if ($('[id="payolution_sum_number_installments"]').length > 0) {
                                $('[id="payolution_sum_number_installments"]')[0].innerHTML = numberOfInstallments;
                            }
                            if ($('[id="payolution_financing_sum"]').length > 0 && $('[id="payolution_installment_total_amount_' + selectedInstallmentIndex + '"]').length > 0) {
                                $('[id="payolution_financing_sum"]')[0].innerHTML = $('[id="payolution_installment_total_amount_' + selectedInstallmentIndex + '"]')[0].value;
                            }
                            if ($('[id="payolution_sum_interest_rate"]').length > 0 && $('[id="payolution_installment_interest_rate_' + selectedInstallmentIndex + '"]').length > 0) {
                                $('[id="payolution_sum_interest_rate"]')[0].innerHTML = $('[id="payolution_installment_interest_rate_' + selectedInstallmentIndex + '"]')[0].value;
                            }
                            if ($('[id="payolution_sum_eff_interest_rate"]').length > 0 && $('[id="payolution_installment_eff_interest_rate_' + selectedInstallmentIndex + '"]').length > 0) {
                                $('[id="payolution_sum_eff_interest_rate"]')[0].innerHTML = $('[id="payolution_installment_eff_interest_rate_' + selectedInstallmentIndex + '"]')[0].value;
                            }
                            if ($('[id="payolution_sum_monthly_rate"]').length > 0 && $('[id="payolution_installment_value_' + selectedInstallmentIndex + '"]').length > 0) {
                                $('[id="payolution_sum_monthly_rate"]')[0].innerHTML = $('[id="payolution_installment_value_' + selectedInstallmentIndex + '"]')[0].value;
                            }
                            if ($('[id="payolution_sum_number_installments"]').length > 0 && $('[id="payolution_installment_duration_' + selectedInstallmentIndex + '"]').length > 0) {
                                $('[id="payolution_sum_number_installments"]')[0].innerHTML = $('[id="payolution_installment_duration_' + selectedInstallmentIndex + '"]')[0].value;
                            }
                            if ($('[id="payolution_selected_installment_index"]').length > 0) {
                                $('[id="payolution_selected_installment_index"]')[0].value = selectedInstallmentIndex;
                            }
                        });
                    }
                }
            );
        }
    );
}

/**
 * Reaction on changes on radio interest selection
 *
 * @param  void
 * @return void
 */



(function(d, t) {
    var g = d.createElement(t),
        s = d.getElementsByTagName(t)[0];
    g.src = 'https://secure.pay1.de/client-api/js/ajax.js';
    s.parentNode.insertBefore(g, s);

    var oForm = fcpoGetPaymentForm();
    if (oForm) {
        fcSetPayoneInputFields(oForm);

        if(oForm["dynvalue[fcpo_sotype]"]) {
            fcCheckOUType(oForm["dynvalue[fcpo_sotype]"]);
        }
        if(oForm["dynvalue[fcpo_elv_country]"]) {
            fcCheckDebitCountry(oForm["dynvalue[fcpo_elv_country]"]);
        }
        $(oForm).on('submit', function(e){
            if ( fcCheckPaymentSelection() == false ) {
                e.preventDefault();
            }
        });
    }
    setTimeout(
        function(){
            if(document.getElementById('fcpoCreditcard') && typeof PayoneRequest == 'function') {
                document.getElementById('fcpoCreditcard').style.display = '';
            }
        }, 2000
    );

}(document, 'script'));


$(document).ready(function() {
    var fcpoPlaceOrderButtonForm = $('#orderConfirmAgbBottom');
    if (fcpoPlaceOrderButtonForm && fcpoPlaceOrderButtonForm.length > 0) {
        fcpoGetAplOrderInfo(fcpoPlaceOrderButtonForm);
    }

    if ($('body').hasClass('cl-basket')
        || $('body').hasClass('cl-user')
        || $('body').hasClass('cl-payment')
    ) {
        fcpoAplCheckDevice(payoneAjaxControllerUrl);
    }
});

/**
 * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 * Following code is only used on cc hosted iframes
 * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 */

function fcInitCCIframes() {
    var oForm = fcpoGetPaymentForm();
    var sKKType = fcpoGetCreditcardType();
    var sMode = oForm["fcpo_mode_fcpocreditcard_" + sKKType].value;

    oFcpoRequest = {
        request: 'creditcardcheck', // fixed value
        responsetype: 'JSON', // fixed value
        mode: sMode, // desired mode
        mid: oForm.fcpo_mid.value, // your MID
        aid: oForm.fcpo_aid.value, // your AID
        portalid: oForm.fcpo_portalid.value, // your PortalId
        encoding: oForm.fcpo_encoding.value, // desired encoding
        storecarddata: 'yes', // fixed value
        hash: oForm["fcpo_hashcc_" + sKKType].value
    };
    var iframes = new Payone.ClientApi.HostedIFrames(oFcpoConfig, oFcpoRequest);

    //set default cardType on initialization
    iframes.setCardType("V");

    return iframes;
}

/**
 * validates the expiredate given in response
 *
 * @param object response
 * @returns bool
 */
function fcpoValidateCardExpireDate(response) {
    if (response.status === "VALID") {
        // current year month string has to be set into format YYMM
        var fullMonth = new Array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");

        var currentDate = new Date();
        var fullYear = currentDate.getFullYear(); // need to use full year because getYear() is broken due to Y2K-Bug
        var month = currentDate.getMonth();

        month = fullMonth[month];
        var year = fullYear.toString();
        year = year.substr(2, 4);
        var currentYearMonth = year + month;
        var responseYearMonth = response.cardexpiredate;

        responseYearMonth = responseYearMonth.toString();
        if (responseYearMonth <= currentYearMonth) {
            response.status = 'INVALID';
            response.errormessage = 'Verfallsdatum der Karte erreicht. Bitte nutzen Sie eine andere Karte.';
        }
    }

    return response;
}

/**
 * Validate input (credit card data) on hosted iframes
 *
 * @return int
 * 0 = incomplete
 * 1 = complete
 * 2 = cvc missing
 *
 */
function fcpoValidateCCHostedInputs() { // Function called by submitting PAY-button
    if (oFcpoIframes.isComplete()) {
        return 1;
    } else {
        if(oFcpoIframes.isCardTypeComplete() &&
            oFcpoIframes.isCardpanComplete() &&
            oFcpoIframes.isExpireMonthComplete() &&
            oFcpoIframes.isExpireYearComplete())
        {
            return 2;
        }
    }

    return 0;
}

/**
 * Process hosted iframe cc data
 *
 * @param response
 */
function fcpoProcessPayoneResponseCCHosted(response) {
    response = fcpoValidateCardExpireDate(response);
    console.log(response);
    if (response.status === "VALID") {
        var oForm = fcpoGetPaymentForm();
        oForm["dynvalue[fcpo_pseudocardpan]"].value = response.pseudocardpan;
        oForm["dynvalue[fcpo_ccmode]"].value = fcpoGetOperationMode(fcpoGetCreditcardType());
        oForm["dynvalue[fcpo_kknumber]"].value = response.truncatedcardpan;
        oForm.submit();
    } else {
        document.getElementById('errorOutput').innerHTML = response.errormessage;
    }
}

/**
 * already displayed error will get hidden before recheck
 */
function fcpoHideCCHostedErrorsAtSubmit() {
    $('[id="errorCardType"]').hide();
    $('[id="errorCVC"]').hide();
    $('[id="errorIncomplete"]').hide();
}

/**
 * validates if customer has selected a valid card type on hosted iframes
 *
 * @param e
 */
function fcpoValidateCardTypeCCHosted(e) {
    var paymentId = $('input[name=paymentid]:checked').val();
    var cardType = $( '#cardtype option:selected' ).attr('data-cardtype');
    var oForm = fcpoGetPaymentForm();

    if(paymentId == 'fcpocreditcard' && oForm.fcpo_cc_type.value == 'hosted' && cardType == 'none') {
        $('[id="errorCardType"]').show();

        e.preventDefault();
    }
}

/**
 * validate input like cvc and missing fields
 *
 * @param e
 */
function fcpoValidateInputCCHosted(e) {
    var paymentId = $('input[name=paymentid]:checked').val();
    var cardType = $( '#cardtype option:selected' ).attr('data-cardtype');
    var oForm = fcpoGetPaymentForm();

    if(paymentId == 'fcpocreditcard' && oForm.fcpo_cc_type.value == 'hosted' && cardType != 'none') {
        $validateResult = fcpoValidateCCHostedInputs();

        var cardholderError = fcpoValidateCardholder(e);
        if (cardholderError) {
            e.preventDefault();
            return;
        }
        if($validateResult == 0) {
            e.preventDefault();
            $('[id="errorIncomplete"]').show();
        } else if($validateResult == 2) {
            $('[id="errorCVC"]').show();
            e.preventDefault();
        } else {
            // halt here if response returns valid but data is not valid (expiry date e.g.)
            e.preventDefault();
            //perform request for validation
            oFcpoIframes.creditCardCheck('fcpoProcessPayoneResponseCCHosted');
        }
    }
}

/**
 * if user is using browser back function,
 * card type is preselected and cvc check may is not working
 */
function fcpoResetCardTypeCCHosted() {
    var cardTypeOptionEl = $('#cardtype option[data-cardtype="none"]');
    var cardTypeEl = $('[id="cardtype"]');

    if(cardTypeOptionEl.length > 0) {
        cardTypeOptionEl[0].setAttribute('selected', true);
    }

    if(cardTypeOptionEl && cardTypeEl.length>0 && (typeof cardTypeEl[0].selectpicker === "function")) {
        cardTypeEl[0].selectpicker('refresh');
    }
}

/**
 * handles form submission if method is credit card hosted iframe
 */
$(document).ready(function() {
    var paymentForm = $('[id="payment"]');

    fcpoResetCardTypeCCHosted();

    if (paymentForm.length > 0) {
        //check cvc, check if cardtype is selected, progress request, output errors
        paymentForm[0].addEventListener('submit', function(e) {
            var klarna_auth_done = '';
            if ($('[id="fcpo_klarna_auth_done"]').length > 0) {
                klarna_auth_done = $('[id="fcpo_klarna_auth_done"]')[0].value;
            }

            var klarna_combined = $('[id="payment_klarna_combined"]');
            var klarna_paymentid = false;
            var klarna_combined_checked = false;
            if (klarna_combined.length > 0) {
                klarna_paymentid = klarna_combined[0].value;
                klarna_combined_checked = klarna_combined[0].checked;
            }

            fcpoHideCCHostedErrorsAtSubmit();
            fcpoValidateCardTypeCCHosted(e);
            fcpoValidateInputCCHosted(e);
            if (klarna_combined_checked && klarna_paymentid) {
                if (klarna_auth_done === 'false') {
                    e.preventDefault();

                    var klarna_combined_agreed = $('[id="fcpo_klarna_combined_agreed"]');
                    if (klarna_combined_agreed.length > 0 && klarna_combined_agreed[0].checked == true) {
                        // defined in snippets/fcpoKlarnaWidget.txt
                        klarnaAuthorize(e);
                    }
                }
            }
        });
    }

    $('#cardtype').on('change', function(e) {
        oFcpoIframes.setCardType(this.value);
    });
});
