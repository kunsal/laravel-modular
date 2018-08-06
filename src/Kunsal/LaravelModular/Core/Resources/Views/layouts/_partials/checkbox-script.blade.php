<script>
    // Define global ids array
    var ids = [];

    // When With selected is clicked
    $(document).on('click', '.checkbox',function(){
        // Get the id of the clicked checkbox
        var id = $(this).val();
        // If checkbox is checked, push id into ids array
        if($(this).is(':checked')){
            ids.push(id);
        }else{ // remove id from ids array
            ids = jQuery.grep(ids, function(value) { // Keep this
                return value != id;
            });
        }

        toggle_get_invoice();
    });

    // Listen for check event and act on footer buttons
    function toggle_get_invoice(){
        if( $('.checkbox:checked').length > 0){
            $('.panel-footer').show();
        }else{
            $('.panel-footer').hide();
        }
    }

    // When check all is clicked
    $(document).on('click', '#check-all', function(){
        if($(this).is(':checked')){
            $('.checkbox').prop('checked',true);
            $('.checkbox').each(function(k,v){
                var id = $(this).val();
                ids.push(id);
            })
        }else{ // remove id from ids array
            $('.checkbox').prop('checked', false);
            $('.checkbox').each(function(k,v){
                var id = $(this).val();
                ids = jQuery.grep(ids, function(value) { // Keep this
                    return value != id;
                });
            });
        }
        toggle_get_invoice()

    });

    /*
     * Footer button events
     * */
    $(document).on('click', '.actions', function(){
        var action = $(this).data('action');
        var description = $(this).data('description');
        var route = $(this).data('route');

        if(confirm('Are you sure you want to '+description)){
            $('#loading-modal').modal({
                keyboard: false,
                backdrop: 'static',
                show: true
            });
            $.ajax({
                url: route,
                method: 'post',
                data: {_token:"{{ csrf_token() }}", ids:ids, action:action}
            }).done(function(data){
                alert(data);
                window.location.reload()
            });
        }

        return false;
    })
</script>