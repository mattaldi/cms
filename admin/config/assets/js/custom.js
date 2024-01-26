	

    $(document).ready(function(){
        // Setting datatable defaults
        $.extend($.fn.dataTable.defaults, {
            autoWidth: false,
            responsive: true,
            dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: {
                    'first': 'First',
                    'last': 'Last',
                    'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                    'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
                }
            }
        }); 
    
        // Apply custom style to select
        $.extend($.fn.dataTableExt.oStdClasses, {
            "sLengthSelect": "custom-select"
        });
 
	 
        $.fn.getParameterValue = function(data) {
            document.form1.faculty.value = data;
        };


        $(".close-colorbox").on("click", function(e){

            parent.jQuery.colorbox.close();
        
        });
        
    

        $(".select_data").colorbox({width:"80%", height:"90%", iframe:true,          
        onClosed:function(){ 
            location.reload(true); 
        } }); 

        $(".select_data2").colorbox({width:"90%", height:"90%", iframe:true,          
        onClosed:function(){ 
//                	location.reload(true); //uncomment this line if you want to refresh the page when child close
        } }); 
  
	  

 
    });