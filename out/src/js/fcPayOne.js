function getSelectedPaymentMethod() {
    var oForm = getPaymentForm();
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

function getPaymentForm() {
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

function getOperationMode(sType) {
    var sSelectedPaymentOperationMode = 'fcpo_mode_' + getSelectedPaymentMethod();
    if(sType != '') {
        sSelectedPaymentOperationMode += '_' + sType;
    }
    var oForm = getPaymentForm();
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
    var oForm = getPaymentForm();
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

function resetErrorContainers() {
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
    if(document.getElementById('fcpo_klarna_campaign_invalid')) {
        document.getElementById('fcpo_klarna_campaign_invalid').style.display = '';
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
}

function fcpoGetCreditcardType() {
    var oForm = getPaymentForm();
    if(oForm["dynvalue[fcpo_kktype]"].nodeName == 'INPUT') {
        sCreditcardType = oForm["dynvalue[fcpo_kktype]"].value;
    } else {
        sCreditcardType = oForm["dynvalue[fcpo_kktype]"].options[oForm["dynvalue[fcpo_kktype]"].selectedIndex].value;
    }
    return sCreditcardType;
}

function fcpoGetCardExpireDate() {
    var oForm = getPaymentForm();
    if(oForm["dynvalue[fcpo_kkyear]"].nodeName == 'INPUT') {
        sDate = oForm["dynvalue[fcpo_kkyear]"].value.substr(2,2) + oForm["dynvalue[fcpo_kkmonth]"].value;
    } else {
        sDate = oForm["dynvalue[fcpo_kkyear]"].options[oForm["dynvalue[fcpo_kkyear]"].selectedIndex].innerHTML.substr(2,2) + oForm["dynvalue[fcpo_kkmonth]"].options[oForm["dynvalue[fcpo_kkmonth]"].selectedIndex].innerHTML;
    }
    return sDate;
}

function startCCRequest() {
    resetErrorContainers();
    var oForm = getPaymentForm();
    oForm["dynvalue[fcpo_kknumber]"].value = getCleanedNumber(oForm["dynvalue[fcpo_kknumber]"].value);
    if(oForm["dynvalue[fcpo_kknumber]"].value == '') {
        document.getElementById('fcpo_cc_number_invalid').style.display = 'block';
        return false;
    }

    oForm["dynvalue[fcpo_kkpruef]"].value = getCleanedNumber(oForm["dynvalue[fcpo_kkpruef]"].value);
    if(oForm["dynvalue[fcpo_kkpruef]"].value == '' || oForm["dynvalue[fcpo_kkpruef]"].value.length < 3) {
        document.getElementById('fcpo_cc_cvc2_invalid').style.display = 'block';
        return false;
    }

    var sKKType = fcpoGetCreditcardType();

    var sMode = getOperationMode(sKKType);

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
        callback_function_name : 'processPayoneResponseCC'
    };

    var request = new PayoneRequest(data, options);
    request.checkAndStore();
    return false;
}

function validateCardholder(e) {
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

function getCleanedNumber(dirtyNumber) {
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

function getCleanedNumberIBAN(sDirtyNumber) {
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

function checkKlarna() {
    resetErrorContainers();
    var oForm = getPaymentForm();

    if(oForm['dynvalue[fcpo_klv_fon]']) {
        oForm['dynvalue[fcpo_klv_fon]'].value = oForm['dynvalue[fcpo_klv_fon]'].value.trim();
        if(oForm['dynvalue[fcpo_klv_fon]'].value == '') {
            document.getElementById('fcpo_klv_fon_invalid').style.display = 'block';
            return false;
        }
    }
    if(oForm['dynvalue[fcpo_klv_birthday][month]']) {
        if(oForm['dynvalue[fcpo_klv_birthday][month]'].value == '' || oForm['dynvalue[fcpo_klv_birthday][day]'].value == '' || oForm['dynvalue[fcpo_klv_birthday][year]'].value == '') {
            document.getElementById('fcpo_klv_birthday_invalid').style.display = 'block';
            return false;
        }
    }
    if(oForm['dynvalue[fcpo_klv_addinfo]']) {
        oForm['dynvalue[fcpo_klv_addinfo]'].value = oForm['dynvalue[fcpo_klv_addinfo]'].value.trim();
        if(oForm['dynvalue[fcpo_klv_addinfo]'].value == '') {
            document.getElementById('fcpo_klv_addinfo_invalid').style.display = 'block';
            return false;
        }
    }
    if(oForm['dynvalue[fcpo_klv_del_addinfo]']) {
        oForm['dynvalue[fcpo_klv_del_addinfo]'].value = oForm['dynvalue[fcpo_klv_del_addinfo]'].value.trim();
        if(oForm['dynvalue[fcpo_klv_del_addinfo]'].value == '') {
            document.getElementById('fcpo_klv_del_addinfo_invalid').style.display = 'block';
            return false;
        }
    }
    if(oForm['dynvalue[fcpo_klv_personalid]']) {
        oForm['dynvalue[fcpo_klv_personalid]'].value = oForm['dynvalue[fcpo_klv_personalid]'].value.trim();
        if(oForm['dynvalue[fcpo_klv_personalid]'].value == '') {
            document.getElementById('fcpo_klv_personalid_invalid').style.display = 'block';
            return false;
        }
    }
    if(oForm['dynvalue[fcpo_klv_confirm]']) {
        if(!oForm['dynvalue[fcpo_klv_confirm]'][1].checked) {
            document.getElementById('fcpo_klv_confirmation_missing').style.display = 'block';
            return false;
        }
    }
    return true;
}

function fcpoGetElvCountry() {
    var oForm = getPaymentForm();
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

function startELVRequest() {
    resetErrorContainers();
    var oForm = getPaymentForm();

    if(oForm['dynvalue[fcpo_elv_blz]']) {
        oForm['dynvalue[fcpo_elv_blz]'].value = getCleanedNumber(oForm['dynvalue[fcpo_elv_blz]'].value);
    }
    if(oForm['dynvalue[fcpo_elv_ktonr]']) {
        oForm['dynvalue[fcpo_elv_ktonr]'].value = getCleanedNumber(oForm['dynvalue[fcpo_elv_ktonr]'].value);
    }
    if(oForm['dynvalue[fcpo_elv_iban]']) {
        oForm['dynvalue[fcpo_elv_iban]'].value = getCleanedNumberIBAN(oForm['dynvalue[fcpo_elv_iban]'].value);
    }
    if(oForm['dynvalue[fcpo_elv_bic]']) {
        oForm['dynvalue[fcpo_elv_bic]'].value = getCleanedNumberIBAN(oForm['dynvalue[fcpo_elv_bic]'].value);
    }

    if(oForm['dynvalue[fcpo_payolution_iban]']) {
        oForm['dynvalue[fcpo_payolution_iban]'].value = getCleanedNumberIBAN(oForm['dynvalue[fcpo_payolution_iban]'].value);
    }
    if(oForm['dynvalue[fcpo_payolution_bic]']) {
        oForm['dynvalue[fcpo_payolution_bic]'].value = getCleanedNumberIBAN(oForm['dynvalue[fcpo_payolution_bic]'].value);
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

    var sMode = getOperationMode('');
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
        callback_function_name : 'processPayoneResponseELV'
    };

    var request = new PayoneRequest(data, options);
    request.checkAndStore();

    return false;
}

function fcCheckPaymentSelection() {
    var sCheckedValue = getSelectedPaymentMethod();
    if(sCheckedValue != false) {
        var oForm = getPaymentForm();
        if(sCheckedValue == 'fcpocreditcard' && oForm.fcpo_cc_type.value == 'ajax') {
            return startCCRequest();
        } else if(sCheckedValue == 'fcpodebitnote') {
            return startELVRequest(true);
        } else if(sCheckedValue == 'fcpoklarna') {
            return checkKlarna();
        }
    }
    return true;
}

function processPayoneResponseELV(response) {
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
        var oForm = getPaymentForm();
        oForm.submit();
    }
}

function processPayoneResponseCC(response) {
    var cardholderError = validateCardholder();
    if (cardholderError) {
        return false;
    }
    if(response.get('status') == 'VALID') {
        var oForm = getPaymentForm();
        oForm["dynvalue[fcpo_pseudocardpan]"].value = response.get('pseudocardpan');
        oForm["dynvalue[fcpo_ccmode]"].value = getOperationMode(fcpoGetCreditcardType());
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
    var oForm = getPaymentForm();
    if (oForm['dynvalue[fcpo_elv_iban]'] && oForm['dynvalue[fcpo_elv_bic]'] ) {
        oForm['dynvalue[fcpo_elv_iban]'].disabled = false;
        oForm['dynvalue[fcpo_elv_iban]'].style.backgroundColor = "";
        oForm['dynvalue[fcpo_elv_bic]'].disabled = false;
        oForm['dynvalue[fcpo_elv_bic]'].style.backgroundColor = "";
    }
}
function fcEnableDebitInputsTypeBlz() {
    var oForm = getPaymentForm();
    if (oForm['dynvalue[fcpo_elv_ktonr]'] && oForm['dynvalue[fcpo_elv_blz]'] ) {
        oForm['dynvalue[fcpo_elv_ktonr]'].disabled = false;
        oForm['dynvalue[fcpo_elv_ktonr]'].style.backgroundColor = "";
        oForm['dynvalue[fcpo_elv_blz]'].disabled = false;
        oForm['dynvalue[fcpo_elv_blz]'].style.backgroundColor = "";
    }
}
function fcDisableDebitInputsTypeIban() {
    var oForm = getPaymentForm();
    if (oForm['dynvalue[fcpo_elv_iban]'] && oForm['dynvalue[fcpo_elv_bic]'] ) {
        oForm['dynvalue[fcpo_elv_iban]'].disabled = true;
        oForm['dynvalue[fcpo_elv_iban]'].style.backgroundColor = "#EEE";
        oForm['dynvalue[fcpo_elv_bic]'].disabled = true;
        oForm['dynvalue[fcpo_elv_bic]'].style.backgroundColor = "#EEE";
    }
}
function fcDisableDebitInputsTypeBlz() {
    var oForm = getPaymentForm();
    if (oForm['dynvalue[fcpo_elv_ktonr]'] && oForm['dynvalue[fcpo_elv_blz]'] ) {
        oForm['dynvalue[fcpo_elv_ktonr]'].disabled = true;
        oForm['dynvalue[fcpo_elv_ktonr]'].style.backgroundColor = "#EEE";
        oForm['dynvalue[fcpo_elv_blz]'].disabled = true;
        oForm['dynvalue[fcpo_elv_blz]'].style.backgroundColor = "#EEE";
    }
}
function fcHandleDebitInputsTypeIban() {
    var oForm = getPaymentForm();
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
    var oForm = getPaymentForm();
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
        var oForm = getPaymentForm();

        if ($('#fcpo_klarna_combined_agreed').is(':checked') == false) {
            $('#klarna_widget_combined_container').empty();

            if ($('#klarna_combined_js_inject').html() !== '') {
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
            "fcpoklarna_installments" : "pay_over_time",
        }

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
                    birthday: birthday,
                },
                success: function(Response) {
                    $('#klarna_widget_combined_container').empty();
                    $('#klarna_combined_js_inject').empty().html(Response);
                    $('#payment_klarna_combined').val(payment_id);
                },
                error: function () {
                    location.reload();
                }
            }
        );
    }
);




/**
 * Triggers precheck for payolution installment via ajax
 *
 * @param void
 */
$('#payolution_installment_check_availability').click(
    function(){
        // trigger loading animation and disable button
        $('#payolution_installment_calculation_selection').html('<div id="payolution_center_animation"><img src="modules/fc/fcpayone/out/img/ajax-loader.gif"</div>');
        $('#payolution_installment_check_availability').attr('disabled', true);
        // collect data from form to pass it through to controller
        var formParams = '{';
        $('[name^="dynvalue"]').each(
            function(key, value) {
                var formType = $(this).attr('type');
                var rawName = $(this).attr("name");

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
                    if ($(this).is(':checked')) {
                        inputValue = $(this).val();
                    }
                }
                else {
                    var inputValue = $(this).val();
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
                data: { paymentid: "fcpopo_installment", action: "precheck", params: formParams },
                success: function(Response) {
                    $('#payolution_installment_calculation_selection').html(Response);
                    $('#payolution_installment_check_availability').attr('disabled', false);
                    var numberOfInstallments = $('#payolution_no_installments').val();
                    $('#payolution_sum_number_installments').html(numberOfInstallments);
                    $('input[name=payolution_installment_selection]').bind(
                        'change', function() {
                            // selected interest data will be set into summary box
                            var selectedInstallmentIndex = $('input[name=payolution_installment_selection]:checked').val();
                            // disable all installment details and enable selected
                            for (i=1;i<=numberOfInstallments;i++) {
                                $('#payolution_rates_details_'+i).removeClass('payolution_rates_visible');
                                $('#payolution_rates_details_'+i).addClass('payolution_rates_invisible');
                            }
                            $('#payolution_rates_details_'+selectedInstallmentIndex).addClass('payolution_rates_visible');
                            $('#payolution_rates_details_'+selectedInstallmentIndex).removeClass('payolution_rates_invisible');
                            // set needed values to foreseen fields
                            $('#payolution_sum_number_installments').html(numberOfInstallments);
                            $('#payolution_financing_sum').html($('#payolution_installment_total_amount_' + selectedInstallmentIndex).val());
                            $('#payolution_sum_interest_rate').html($('#payolution_installment_interest_rate_' + selectedInstallmentIndex).val());
                            $('#payolution_sum_eff_interest_rate').html($('#payolution_installment_eff_interest_rate_' + selectedInstallmentIndex).val());
                            $('#payolution_sum_monthly_rate').html($('#payolution_installment_value_' + selectedInstallmentIndex).val());
                            $('#payolution_sum_number_installments').html($('#payolution_installment_duration_' + selectedInstallmentIndex).val());
                            $('#payolution_selected_installment_index').val(selectedInstallmentIndex);
                        }
                    );
                }
            }
        );
    }
);

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

    var oForm = getPaymentForm();
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

/**
 * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 * Following code is only used on cc hosted iframes
 * ++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 */

function fcInitCCIframes() {
    var oForm = getPaymentForm();
    var sKKType = fcpoGetCreditcardType();
    var sMode = oForm["fcpo_mode_fcpocreditcard_" + sKKType].value;

    request = {
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
    var iframes = new Payone.ClientApi.HostedIFrames(config, request);

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
function validateCardExpireDate(response) {
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
function validateCCHostedInputs() { // Function called by submitting PAY-button
    if (iframes.isComplete()) {
        return 1;
    } else {
        if(iframes.isCardTypeComplete() &&
            iframes.isCardpanComplete() &&
            iframes.isExpireMonthComplete() &&
            iframes.isExpireYearComplete())
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
function processPayoneResponseCCHosted(response) {
    response = validateCardExpireDate(response);
    console.log(response);
    if (response.status === "VALID") {
        var oForm = getPaymentForm();
        oForm["dynvalue[fcpo_pseudocardpan]"].value = response.pseudocardpan;
        oForm["dynvalue[fcpo_ccmode]"].value = getOperationMode(fcpoGetCreditcardType());
        oForm["dynvalue[fcpo_kknumber]"].value = response.truncatedcardpan;
        oForm.submit();
    } else {
        document.getElementById('errorOutput').innerHTML = response.errormessage;
    }
}

/**
 * already displayed error will get hidden before recheck
 */
function hideCCHostedErrorsAtSubmit() {
    $('#errorCardType').hide();
    $('#errorCVC').hide();
    $('#errorIncomplete').hide();
}

/**
 * validates if customer has selected a valid card type on hosted iframes
 *
 * @param e
 */
function validateCardTypeCCHosted(e) {
    var paymentId = $('input[name=paymentid]:checked').val();
    var cardType = $( '#cardtype option:selected' ).attr('data-cardtype');
    var oForm = getPaymentForm();

    if(paymentId == 'fcpocreditcard' && oForm.fcpo_cc_type.value == 'hosted' && cardType == 'none') {
        $('#errorCardType').show();

        e.preventDefault();
    }
}

/**
 * validate input like cvc and missing fields
 *
 * @param e
 */
function validateInputCCHosted(e) {
    var paymentId = $('input[name=paymentid]:checked').val();
    var cardType = $( '#cardtype option:selected' ).attr('data-cardtype');
    var oForm = getPaymentForm();

    if(paymentId == 'fcpocreditcard' && oForm.fcpo_cc_type.value == 'hosted' && cardType != 'none') {
        $validateResult = validateCCHostedInputs();

        var cardholderError = validateCardholder(e);
        if (cardholderError) {
            e.preventDefault();
            return;
        }
        if($validateResult == 0) {
            e.preventDefault();
            $('#errorIncomplete').show();
        } else if($validateResult == 2) {
            $('#errorCVC').show();
            e.preventDefault();
        } else {
            // halt here if response returns valid but data is not valid (expiry date e.g.)
            e.preventDefault();
            //perform request for validation
            iframes.creditCardCheck('processPayoneResponseCCHosted');
        }
    }
}

/**
 * if user is using browser back function,
 * card type is preselected and cvc check may is not working
 */
function resetCardTypeCCHosted() {
    var cardTypeOptionEl = $('#cardtype option[data-cardtype="none"]');
    var cardTypeEl = $('#cardtype');

    if(cardTypeOptionEl) {
        cardTypeOptionEl.attr('selected', true);
    }

    if(cardTypeOptionEl && cardTypeEl && (typeof cardTypeEl.selectpicker === "function")) {
        cardTypeEl.selectpicker('refresh');
    }
}

/**
 * handles form submission if method is credit card hosted iframe
 */
$(document).ready(function() {
    var paymentForm = $('#payment');

    resetCardTypeCCHosted();

    //check cvc, check if cardtype is selected, progress request, output errors
    paymentForm.on('submit', function(e) {
        var klarna_auth_done = $('#fcpo_klarna_auth_done').val();
        var klarna_paymentid = $('#payment_klarna_combined').val();
        var klarna_combined_checked = $('#payment_klarna_combined').is(':checked');

        hideCCHostedErrorsAtSubmit();
        validateCardTypeCCHosted(e);
        validateInputCCHosted(e);
        if (klarna_combined_checked && klarna_paymentid) {
            if (klarna_auth_done === 'false') {
                e.preventDefault();
                if ($('#fcpo_klarna_combined_agreed').is(':checked') == true) {
                    // defined in snippets/fcpoKlarnaWidget.txt
                    klarnaAuthorize(e);
                }
            }
        }
    });

    $('#cardtype').on('change', function(e) {
        iframes.setCardType(this.value);
    });
});
