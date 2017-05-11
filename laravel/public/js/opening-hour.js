$('.checkbox').change(function(){
    var days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    days.forEach(function callback(currentValue, index, array) {
        var dayCheckbox = $('#checkbox-' + currentValue);
        var openingHour = $('#opening-hour-' + currentValue);

        console.log(currentValue, dayCheckbox);

        if(dayCheckbox[0].checked)
            openingHour.show();
        else
            openingHour.hide();
    });
});