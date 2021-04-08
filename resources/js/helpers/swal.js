/**
 * Display error popup
 * @param errorCode
 */
export function displayError(errorCode) {
    // define error text
    let errorText = '';

    // determine error text
    switch (errorCode) {
        case 403:
            errorText = 'Không được cấp quyền';
            break;
        default:
            errorText = 'Mã lỗi: ' + errorCode
    }

    // create swal
    Swal.fire({
        type: 'error',
        title: 'LỖI',
        text: errorText
    })
}


/**
 * Display delete confirm popup
 * @param title
 * @param text
 * @param fnCallback
 */
export function displayConfirmDelete(title, text, fnCallback) {
    Swal.fire({
        type: 'warning',
        title: title,
        text: text,
        showCancelButton: true,
        confirmButtonColor: '#dc3f4e',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Không'
    }).then((result) => {
        if (result.value) {
            fnCallback.call();
        }
    });
}

/**
 * Display delete confirm popup
 * @param title
 * @param text
 * @param fnCallback
 */
export function displayConfirmPasswordReset(title, fnCallback) {
    Swal.fire({
        type: 'warning',
        title: title,
        text: 'Reset mật khẩu cho tài khoản này',
        showCancelButton: true,
        confirmButtonColor: '#dc3f4e',
        confirmButtonText: 'Reset mật khẩu',
        cancelButtonText: 'Không'
    }).then((result) => {
        if (result.value) {
            fnCallback.call();
        }
    });
}

/**
 * Display delete confirm popup
 * @param username
 * @param newPassword
 * @created 2020-02-13 LongTHK
 */
export function displayNewPassword(username, newPassword) {
    Swal.fire(username, '<small>Mật khẩu mới: </small><strong><u>' + newPassword + '</u></strong>', 'success')
}

