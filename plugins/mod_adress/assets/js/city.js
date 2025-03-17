$(document).ready(function() {
    function getCityList(callback) {
        $.ajax({
            type: 'GET',
            url: '../api/city',
            success: function(responseCity) {
                if (responseCity && responseCity.status_code === 200) {
                    callback(responseCity.data);
                }
            }
        });
    }
    var cityTable = $('#city_table').DataTable({
        responsive: true,
        select: true,
        search: {
            caseInsensitive: true // Aramaları büyük/küçük harf duyarlı yapar
        },
        language: {
            search: "İl Ara", // Burada search anahtarını ekliyoruz
            emptyTable: "Herhangi Bir İl Bulunamadı",
            zeroRecords: "Eşleşen İl kaydı bulunamadı", // Arama sonucu boşsa gösterilen mesaj
            infoEmpty: "Gösterilecek kayıt yok", // Tablo boşken bilgi satırı
            loadingRecords: "Yükleniyor...", // Kayıtlar yüklenirken gösterilen mesaj
            processing: "İşleniyor...", // İşlem yapılıyor mesajı
            infoFiltered: "(_MAX_ toplam kayıttan filtrelendi)", // Filtrelenmiş tablo bilgi satırı
            info: "Toplam _TOTAL_ kayıttan _START_ ile _END_ arası gösteriliyor", // Tablo bilgi satırı
            lengthMenu: "Sayfada _MENU_ Kayıt Göster", // Sayfa başına kayıt sayısı seçici
            paginate: {
                next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
            }
        },
    });
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var searchValue = $('.dataTables_filter input').val().toLowerCase();
        var cityName = data[1].toLowerCase(); // il adı sütunu
    
        return cityName.includes(searchValue);
    });

    function processCityData(cityData) {
        cityData.forEach(function(city) {
            let cityStatus = 'Pasif';
            let cityColor  = 'danger';
            if(city.status == 1) {
                cityStatus = 'Aktif';
                cityColor  = 'success';
            }
            cityTable.row.add([
                '<div class="col-12 badge badge-lg badge-dark fw-bold text-left">TR -' + city.id + '</div>',
                '<div class="col-12 badge badge-lg badge-primary fw-bold text-left">' + city.city_name + '</div>',
                '<div class="col-12 badge badge-lg badge-' + cityColor + ' fw-bold">' + cityStatus + '</div>',
                '<div class="d-flex justify-content-center">' +
                '<button type="button" class="btn btn-info shadow btn-xs me-1 edit-city-btn" ' +
                'data-id="' + city.id + '" data-name="' + city.city_name + '" data-status="' + city.status + '" ' +
                'data-bs-target="#edit_city_modal" data-bs-toggle="modal">' +
                '<i class="fas fa-pencil-alt"></i></button>' +
                '<button type="button" class="btn btn-danger shadow btn-xs me-1 delete-city-btn" ' +
                'data-id="' + city.id + '" data-name="' + city.city_name + '"' +
                'data-bs-target="#delete_city_modal" data-bs-toggle="modal">' +
                '<i class="fas fa-trash"></i></button>' +
                '</div>'
            ]).draw(false);
        });
    }

    getCityList(function(cityList) { processCityData(cityList); });

    $(document).on("submit", "#new_city_form", function(e) {
        e.preventDefault();
        $("#city_add_btn").prop("disabled", true);
        $.ajax({
            url: '../api/city',
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                showErrorSwalToast(response.status, "", response.message, function() {
                    $("#city_add_btn").prop("disabled", false);
                    //$('#add_city_modal').modal('hide');
                    $("#new_city_form")[0].reset();
                    getCityList(function(cityList) { 
                        cityTable.clear().draw();
                        processCityData(cityList);
                    });
                });
            },
            error: function(error) {
                data = error.responseJSON;
                $("#city_add_btn").prop("disabled", false);
                showErrorSwalToast(data.status, "", data.message);
            }
        });
    });

    $(document).on("click", ".edit-city-btn", function() {
        let cityId = $(this).data("id"); // Butonun data-id özelliğinden şehir ID'sini al
        let cityName = $(this).data("name"); // Şehir adını al
        let cityStatus = $(this).data("status"); // Şehir durumunu al
    
        // Modal içindeki inputlara bu verileri yerleştir
        $("#edit_city_id").val(cityId);
        $("#edit_city_name").val(cityName);
        $("#edit_city_status").val(cityStatus);
    
        // Modalı aç
        $("#edit_city_modal").modal("show");
    });

    $(document).on("submit", "#edit_city_form", function(e) {
        e.preventDefault();
        $("#city_edit_btn").prop("disabled", true);
        $.ajax({
            url: '../api/city',
            type: "PUT",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                showErrorSwalToast(response.status, "", response.message, function() {
                    $("#city_edit_btn").prop("disabled", false);
                    $('#edit_city_modal').modal('hide');
                    getCityList(function(cityList) { 
                        cityTable.clear().draw();
                        processCityData(cityList);
                    });
                });
            },
            error: function(error) {
                data = error.responseJSON;
                $("#city_edit_btn").prop("disabled", false);
                showErrorSwalToast(data.status, "", data.message);
            }
        });
    });

    $(document).on("click", ".delete-city-btn", function() {
        let cityId = $(this).data("id");
        let cityName = $(this).data("name");
    
        $("#delete_city_id").val(cityId);
        $("#delete_city_name").text(cityName);
        $("#delete_city_modal").modal("show");
    });

    $(document).on("click", "#confirm_delete_city_btn", function() {
        let cityId = $("#delete_city_id").val();
        $("#confirm_delete_city_btn").prop("disabled", true);
        $.ajax({
            url: '../api/city',
            data: {"cityId":cityId},
            type: 'DELETE',
            success: function(response) {
                showErrorSwalToast(response.status, "", response.message, function() {
                    $("#confirm_delete_city_btn").prop("disabled", false);
                    $('#delete_city_modal').modal('hide');
                    getCityList(function(cityList) { 
                        cityTable.clear().draw();
                        processCityData(cityList);
                    });
                });
            },
            error: function(error) {
                data = error.responseJSON;
                $("#confirm_delete_city_btn").prop("disabled", false);
                showErrorSwalToast(data.status, "", data.message);
            }
        });
    });
});
