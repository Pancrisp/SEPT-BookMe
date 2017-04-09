var currentDate = new Date().toISOString().slice(0,10);

$(document).ready(function() {
    $('#date').attr("placeholder", currentDate);
    $('#date-selected').html(currentDate);

    $(function() {
        $('#date').datepicker({ minDate: 0, maxDate: '+1M', dateFormat: 'yy-mm-dd' });
    });

    getBookingsByDate(currentDate);
});

// sets date value for roster date input field
$('#roster-date').datepicker({
    minDate: 0,
    maxDate: '+1M',
    dateFormat: 'yy-mm-dd',
    onSelect: function() {
        var date = document.querySelector('#roster-date').value;
        $('#dateHidden').val(date);
    },
    onClose: function() {
        var date = document.querySelector('#roster-date').value;
        $('#dateHidden').val(date);
    }
});

// appends the user's selected date onto the current bookings section
$('#date').change(function() {
    var date = document.querySelector('#date').value;
    $('#date-selected').html('');
    $('#date-selected').append(date);
});

$('#date').change(function() {

    var date = document.querySelector('#date').value;
    getBookingsByDate(date);

});

// AJAX request for viewing available booking slots
function getBookingsByDate(date){

    // To reset all the slots
    var allSlots = document.querySelectorAll('[class="slot"]');
    allSlots.forEach(function(slot){
        slot.innerHTML = "";
    });

    $.ajax({
        url: '/bookings/getByDate',
        type: 'get',
        data: {
            'date': date
        },
        success: function(response) {
            var res = JSON.parse(response);

            res.forEach(function(booking){
                var id = 'slot-'+booking['start_time'];
                var slot = document.querySelector('[id="'+id+'"]');
                slot.innerHTML = 'x';
            });
        }
    })
        .error(function(response) {
            alert("Unable to retrieve bookings");
        });
}
