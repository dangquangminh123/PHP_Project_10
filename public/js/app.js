$(document).ready(function () {
    $("#check_ajax").click(function() {
        var data_id = $(this).attr('data-id');
        var data = {id: data_id};

        $.ajax({
            url: '?mod=order&action=update', //trang xử lý, mặc định trang hiện tại
            method: 'POST', // Post hoặc Get mặc định là get
            data: data, // Dữ liệu truyền lên server
            dataType: 'text', // html, text, script hoặc json
            success: function (data) {
                alert(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
        return false;
    });
});