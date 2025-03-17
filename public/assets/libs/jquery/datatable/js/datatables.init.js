(function($) {
     "use strict"
	
    //example 1
    var table = $('#example').DataTable({
        createdRow: function ( row, data, index ) {
           $(row).addClass('selected')
        } ,
		language: {
			paginate: {
			   next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
			  previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
			}
		  }
    });
      
    table.on('click', 'tbody tr', function() {
    var $row = table.row(this).nodes().to$();
    var hasClass = $row.hasClass('selected');
    if (hasClass) {
        $row.removeClass('selected')
    } else {
        $row.addClass('selected')
    }
    })
    
    table.rows().every(function() {
    this.nodes().to$().removeClass('selected')
    });
	

    //example 2
    var table2 = $('#example2').DataTable( {
        createdRow: function ( row, data, index ) {
            $(row).addClass('selected')
        },

        "scrollY":        "42vh",
        "scrollCollapse": true,
        "paging":         false
    });

    table2.on('click', 'tbody tr', function() {
        var $row = table2.row(this).nodes().to$();
        var hasClass = $row.hasClass('selected');
        if (hasClass) {
            $row.removeClass('selected')
        } else {
            $row.addClass('selected')
        }
    })
        
    table2.rows().every(function() {
        this.nodes().to$().removeClass('selected')
    });
	
	// dataTable1
	var table = $('#dataTable1').DataTable({
		searching: false,
		paging:true,
		select: false,         
		lengthChange:false ,
		
	});
	// dataTable2
	var table = $('#dataTable2').DataTable({
		searching: false,
		paging:true,
		select: false,         
		lengthChange:false ,
		
	});
	// dataTable3
	var table = $('#dataTable3').DataTable({
		searching: false,
		paging:true,
		select: false,         
		lengthChange:false ,
		
	});
	// dataTable4
	var table = $('#dataTable4').DataTable({
		searching: false,
		paging:true,
		select: false,         
		lengthChange:false,
		
	});
   
	// dataTable5
	var table = $('#example5').DataTable({
		searching: false,
		paging:false,
		select: false,
		info: false,         
		lengthChange:false ,
		language: {
			paginate: {
			  next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
			  previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
			}
		  }
		
	}); 
	
	// dataTable6
	var table = $('#example6').DataTable({
		searching: false,
		paging:true,
		select: false,
		info: false,         
		lengthChange:false ,
		language: {
		paginate: {
			next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
			previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
		}
		}
		
	});
		
	// dataTable7
	var table = $('#example7').DataTable({
		searching: false,
		paging:true,
		select: false,
		info: true,         
		lengthChange:false ,
		language: {
			paginate: {
			   next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
			  previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
			}
		  }
		
	}); 	
	// dataTable9
		
	// table row
	var table = $('#dataTable1, #dataTable2, #dataTable3, #dataTable4,  #example3, #example4').DataTable({
		language: {
			paginate: {
			  next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
			  previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
			}
		  }
	});
	
	$('#example tbody').on('click', 'tr', function () {
		var data = table.row( this ).data();
	});
   
	// application table
	var table = $('#application-tbl1,#application-tbl2,#application-tbl3,#application-tbl4 ').DataTable({
		searching: false,
		lengthChange:false ,
		language: {
			paginate: {
			  next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
			  previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
			}
		  }
	});
	
})(jQuery);
