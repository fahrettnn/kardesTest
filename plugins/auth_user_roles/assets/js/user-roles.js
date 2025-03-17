$(document).ready(function() 
{ 
    function getRoleList(callback) {
        $.ajax({
            type: 'GET',
            url:  "../api/auth/user-roles",
            success: function(responseCity) {
                if (responseCity && responseCity.status_code === 200) {
                    callback(responseCity.data);
                }
            }
        });
    }

    var personnelRolesTable = $('#personnel_roles_table').DataTable({
        responsive: true,
        select: true,
        search: {
            caseInsensitive: true // Aramaları büyük/küçük harf duyarlı yapar
        },
		language: {
            search: "Rollerde Ara", // Burada search anahtarını ekliyoruz
            emptyTable: "Herhangi Bir Personel Rolü Bulunamadı",
            zeroRecords: "Eşleşen Rol kaydı bulunamadı", // Arama sonucu boşsa gösterilen mesaj
            infoEmpty: "Gösterilecek kayıt yok", // Tablo boşken bilgi satırı
            loadingRecords: "Yükleniyor...", // Kayıtlar yüklenirken gösterilen mesaj
            processing: "İşleniyor...", // İşlem yapılıyor mesajı
            infoFiltered: "(_MAX_ toplam kayıttan filtrelendi)", // Filtrelenmiş tablo bilgi satırı
            info: "Toplam _TOTAL_ kayıttan _START_ ile _END_ arası gösteriliyor", // Tablo bilgi satırı
            lengthMenu: "Rollerden _MENU_ Kayıt Göster", // Sayfa başına kayıt sayısı seçici
			paginate: {
			  next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
			  previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
			}
        },
        order: [[1, 'desc']]
	});

    function processRoleData(roleData,permissionsApp) 
    {
        roleData.forEach(function(roleData) 
        {
            let roles = roleData.role;
            let permissions = roleData.permissions;

            rolStatus = 'Pasif';
            rolColor  = 'danger';
            if(roles.disabled == 0)
            {
                rolStatus = 'Aktif';
                rolColor  = 'success';
            }

            const personelRolesModal = personelShowModal(roles,permissionsApp, permissions);
            $('body').append(personelRolesModal);
            $('#personell_roles_' + roles.id).click(function() 
            { $('#personell_roles_' + roles.id).modal('show'); });


            const personelEditRolesModal = EditRolesShowModal(roles);
            $('body').append(personelEditRolesModal);
            $('#editmodal' + roles.id).click(function(){ $('#editmodal' + roles.id).modal('show'); });

            const personelDeleteRolesModal = deleteShowModel(roles);
            $('body').append(personelDeleteRolesModal);

            personnelRolesTable.row.add([
                '<div class="col-12 badge badge-lg badge-info fw-bold">' + roles.role + '</div>',
                '<div class="col-12 badge badge-lg badge-'+rolColor+' fw-bold">' + rolStatus + '</div>',
                '<div class="col-md-12"><div class="row">'+
                '<button type="button" class="btn btn-sm btn-pinterest" data-bs-toggle="modal" data-bs-target="#personell_roles_' + roles.id + '">' + roles.role + ' Rol İzinleri </button>'+
                '</div></div>',
                '<div class="d-flex justify-content-center">' +
                '<button type="button" data-bs-target="#edit_personnel_role_modal' + roles.id + '" data-bs-toggle="modal" class="btn btn-info shadow btn-xs me-1">Düzenle <span class="btn-icon-end"><i class="fas fa-pencil-alt"></i></span></button>' +
                '<button type="button" data-bs-target="#delete_personnel_role_modal' + roles.id + '" data-bs-toggle="modal" class="btn btn-danger shadow btn-xs">Sil <span class="btn-icon-end"><i class="fas fa-trash"></i></span></button>' +
                '</div>'
            ]).draw(false);
        });
    }

    getRoleList(function(rolelist) 
    { processRoleData(rolelist, permissionsApp); });

    $(document).on("submit", "#new_personnel_role_add", function(e) {
        e.preventDefault();
        $("#personnel_role_add_btn").prop("disabled", true);
        $.ajax({
            url: '../api/auth/user-roles',
            type: "post",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) 
            {
                showErrorSwalToast(response.status, "", response.message, function() {
                    $("#personnel_role_add_btn").prop("disabled", false);
                    $('#add_role_modal').modal('hide');
                    $("#new_personnel_role_add")[0].reset(); // Formu resetleme
                    getRoleList(function(rolelist) 
                    {
                        personnelRolesTable.clear().draw();
                        processRoleData(rolelist, permissionsApp);
                    });
                });
            },
            error: function(error) 
            {
                data = error.responseJSON;
                $("#personnel_role_add_btn").prop("disabled", false);
                showErrorSwalToast(data.status, "", data.message);
            }
        });
    });

    function personelShowModal(roles,permissionsApp, permissions) {

        const showModal     = $('<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">').attr('id', 'personell_roles_' + roles.id);
        const modalDialog = $('<div class="modal-dialog modal-lg">');
        const modalContent = $('<div class="modal-content">');
        const modalHeader = $('<div class="modal-header">').append(
            $('<h5 class="modal-title">').text(roles.role+" Rol İzinleri"),
            $('<button type="button" class="btn-close" data-bs-dismiss="modal">')
        );
        let modalBodyContent;
        if (roleEditPermission)
        {
            let permissionsHTML = '';
            let num = 0;
            
            permissionsApp.forEach(function(perm) {
                num++;
                let isChecked = permissions.some(p => p.permission === perm) ? "checked" : "";
                let checkboxHTML = `
                    <div class="col-xl-4 col-xxl-4 col-4 mb-1"><div class="border border-dark rounded p-1">
                        <div class="form-check custom-checkbox checkbox-info ">
                            <input ${isChecked} type="checkbox" class="form-check-input lovesta-checkbox" id="${roles.id}check${num}" data-id="${roles.id}" data-permission="${perm}" value="${perm}" name="permission">
                            <label class="form-check-label lovesta-checkbox" for="${roles.id}check${num}">${capitalizeWords(perm)}</label>
                        </div>
                    </div></div>
                `;
                permissionsHTML += checkboxHTML;
            });

            modalBodyContent =  $('<div class="col-12">').append( $('<div class="row">').append( permissionsHTML ),);
        }else
        {
            modalBodyContent = $('<div>').html(permissionFail);
        }
        const modalBody = $('<div class="modal-body col-12">').append(modalBodyContent );
        modalContent.append(modalBody);
        showModal.append(modalDialog.append(modalContent.prepend(modalHeader)));

        return showModal;
    }

    function EditRolesShowModal(roles) 
    {
        const roleId = roles.id;
        const showModal = $('<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">').attr('id', 'edit_personnel_role_modal' + roleId);
        const modalDialog = $('<div class="modal-dialog modal-lg">');
        const modalContent = $('<div class="modal-content">');
        const modalHeader = $('<div class="modal-header">').append(
            $('<h5 class="modal-title">').text(roles.role + " Düzenle"),
            $('<button type="button" class="btn-close" data-bs-dismiss="modal">')
        );
        const editForm = $('<form id="edit_personnel_role_form" method="PUT"><input type="hidden" data-id="roleId' + roleId + '" name="roleId" value="' + roleId + '">');
        
        let modalBodyContent;

        if (editRolePermission) {
            modalBodyContent = $('<div class="row">').append(
                $('<div class="row pt-4">').append(
                    $('<div class="col-xl-6">').append(
                        $('<label class="form-label">').text(role_name + " (*)"),
                        $('<input type="text" class="form-control" name="role_name" value="' + roles.role + '" placeholder="Muhasebe">')
                    ),
                    $('<div class="col-xl-6">').append(
                        $('<label class="form-label">').text(role_status + " (*)"),
                        $('<select name="role_status" id="role_status" class="default-select form-control wide ms-0">').append(
                            $('<option>').attr('value', '').text(please_status_select),
                            $('<option>').attr('value', '0').attr('selected', roles.disabled == 0 ? 'selected' : false).text(active),
                            $('<option>').attr('value', '1').attr('selected', roles.disabled == 1 ? 'selected' : false).text(passive)
                        )
                    )
                )
            );
        } else {
            modalBodyContent = $('<div>').html(permissionFail);
        }
        const modalBody = $('<div class="modal-body">').append(modalBodyContent);
        const modalFooter = $('<div class="modal-footer">').append(
            $('<button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">').text(close_btn),
        );
        if (editRolePermission) {
            modalFooter.append(
                $('<button type="submit" id="personnel_role_edit_btn' + roleId + '" class="btn btn-success btn-sm">').text(new_personnel_role)
            );
        }
        editForm.append(modalBody, modalFooter);
        modalContent.append(editForm);
        showModal.append(modalDialog.append(modalContent.prepend(modalHeader)));

        return showModal;
    }


    $(document).on("submit", "#edit_personnel_role_form", function(e) {
        e.preventDefault();
        var roleId = $(this).find('input[name="roleId"]').val();
        $("#personnel_role_edit_btn"+roleId).prop("disabled", true);
        $.ajax({
            url: '../api/auth/user-roles',
            type: "put",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) 
            {
                showErrorSwalToast(response.status, "", response.message, function() {
                    $("#personnel_role_edit_btn"+roleId).prop("disabled", false);
                    $('#edit_personnel_role_modal'+roleId).modal('hide');
                    getRoleList(function(rolelist) 
                    {
                        personnelRolesTable.clear().draw();
                        processRoleData(rolelist, permissionsApp);
                    });
                });
            },
            error: function(error) 
            {
                data = error.responseJSON;
                $("#personnel_role_edit_btn"+roleId).prop("disabled", false);
                showErrorSwalToast(data.status, "", data.message);
            }
        });
    });

    function deleteShowModel(roles) {
        $(document).on('click', '#delete_role_btn' + roles.id, function(e) {
            e.preventDefault();
            const roleId = $(this).data("role");
            $("#delete_role_btn" + roleId).prop("disabled", true);
            $.ajax({
                type: "DELETE",
                url: "../api/auth/user-roles/" + roleId,
                success: function(response) {
                    showErrorSwalToast("success", response.status, response.message, function() {
                        $("#delete_role_btn" + roleId).prop("disabled", false);
                        $('#delete_personnel_role_modal' + roleId).modal('hide');
                        getRoleList(function(rolelist) {
                            personnelRolesTable.clear().draw();
                            processRoleData(rolelist, permissionsApp);
                        });
                    });
                },
                error: function(error) {
                    var data = error.responseJSON;
                    $("#delete_role_btn" + roleId).prop("disabled", false);
                    showErrorSwalToast("error", data.status, data.message);
                }
            });
        });

        const showModal = $('<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">').attr('id', 'delete_personnel_role_modal' + roles.id);
        const modalDialog = $('<div class="modal-dialog modal-lg">');
        const modalContent = $('<div class="modal-content">');
        const modalHeader = $('<div class="modal-header">').append(
            $('<h5 class="modal-title">').text(roles.role + " Adlı Rolü Silme İşlemi"),
            $('<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">')
        );
        let modalBodyContent;
        if (deleteRolePermission) {
            modalBodyContent = $('<p class="text-justify">').text(roles.role + ' adlı personel rolünü siliyorsunuz! Bu işlem geri alınamaz!');
        } else {
            modalBodyContent = $('<div>').html(permissionFail);
        }
        const modalBody = $('<div class="modal-body">').append(modalBodyContent);
        const modalFooter = $('<div class="modal-footer">').append(
            $('<button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">').text("Kapat"),
        );
        if (deleteRolePermission) {
            modalFooter.append(
                $('<button type="button" id="delete_role_btn' + roles.id + '" data-role="' + roles.id + '" class="btn btn-success btn-sm">').text("Sil")
            );
        }

        showModal.append(modalDialog.append(modalContent.append(modalHeader, modalBody, modalFooter)));
        return showModal;
    }
    
    $(document).on("click", "input[type='checkbox']", function(e) {

        var checkbox = $(this);
        var role_id = checkbox.data("id");
        var permission = checkbox.data("permission");
        var check = "";
        
        if (checkbox.prop("checked")) {
            check = 0;
            checkbox.prop("checked", true);
        } else {
            check = 1;
            checkbox.prop("checked", false);
        }
        $.ajax({
            type: "POST",
            url: "../api/auth/user-role-permissions",
            data: { "permission" : permission, "role_id":role_id, "disabled": check },
        });
    }); 

    function capitalizeWords(str) {
        return str
            .split('_') // Alt çizgilerden ayır
            .map(word => word.charAt(0).toUpperCase() + word.slice(1)) // Her kelimenin ilk harfini büyük yap
            .join(' '); // Kelimeleri boşlukla birleştir
    }
});