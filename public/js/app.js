document.addEventListener('DOMContentLoaded', function () {

    let toastData = sessionStorage.getItem('toast');
    
    if (toastData) {
    
        let toast = JSON.parse(toastData);
    
        showToast(toast.message, toast.type);
    
        sessionStorage.removeItem('toast');
    }
    
    });
    
    function showToast(message, type = 'success') {
    
    let toast = document.createElement('div');
    
    toast.className = `toast toast-${type}`;
    toast.innerText = message;
    
    document
        .getElementById('toast-container')
        .appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
    
    }
    
    function showLoading() {
    
    document
        .getElementById('loading-overlay')
        .style.display = 'flex';
    
    }
    
    function hideLoading() {
    
    document
        .getElementById('loading-overlay')
        .style.display = 'none';
    
    }
    
    function redirectWithToast(
    url,
    message,
    type = 'success'
    ) {
    
    sessionStorage.setItem(
        'toast',
        JSON.stringify({
            message,
            type
        })
    );
    
    window.location.href = url;
    
    }
    
    function ajaxRequest({
    url,
    type = 'GET',
    data = {},
    processData = true,
    contentType =
    'application/x-www-form-urlencoded; charset=UTF-8',
    
    showSuccess = true,
    showError = true,
    loading = true,
    
    successCallback = null
    
    }) {
    
    $.ajax({
    
        url: url,
        type: type,
        data: data,
    
        processData: processData,
        contentType: contentType,
    
        headers: {
            'X-CSRF-TOKEN':
                $('meta[name="csrf-token"]').attr('content')
        },
    
        beforeSend: function () {
    
            if (loading) {
                showLoading();
            }
        },
    
        complete: function () {
    
            if (loading) {
                hideLoading();
            }
        },
    
        success: function (res) {
    
            if (res.message && showSuccess) {
                showToast(res.message, 'success');
            }
    
            if (typeof successCallback === 'function') {
                successCallback(res);
            }
        },
    
        error: function (err) {
    
            if (!showError) return;
    
            if (err.status === 422) {
    
                let errors = err.responseJSON.errors;
    
                let firstError =
                    Object.values(errors)[0][0];
    
                showToast(firstError, 'warning');
    
            } else if (err.responseJSON?.message) {
    
                showToast(
                    err.responseJSON.message,
                    'error'
                );
    
            } else {
    
                showToast(
                    'Có lỗi xảy ra',
                    'error'
                );
            }
        }
    });
}