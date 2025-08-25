$(document).ready(function() {


    $.fn.selected_icon = function() {
        return this.each(function() {
            var icon_select = $("#icon").find(':selected').attr('data-icon')
            $(".icon_label").empty().html( `<i class='${icon_select} fs-24'></i> `)
        })
    }

})