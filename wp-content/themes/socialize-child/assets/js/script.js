jQuery(document).ready(function($)
{
    $(document).on('click', '.btn-add-more', function(e)
    {
        var controlForm = $('#control_form');
        var newEntry = $($("#blank_entry").html());
        newEntry.appendTo(controlForm);
        
        newEntry.find('input').val('');
        controlForm.find('.entry:not(:last) .btn-add-more')
            .removeClass('btn-add-more').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="glyphicon glyphicon-minus"></span>');

        e.preventDefault();
    }).on('click', '.btn-remove', function(e)
    {
        $(this).parents('.entry:first').remove();

        e.preventDefault();
        return false;
    });
})
