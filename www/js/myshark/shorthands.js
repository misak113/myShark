
// Helper funkce

/**
     * Funkce pro překlad
     */
function _t(text, params) {
    // @todo dodelat cachovany preklad
    if (typeof params != 'Array') params = [];
    return _.string.sprintf(text, params.shift(), params.shift(), params.shift());
}

/**
     * Debugovací funkce
     */
function _d(message, e) {
    console.log(message+(e ?', Data:\n\n'+e :''));
}