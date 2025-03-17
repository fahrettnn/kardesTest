"use strict"
function addSwitcher()
{
    function createspan(id, name, value, labelClass) {
        const input = $('<input>', {
            class: 'chk-col-primary filled-in',
            id: id,
            name: name,
            type: 'radio',
            value: value
        });
        const label = $('<label>', {
            for: id,
            class: labelClass
        });
        return input.add(label);
    }

    var dlabSwitcher = $('<div class="sidebar-right">').append(
            '<div class="bg-overlay"></div>',
            $('<span>').append(
                $('<a class="sidebar-right-trigger wave-effect wave-effect-x" data-bs-toggle="tooltip" data-placement="right" data-original-title="Change Layout" href="javascript:void(0);">').append(
                    '<span><i class="fa fa-cog fa-spin"></i></span>'
                ),
                $('<a class="sidebar-close-trigger" href="javascript:void(0);">').append(
                    '<span><i class="la-times las"></i></span>'
                ),
                $('<div class="sidebar-right-inner">').append(
                    '<h4>Tarzınızı seçin <a href="javascript:void(0);" onclick="deleteAllCookie()" class="btn btn-primary btn-sm pull-right">Tüm Değişiklikleri Sil</a></h4>',
                    $('<div class="card-tabs">').append(
                        $('<ul class="nav nav-tabs" role="tablist">').append(
                            '<li class="nav-item"><a class="nav-link active" href="#tab1" data-bs-toggle="tab">Tema</a></li>',
                            '<li class="nav-item"><a class="nav-link" href="#tab2" data-bs-toggle="tab">Başlık</a></li>',
                            '<li class="nav-item"><a class="nav-link" href="#tab3" data-bs-toggle="tab">İçerik</a></li>'
                        ),
                    ),
                    $('<div class="tab-content tab-content-default tabcontent-border">').append(
                        $('<div class="fade tab-pane active show" id="tab1">').append(
                            $('<div class="admin-settings">').append(
                                $('<div class="row">').append(
                                    $('<div class="col-sm-12">').append(
                                        '<p>Arka plan</p>',
                                        $('<select class="default-select wide form-control" id="theme_version" name="theme_version"></select>').append(
                                            '<option value="light">Açık Tema</option>',
                                            '<option value="dark">Koyu Tema</option>'
                                        ),
                                    ),
                                    $('<div class="col-sm-6">').append(
                                        '<p>Ana renk</p>',
                                        $('<div>').append(
                                            $('<span data-placement="top" data-bs-toggle="tooltip" title="Color 1">').append(
                                                createspan("primary_color_1","primary_bg","color_1","bg-label-pattern")
                                            ),
                                            $('<span>').append(
                                                createspan("primary_color_2","primary_bg","color_2","")
                                            ),
                                            $('<span>').append(
                                                createspan("primary_color_3","primary_bg","color_3","")
                                            ),
                                            $('<span>').append(
                                                createspan("primary_color_4","primary_bg","color_4","")
                                            ),
                                            $('<span>').append(
                                                createspan("primary_color_5","primary_bg","color_5","")
                                            ),
                                            $('<span>').append(
                                                createspan("primary_color_6","primary_bg","color_6","")
                                            ),
                                            $('<span>').append(
                                                createspan("primary_color_7","primary_bg","color_7","")
                                            ),
                                            $('<span>').append(
                                                createspan("primary_color_8","primary_bg","color_8","")
                                            ),
                                            $('<span>').append(
                                                createspan("primary_color_9","primary_bg","color_9","")
                                            ),
                                            $('<span>').append(
                                                createspan("primary_color_10","primary_bg","color_10","")
                                            ),
                                            $('<span>').append(
                                                createspan("primary_color_11","primary_bg","color_11","")
                                            ),
                                            $('<span>').append(
                                                createspan("primary_color_12","primary_bg","color_12","")
                                            ),
                                            $('<span>').append(
                                                createspan("primary_color_13","primary_bg","color_13","")
                                            ),
                                            $('<span>').append(
                                                createspan("primary_color_14","primary_bg","color_14","")
                                            ),
                                            $('<span>').append(
                                                createspan("primary_color_15","primary_bg","color_15","")
                                            ),
                                        
                                        )
                                    ),
                                    $('<div class="col-sm-6">').append(
                                        '<p>Gezinme Başlığı</p>',
                                        $('<div>').append(
                                            $('<span>').append(
                                                createspan("nav_header_color_1","navigation_header","color_1","")
                                            ),
                                            $('<span>').append(
                                                createspan("nav_header_color_2","navigation_header","color_2","")
                                            ),
                                            $('<span>').append(
                                                createspan("nav_header_color_3","navigation_header","color_3","")
                                            ),
                                            $('<span>').append(
                                                createspan("nav_header_color_4","navigation_header","color_4","")
                                            ),
                                            $('<span>').append(
                                                createspan("nav_header_color_5","navigation_header","color_5","")
                                            ),
                                            $('<span>').append(
                                                createspan("nav_header_color_6","navigation_header","color_6","")
                                            ),
                                            $('<span>').append(
                                                createspan("nav_header_color_7","navigation_header","color_7","")
                                            ),
                                            $('<span>').append(
                                                createspan("nav_header_color_8","navigation_header","color_8","")
                                            ),
                                            $('<span>').append(
                                                createspan("nav_header_color_9","navigation_header","color_9","")
                                            ),
                                            $('<span>').append(
                                                createspan("nav_header_color_10","navigation_header","color_10","")
                                            ),
                                            $('<span>').append(
                                                createspan("nav_header_color_11","navigation_header","color_11","")
                                            ),
                                            $('<span>').append(
                                                createspan("nav_header_color_12","navigation_header","color_12","")
                                            ),
                                            $('<span>').append(
                                                createspan("nav_header_color_13","navigation_header","color_13","")
                                            ),
                                            $('<span>').append(
                                                createspan("nav_header_color_14","navigation_header","color_14","")
                                            ),
                                            $('<span>').append(
                                                createspan("nav_header_color_15","navigation_header","color_15","")
                                            ),
                                        )
                                    ),
                                    $('<div class="col-sm-6">').append(
                                        '<p>Header</p>',
                                        $('<div>').append(
                                            $('<span>').append(
                                                createspan("header_color_1","header_bg","color_1","bg-label-pattern")
                                            ),
                                            $('<span>').append(
                                                createspan("header_color_2","header_bg","color_2","")
                                            ),
                                            $('<span>').append(
                                                createspan("header_color_3","header_bg","color_3","")
                                            ),
                                            $('<span>').append(
                                                createspan("header_color_4","header_bg","color_4","")
                                            ),
                                            $('<span>').append(
                                                createspan("header_color_5","header_bg","color_5","")
                                            ),
                                            $('<span>').append(
                                                createspan("header_color_6","header_bg","color_6","")
                                            ),
                                            $('<span>').append(
                                                createspan("header_color_7","header_bg","color_7","")
                                            ),
                                            $('<span>').append(
                                                createspan("header_color_8","header_bg","color_8","")
                                            ),
                                            $('<span>').append(
                                                createspan("header_color_9","header_bg","color_9","")
                                            ),
                                            $('<span>').append(
                                                createspan("header_color_10","header_bg","color_10","")
                                            ),
                                            $('<span>').append(
                                                createspan("header_color_11","header_bg","color_11","")
                                            ),
                                            $('<span>').append(
                                                createspan("header_color_12","header_bg","color_12","")
                                            ),
                                            $('<span>').append(
                                                createspan("header_color_13","header_bg","color_13","")
                                            ),
                                            $('<span>').append(
                                                createspan("header_color_14","header_bg","color_14","")
                                            ),
                                            $('<span>').append(
                                                createspan("header_color_15","header_bg","color_15","")
                                            ),
                                        )
                                    ),
                                    $('<div class="col-sm-6">').append(
                                        '<p>Sidebar</p>',
                                        $('<div>').append(
                                            $('<span>').append(
                                                createspan("sidebar_color_1","sidebar_bg","color_1","bg-label-pattern")
                                            ),
                                            $('<span>').append(
                                                createspan("sidebar_color_2","sidebar_bg","color_2","")
                                            ),
                                            $('<span>').append(
                                                createspan("sidebar_color_3","sidebar_bg","color_3","")
                                            ),
                                            $('<span>').append(
                                                createspan("sidebar_color_4","sidebar_bg","color_4","")
                                            ),
                                            $('<span>').append(
                                                createspan("sidebar_color_5","sidebar_bg","color_5","")
                                            ),
                                            $('<span>').append(
                                                createspan("sidebar_color_6","sidebar_bg","color_6","")
                                            ),
                                            $('<span>').append(
                                                createspan("sidebar_color_7","sidebar_bg","color_7","")
                                            ),
                                            $('<span>').append(
                                                createspan("sidebar_color_8","sidebar_bg","color_8","")
                                            ),
                                            $('<span>').append(
                                                createspan("sidebar_color_9","sidebar_bg","color_9","")
                                            ),
                                            $('<span>').append(
                                                createspan("sidebar_color_10","sidebar_bg","color_10","")
                                            ),
                                            $('<span>').append(
                                                createspan("sidebar_color_11","sidebar_bg","color_11","")
                                            ),
                                            $('<span>').append(
                                                createspan("sidebar_color_12","sidebar_bg","color_12","")
                                            ),
                                            $('<span>').append(
                                                createspan("sidebar_color_13","sidebar_bg","color_13","")
                                            ),
                                            $('<span>').append(
                                                createspan("sidebar_color_14","sidebar_bg","color_14","")
                                            ),
                                            $('<span>').append(
                                                createspan("sidebar_color_15","sidebar_bg","color_15","")
                                            ),
                                        )
                                    ),
                                ),
                            ) 
                        ),
                        $('<div class="fade tab-pane" id="tab2"></div>').append(
                            $('<div class="admin-settings"></div>').append(
                                $('<div class="row"></div>').append(
                                    $('<div class="col-sm-6"><p>Layout</p></div>').append(
                                        $('<select class="default-select wide form-control" id="theme_layout" name="theme_layout"></select>').append(
                                            '<option value="vertical">Dikey</option>',
                                            '<option value="horizontal">Yatay</option>'
                                        )
                                    ),
                                    $('<div class="col-sm-6"><p>Başlık Pozisyon</p></div>').append(
                                        $('<select class="default-select wide form-control" id="header_position" name="header_position"></select>').append(
                                            '<option value="static">Statik</option>',
                                            '<option value="fixed">Sabit</option>'
                                        )
                                    ),
                                    $('<div class="col-sm-6"><p>Kenar çubuğu</p></div>').append(
                                        $('<select class="default-select wide form-control" id="sidebar_style" name="sidebar_style"></select>').append(
                                            '<option value="full">Full</option>',
                                            '<option value="mini">Mini</option>',
                                            '<option value="compact">Compact</option>',
                                            '<option value="modern">Modern</option>',
                                            '<option value="overlay">Overlay</option>',
                                            '<option value="icon-hover">Icon-hover</option>'
                                        )
                                    ),
                                    $('<div class="col-sm-6"><p>Kenar çubuğu konumu</p></div>').append(
                                        $('<select class="default-select wide form-control" id="sidebar_position" name="sidebar_position"></select>').append(
                                            '<option value="static">Statik</option>',
                                            '<option value="fixed">Sabit</option>'
                                        )
                                    )
                                )
                            )
                        ),
                        $('<div class="fade tab-pane" id="tab3"></div>').append(
                            $('<div class="admin-settings"></div>').append(
                                $('<div class="row"></div>').append(
                                    $('<div class="col-sm-6"><p>Konteyner</p></div>').append(
                                        $('<select class="default-select wide form-control" id="container_layout" name="container_layout"></select>').append(
                                            '<option value="wide">Wide</option>',
                                            '<option value="boxed">Boxed</option>',
                                            '<option value="wide-boxed">Wide Boxed</option>'
                                        )
                                    ),
                                    $('<div class="col-sm-6"><p>Yazı Tipi</p></div>').append(
                                        $('<select class="default-select wide form-control" id="typography" name="typography"></select>').append(
                                            '<option value="roboto">Roboto</option>',
                                            '<option value="poppins">Poppins</option>',
                                            '<option value="opensans">Open Sans</option>',
                                            '<option value="HelveticaNeue">HelveticaNeue</option>'
                                        )
                                    )
                                )
                            )
                        )
                    ),
                ),
            ));

	if($("#dlabSwitcher").length == 0) {
		$('body').append(dlabSwitcher);
			
		 const ps = new PerfectScrollbar('.sidebar-right-inner');
		 console.log(ps.reach.x);	
			ps.isRtl = false;
				
		  $('.sidebar-right-trigger,.sidebar-switcher-trigger').on('click', function() {
				$('.sidebar-right').toggleClass('show');
		  });
		  $('.sidebar-close-trigger,.bg-overlay').on('click', function() {
				$('.sidebar-right').removeClass('show');
		  });
	}
}

(function($) {
    "use strict"
	addSwitcher();
	
    const body = $('body');
    const html = $('html');

    //get the DOM elements from right sidebar
    const typographySelect = $('#typography');
    const versionSelect = $('#theme_version');
    const layoutSelect = $('#theme_layout');
    const sidebarStyleSelect = $('#sidebar_style');
    const sidebarPositionSelect = $('#sidebar_position');
    const headerPositionSelect = $('#header_position');
    const containerLayoutSelect = $('#container_layout');
    const themeDirectionSelect = $('#theme_direction');

    typographySelect.on('change', function() {
        body.attr('data-typography', this.value);
		
		setCookie('typography', this.value);
    });

    versionSelect.on('change', function() {
		body.attr('data-theme-version', this.value);
		
		/* if(this.value === 'dark'){
			//jQuery(".nav-header .logo-abbr").attr("src", "./images/logo-white.png");
			jQuery(".nav-header .logo-compact").attr("src", "images/logo-text-white.png");
			jQuery(".nav-header .brand-title").attr("src", "images/logo-text-white.png");
			
			setCookie('logo_src', './images/logo-white.png');
			setCookie('logo_src2', 'images/logo-text-white.png');
		}else{
			jQuery(".nav-header .logo-abbr").attr("src", "./images/logo.png");
			jQuery(".nav-header .logo-compact").attr("src", "images/logo-text.png");
			jQuery(".nav-header .brand-title").attr("src", "images/logo-text.png");
			
			setCookie('logo_src', './images/logo.png');
			setCookie('logo_src2', 'images/logo-text.png');
		} */
		
		setCookie('version', this.value);
    }); 
	
    sidebarPositionSelect.on('change', function() {
        this.value === "fixed" && body.attr('data-sidebar-style') === "modern" && body.attr('data-layout') === "vertical" ? 
        alert("Üzgünüz, Modern kenar çubuğu düzeni sabit konumu desteklemiyor!") :
        body.attr('data-sidebar-position', this.value);
		setCookie('sidebarPosition', this.value);
    });

    headerPositionSelect.on('change', function() {
        body.attr('data-header-position', this.value);
		setCookie('headerPosition', this.value);
    });

    themeDirectionSelect.on('change', function() {
        html.attr('dir', this.value);
        html.attr('class', '');
        html.addClass(this.value);
        body.attr('direction', this.value);
		setCookie('direction', this.value);
    });

    layoutSelect.on('change', function() {
        if(body.attr('data-sidebar-style') === 'overlay') {
            body.attr('data-sidebar-style', 'full');
            body.attr('data-layout', this.value);
            return;
        }

        body.attr('data-layout', this.value);
		setCookie('layout', this.value);
    });
    
    containerLayoutSelect.on('change', function() {
        if(this.value === "boxed") {

            if(body.attr('data-layout') === "vertical" && body.attr('data-sidebar-style') === "full") {
                body.attr('data-sidebar-style', 'overlay');
                body.attr('data-container', this.value);
                
                setTimeout(function(){
                    $(window).trigger('resize');
                },200);
                
                return;
            }
        }

        body.attr('data-container', this.value);
		setCookie('containerLayout', this.value);
    });

    sidebarStyleSelect.on('change', function() {
        if(body.attr('data-layout') === "horizontal") {
            if(this.value === "overlay") {
                alert("Üzgünüm! Yatay düzende kaplama mümkün değildir.");
                return;
            }
        }

        if(body.attr('data-layout') === "vertical") {
            if(body.attr('data-container') === "boxed" && this.value === "full") {
                alert("Üzgünüm! Dikey Kutulu düzende tam menü mevcut değildir.");
                return;
            }

            if(this.value === "modern" && body.attr('data-sidebar-position') === "fixed") {
                alert("Üzgünüm! Modern kenar çubuğu düzeni sabit konumda mevcut değildir. Lütfen kenar çubuğu konumunu Statik olarak değiştirin.");
                return;
            }
        }
		
		/* if(this.value === "modern") {
			//body.attr('data-sibebarbg') === "color_11"
			body.attr("data-sibebarbg", "color_12");
		} */

        body.attr('data-sidebar-style', this.value);

         if(body.attr('data-sidebar-style') === 'icon-hover') {
            $('.dlabnav').on('hover',function() {
			$('#main-wrapper').addClass('iconhover-toggle'); 
            }, function() {
			$('#main-wrapper').removeClass('iconhover-toggle'); 
            });
        } 
		
		setCookie('sidebarStyle', this.value);
	});

    $('input[name="navigation_header"]').on('click', function() {
		body.attr('data-nav-headerbg', this.value);
		setCookie('navheaderBg', this.value);
    });
    $('input[name="header_bg"]').on('click', function() {
        body.attr('data-headerbg', this.value);
		setCookie('headerBg', this.value);
    });
    $('input[name="sidebar_bg"]').on('click', function() {
        body.attr('data-sibebarbg', this.value);
		setCookie('sidebarBg', this.value);
    });
    $('input[name="primary_bg"]').on('click', function() {
        body.attr('data-primary', this.value);
		setCookie('primary', this.value);
    });
	$('input[name="secondary_bg"]').on('click', function() {
        body.attr('data-secondary', this.value);
		setCookie('secondary', this.value);
    });
})(jQuery);
