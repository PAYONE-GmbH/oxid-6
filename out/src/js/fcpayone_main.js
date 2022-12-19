/* 
 * JS of fcpayone_main.js
 */

function fcpoHandlePresaveOrderCheckbox(oCheckbox) {
    if(oCheckbox.checked) {
        document.getElementById('reduce_stock').style.display = "";
    } else {
        document.getElementById('reduce_stock').style.display = "none";
    }
}

function fcpoHandleRatePayShowDetails(oCheckbox) {
    var sOxid = oCheckbox.value;
    var sId = 'ratepay_profile_details_' + sOxid;
    if(oCheckbox.checked) {
        document.getElementById(sId).style.display = 'block';
    } else {
        document.getElementById(sId).style.display = 'none';
    }
}

function _groupExp(oElement) {
    var _cur = oElement.parentNode;

    if (_cur.className === "exp") {
        _cur.className = "";
    } else {
        _cur.className = "exp";
    }
}

function fcpoToggleHostedTemplate() {
    if(document.getElementById('fcpoHostedCCTemplate').style.display !== "") {
        document.getElementById('fcpoHostedCCTemplate').style.display = "";
    } else {
        document.getElementById('fcpoHostedCCTemplate').style.display = "none";
    }
}

function fcpoTogglePreview() {
    if(document.getElementById('fcpoHostedCCPreview').style.display !== "") {
        document.getElementById('fcpoHostedCCPreview').style.display = "";
    } else {
        document.getElementById('fcpoHostedCCPreview').style.display = "none";
    }
}

function fcpoInputEnable(sInputId) {
    document.getElementById(sInputId).disabled = false;
}
function fcpoInputDisable(sInputId) {
    document.getElementById(sInputId).disabled = true;
}

function fcpoHandleSizeFields(oSelect, sRowName) {
    if(oSelect.options[oSelect.selectedIndex].value === 'custom') {
        fcpoInputEnable('input_height_' + sRowName);
        fcpoInputEnable('input_width_' + sRowName);
    } else {
        fcpoInputDisable('input_height_' + sRowName);
        fcpoInputDisable('input_width_' + sRowName);
    }
}

function fcpoHandleCss(oSelect, sRowName) {
    if(oSelect.options[oSelect.selectedIndex].value === 'custom') {
        fcpoInputEnable('input_css_' + sRowName);
    } else {
        fcpoInputDisable('input_css_' + sRowName);
    }
}