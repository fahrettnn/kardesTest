$(document).ready(function() 
{ 
    function getApiCityList(callback) {
        $.ajax({
            type: 'GET',
            url:  "../api/ps/city",
            cache: true,
            success: function(responseCity) {
                if (responseCity && responseCity.status_code === 200) {
                    callback(responseCity.data);
                }
            }
        });
    }
    function getApiCustomersList(callback) {
        $.ajax({
            type: 'GET',
            url:  "../api/customers",
            success: function(response) {
                if (response && response.status === "success") {
                    callback(response.data);
                }
            }
        });
    }
    var customertable = $('#customerlistTable').DataTable({
		language: {
            emptyTable: emptyDataMessage,
			paginate: {
			  next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
			  previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
			}
        }
	});

    getApiCityList(function(cityData) 
    {
        $('#citySelect').empty().append($('<option>').val('').text(select_city));
        cityData.forEach(function(city) {
            $('#citySelect').append($('<option>').val(city.id).text(city.city_name));
        });
    
        $('#citySelect').change(function(e) {
            e.preventDefault();
            $('#district').show();
            const $editDistrictSelect = $('#district');
            const cityID = $(this).val();
            if (cityID != "") {
                $editDistrictSelect.prop('disabled', false);
                $editDistrictSelect.empty();
                const selectedCity = cityData.find(city => city.id == cityID);
                if (selectedCity) {
                    selectedCity.districts.forEach(function(district) {
                        $editDistrictSelect.append('<option value="' + district.id + '">' + district.district_name + '</option>');
                    });
                }
            } else {
                $editDistrictSelect.prop('disabled', true);
                $editDistrictSelect.empty();
                $editDistrictSelect.append('<option value="">'+select_district+'</option>');
            }
        });

        function processCustomerData(cityData,customerList)
        {
            customerList.forEach(function(data) 
            {
                const customerInfo  = data.customer_detail;
                const contactInfo   = data.contacts_detail;

                const viewmodal = viewShowModal(bankInfo,contactInfo,customerInfo);
                $('body').append(viewmodal);
                $('#viewmodal' + customerInfo.customer_id).click(function() 
                { $('#viewmodal' + customerInfo.customer_id).modal('show'); });

                const editmodal = editShowModel(cityData,customerInfo);
                $('body').append(editmodal);
                $('#editmodal' + customerInfo.customer_id).click(function() 
                {
                    $('#editmodal' + customerInfo.customer_id).modal('show');
                    $('#editmodal' + customerInfo.customer_id).on('change', '#editcity'+customerInfo.customer_id, function(e) {
                        const $editDistrictSelect = $('#editdistrict'+customerInfo.customer_id);
                        const cityID = $(this).val();
                        if (cityID != "") {
                            $editDistrictSelect.prop('disabled', false);
                            $editDistrictSelect.empty();
                            const selectedCity = cityData.find(city => city.id == cityID);
                            if (selectedCity) {
                                selectedCity.districts.forEach(function(district) {
                                    $editDistrictSelect.append('<option value="' + district.id + '">' + district.district_name + '</option>');
                                });
                            }
                        } else {
                            $editDistrictSelect.prop('disabled', true);
                            $editDistrictSelect.empty();
                            $editDistrictSelect.append('<option value="">'+select_city+'</option>');
                        }
                    });
                    $('input[id="companyPhone'+ customerInfo.customer_id+'"]').mask('+90 (000) 000 00 00');
                    $('input[id="companyFax'+ customerInfo.customer_id+'"]').mask('+90 (000) 000 00 00');
                });

                const deletemodel = deleteShowModel(customerInfo);
                $('body').append(deletemodel);
                $('#deletemodal' + customerInfo.customer_id).click(function() 
                { $('#deletemodal' + customerInfo.customer_id).modal('show'); });

                if(customerInfo.company_email != null)
                {
                    var email_href = 'mailto:'+ customerInfo.company_email;
                    var emailAdres = customerInfo.company_email;
                }else{
                    var email_href = 'javascript:void(0);';
                    var emailAdres = "Kayıt Yok";
                }

                customertable.row.add([
                    '<div class="col-12 badge badge-lg badge-info fw-bold">'+customerInfo.company_name+'</div>',
                    '<div class="col-12 badge badge-lg badge-'+taxpayer_type_color+'">'+taxpayer_type+'</div>',
                    '<div class="col-12 text-center"><a href="tel:'+customerInfo.company_phone+'"><strong>'+customerInfo.company_phone+'</strong></a></div>',
                    '<a class="col-12" href="'+email_href+'"><strong>'+emailAdres+'</strong></a>',
                    '<div class="d-flex">' +
                        '<a href="#viewmodal' + customerInfo.customer_id + '" data-bs-toggle="modal" class="btn btn-info shadow btn-xs sharp me-1"><i class="fas fa-eye"></i></a>' +
                        '<a href="#editmodal' + customerInfo.customer_id + '" data-bs-toggle="modal" class="btn btn-primary shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>' +
                        '<a href="#deletemodal' + customerInfo.customer_id + '" data-bs-toggle="modal" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a>' +
                    '</div>'
                ]).draw(false).node();
            });
        }

        getApiCustomersList(function(customerList) {
            processCustomerData(cityData, customerList);
        });

        function viewShowModal(bankInfo,contactInfo,customerInfo) 
        {
            const taxpayer_type = customerInfo.taxpayer_type == "1" ? real_person : legal_entity;
            const customerId    = customerInfo.customer_id;
            const showModal     = $('<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">').attr('id', 'viewmodal' + customerId);
            const modalDialog   = $('<div class="modal-dialog modal-lg">');
            const modalContent  = $('<div class="modal-content">');
            const modalHeader   = createModalHeader("Müşteri/Firma Detay Görüntüle");
    
            const tabItems = [
                { href: '#homeview' + customerId, html: '<i class="la la-home me-2"></i>' + customer_information, isActive: true },
                { href: '#contactview' + customerId, html: '<i class="la la-phone me-2"></i>' + customer_contact_information },
                { href: '#adressview' + customerId, html: '<i class="la la-map-marked me-2"></i>' + customer_adress_information },
                { href: '#inoviceview' + customerId, html: '<i class="la la-home me-2"></i>' + customer_invoice_information },
                { href: '#bankview' + customerId, html: '<i class="la la-home me-2"></i>' + customer_bank_information },
            ];
            const tabNav = createTabNav(tabItems);
            const homeTabContent = $('<div class="col-xl-12">').append(
                $('<label class="form-label">').text("Müşteri/Firma Adı"),
                $('<input class="form-control mb-3" disabled>').val(customerInfo.company_name),
                $('<hr>'),
                $('<div class="accordion accordion-start-indicator" id="accordion-five">').append(
                    contactInfo.length > 0 ?
                    $.map(contactInfo, function (contact, index) {
                        const accordionHeader = createAccordionItem(index, contact.contact_firstname + ' ' + contact.contact_lastname, '');
                        const inputRows = [
                            createInputWithLabel('Authorized Position', '', contact.contact_position),
                            createInputWithLabel(customer_authorized_email, customer_authorized_email, contact.contact_email),
                            createInputWithLabel(customer_authorized_phone, contact.contact_phone, contact.contact_phone),
                            createInputWithLabel(customer_authorized_gsm, customer_authorized_gsm, contact.authorizedGsm)
                        ];
                        const accordionBodyContent = $('<div class="accordion-body-text row">').append(inputRows);
                        return accordionHeader.find('.accordion__body').append(accordionBodyContent).end();
                    }) :
                    $('<div class="col-12 text-center badge badge-pill badge-info fs-lg"><span class="fs-5">'+authorized_contact_record_not_found+'</span></div>')
                )
            );
            const contactTabContent = $('<div class="row">').append(
                $('<div class="col-xl-6">').append(
                    $('<label class="form-label">').text(customer_company_email),
                    $('<input class="form-control mb-3" disabled>').val(customerInfo.company_email)
                ),
                $('<div class="col-xl-6">').append(
                    $('<label class="form-label">').text(customer_company_web),
                    $('<input class="form-control mb-3" disabled>').val(customerInfo.company_web)
                ),
                $('<div class="col-xl-6">').append(
                    $('<label class="form-label">').text(customer_company_phone),
                    $('<input class="form-control mb-3" disabled>').val(customerInfo.company_phone)
                ),
                $('<div class="col-xl-6">').append(
                    $('<label class="form-label">').text(customer_company_fax),
                    $('<input class="form-control mb-3" disabled>').val(customerInfo.company_fax)
                )
            );
            const adressTabContent = $('<div class="row">').append(
                $('<div class="col-xl-12">').append(
                    $('<label class="form-label">').text(adress_detail),
                    $('<textarea class="form-control h-auto mb-3" rows="4" disabled placeholder="'+adress_detail+'">').val(customerInfo.company_adress[0].adress_detail)
                ),
                $('<div class="col-xl-6">').append(
                    $('<input id="cityName" class="form-control mb-3" disabled>').val(customerInfo.company_adress[0].city_name )
                ),
                $('<div class="col-xl-6">').append(
                    $('<input class="form-control mb-3" disabled>').val(customerInfo.company_adress[0].district_name)
                )
            );
            const homeTabPane       = createTabPane('homeview' + customerId, homeTabContent, true);
            const contactTabPane    = createTabPane('contactview' + customerId, contactTabContent);
            const adressTabPane     = createTabPane('adressview' + customerId, adressTabContent);
    
            const tabContent    = $('<div class="tab-content">').append(homeTabPane,contactTabPane,adressTabPane);
            const modalBody     = $('<div class="modal-body">').append($('<div class="default-tab">').append(tabNav, tabContent));
        
            const modalFooter = $('<div class="modal-footer">').append(
                $('<button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">').text(close_btn)
            );
        
            showModal.append(modalDialog.append(modalContent.append(modalHeader, modalBody, modalFooter)));
            return showModal;
        }
    
        function editShowModel(cityData, customerInfo) 
        {
            const editcustomerId        = customerInfo.customer_id;
            const customerCityID        = customerInfo.company_adress[0].id;
            const customerDistrictID    = customerInfo.company_adress[0].id;

            /** Müşteri Güncelle */
            $(document).on("submit", "#edit_customer"+editcustomerId, function(e) 
            {
                e.preventDefault();
                $("#customer_edit_btn").prop("disabled", true);
                var formData = new FormData(this);
                $.ajax({
                    type: "PUT",
                    url: "../api/customers",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) 
                    {
                        $("#customer_edit_btn").prop("disabled", false);
                        showErrorSwalToast(response.status, "", response.message, function() 
                        {
                            if (response.status === "success") {
                                getApiCustomersList(function(customerlist) 
                                {
                                    $('#editmodal'+editcustomerId).modal('hide');
                                    customertable.clear().draw();
                                    processCustomerData(cityData,customerlist);
                                });
                            }
                        });
                    },
                    error: function(error) 
                    {
                        data = error.responseJSON;
                        $("#customer_edit_btn").prop("disabled", false);
                        showErrorSwalToast(data.status, "", data.message);
                    }
                });
            });

            const citySelect = $('<div class="col-xl-6">').append(
                $('<select name="cityId" id="editcity'+editcustomerId+'" class="form-control wide ms-0">').append(
                    $('<option value="">'+select_city+'</option>'),
                    createCityOptions(cityData, customerCityID)
                )
            );
    
            const showModal = $('<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">').attr('id', 'editmodal' + editcustomerId);
            const modalDialog = $('<div class="modal-dialog modal-lg">');
            const modalContent = $('<div class="modal-content">');
            const editForm = $('<form id="edit_customer'+editcustomerId+'" method="PUT"><input type="hidden" id="editcustomerId"  value="'+editcustomerId+'" name="customerId">');
            const modalHeader = $('<div class="modal-header">').append(
                $('<h5 class="modal-title">').text(customer_edit),
                $('<button type="button" class="btn-close" data-bs-dismiss="modal">')
            );
            let modalBody;
            let editbtncontent = "";

            if(editpermission)
            {
                modalBody = $('<div class="modal-body">').append(
                    $('<div class="default-tab">').append(
                        $('<ul class="nav nav-tabs" role="tablist">').append(
                            $('<li class="nav-item">').append($('<a class="nav-link active" data-bs-toggle="tab" href="#homeedit' + editcustomerId + '">').html('<i class="la la-home me-2"></i>' + customer_information)),
                            $('<li class="nav-item">').append($('<a class="nav-link" data-bs-toggle="tab" href="#contactedit' + editcustomerId + '">').html('<i class="la la-phone me-2"></i>' + customer_contact_information)),
                            $('<li class="nav-item">').append($('<a class="nav-link" data-bs-toggle="tab" href="#adressedit' + editcustomerId + '">').html('<i class="la la-map-marked me-2"></i>' + customer_adress_information)),
                            $('<li class="nav-item">').append($('<a class="nav-link" data-bs-toggle="tab" href="#inoviceedit' + editcustomerId + '">').html('<i class="la la-map-marked me-2"></i>' + customer_invoice_information))
                        ),
                        $('<div class="tab-content">').append(
                            $('<div class="tab-pane fade show active" id="homeedit' + editcustomerId + '" role="tabpanel">').append(
                                $('<div class="row pt-4">').append(
                                    $('<div class="col-xl-12">').append(
                                        $('<label class="form-label">').text(customer_company_name+' (*)'),
                                        $('<input type="text" name="companyName" class="form-control mb-3" placeholder="'+customer_company_name+'">').val(customerInfo.company_name),
                                    )
                                ),
                            ),
                            $('<div class="tab-pane fade" id="contactedit' + editcustomerId + '">').append(
                                $('<div class="row pt-4">').append(
                                    $('<div class="col-xl-6">').append(
                                        $('<label class="form-label">').text(customer_company_email),
                                        $('<input type="email" name="companyEmail" class="form-control mb-3" placeholder="'+customer_company_email+'">').val(customerInfo.company_email)
                                    ),
                                    $('<div class="col-xl-6">').append(
                                        $('<label class="form-label">').text(customer_company_web),
                                        $('<input type="text" name="companyWeb" class="form-control mb-3" placeholder="'+customer_company_web+'">').val(customerInfo.company_web)
                                    ),
                                    $('<div class="col-xl-6">').append(
                                        $('<label class="form-label">').text(customer_company_phone+' (*)'),
                                        $('<input type="text" name="companyPhone" id="companyPhone' + editcustomerId + '" class="form-control mb-3" placeholder="'+customer_company_phone+'">').val(customerInfo.company_phone)
                                    ),
                                    $('<div class="col-xl-6">').append(
                                        $('<label class="form-label">').text(customer_company_fax),
                                        $('<input type="text" name="companyFax" id="companyFax' + editcustomerId + '"  class="form-control mb-3" placeholder="'+customer_company_fax+'">').val(customerInfo.company_fax)
                                    )
                                )
                            ),
                            $('<div class="tab-pane fade" id="adressedit' + editcustomerId + '">').append(
                                $('<div class="row pt-4">').append(
                                    $('<div class="col-xl-12">').append(
                                        $('<label class="form-label">').text(adress_detail+' (*)'),
                                        $('<textarea name="adressDetail" class="form-control h-auto mb-3" rows="4" placeholder="'+adress_detail+'">').val(customerInfo.company_adress[0].adress_detail)
                                    ),
                                    citySelect,
                                    $('<div class="col-xl-6">').append(
                                        $('<select name="districtId" id="editdistrict'+editcustomerId+'" class="form-control wide ms-0">').append(
                                            $('<option value="">'+select_district+'</option>'),
                                            getDistrictOptions(cityData, customerInfo.company_adress[0].city_id, customerInfo.company_adress[0].district_id)
                                        )
                                    )
                                )
                            ),
                        )
                    )
                );
                editbtncontent = $('<button type="submit" id="customer_edit_btn" class="btn btn-success btn-sm">').text("Güncelle");
            }else{
                modalBody = $('<div class="modal-body">').append(
                    $('<div>').html(permissionFail)
                );
            }

            const modalFooter = $('<div class="modal-footer">').append(
                $('<button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">').text(close_btn),
                editbtncontent
            );
            editForm.append(modalBody, modalFooter);
            modalContent.append(editForm);
            showModal.append(modalDialog.append(modalContent.prepend(modalHeader)));
            return showModal;
        }
    
        function deleteShowModel(customerInfo) {
            const showModal = $('<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">').attr('id', 'deletemodal' + customerInfo.customer_id);
            const modalDialog = $('<div class="modal-dialog modal-lg">');
            const modalContent = $('<div class="modal-content">');
            const modalHeader = $('<div class="modal-header">').append(
                $('<h5 class="modal-title">').text(customer_delete),
                $('<button type="button" class="btn-close" data-bs-dismiss="modal">')
            );
            let modalBody;
            let deleteButton;
            if(deletepermission)
            {
                modalBody = $('<div class="modal-body">').append(
                    $('<p class="text-center">').text(customerInfo.company_name + ' ' + customer_delete_message),
                );
            }else{
                modalBody = $('<div class="modal-body">').append(
                    $('<div>').html(permissionFail)
                );
            }

            if(deletepermission)
            {
                deleteButton = $('<button type="button" id="delete_customer_btn' + customerInfo.customer_id + '" class="btn btn-success btn-sm">').text("Sil");
                deleteButton.click(function(e) 
                {
                    e.preventDefault();
                    const customerId = customerInfo.customer_id;
                    $("#delete_customer_btn"+customerId).prop("disabled", true);
                    $.ajax({
                        type: "DELETE",
                        url: "../api/customers",
                        data: {customerId: customerId},
                        success: function(response) 
                        {
                            $("#delete_customer_btn"+customerId).prop("disabled", false);
                            showErrorSwalToast("success","", response.message, function() 
                            {
                                if (response.status === 'success') {
                                    getApiCustomersList(function(customerlist) 
                                    {
                                        $('#deletemodal'+customerId).modal('hide');
                                        customertable.clear().draw();
                                        processCustomerData(cityData,customerlist);
                                    });
                                }
                            });
                        },
                        error: function(error) 
                        {
                            data = error.responseJSON;
                            $("#delete_customer_btn"+customerId).prop("disabled", false);
                            showErrorSwalToast("error","",data.message);
                        }
                    });
                });
            }
            

            const modalFooter = $('<div class="modal-footer">').append(
                $('<button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">').text(close_btn),
                deleteButton
            );
            showModal.append(modalDialog.append(modalContent.append(modalHeader, modalBody, modalFooter)));
            return showModal;
        }

        $(document).on("submit", "#customer_add", function(e) 
        {
            e.preventDefault();
            $("#customer_add_btn").prop("disabled", true);
            var formData = new FormData(this);
            $.ajax({
                type: "post",
                url: "../api/customers",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) 
                {
                    $("#customer_add_btn").prop("disabled", false);
                    showErrorSwalToast(response.status, "", response.message, function() 
                    {
                        getApiCustomersList(function(customerlist) 
                        {
                            $('#addcustomersmodal').modal('hide');
                            $('#customer_add')[0].reset();
                            customertable.clear().draw();
                            processCustomerData(cityData,customerlist);
                        });
                    });
                },
                error: function(error) 
                {
                    data = error.responseJSON;
                    $("#customer_add_btn").prop("disabled", false);
                    showErrorSwalToast(data.status, "", data.message);
                }
            });
        });
    });

    
    function createModalHeader(title) {
        return $('<div class="modal-header">').append(
            $('<h5 class="modal-title">').text(title),
            $('<button type="button" class="btn-close" data-bs-dismiss="modal">')
        );
    }
    function createTabPane(tabId, content, isActive = false) {
        const tabPane = $('<div class="tab-pane fade">').attr('id', tabId).append(
            $('<div class="row pt-4">').append(content)
        );
        if (isActive) {
            tabPane.addClass('show active');
        }
        return tabPane;
    }
    function createTabNav(tabItems) {
        const navTabs = $('<ul class="nav nav-tabs" role="tablist">');
        tabItems.forEach(tab => {
            const navLink = $('<a class="nav-link">').attr('data-bs-toggle', 'tab').attr('href', tab.href).html(tab.html);
            if (tab.isActive) {
                navLink.addClass('active');
            }
            navTabs.append(
                $('<li class="nav-item">').append(navLink)
            );
        });
        return navTabs;
    }
    function createAccordionItem(index, headerText, bodyContent) {
        return $('<div class="accordion-item">').append(
            $('<div class="accordion-header rounded-lg collapsed" data-bs-toggle="collapse" role="button">').attr('data-bs-target', '#collapse-' + index).append(
                $('<span class="accordion-header-text">').text(headerText),
                $('<span class="accordion-header-indicator">')
            ),
            $('<div class="accordion__body collapse" data-bs-parent="#accordion-five">').attr('id', 'collapse-' + index).append(bodyContent)
        );
    }
    function createInputWithLabel(labelText, placeholder, value) {
        return $('<div>').append(
            $('<label class="form-label">').text(labelText),
            $('<input class="form-control mb-3" disabled>').attr('placeholder', placeholder).val(value)
        );
    }
    function createCityOptions(cityData, customerCityID) {
        const options = cityData.map(function(city) {
            const option = $('<option>').val(city.city_id).text(city.city_name);
            if (city.city_id == customerCityID) {
                option.attr('selected', 'selected');
            }
            return option;
        });
        return options;
    }
    function getDistrictOptions(cityData, customerCityID, customerDistrictID) {
        const city = cityData.find(function(city) {
            return city.city_id == customerCityID;
        });
    
        if (city && city.districts) {
            return city.districts.map(function(district) {
                const disoption = $('<option>').val(district.district_id).text(district.district_name);
                if (district.district_id == customerDistrictID) {
                    disoption.attr('selected', 'selected');
                }
                return disoption;
            });
        } else {
            return [];
        }
    }
    $('input[name="authorizedPhone"]').mask('+00 (000) 000 00 00');
	$('input[name="authorizedGsm"]').mask('+00 (000) 000 00 00');
	$('input[name="companyPhone"]').mask('+00 (000) 000 00 00');
	$('input[name="companyFax"]').mask('+00 (000) 000 00 00');
});