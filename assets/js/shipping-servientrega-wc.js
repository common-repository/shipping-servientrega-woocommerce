(function($){

    $('button.wc_ss_action_generate_sticker').click(function (e) {
        e.preventDefault();

        $.ajax({
           data: {
               action: 'servientrega_generate_sticker',
               nonce: $(this).data("nonce"),
               guide_number: $(this).data("guide")
           },
           type: 'POST',
           url: ajaxurl,
           dataType: "json",
           beforeSend : () => {
               Swal.fire({
                    title: 'Generando stickers de la guía',
                    onOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
               });
           },
           success: (r) => {
               if (r.status){
                   Swal.close();
                   window.location.replace(r.url);
               }else{
                   Swal.fire(
                       'Error',
                       r.message,
                       'error'
                   );
               }
           }
        });
    });

    let sizesContainer = $('#sizes_options');

    let fieldBoxSize = `<tr>
                                <td><input type="checkbox" class="chosen_box"></td>
                                <td><input type="text" name="packing[name_box_size][]" required></td>
                                <td><input type="text" name="packing[length_box_size][]" class="wc_input_price" required></td>
                                <td><input type="text" name="packing[width_box_size][]" class="wc_input_price" required></td>
                                <td><input type="text" name="packing[height_box_size][]" class="wc_input_price" required></td>
                                <td><input type="text" name="packing[max_weight_box_size][]" class="wc_input_price" required></td>
                            </tr>`;

    sizesContainer.find('.add').click(function (){
        sizesContainer.find('tbody').append(fieldBoxSize);
    });


    sizesContainer.on('click', '.remove', function (){

        sizesContainer.find('.chosen_box:checked').each(function () {
            $(this).parent().parent('tr').remove();
        })
    });


})(jQuery);