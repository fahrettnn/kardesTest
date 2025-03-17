$(document).ready(function() { 
    function getUserList(callback) {
        $.ajax({
            type: 'GET',
            url:  "../api/personnel-transcation",
            success: function(responseCity) {
                if (responseCity && responseCity.status_code === 200) {
                    callback(responseCity.data);
                }
            }
        });
    }

    function getRoleList() {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'GET',
                url: "../api/auth/user-roles",
                success: function(response) { resolve(response.data); },
                error: function(err) { reject(err); }
            });
        });
    }
    
    var personnelTable = $('#personnel_table').DataTable({
        responsive: true,
        select: true,
        search: {
            caseInsensitive: true // Aramaları büyük/küçük harf duyarlı yapar
        },
        language: {
            search: "Personel Ara", // Burada search anahtarını ekliyoruz
            emptyTable: "Herhangi Bir Personel Bulunamadı",
            zeroRecords: "Eşleşen Personel kaydı bulunamadı", // Arama sonucu boşsa gösterilen mesaj
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
        order: [[1, 'desc']]
    });

    getUserList(function(userList) { 
        getRoleList().then(roleList => {
            roleList.forEach(function(role) { 
                $('#role_select').append($('<option>').val(role.role.id).text(role.role.role)); 
            });
            processUserData(userList,roleList);
        }).catch(error => {
            console.error("Rol listesi alınırken bir hata oluştu:");
        });
    });

    function processUserData(userData,roleList) {
        userData.forEach(function(user) {
            let userRoles = user.roles;
            let roleName = "Rol Atanmadı";
            let roleStatus = 0;
            if(userRoles != null) {
                roleName = userRoles.role;
                roleStatus = userRoles.disabled;
            }

            let rolStatus = 'Pasif';
            let rolColor  = 'danger';
            if(user.status == 1) {
                rolStatus = 'Aktif';
                rolColor  = 'success';
            }

            const personelEditModal = EditShowModal(user,roleList);
            $('body').append(personelEditModal);

            const personelDeleteModal = deleteShowModel(user);
            $('body').append(personelDeleteModal);

            personnelTable.row.add([
                '<div class="col-12 badge badge-lg badge-dark fw-bold text-left">' + user.user_firstname + ' ' + user.user_lastname + '</div>',
                '<div class="col-12 badge badge-lg badge-dark fw-bold"><a href="mailto:'+user.user_email+'" style="color: inherit; text-decoration: none;">'+user.user_email+'</a></div>',
                '<div class="col-12 badge badge-lg badge-dark fw-bold">' + roleName + '</div>',
                '<div class="col-12 badge badge-lg badge-'+rolColor+' fw-bold">' + rolStatus + '</div>',
                '<div class="d-flex justify-content-center">' +
                '<button type="button" data-bs-target="#edit_personnel_modal' + user.user_id + '" data-bs-toggle="modal" class="btn btn-info shadow btn-xs me-1"><i class="fas fa-pencil-alt"></i></button>' +
                '<button type="button" data-bs-target="#delete_personnel_modal' + user.user_id + '" data-bs-toggle="modal" class="btn btn-danger shadow btn-xs"><i class="fas fa-trash"></i></button>' +
                '</div>'
            ]).draw(false);
        });
    }
    
    function EditShowModal(user, roleList) {
        const userId = user.user_id;
        const showModal = $('<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">').attr('id', 'edit_personnel_modal' + userId);
        const modalDialog = $('<div class="modal-dialog modal-lg">');
        const modalContent = $('<div class="modal-content">');
        const modalHeader = $('<div class="modal-header">').append(
            $('<h5 class="modal-title">').text(user.user_firstname + " " + user.user_lastname + " Personeli Düzenle"),
            $('<button type="button" class="btn-close" data-bs-dismiss="modal">')
        );
        const editForm = $('<form id="edit_personnel_form" method="PUT"><input type="hidden" data-id="userId' + userId + '" name="userId" value="' + userId + '">');
    
        let modalBodyContent;
    
        if (editUserPermission) 
        {
            let userRoleOptions = `<option value="">${please_select_personnel_role}</option>`;
            roleList.forEach(function(roleData) {
                const role = roleData.role;
                let isSelected = user.roles!=null && user.roles.id === role.id ? "selected" : "";
                userRoleOptions += `<option ${isSelected} value="${role.id}">${role.role}</option>`;
            });

            modalBodyContent = $('<div class="row pt-4">').append(
                $('<div class="col-xl-4">').append(
                    $('<label class="form-label">').text(personnel_first_name + " (*)"),
                    $('<input type="text" class="form-control" name="e_first_name" value="' + user.user_firstname + '" placeholder="Ayşe">')
                ),
                $('<div class="col-xl-4">').append(
                    $('<label class="form-label">').text(personnel_last_name + " (*)"),
                    $('<input type="text" class="form-control mb-3" name="e_last_name" value="' + user.user_lastname + '" placeholder="Yılmaz">')
                ),
                $('<div class="col-xl-4">').append(
                    $('<label class="form-label">').text(id_number),
                    $('<input type="text" class="form-control mb-3" name="e_id_number" value="' + user.id_number + '">')
                ),
                $('<div class="col-xl-4">').append(
                    $('<label class="form-label">').text(personnel_phone + " (*)"),
                    $('<input type="text" class="form-control mb-3" name="e_phone" value="' + user.user_phone + '" placeholder="+90 (000) 000 00 00">')
                ),
                $('<div class="col-xl-3">').append(
                    $('<label class="form-label">').text(personnel_status),
                    $('<select name="e_personnel_status" id="e_personnel_status" class="default-select form-control wide ms-0 mb-3">').append(
                        $('<option>').attr('value', '1').attr('selected', user.status == 1 ? 'selected' : false).text(active),
                        $('<option>').attr('value', '0').attr('selected', user.status == 0 ? 'selected' : false).text(passive)
                    )
                ),
                $('<div class="col-xl-5">').append(
                    $('<label class="form-label">').text(personnel_role),
                    $('<select name="e_role_select" id="e_role_select" class="default-select form-control wide ms-0 mb-3">').html(userRoleOptions)
                ),
                $('<div class="col-xl-5">').append(
                    $('<label class="form-label">').text(personnel_email + " (*)"),
                    $('<input type="text" class="form-control mb-3" name="e_email" value="' + user.user_email + '" placeholder="mail@example.com">')
                ),
                $('<div class="col-xl-7">').append(
                    $('<label class="form-label">').text(personnel_password),
                    $('<input type="text" class="form-control mb-3" name="e_password" value="" placeholder="Personelin parolasını değiştirmek için bu alanı düzenleyebilirsiniz">')
                )
            );
        } else {
            modalBodyContent = $('<div>').html(permissionFail);
        }
        const modalBody = $('<div class="modal-body">').append(modalBodyContent);
        const modalFooter = $('<div class="modal-footer">').append(
            $('<button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">').text(close_btn)
        );
        if (editUserPermission) {
            modalFooter.append(
                $('<button type="submit" id="personnel_edit_btn' + userId + '" class="btn btn-success btn-sm">').text("Personeli Düzenle")
            );
        }
        editForm.append(modalBody, modalFooter);
        modalContent.append(editForm);
        showModal.append(modalDialog.append(modalContent.prepend(modalHeader)));
    
        return showModal;
    }
    
    function deleteShowModel(user) {
        $(document).on('click', '#delete_user_btn' + user.user_id, function(e) {
            e.preventDefault();
            const userId = $(this).data("user");
            $("#delete_user_btn" + userId).prop("disabled", true);
            $.ajax({
                type: "DELETE",
                url: "../api/personnel-transcation/",
                data: {"userId":userId},
                success: function(response) {
                    showErrorSwalToast(response.status, "", response.message, function() {
                        $("#delete_user_btn" + userId).prop("disabled", false);
                        $('#delete_personnel_modal' + userId).modal('hide');
                        getUserList(function(userList) { 
                            getRoleList().then(roleList => {
                                personnelTable.clear().draw();
                                processUserData(userList, roleList);
                            });
                        });
                    });
                },
                error: function(error) {
                    var data = error.responseJSON;
                    $("#delete_user_btn" + userId).prop("disabled", false);
                    showErrorSwalToast("error", data.status, data.message);
                }
            });
        });
    
        const showModal = $('<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">').attr('id', 'delete_personnel_modal' + user.user_id);
        const modalDialog = $('<div class="modal-dialog modal-lg">');
        const modalContent = $('<div class="modal-content">');
        const modalHeader = $('<div class="modal-header">').append(
            $('<h5 class="modal-title">').text(user.user_firstname + " " + user.user_lastname + " Adlı Personeli Silme İşlemi"),
            $('<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">')
        );
    
        let modalBodyContent;
        if (deleteUserPermission) {
            modalBodyContent = $('<p class="text-justify">').text(user.user_firstname + ' adlı personeli siliyorsunuz! Bu işlem geri alınamaz!');
        } else {
            modalBodyContent = $('<div>').html(permissionFail);
        }
        const modalBody = $('<div class="modal-body">').append(modalBodyContent);
        const modalFooter = $('<div class="modal-footer">').append(
            $('<button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">').text("Kapat"),
        );
        if (deleteUserPermission) {
            modalFooter.append(
                $('<button type="button" id="delete_user_btn' + user.user_id + '" data-user="' + user.user_id + '" class="btn btn-success btn-sm">').text("Sil")
            );
        }
    
        showModal.append(modalDialog.append(modalContent.append(modalHeader, modalBody, modalFooter)));
        return showModal;
    }
    
    $(document).on("submit", "#new_personnel_form", function(e) {
        e.preventDefault();
        $("#personnel_add_btn").prop("disabled", true);
        $.ajax({
            url: '../api/personnel-transcation',
            type: "post",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                showErrorSwalToast(response.status, "", response.message, function() {
                    $("#personnel_add_btn").prop("disabled", false);
                    $('#addpersonalmodal').modal('hide');
                    $("#new_personnel_form")[0].reset();
                    getUserList(function(userList) { 
                        getRoleList().then(roleList => {
                            personnelTable.clear().draw();
                            processUserData(userList,roleList);
                        });
                    });
                });
            },
            error: function(error) {
                data = error.responseJSON;
                $("#personnel_add_btn").prop("disabled", false);
                showErrorSwalToast(data.status, "", data.message);
            }
        });
    });

    $(document).on("submit", "#edit_personnel_form", function(e) {
        e.preventDefault();
        var userId = $(this).find('input[name="userId"]').val();
        $("#personnel_edit_btn"+userId).prop("disabled", true);
        $.ajax({
            url: '../api/personnel-transcation',
            type: "PUT",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) 
            {
                showErrorSwalToast(response.status, "", response.message, function() {
                    $("#personnel_edit_btn"+userId).prop("disabled", false);
                    $('#edit_personnel_modal'+userId).modal('hide');
                    getUserList(function(userList) { 
                        getRoleList().then(roleList => {
                            personnelTable.clear().draw();
                            processUserData(userList,roleList);
                        });
                    });
                });
            },
            error: function(error) 
            {
                data = error.responseJSON;
                $("#personnel_edit_btn"+userId).prop("disabled", false);
                showErrorSwalToast(data.status, "", data.message);
            }
        });
    });
});
