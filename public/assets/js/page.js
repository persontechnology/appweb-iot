var block= $.dialog({
    title: '',
    content: 'Procesando solicitud. Por favor, espera.',
    type: 'blue',
    theme: 'modern',
    icon: 'fas fa-spinner fa-spin',
    typeAnimated: true,
    closeIcon: false,
    lazyOpen: true,
    
});

$.validator.setDefaults( {
    submitHandler: function(form) {
        block.open();
        form.submit();
    },
    errorElement: "strong",
    errorPlacement: function ( error, element ) {
        error.addClass( "invalid-feedback" );

        if ( element.prop( "type" ) === "checkbox" ) {
            error.insertAfter( element.next( "label" ) );
        } else {
            error.insertAfter( element );
        }
    },
    highlight: function ( element, errorClass, validClass ) {
        $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
    },
    unhighlight: function (element, errorClass, validClass) {
        $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
    }
} );

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('table').on('draw.dt', function() {
    $('[data-bs-popup="tooltip"]').tooltip();
})

function eliminar(arg){
    var url = $(arg).attr('href');
    var msg = $(arg).data('msg')??'...';
    $.confirm({
        title: 'Está seguro de eliminar.!',
        content: String(msg),
        type: 'blue',
        theme: 'modern',
        icon: 'fa fa-trash fa-2x',
        typeAnimated: true,
        buttons: {
            SI: {
                
                action: function(){
                    block.open();
                    $('#formEliminar').attr('action', url);
                    $("#formEliminar").submit();
                    
                }
            },
            NO: function () {
            }
        }
    });
}