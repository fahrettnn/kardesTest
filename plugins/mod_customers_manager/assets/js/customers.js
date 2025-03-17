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

    function getApiDistricts(cityId, callback) {
        $.ajax({
            type: 'GET',
            url: "../api/ps/discrits",
            data: { cityId: cityId },
            success: function (response) {
                if (response && response.status_code === 200) {
                    callback(response.data);
                }
            }
        });
    }

    function getApiCustomersList(callback) {
        $.ajax({
            type: 'GET',
            url:  "../api/customers",
            success: function(response) {
                if (response && response.status_code === 200) {
                    callback(response.data);
                }
            }
        });
    }

    var customertable = $('#customerlistTable').DataTable({
		responsive: true,
        select: true,
        search: {
            caseInsensitive: true // Aramaları büyük/küçük harf duyarlı yapar
        },
        language: {
            search: "Müşteri Ara", // Burada search anahtarını ekliyoruz
            emptyTable: "Herhangi Bir Müşteri Bulunamadı",
            zeroRecords: "Eşleşen Müşteri kaydı bulunamadı", // Arama sonucu boşsa gösterilen mesaj
            infoEmpty: "Gösterilecek kayıt yok", // Tablo boşken bilgi satırı
            loadingRecords: "Müşteriler Yükleniyor...", // Kayıtlar yüklenirken gösterilen mesaj
            processing: "Veriler İşleniyor...", // İşlem yapılıyor mesajı
            infoFiltered: "(_MAX_ toplam kayıttan filtrelendi)", // Filtrelenmiş tablo bilgi satırı
            info: "Toplam _TOTAL_ kayıttan _START_ ile _END_ arası gösteriliyor", // Tablo bilgi satırı
            lengthMenu: "Sayfada _MENU_ Kayıt Göster", // Sayfa başına kayıt sayısı seçici
            paginate: {
                next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
            }
        },
	});

    getApiCityList(function (cities) {
        var citySelect = $('#citySelect');
        citySelect.append('<option value="">Lütfen İl Seçiniz</option>');

        $.each(cities, function (index, city) {
            citySelect.append('<option value="' + city.id + '">' + city.city_name + '</option>');
        });
    });

    $('#citySelect').on('change', function () {
        var cityId = $(this).val();
        var districtSelect = $('#district');
        districtSelect.prop('disabled', true).html('<option value="">Lütfen İlçe Seçiniz</option>');
        if (cityId) {
            getApiDistricts(cityId, function (districts) {
                $.each(districts, function (index, district) {
                    districtSelect.append('<option value="' + district.id + '">' + district.district_name + '</option>');
                });
                districtSelect.prop('disabled', false);
            });
        }
    });

    function processCustomerData(customerData)
    {
        customerData.forEach(function(customer) 
        {
            let companyStatus = 'Pasif';
            let companyColor  = 'danger';
            if(customer.company_status == 1) {
                companyStatus = 'Aktif';
                companyColor  = 'success';
            }
            customertable.row.add([
                '<div class="col-12 badge badge-lg badge-dark fw-bold text-left">' + customer.company_name + '</div>',
                '<div class="col-12 badge badge-lg badge-primary fw-bold text-left">' + customer.company_phone + '</div>',
                '<div class="col-12 badge badge-lg badge-warning fw-bold text-left">' + customer.company_email + '</div>',
                '<div class="col-12 badge badge-lg badge-' + companyColor + ' fw-bold">' + companyStatus + '</div>',
                '<div class="d-flex justify-content-center">' +
                '<button type="button" class="btn btn-info shadow btn-xs me-1 edit-city-btn" ' +
                'data-id="' + customer.customer_id + '"'+'data-bs-target="#view_city_modal" data-bs-toggle="modal">' +
                '<i class="fas fa-pencil-alt"></i></button>' +
                '<button type="button" class="btn btn-info shadow btn-xs me-1 edit-city-btn" ' +
                'data-id="' + customer.customer_id + '" data-name="' + customer.city_name + '" data-status="' + customer.status + '" ' +
                'data-bs-target="#edit_city_modal" data-bs-toggle="modal">' +
                '<i class="fas fa-pencil-alt"></i></button>' +
                '<button type="button" class="btn btn-danger shadow btn-xs me-1 delete-city-btn" ' +
                'data-id="' + customer.customer_id + '" data-name="' + customer.city_name + '"' +
                'data-bs-target="#delete_city_modal" data-bs-toggle="modal">' +
                '<i class="fas fa-trash"></i></button>' +
                '</div>'
            ]).draw(false);
        });
    }

    getApiCustomersList(function(customerList) {
        processCustomerData(customerList);
    });

    $('input[name="authorizedPhone"]').mask('+00 (000) 000 00 00');
	$('input[name="authorizedGsm"]').mask('+00 (000) 000 00 00');
	$('input[name="companyPhone"]').mask('+00 (000) 000 00 00');
	$('input[name="companyFax"]').mask('+00 (000) 000 00 00');
});