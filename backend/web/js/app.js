$(function() {
    "use strict";

    //Make the dashboard widgets sortable Using jquery UI
    $(".connectedSortable").sortable({
        placeholder: "sort-highlight",
        connectWith: ".connectedSortable",
        handle: ".box-header, .nav-tabs",
        forcePlaceholderSize: true,
        zIndex: 999999
    }).disableSelection();
    $(".connectedSortable .box-header, .connectedSortable .nav-tabs-custom").css("cursor", "move");
    $("[name='doctor-status']").bootstrapSwitch();
});

function updateStateStatus(url, state_id) {
    $.ajax({
        type: "post",
        url: url,
        data: {
            state_id: state_id
        },
        success: function() {
            $.pjax.reload({container:'#state_list'});
        }
    });
}

function updateOfferStatus(url, inquiry_id, status_id) {
    $.ajax({
        type: "post",
        url: url,
        data: {
            inquiry_id: inquiry_id,
            status_id: status_id
        },
        success: function() {
            $.pjax.reload({container:'#inquiry_list'});
        }
    });
}

function getChartNote(url, note_id) {
    $.ajax({
        type: "post",
        url: url,
        data: {
            note_id: note_id
        },
        success:
            function(data) {
                $('#chart_note').html(data);
            }
    });
}

function denyInquiry(url, inquiry_id, user_id) {
    $.ajax({
        type: "post",
        url: url,
        data: {
            inquiry_id: inquiry_id,
            user_id: user_id
        },
        //success:
        //    function(data) {
        //        $('#chart_note').html(data);
        //    }
    });
}

function updateDoctorStatus(url) {
    $.ajax({
        type: "post",
        url: url
    });
}