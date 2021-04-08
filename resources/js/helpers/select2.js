/**
 * Initialize Select2 plugin
 */
function initializeSelect2Plugin() {
    // initialize select2
    $('.select2').select2();
}

/**
 * Select2 trigger change
 * @param element
 * @param value
 */
function resetSelect2Value(element, value) {
    $('.select2').val('').trigger('change');
}

/**
 * Export
 */
export {
    initializeSelect2Plugin,
    resetSelect2Value
}
