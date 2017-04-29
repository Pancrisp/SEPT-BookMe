var currentDate = new Date().toISOString().slice(0, 10);

$(document).ready(function() {
    $('#date').attr("placeholder", currentDate);
    $('#date-selected').html(currentDate);

    $(function() {
        $('#date').datepicker({minDate: 0, maxDate: '+1M', dateFormat: 'yy-mm-dd'});
    });
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

// AJAX request to get employee's availability
$('#roster-select-employee').on('change', function() {
    var empID = $(this).val();

    $.ajax({
        url: '/staff/availability/get',
        type: 'get',
        data: {
            'empID': empID
        },
        success: function(response) {
            var res = JSON.parse(response);
            console.log(res);
        }
    }).error(function(res){
       alert("Unable to retrieve employee availability");
    });
})

// appends the user's selected date onto the current bookings section
$('#date').change(function() {
    var date = document.querySelector('#date').value;
    $('#date-selected').html('');
    $('#date-selected').append(date);
    var businessId = document.querySelector('#business').value;
    getBookingsByDate(date, businessId);
});

// AJAX request for viewing available booking slots
function getBookingsByDate(date, id) {

    // To reset all the slots
    var allSlots = document.querySelectorAll('[class="slot"]');
    allSlots.forEach(function(slot) {
        slot.innerHTML = "";
    });

    $.ajax({
        url: '/booking/get/byDate',
        type: 'get',
        data: {
            'date': date,
            'id': id
        },
        success: function(response) {
            var res = JSON.parse(response);

            res.forEach(function(booking){
                var id = 'slot-'+booking['slot_time'];
                var slot = document.querySelector('[id="'+id+'"]');
                slot.innerHTML = '[X]';
            });
            $('.booked-slots').show();
        }
    }).error(function(response) {
        alert("Unable to retrieve bookings");
    });
}

// AJAX to list staff according to activity selected
$('#service').change(function(){
    var activityID = $(this).val();
    var date = $('#date').val();

    $.ajax({
        url: '/roster/staff/get/byActivity',
        type: 'get',
        data: {
            'activityID': activityID,
            'date': date
        },
        success: function(response) {
            var res = JSON.parse(response);

            // hide the dropdown list
            $('#employee-list').hide();
            // hide all staff
            $('.employee-option').hide();

            // when array returned is not empty
            if(res.length > 0)
            {
                // reset staff selection
                $('#employee').val('0');
                // display available staff
                res.forEach(function(staff, index, array) {
                    var id = staff['employee_id'];
                    $('#employee-'+id).show();
                })
            }
            // if it's empty, set to not available
            else
                $('#employee').val('-1');

            // show the dropdown list
            $('#employee-list').show();

        }
    }).error(function(res){
        alert("Unable to retrieve roster");
    });
});