$('.checkbox').change(function(){
    var days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    days.forEach(function callback(currentValue, index, array) {
        var dayCheckbox = $('#checkbox-' + currentValue);
        var openingHour = $('#opening-hour-' + currentValue);

        if(dayCheckbox[0].checked)
        {
            openingHour.addClass('business-hrs');
            openingHour.show();
            $('#opening-time-' + currentValue).attr('required',true);
            $('#closing-time-' + currentValue).attr('required',true);
        }
        else
        {
            openingHour.hide();
            $('#opening-time-' + currentValue).removeAttr('required');
            $('#closing-time-' + currentValue).removeAttr('required');
        }
    });
});

$('.update-business-hour').click(function(){
    var day = $(this).attr('data-day');
    var days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    days.forEach(function callback(currentValue, index, array) {
        var dayCheckbox = $('#checkbox-' + currentValue);
        var openingHour = $('#opening-hour-' + currentValue);

        if(currentValue == day)
        {
            dayCheckbox[0].checked = true;
            openingHour.show();
            $('#opening-time-' + currentValue).attr('required',true);
            $('#closing-time-' + currentValue).attr('required',true);
        }
        else
        {
            dayCheckbox[0].checked = false;
            openingHour.hide();
            $('#opening-time-' + currentValue).removeAttr('required');
            $('#closing-time-' + currentValue).removeAttr('required');
        }
    });
});

