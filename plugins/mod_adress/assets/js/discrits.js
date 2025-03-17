$(document).ready(function() {
    // API URL'leri
    var getCitiesUrl = "../api/city"; // İller için API
    var getDistrictsUrl = "../api/discrits"; // İlçeler için API

    // İl listesini getir ve select içine ekle
    function getCityList(callback) {
        $.ajax({
            type: 'GET',
            url: getCitiesUrl,
            success: function(response) {
                if (response && response.status_code === 200) {
                    callback(response.data);
                }
            }
        });
    }

    // İlçeleri listeleyen DataTable tanımlaması
    var districtTable = $('#discrits_table').DataTable({
        responsive: true,
        select: true,
        search: {
            caseInsensitive: true
        },
        language: {
            search: "İlçe Ara",
            emptyTable: "Herhangi bir ilçe bulunamadı",
            zeroRecords: "Eşleşen ilçe kaydı bulunamadı",
            infoEmpty: "Gösterilecek kayıt yok",
            loadingRecords: "Yükleniyor...",
            processing: "İşleniyor...",
            infoFiltered: "(_MAX_ toplam kayıttan filtrelendi)",
            info: "Toplam _TOTAL_ kayıttan _START_ ile _END_ arası gösteriliyor",
            lengthMenu: "Sayfada _MENU_ Kayıt Göster",
            paginate: {
                next: '<i class="fa fa-angle-double-right"></i>',
                previous: '<i class="fa fa-angle-double-left"></i>' 
            }
        },
    });
    
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var searchValue = $('.dataTables_filter input').val().toLowerCase();
        var districtName = data[1].toLowerCase(); // İlçe adı sütunu
    
        return districtName.includes(searchValue);
    });
    // Seçilen ilin ilçelerini getir
    function getDistricts(cityId) {
        if (!cityId) {
            districtTable.clear().draw();
            return;
        }

        $.ajax({
            type: 'GET',
            url: getDistrictsUrl,
            data: { city_id: cityId },
            success: function(response) {
                if (response && response.status_code === 200) {
                    processDistrictData(response.data);
                }
            }
        });
    }

    // İlçeleri tabloya ekle
    function processDistrictData(districts) {
        districtTable.clear();
        districts.forEach(function(district) {
            let districtStatus = district.status == 1 ? "Aktif" : "Pasif";
            let districtColor = district.status == 1 ? "success" : "danger";

            districtTable.row.add([
                '<div class="badge badge-lg badge-dark fw-bold">' + district.id + '</div>',
                '<div class="badge badge-lg badge-primary fw-bold">' + district.district_name + '</div>',
                '<div class="badge badge-lg badge-' + districtColor + ' fw-bold">' + districtStatus + '</div>',
                '<div class="d-flex justify-content-center">' +
                '<button type="button" class="btn btn-info shadow btn-xs me-1 edit-district-btn" ' +
                'data-id="' + district.id + '" data-name="' + district.district_name + '" data-status="' + district.status + '" ' +
                'data-city-id="' + district.city_id + '" data-bs-target="#edit_discrits_modal" data-bs-toggle="modal">' +
                '<i class="fas fa-pencil-alt"></i></button>' +
                '<button type="button" class="btn btn-danger shadow btn-xs delete-district" data-id="' + district.id + '">' +
                '<i class="fas fa-trash"></i></button>' +
                '</div>'
            ]).draw(false);
        });
    }

    // Sayfa yüklendiğinde illeri getir
    getCityList(function(cityList) {
        let citySelect = $("#city_select_table");
        let newcitySelect = $("#city_select");
        citySelect.append('<option value="">Lütfen bir il seçin</option>');
        newcitySelect.append('<option value="">Lütfen bir il seçin</option>');
        cityList.forEach(function(city) {
            newcitySelect.append('<option value="' + city.id + '">' + city.city_name + '</option>');
            citySelect.append('<option value="' + city.id + '">' + city.city_name + '</option>');
        });
    });

    // İl seçildiğinde ilçeleri getir
    $("#city_select_table").change(function() {
        var cityId = $(this).val();
        districtTable.clear().draw(); // Önce eski ilçeleri temizle
        getDistricts(cityId); // Yeni ilçeleri getir
    });

    // Yeni ilçe ekleme işlemi
    $(document).on("submit", "#new_discrits_form", function(e) {
        e.preventDefault();
        $("#discrit_add_btn").prop("disabled", true);

        $.ajax({
            url: getDistrictsUrl,
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                showErrorSwalToast(response.status, "", response.message, function() {
                    $("#discrit_add_btn").prop("disabled", false);
                    $("#new_discrits_form")[0].reset();
                    getDistricts($("#city_select_table").val());
                });
            },
            error: function(error) {
                data = error.responseJSON;
                $("#discrit_add_btn").prop("disabled", false);
                showErrorSwalToast(data.status, "", data.message);
            }
        });
    });

    // İlçe düzenleme butonuna basıldığında modalı aç ve verileri yerleştir
    $(document).on("click", ".edit-district-btn", function() {
        let districtId = $(this).data("id");
        let districtName = $(this).data("name");
        let districtStatus = $(this).data("status");
        let cityId = $(this).data("city-id");
    
        // İlçe bilgilerini modal içine ekle
        $("#edit_discrit_id").val(districtId);
        $("#edit_discrit_name").val(districtName);
        $("#edit_discrit_status").val(districtStatus);
    
        // İllerin listesini al ve düzenleme dropdown'unu güncelle
        getCityList(function(cityList) {
            let citySelect = $("#edit_city_select");
            citySelect.empty(); // Önce temizle
            citySelect.append('<option value="">Lütfen bir il seçin</option>');
            
            cityList.forEach(function(city) {
                let selected = city.id == cityId ? "selected" : "";
                citySelect.append('<option value="' + city.id + '" ' + selected + '>' + city.city_name + '</option>');
            });
    
            // Modalı aç
            $("#edit_discrits_modal").modal("show");
        });
    });
    
    // İlçe düzenleme formunu gönder
    $(document).on("submit", "#edit_discrit_form", function(e) {
        e.preventDefault();
        $("#discrit_edit_btn").prop("disabled", true);
        $.ajax({
            url: getDistrictsUrl,
            type: "PUT",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                showErrorSwalToast(response.status, "", response.message, function() {
                    $("#discrit_edit_btn").prop("disabled", false);
                    $('#edit_discrits_modal').modal('hide');
                    getDistricts($("#city_select_table").val());
                });
            },
            error: function(error) {
                data = error.responseJSON;
                $("#discrit_edit_btn").prop("disabled", false);
                showErrorSwalToast(data.status, "", data.message);
            }
        });
    });


    $(document).on('click', '.delete-district', function() {
        let districtId = $(this).data('id');
        let districtName = $(this).closest('tr').find('td:eq(1)').text(); // İlçe adını al
    
        // Modal içeriğini güncelle
        $("#delete_district_id").val(districtId);
        $("#delete_district_name").text(districtName);
    
        // Modalı aç
        $("#delete_district_modal").modal("show");
    });

    $(document).on("click", "#confirm_delete_btn", function() {
        let districtId = $("#delete_district_id").val(); // Modal içinden ID al
        $.ajax({
            url: getDistrictsUrl,
            type: 'DELETE',
            data: {"discritId": districtId },
            success: function(response) {
                showErrorSwalToast(response.status, "", response.message, function() {
                    $("#delete_district_modal").modal("hide"); // Modalı kapat
                    districtTable.clear().draw();
                    getDistricts($("#city_select_table").val()); // DataTable'ı yenile
                });
            },
            error: function(error) {
                showErrorSwalToast(error.status, "", $error.message);
            }
        });
    });
});
