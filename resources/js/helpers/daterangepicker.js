

/**
 * Initialize DateRangePicker plugin
 * @created 2020-02-26 LongTHK
 */
function initializeDateRangePicker(element, onChangedCallbackFunction) {
    element.daterangepicker({
        ranges: {
            'Hôm nay': [moment(), moment()],
            'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 ngày gần đây': [moment().subtract(6, 'days'), moment()],
            'Tháng này': [moment().startOf('month'), moment().endOf('month')],
            'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        alwaysShowCalendars: true,
        showDropdowns: true,
        maxDate: moment(),
        showCustomRangeLabel: false,
        autoApply: true,
        linkedCalendars: true,
        locale: {
            format: 'DD/MM/YYYY'
        }
    }).on('apply.daterangepicker', onChangedCallbackFunction);
}

/**
 * Export
 */
export {
    initializeDateRangePicker
}
