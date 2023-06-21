$(function () {

    //Initialize Select2 Elements
    $('.select2').select2();

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });
    //Money Euro
    $('[data-mask]').inputmask();

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass   : 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass   : 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass   : 'iradio_flat-green'
    });




    $("#table").DataTable({
        "language": {
            "paginate": {
                "next": "بعدی",
                "previous" : "قبلی"
            }
        },
        "info" : false,
    });



    // add rack or module to position in project map
    $(".add-entity").click(function (e){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var formData = {
            project_id: $(this).attr('project-id'),
            device_position_id: $(this).attr('device-position-id'),
            entity_id: $(this).parent().siblings('.modal-body').find('.entity-select').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        var type = "POST";
        var ajaxurl = base_url + '/project/map/setEntity';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                wrapperId = '#wrapper' + data.map.device_position_id;
                overlay = wrapperId + ' .overlay';
                entityModal = '#entity' + data.map.device_position_id;
                $(wrapperId).addClass('two-col');
                if((data.map.entity.position_type_match != 0) && (data.map.entity.position_type_match != 7) && (data.map.entity.position_type_match != 6)){
                    $(wrapperId + ' .add-liquids').append( '<p>' + data.map.entity.name + '</p>' + '<div><a href="#" class="btn btn-danger btn-sm delete-project-map" project-map-id="'+ data.map.id +'" > حذف </a><a  href="#" class="btn btn-success btn-sm calibrate-project-map" data-toggle="modal" data-target="#calibrate'+ data.map.id +'" project-map-id="'+ data.map.id +'"  > کالیبراسیون </a></div><div><a class="add_liquids btn btn-primary btn-sm " data-toggle="modal" data-target="#add_liquids'+ data.map.id +'" href="#" >اضافه کردن نمونه ها</a></div>');
                    $(wrapperId + ' .add-liquid-modal').attr('id','add_liquids'+data.map.id);
                    $('.modal-wrapper .add-modal[device-position-id='+data.map.device_position_id+']').attr('id','add_target'+data.map.id);
                    $('.modal-wrapper .add-modal[device-position-id='+data.map.device_position_id+'] .add-target-liquid').attr('project-map-id',data.map.id);
                    $(wrapperId + ' .add-liquid-modal .add-liquid').attr('project-map-id',data.map.id);
                    k = 12/(data.map.entity.meta_data.col);
                    k = Math.floor(k);
                    rows = ['A','B','C','D','E','F','G','H','I'];
                    appendFile = '<div class="liquids-wrapper" ><form ><div class="alert alert-success mt-3 d-none">نمونه با موفقیت ثبت شد.</div><div class="form-group has-error"><label for="liquids">مایع</label><select class="form-control" name="liquid">';
                    jQuery.each(data.liquids, function(index, item) {
                        appendFile += '<option value="'+item.id+'">'+item.name+'</option>';
                    }.bind(this));
                    appendFile += '</select></div><div class="form-group has-error"><label for="volume">حجم (میکرولیتر)</label><input id="volume" type="number" class="form-control" name="volume" min="0" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="حجم" /><span class="invalid-feedback d-none" role="alert"><strong>حجم درخواستی بیشتر از حجم موجود مایع می باشد.</strong></span></div><input type="hidden" name="project-map" value="'+data.map.id+'" /><input type="hidden" name="selected" value="" /><button type="button" class="btn btn-success add-liquid">ثبت</button><button type="button" class="btn btn-success edit-liquid d-none">تغییر</button><button type="button" class="btn btn-danger remove-liquid d-none">حذف</button><button type="button" class="btn btn-secondary cancel-liquid" >انصراف</button></form></div>'
                    appendFileCircle = '<form class="form-wrapper" ><div class="form-group"><div class="row"><div class="col-11"><div class="row">';
                    for(let n =0; n<data.map.entity.meta_data.col;n++){
                        appendFileCircle += '<div class="col-' + k + ' mt-2 d-flex align-items-center justify-content-center"><p>'+ (data.map.entity.meta_data.col-n) +'</p></div>';
                    }
                    appendFileCircle += '</div></div></div>';
                    for(let i =0; i<data.map.entity.meta_data.row;i++){
                        appendFileCircle += '<div class="row"><div class="col-11"><div class="row">';
                        for(let j=0;j < data.map.entity.meta_data.col;j++){
                            appendFileCircle += '<div class="col-' + k +' mt-2"><div class="circle" row="'+ (i+1) +'" col="'+(data.map.entity.meta_data.col - j)+'"> </div></div>';
                        }
                        appendFileCircle += '</div></div><div class="col-1 d-flex align-items-center justify-content-center"><div class="row mt-2"><p>'+ rows[i] +'</p></div></div></div>';
                    }
                    appendFile += appendFileCircle;

                    appendCalibrateFile = '<div class="form-group"> <label>جا به جایی در راستای محور x</label><input class="form-control" type="number" name="x" placeholder="مقادیر مثبت و منفی قابل قبول است." /></div><div class="form-group"><label>جا به جایی در راستای محور y</label><input class="form-control" type="number" name="y" placeholder="مقادیر مثبت و منفی قابل قبول است." /></div><div class="form-group"><label>جا به جایی در راستای محور z</label><input class="form-control" type="number" name="z" placeholder="مقادیر مثبت و منفی قابل قبول است." /></div></form>';
                    $(wrapperId + ' .add-liquid-modal .modal-body').append(appendFile);
                    $(wrapperId + ' .calibrate .modal-body').append(appendCalibrateFile);
                    $(wrapperId + ' .calibrate .add-calibrate').attr('project-map-id',data.map.id);
                    $(wrapperId + ' .calibrate').attr('id','calibrate'+data.map.id);
                    $('.modal-wrapper .add-modal[device-position-id='+data.map.device_position_id+'] .modal-body').append(appendFileCircle);
                    $('.project-map-select[name=source]').append('<option value='+data.map.id+'>'+data.map.entity.name+'</option>');
                    $('.project-map-select[name=target]').append('<option value='+data.map.id+'>'+data.map.entity.name+'</option>');
                }
                else{
                    $(wrapperId + ' .add-liquids').append( '<p>' + data.map.entity.name + '</p>' + '<div><a href="#" class="btn btn-danger btn-sm delete-project-map" project-map-id="'+ data.map.id +'" > حذف </a><a  href="#" class="btn btn-success btn-sm calibrate-project-map" data-toggle="modal" data-target="#calibrate'+ data.map.id +'" project-map-id="'+ data.map.id +'"  > کالیبراسیون </a></div>');
                    if(data.map.entity.position_type_match == 7){
                        $('.project-map-select[name=sampler]').append('<option value='+data.map.id+'>'+data.map.entity.name+'-'+data.map.device_position.position.name+'</option>');
                    }
                    if(data.map.entity.position_type_match == 6){
                        $('.project-map-select[name=target]').append('<option value='+data.map.id+'>'+data.map.entity.name+'</option>');
                    }
                    appendCalibrateFile = '<div class="form-group"> <label>جا به جایی در راستای محور x</label><input class="form-control" type="number" name="x" placeholder="مقادیر مثبت و منفی قابل قبول است." /></div><div class="form-group"><label>جا به جایی در راستای محور y</label><input class="form-control" type="number" name="y" placeholder="مقادیر مثبت و منفی قابل قبول است." /></div><div class="form-group"><label>جا به جایی در راستای محور z</label><input class="form-control" type="number" name="z" placeholder="مقادیر مثبت و منفی قابل قبول است." /></div></form>';
                    $(wrapperId + ' .calibrate .modal-body').append(appendCalibrateFile);
                    $(wrapperId + ' .calibrate .add-calibrate').attr('project-map-id',data.map.id);
                    $(wrapperId + ' .calibrate').attr('id','calibrate'+data.map.id);

                }

                $(overlay).css('display','none');
                $(entityModal).modal('hide');

            },
            error: function (data) {

            }
        });
    });







    // delete rack or module to position in project map
    $(document).on('click','.delete-project-map',function(e){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var formData = {
            project_map_id: $(this).attr('project-map-id'),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        var type = "get";
        var ajaxurl = base_url + '/project/map/deleteEntity/' + $(this).attr('project-map-id');
        $.ajax({
            type: type,
            url: ajaxurl,
            success: function (data) {
                if(data){
                    wrapperId = '#wrapper' + data.devicePosition;
                    overlay = wrapperId + ' .overlay';
                    entityModal = '#entity' + data.devicePosition;
                    $(wrapperId + ' .add-liquids').html(' ');
                    $(wrapperId + ' .add-liquid-modal .modal-body').html(' ');
                    $(wrapperId + ' .calibrate .modal-body').html(' ');
                    $('.modal-wrapper .add-modal[device-position-id='+data.devicePosition+'] .modal-body').html(' ');
                    $('.modal-wrapper .add-modal[device-position-id='+data.devicePosition+'] .add-target-liquid').attr('project-map-id',false);
                    $('.modal-wrapper .add-modal[device-position-id='+data.devicePosition+']').attr('id',false);
                    $('.project-map-select option[value='+data.map+']').remove();
                    $(overlay).css('display','flex');
                    $(wrapperId).removeClass('two-col');
                }
            },
            error: function (data) {

            }
        });
    });





    // add calibration for a rack or module
    $(document).on('click','.add-calibrate',function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var button = $(this);
        var formData = {
            project_map_id: $(this).attr('project-map-id'),
            x: $(this).closest('.modal-footer').siblings('.modal-body').find('input[name=x]').val(),
            y: $(this).closest('.modal-footer').siblings('.modal-body').find('input[name=y]').val(),
            z: $(this).closest('.modal-footer').siblings('.modal-body').find('input[name=z]').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        var type = "POST";
        var ajaxurl = base_url + '/project/map/addCalibrate';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                if( data.status){
                    button.closest('.modal-footer').siblings('.modal-body').find('input[name=x]').val(formData.x);
                    button.closest('.modal-footer').siblings('.modal-body').find('input[name=y]').val(formData.y);
                    button.closest('.modal-footer').siblings('.modal-body').find('input[name=z]').val(formData.z);
                    button.closest('.modal').modal('hide');
                }
                else{

                }

            },
            error: function (data) {

            }
        });
    });



    // function for selecting circles for add,  edit or delete liquid
    $(document).on('click','.add-liquid-modal .circle',function(e) {
        $(this).addClass('selecting');
    });

    $(document).on('click','.add-liquid-modal .circle.selected',function(e) {
        $(this).addClass('selecting');
    });

    $(document).on('click','.add-liquid-modal .circle.selecting',function(e) {
        $(this).removeClass('selecting');
    });
    var clicking = false;

    $('.add-liquid-modal').mousedown(function(){
        clicking = true;
    });

    $(document).mouseup(function(){
        clicking = false;
    });

    $('.add-liquid-modal .circle').mouseenter(function(){
        if(clicking === false) return;

        $(this).addClass('selecting');
    });

    $('.add-liquid-modal').on('hidden.bs.modal', function (e) {
        $(this).find('.circle.selecting').removeClass('selecting');
        $(this).find('.invalid-feedback').css('display','none');
    })


    // cancel add, edit or delete liquid process
    $(document).on('click','.cancel-liquid',function(e) {
        $(this).closest('.liquids-wrapper').fadeOut();
        $(this).closest('.modal-body').find('.circle.selecting').removeClass('selecting');
    });



    // add selected circlle for add, edit or delete process
    $(document).on('click','.add-selecting-liquid',function(){
        $(this).siblings('.invalid-feedback').css('display','none');
        var selected_array = [];
        $(this).parent().siblings('.modal-body').find('.circle.selected.selecting').each(function (){
            var selected = {};
            selected.row = $(this).attr('row');
            selected.col = $(this).attr('col');
            selected.id = $(this).attr('liquid-id');
            selected.vol = $(this).attr('liquid-vol');
            selected_array.push(selected);
        });

        var selecting_array = [];
        $(this).parent().siblings('.modal-body').find('.circle.selecting:not(.selected)').each(function (){
            var selecting = {};
            selecting.row = $(this).attr('row');
            selecting.col = $(this).attr('col');
            selecting_array.push(selecting);
        });

        if(selected_array.length !== 0 && selecting_array.length !== 0){
            $(this).siblings('.invalid-feedback').css('display','block');
        }
        else if(selected_array.length === 0 ){
            $(this).parent().siblings('.modal-body').find('input[name=selected]').val(JSON.stringify(selecting_array));
            $(this).parent().siblings('.modal-body').find('select[name=liquid] option').prop('selected',false);
            $(this).parent().siblings('.modal-body').find('input[name=volume]').val(false);
            $(this).parent().siblings('.modal-body').find('.add-liquid').removeClass('d-none');
            $(this).parent().siblings('.modal-body').find('.remove-liquid').addClass('d-none');
            $(this).parent().siblings('.modal-body').find('.edit-liquid').addClass('d-none');
            $(this).parent().siblings('.modal-body').find('.invalid-feedback strong').addClass('d-none');
            $(this).parent().siblings('.modal-body').find('.alert.alert-success').addClass('d-none');
            $(this).parent().siblings('.modal-body').find('.liquids-wrapper').fadeIn();

        }
        else if(selecting_array.length === 0 ){
            $(this).parent().siblings('.modal-body').find('input[name=selected]').val(JSON.stringify(selected_array));
            $(this).parent().siblings('.modal-body').find('select[name=liquid] option').prop('selected',false);
            $(this).parent().siblings('.modal-body').find('select[name=liquid] option[value='+ selected_array[0].id +']').prop('selected',true);
            $(this).parent().siblings('.modal-body').find('input[name=volume]').val(selected_array[0].vol);
            $(this).parent().siblings('.modal-body').find('.add-liquid').addClass('d-none');
            $(this).parent().siblings('.modal-body').find('.remove-liquid').removeClass('d-none');
            $(this).parent().siblings('.modal-body').find('.edit-liquid').removeClass('d-none');
            $(this).parent().siblings('.modal-body').find('.invalid-feedback strong').addClass('d-none');
            $(this).parent().siblings('.modal-body').find('.alert.alert-success').addClass('d-none');
            $(this).parent().siblings('.modal-body').find('.liquids-wrapper').fadeIn();
        }

    });



    // add liquid to selected circles
    $(document).on('click','.add-liquid',function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var button = $(this);
        var formData = {
            project_map_id: $(this).siblings('input[name=project-map]').val(),
            selected: $(this).siblings('input[name=selected]').val(),
            liquid: $(this).siblings('.form-group').find('select[name=liquid]').val(),
            volume: $(this).siblings('.form-group').find('input[name=volume]').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        var type = "POST";
        var ajaxurl = base_url + '/project/map/addLiquid';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                if( data.status){
                    button.parent().find('.alert.alert-success').removeClass('d-none');
                    button.addClass('d-none');
                    button.siblings('.remove-liquid').removeClass('d-none');
                    button.siblings('.edit-liquid').removeClass('d-none');
                    jQuery.each(JSON.parse(formData.selected), function(index, item) {
                        button.closest('.modal-body').find('.circle[row='+item.row+'][col='+item.col+']').addClass('selected');
                        button.closest('.modal-body').find('.circle[row='+item.row+'][col='+item.col+']').attr('liquid-id',formData.liquid);
                        button.closest('.modal-body').find('.circle[row='+item.row+'][col='+item.col+']').attr('liquid-vol',formData.volume);
                        $('#add_target'+ formData.project_map_id).find('.circle[row='+item.row+'][col='+item.col+']').attr('liquid-vol',formData.volume);
                        $('#add_target'+ formData.project_map_id).find('.circle[row='+item.row+'][col='+item.col+']').attr('liquid-id',formData.liquid);
                        $('#add_target'+ formData.project_map_id).find('.circle[row='+item.row+'][col='+item.col+']').addClass('selected');
                    });

                }
                else{
                    button.siblings('.form-group').find('.invalid-feedback strong').removeClass('d-none');
                }

            },
            error: function (data) {
            }
        });
    });







    // edit liquid to selected circles
    $(document).on('click','.edit-liquid',function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var button = $(this);
        var formData = {
            project_map_id: $(this).siblings('input[name=project-map]').val(),
            selected: $(this).siblings('input[name=selected]').val(),
            liquid: $(this).siblings('.form-group').find('select[name=liquid]').val(),
            volume: $(this).siblings('.form-group').find('input[name=volume]').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        var type = "POST";
        var ajaxurl = base_url + '/project/map/editLiquid';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                if( data.status){
                    button.parent().find('.alert.alert-success').removeClass('d-none');
                    jQuery.each(JSON.parse(formData.selected), function(index, item) {
                        button.closest('.modal-body').find('.circle[row='+item.row+'][col='+item.col+']').attr('liquid-id',formData.liquid);
                        button.closest('.modal-body').find('.circle[row='+item.row+'][col='+item.col+']').attr('liquid-vol',formData.volume);
                        $('#add_target'+ formData.project_map_id).find('.circle[row='+item.row+'][col='+item.col+']').attr('liquid-vol',formData.volume);
                        $('#add_target'+ formData.project_map_id).find('.circle[row='+item.row+'][col='+item.col+']').attr('liquid-id',formData.liquid);
                    });
                }
                else{
                    button.siblings('.form-group').find('.invalid-feedback strong').removeClass('d-none');
                }

            },
            error: function (data) {
            }
        });
    });








    // remove liquid to selected circles
    $(document).on('click','.remove-liquid',function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var button = $(this);
        var formData = {
            project_map_id: $(this).siblings('input[name=project-map]').val(),
            selected: $(this).siblings('input[name=selected]').val(),
            liquid: $(this).siblings('.form-group').find('select[name=liquid]').val(),
            volume: $(this).siblings('.form-group').find('input[name=volume]').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        var type = "POST";
        var ajaxurl = base_url + '/project/map/removeLiquid';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                if( data.status){
                    button.parent().find('.alert.alert-success').removeClass('d-none');
                    button.addClass('d-none');
                    button.siblings('.add-liquid').removeClass('d-none');
                    button.siblings('.edit-liquid').addClass('d-none');
                    jQuery.each(JSON.parse(formData.selected), function(index, item) {
                        button.closest('.modal-body').find('.circle[row='+item.row+'][col='+item.col+']').removeClass('selected');
                        button.closest('.modal-body').find('.circle[row='+item.row+'][col='+item.col+']').attr('liquid-id',false);
                        button.closest('.modal-body').find('.circle[row='+item.row+'][col='+item.col+']').attr('liquid-vol',false);
                        $('#add_target'+ formData.project_map_id).find('.circle[row='+item.row+'][col='+item.col+']').attr('liquid-vol',false);
                        $('#add_target'+ formData.project_map_id).find('.circle[row='+item.row+'][col='+item.col+']').attr('liquid-id',false);
                        $('#add_target'+ formData.project_map_id).find('.circle[row='+item.row+'][col='+item.col+']').removeClass('selected');
                    });
                }
                else{
                    button.siblings('.form-group').find('.invalid-feedback strong').removeClass('d-none');
                }

            },
            error: function (data) {
            }
        });
    });





    // update sequence of protocols in project
    function updatesequence(e , ui){
        var sequence_array = [];
        $('.droppable-area li').each(function(index,value){
            var sequence = {};
            sequence.id = $(this).find('.edit-protocol').attr('protocol-id');
            sequence.index = $(this).index() + 1;
            sequence_array.push(sequence);
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var formData = {
            sequences: sequence_array,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        var type = "POST";
        var ajaxurl = base_url + '/project/protocol/changeSequence';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {

            },
            error: function (data) {

            }
        });
    }


    // add drag feature to protocols menu
    $( ".droppable-area" ).sortable({
        connectWith: ".connected-sortable",
        stack: '.connected-sortable ul',
        update: updatesequence
    }).disableSelection();






    // open add protocol wrapper
    $('#add-protocol').click(function(){
        $('.add-protocol').fadeIn();
        $('.add-protocol .btn-add-protocol').attr('method',0);
        $('.add-protocol .btn-add-protocol').attr('project-protocol-id',false);
        $('.add-protocol .circle').removeClass('source-selected');
        $('.add-protocol .circle').removeClass('target-selected');
        $('.add-protocol .selected').val(false);
    });





    // close add protocol wrapper
    $('#close-protocol').click(function(){
        $('.add-protocol').fadeOut();
        $(this).closest('.modal-footer').removeClass('has-error');
        $('.add-protocol .circle').removeClass('source-selected');
        $('.add-protocol .circle').removeClass('target-selected');
    });




    // change protocol type
    $('.add-protocol .entity-select').change(function(){
        var type = $('option:selected', this).attr('type');
        $('.extra-input.display-extra').removeClass('display-extra');
        $('#protocol-type-'+type).addClass('display-extra');
        $('.add-protocol .btn-add-protocol').attr('protocol-type',type);
    });




    // add source or target for transfer protocol
    $('.add-protocol .project-map-select').change(function(){
        modalId = '#add_target' + $(this).val();
        $(this).siblings('.btn-add-entity').fadeIn();
        $(this).siblings('.btn-add-entity').attr('data-target',modalId);
        $(this).siblings('.selected').attr('id','add_target'+$(this).val());
    });



    $('.add-protocol .btn-add-entity').attr('data-target',function(n,v){
        return '#add_target'+$(this).siblings('.project-map-select').children('option').first().attr('value');
    });
    $('.add-protocol .selected').attr('id',function(n,v){
        return 'add_target'+$(this).siblings('.project-map-select').children('option').first().attr('value');
    });
    $('.add-protocol .source .btn-add-entity').click(function(e){
        e.preventDefault();
        $($(this).attr('data-target')).removeClass('target');
        $($(this).attr('data-target')).addClass('source');
    });
    $('.add-protocol .target .btn-add-entity').click(function(e){
        e.preventDefault();
        $($(this).attr('data-target')).removeClass('source');
        $($(this).attr('data-target')).addClass('target');
    });



    // select circles in rack for source or target in transfer protocol
    $(document).on('click','.add-protocol .source .circle',function(){
        $(this).addClass('source-selected');
    });
    $(document).on('click','.add-protocol .source .circle.source-selected',function(){
        $(this).removeClass('source-selected');
    });

    $(document).on('click','.add-protocol .target .circle',function(){
        $(this).addClass('target-selected');
    });
    $(document).on('click','.add-protocol .target .circle.target-selected',function(){
        $(this).removeClass('target-selected');
    });




    // add selected circle to source of transfer protocol
    $(document).on('click','.add-protocol .source .add-target-liquid',function(){
        var source_selected_array = [];
        $('.add-protocol .source .source-selected').each(function (){
            var source_selected = {};
            source_selected.row = $(this).attr('row');
            source_selected.col = $(this).attr('col');
            source_selected.id = $(this).attr('liquid-id');
            source_selected_array.push(source_selected);
        });
        $('.add-protocol .source #' + $(this).closest('.modal').attr('id')).val(JSON.stringify(source_selected_array));
        $(this).closest('.modal').modal('hide');

    });





    // add selected circle to target of transfer protocol
    $(document).on('click','.add-protocol .target .add-target-liquid',function(){
        var target_selected_array = [];
        $('.add-protocol .target .target-selected').each(function (){
            var target_selected = {};
            target_selected.row = $(this).attr('row');
            target_selected.col = $(this).attr('col');
            target_selected_array.push(target_selected);
        });
        $('.add-protocol .target #' + $(this).closest('.modal').attr('id')).val(JSON.stringify(target_selected_array));
        $(this).closest('.modal').modal('hide');
    });

    $('.btn-add-protocol').closest('.modal-footer').removeClass('has-error');







    // add protocol to project
    $(document).on('click','.btn-add-protocol[method=0]',function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var button = $(this);
        // transfer protocol
        if( $(this).attr('protocol-type') == 0){
            var formData = {
                project_id: $(this).attr('project-id'),
                entity_id: $('.add-protocol .entity-select').val(),
                sampler_id: $('.add-protocol #protocol-type-0 .project-map-select[name=sampler]').val(),
                source_id: $('.add-protocol #protocol-type-0 .project-map-select[name=source]').val(),
                source_selected: $('.add-protocol #protocol-type-0 input[name=source-selected]').val(),
                source_volume: $('.add-protocol #protocol-type-0 input[name=source-volume]').val(),
                target_id: $('.add-protocol #protocol-type-0 .project-map-select[name=target]').val(),
                target_selected: $('.add-protocol #protocol-type-0 input[name=target-selected]').val(),
                target_volume: $('.add-protocol #protocol-type-0 input[name=target-volume]').val(),
                tip_change: $('.add-protocol #protocol-type-0 input[name=tip-change]:checked').val(),
                loop: $('.add-protocol #protocol-type-0 input[name=loop]:checked').val(),
                sequence: $('.control-sidebar .connected-sortable li').length + 1,
                _token: $('meta[name="csrf-token"]').attr('content')
            };
        }
        // pippet protocol
        else if($(this).attr('protocol-type') == 1){
            var formData = {
                project_id: $(this).attr('project-id'),
                entity_id: $('.add-protocol .entity-select').val(),
                sampler_id: $('.add-protocol #protocol-type-1 .project-map-select[name=sampler]').val(),
                target_id: $('.add-protocol #protocol-type-1 .project-map-select[name=target]').val(),
                target_selected: $('.add-protocol #protocol-type-1 input[name=target-selected]').val(),
                pipet_num: $('.add-protocol #protocol-type-1 input[name=pipet-num]').val(),
                pipet_volume: $('.add-protocol #protocol-type-1 input[name=pipet-volume]').val(),
                sequence: $('.control-sidebar .connected-sortable li').length + 1,
                _token: $('meta[name="csrf-token"]').attr('content')
            };
        }
        // pause protocol
        else if($(this).attr('protocol-type') == 2){
            var formData = {
                project_id: $(this).attr('project-id'),
                entity_id: $('.add-protocol .entity-select').val(),
                second: $('.add-protocol #protocol-type-2 input[name=second]').val(),
                sequence: $('.control-sidebar .connected-sortable li').length + 1,
                _token: $('meta[name="csrf-token"]').attr('content')
            };
        }
        // magnet protocol
        else if( $(this).attr('protocol-type') == 3){
            var formData = {
                project_id: $(this).attr('project-id'),
                entity_id: $('.add-protocol .entity-select').val(),
                magnet_engage: $('.add-protocol #protocol-type-3 input[type=checkbox]:checked').val(),
                tube_volume: $('.add-protocol #protocol-type-3 input[name=tube-volume]').val(),
                magnet_height: $('.add-protocol #protocol-type-3 input[name=magnet-height]').val(),
                second: $('.add-protocol #protocol-type-3 input[name=second]').val(),
                sequence: $('.control-sidebar .connected-sortable li').length + 1,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        }
        // termoshaker protocol
        else if( $(this).attr('protocol-type') == 4){
            var formData = {
                project_id: $(this).attr('project-id'),
                entity_id: $('.add-protocol .entity-select').val(),
                type: $('.add-protocol #protocol-type-4 .warmer-type').val(),
                warmer_time: $('.add-protocol #protocol-type-4 input[name=warmer-time]').val(),
                warmer_temperature: $('.add-protocol #protocol-type-4 input[name=warmer-temperature]').val(),
                mixer_time: $('.add-protocol #protocol-type-4 input[name=mixer-time]').val(),
                mixer_repeat: $('.add-protocol #protocol-type-4 input[name=mixer-repeat]').val(),
                sequence: $('.control-sidebar .connected-sortable li').length + 1,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        }
        // vacuum protocol
        else if( $(this).attr('protocol-type') == 5){
            var formData = {
                project_id: $(this).attr('project-id'),
                entity_id: $('.add-protocol .entity-select').val(),
                time: $('.add-protocol #protocol-type-5 input[name=time]').val(),
                sequence: $('.control-sidebar .connected-sortable li').length + 1,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        }
        // uvc protocol
        else if( $(this).attr('protocol-type') == 6){
            var formData = {
                project_protocol_id: $(this).attr('project-protocol-id'),
                project_id: $(this).attr('project-id'),
                entity_id: $('.add-protocol .entity-select').val(),
                time: $('.add-protocol #protocol-type-6 input[name=time]').val(),
                sequence: $('.control-sidebar .connected-sortable li').length + 1,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        }
        var type = "POST";
        var ajaxurl = base_url + '/project/protocol/addProtocol';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                if(data.status == 1){
                    $('.control-sidebar .connected-sortable').append('<li class="draggable-item" id="protocol'+data.protocol.id+'"><p>'+data.protocol.entity.name+'</p><button class="btn btn-sm btn-success edit-protocol" protocol-id="'+data.protocol.id+'" >تغییر</button> <button class="btn btn-sm btn-danger delete-protocol" protocol-id="'+data.protocol.id+'" > حذف</button> </li>');
                    $('.add-protocol').fadeOut();
                }
                else{
                    button.closest('.modal-footer').addClass('has-error');
                    button.siblings('.invalid-feedback').append(data.error);
                }

            },
            error: function (data) {
                button.siblings('.invalid-feedback').append('اطلاعات از سمت سرور دریافت نشد.');
            }
        });
    });





    // open edit protocol wrapper
    $(document).on('click','.edit-protocol',function(e){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var formData = {
            project_protocol_id: $(this).attr('protocol-id'),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        var type = "get";
        var ajaxurl = base_url + '/project/protocol/editProtocolShow/' + $(this).attr('protocol-id');
        $.ajax({
            type: type,
            url: ajaxurl,
            success: function (data) {
                if(data){
                    $('.add-protocol .circle').removeClass('source-selected');
                    $('.add-protocol .circle').removeClass('target-selected');
                    $('.add-protocol .content .entity-select option').prop('selected',false);
                    $('.add-protocol .content .entity-select option[value='+data.entity_id+']').prop('selected',true);
                    $('.add-protocol .content .project-map-select option').prop('selected',false);
                    $('.extra-input.display-extra').removeClass('display-extra');
                    $('#protocol-type-'+data.entity.position_type_match).addClass('display-extra');
                    $('.add-protocol .btn-add-protocol').attr('protocol-type',data.entity.position_type_match);
                    $('.add-protocol .btn-add-protocol').attr('method',1);
                    $('.add-protocol .btn-add-protocol').attr('project-protocol-id',data.id);
                    //transfer protocol
                    if( data.entity.position_type_match == 0 ){
                        $('.add-protocol .content .project-map-select[name=sampler] option[value='+data.meta_data.sampler_id+']').prop('selected',true);
                        $('.add-protocol .content .project-map-select[name=source] option[value='+data.source_id+']').prop('selected',true);
                        $('.add-protocol .content .project-map-select[name=target] option[value='+data.target_id+']').prop('selected',true);
                        $('.add-protocol .source .btn-add-entity').attr('data-target','#add_target'+data.source_id);
                        $('.add-protocol .source .selected').attr('id','add_target'+data.source_id);
                        $('.add-protocol .target .btn-add-entity').attr('data-target','#add_target'+data.target_id);
                        $('.add-protocol .target .selected').attr('id','add_target'+data.target_id);
                        $('.add-protocol .source .selected').val(data.meta_data.source_selected);
                        jQuery.each(JSON.parse(data.meta_data.source_selected), function(index, item) {
                            $('#add_target'+data.source_id).find('.circle[row='+item.row+'][col='+item.col+']').addClass('source-selected');
                        }.bind(this));
                        $('.add-protocol .target .selected').val(data.meta_data.target_selected);
                        jQuery.each(JSON.parse(data.meta_data.target_selected), function(index, item) {
                            $('#add_target'+data.target_id).find('.circle[row='+item.row+'][col='+item.col+']').addClass('target-selected');
                        }.bind(this));
                        $('.add-protocol input[name=source-volume]').val(data.meta_data.source_volume);
                        $('.add-protocol input[name=target-volume]').val(data.meta_data.target_volume);
                        if(data.meta_data.tip_change){
                            $('.add-protocol input[name=tip-change]').prop('checked', true);
                        }
                        else{
                            $('.add-protocol input[name=tip-change]').prop('checked', false);
                        }
                        if(data.meta_data.loop){
                            $('.add-protocol input[name=loop]').prop('checked', true);
                        }
                        else{
                            $('.add-protocol input[name=loop]').prop('checked', false);
                        }
                    }
                    // pippet protocol
                    else if( data.entity.position_type_match == 1 ){
                        $('.add-protocol .content .project-map-select[name=sampler] option[value='+data.meta_data.sampler_id+']').prop('selected',true);
                        $('.add-protocol .content .project-map-select[name=target] option[value='+data.target_id+']').prop('selected',true);
                        $('.add-protocol .target .btn-add-entity').attr('data-target','#add_target'+data.target_id);
                        $('.add-protocol .target .selected').attr('id','add_target'+data.target_id);
                        $('.add-protocol .target .selected').val(data.meta_data.target_selected);
                        jQuery.each(JSON.parse(data.meta_data.target_selected), function(index, item) {
                            $('#add_target'+data.target_id).find('.circle[row='+item.row+'][col='+item.col+']').addClass('target-selected');
                        }.bind(this));
                        $('.add-protocol input[name=pipet-num]').val(data.meta_data.pipet_num);
                        $('.add-protocol input[name=pipet-volume]').val(data.meta_data.pipet_volume);
                    }
                    // pause protocol
                    else if( data.entity.position_type_match == 2 ){
                        $('.add-protocol input[name=second]').val(data.meta_data.second);
                    }
                    // magnet protocol
                    else if( data.entity.position_type_match == 3 ){
                        $('.add-protocol input[name=magnet-height]').val(data.meta_data.magnet_height);
                        $('.add-protocol input[name=tube-volume]').val(data.meta_data.tube_volume);
                        $('.add-protocol input[name=second]').val(data.meta_data.second);
                        if(data.meta_data.magnet_engage){
                            $('.add-protocol input[name=magnet-engage]').prop('checked', true);
                        }
                        else{
                            $('.add-protocol input[name=magnet-engage]').prop('checked', false);
                        };
                    }
                    // termoshaker protocol
                    else if( data.entity.position_type_match == 4 ){
                        $('.add-protocol .content .warmer-type option').prop('selected',false);
                        $('.add-protocol .content .warmer-type option[value='+data.meta_data.type+']').prop('selected',true);
                        $('.add-protocol input[name=mixer-repeat]').val(data.meta_data.mixer_repeat);
                        $('.add-protocol input[name=mixer-time]').val(data.meta_data.mixer_time);
                        $('.add-protocol input[name=warmer-temperature]').val(data.meta_data.warmer_temperature);
                        $('.add-protocol input[name=warmer-time]').val(data.meta_data.warmer_time);
                    }
                    // vacuum protocol
                    else if( data.entity.position_type_match == 5 ){
                        $('.add-protocol input[name=time]').val(data.meta_data.time);
                    }
                    // uvc protocol
                    else if( data.entity.position_type_match == 6 ){
                        $('.add-protocol input[name=time]').val(data.meta_data.time);
                    }

                    $('.add-protocol').fadeIn();
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });






    // edit protocol in project
    $(document).on('click','.btn-add-protocol[method=1]',function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var button = $(this);
        var tipChecked = 1;
        var loopChecked = 1;
        if($('.add-protocol #protocol-type-0 input[name=tip-change]').is(':checked')){
            tipChecked = 1;
        }
        else{
            tipChecked = 0;
        }
        if($('.add-protocol #protocol-type-0 input[name=loop]').is(':checked')){
            loopChecked = 1;
        }
        else{
            loopChecked = 0;
        }
        // transfer protocol
        if( $(this).attr('protocol-type') == 0){
            var formData = {
                project_protocol_id: $(this).attr('project-protocol-id'),
                project_id: $(this).attr('project-id'),
                entity_id: $('.add-protocol .entity-select').val(),
                sampler_id: $('.add-protocol #protocol-type-0 .project-map-select[name=sampler]').val(),
                source_id: $('.add-protocol #protocol-type-0 .project-map-select[name=source]').val(),
                source_selected: $('.add-protocol #protocol-type-0 input[name=source-selected]').val(),
                source_volume: $('.add-protocol #protocol-type-0 input[name=source-volume]').val(),
                target_id: $('.add-protocol #protocol-type-0 .project-map-select[name=target]').val(),
                target_selected: $('.add-protocol #protocol-type-0 input[name=target-selected]').val(),
                target_volume: $('.add-protocol #protocol-type-0 input[name=target-volume]').val(),
                tip_change: tipChecked,
                loop: loopChecked,
                sequence: $('.control-sidebar .connected-sortable li#protocol'+$(this).attr('project-protocol-id')).index()+1,
                _token: $('meta[name="csrf-token"]').attr('content')
            };
        }
        // pippet protocol
        else if($(this).attr('protocol-type') == 1){
            var formData = {
                project_protocol_id: $(this).attr('project-protocol-id'),
                project_id: $(this).attr('project-id'),
                entity_id: $('.add-protocol .entity-select').val(),
                sampler_id: $('.add-protocol #protocol-type-1 .project-map-select[name=sampler]').val(),
                target_id: $('.add-protocol #protocol-type-1 .project-map-select[name=target]').val(),
                target_selected: $('.add-protocol #protocol-type-1 input[name=target-selected]').val(),
                pipet_num: $('.add-protocol #protocol-type-1 input[name=pipet-num]').val(),
                pipet_volume: $('.add-protocol #protocol-type-1 input[name=pipet-volume]').val(),
                sequence: $('.control-sidebar .connected-sortable li#protocol'+$(this).attr('project-protocol-id')).index()+1,
                _token: $('meta[name="csrf-token"]').attr('content')
            };
        }
        // pause protocol
        else if($(this).attr('protocol-type') == 2){
            var formData = {
                project_protocol_id: $(this).attr('project-protocol-id'),
                project_id: $(this).attr('project-id'),
                entity_id: $('.add-protocol .entity-select').val(),
                second: $('.add-protocol #protocol-type-2 input[name=second]').val(),
                sequence: $('.control-sidebar .connected-sortable li#protocol'+$(this).attr('project-protocol-id')).index()+1,
                _token: $('meta[name="csrf-token"]').attr('content')
            };
        }
        // magnet protocol
        else if( $(this).attr('protocol-type') == 3){
            var formData = {
                project_protocol_id: $(this).attr('project-protocol-id'),
                project_id: $(this).attr('project-id'),
                entity_id: $('.add-protocol .entity-select').val(),
                magnet_engage: $('.add-protocol #protocol-type-3 input[type=checkbox]:checked').val(),
                tube_volume: $('.add-protocol #protocol-type-3 input[name=tube-volume]').val(),
                magnet_height: $('.add-protocol #protocol-type-3 input[name=magnet-height]').val(),
                second: $('.add-protocol #protocol-type-3 input[name=second]').val(),
                sequence: $('.control-sidebar .connected-sortable li#protocol'+$(this).attr('project-protocol-id')).index()+1,
                _token: $('meta[name="csrf-token"]').attr('content')
            };
        }
        // termoshaker protocol
        else if( $(this).attr('protocol-type') == 4){
            var formData = {
                project_protocol_id: $(this).attr('project-protocol-id'),
                project_id: $(this).attr('project-id'),
                entity_id: $('.add-protocol .entity-select').val(),
                type: $('.add-protocol #protocol-type-4 .warmer-type').val(),
                warmer_time: $('.add-protocol #protocol-type-4 input[name=warmer-time]').val(),
                warmer_temperature: $('.add-protocol #protocol-type-4 input[name=warmer-temperature]').val(),
                mixer_time: $('.add-protocol #protocol-type-4 input[name=mixer-time]').val(),
                mixer_repeat: $('.add-protocol #protocol-type-4 input[name=mixer-repeat]').val(),
                sequence: $('.control-sidebar .connected-sortable li#protocol'+$(this).attr('project-protocol-id')).index()+1,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        }
        // vacuum protocol
        else if( $(this).attr('protocol-type') == 5){
            var formData = {
                project_protocol_id: $(this).attr('project-protocol-id'),
                project_id: $(this).attr('project-id'),
                entity_id: $('.add-protocol .entity-select').val(),
                time: $('.add-protocol #protocol-type-5 input[name=time]').val(),
                sequence: $('.control-sidebar .connected-sortable li#protocol'+$(this).attr('project-protocol-id')).index()+1,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        }
        // uvc protocol
        else if( $(this).attr('protocol-type') == 6){
            var formData = {
                project_protocol_id: $(this).attr('project-protocol-id'),
                project_id: $(this).attr('project-id'),
                entity_id: $('.add-protocol .entity-select').val(),
                time: $('.add-protocol #protocol-type-6 input[name=time]').val(),
                sequence: $('.control-sidebar .connected-sortable li#protocol'+$(this).attr('project-protocol-id')).index()+1,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        }
        var type = "POST";
        var ajaxurl = base_url + '/project/protocol/editProtocol';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                if(data.status == 1){
                    $('.connected-sortable').find('.edit-protocol[protocol-id='+data.protocol.id+']').siblings('p').text(data.protocol.entity.name);
                    $('.add-protocol').fadeOut();
                }
                else{
                    button.closest('.modal-footer').addClass('has-error');
                    button.siblings('.invalid-feedback').append(data.error);
                }

            },
            error: function (data) {
                button.siblings('.invalid-feedback').append('اطلاعات از سمت سرور دریافت نشد.');
            }
        });
    });








    // delete protocol in project
    $(document).on('click','.delete-protocol',function(e){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var formData = {
            project_protocol_id: $(this).attr('protocol-id'),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        var button = $(this);
        var type = "get";
        var ajaxurl = base_url + '/project/protocol/removeProtocol/' + $(this).attr('protocol-id');
        $.ajax({
            type: type,
            url: ajaxurl,
            success: function (data) {
                if(data){
                    button.parent().remove();
                    updatesequence();
                }
            },
            error: function (data) {
            }
        });
    });

    // produce gcode for project
    $('#gcode-decoder').click(function(e){
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var formData = {
            project_id: $(this).attr('project-id'),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        var button = $(this);
        var type = "get";
        var ajaxurl = base_url + '/project/gcode/decoder/' + $(this).attr('project-id');
        $.ajax({
            type: type,
            url: ajaxurl,
            success: function (data) {
                if(data.status == 1){
                    var message = data.message;
                    $("#gcode_error").append(
                        '<div class="alert alert-success" role="alert" id="err_message"></div>');
                        $("#err_message").text(message);
                    $("#gcode_error").fadeOut(5000);
                    $(".estimate").append('<li class="nav-item d-none d-sm-inline-block"><p>تیپ 10 میکرولیتر: '+data.estimate_array[0]+'</p></li>');
                    $(".estimate").append('<li class="nav-item d-none d-sm-inline-block"><p>تیپ 100 میکرولیتر: '+data.estimate_array[1]+'</p></li>');
                    $(".estimate").append('<li class="nav-item d-none d-sm-inline-block"><p>تیپ 1000 میکرولیتر: '+data.estimate_array[2]+'</p></li>');
                    $(".estimate").append('<li class="nav-item d-none d-sm-inline-block"><p>زمان: '+Math.round(data.estimate_array[3])+' ثانیه</p></li>');
                }
                else{
                    if(data.status == 0){
                        var error_message = data.message;
                    $("#gcode_error").append(
                        '<div class="alert alert-danger" role="alert" id="err_message"></div>');
                        $("#err_message").text(error_message);
                        $("#err_message").fadeOut(5000);
                    }
                }
            },
            error: function (data) {

            }
        });
    });





    $("#add_value").click(function(){
        $("#value_table").append('<tr><th><i class="fa fa-minus-square" style="font-size:25px;" aria-hidden="true" id="remove_value"></i></th><div id="container_value"><td scope="row"><input  name="key_value[]" type="text" class="form-control aria-label="Small"  aria-describedby="inputGroup-sizing-sm" placeholder="کلید"></td><td><input  name="value[]" type="text" class="form-control  aria-label="Small"  aria-describedby="inputGroup-sizing-sm" placeholder="مقدار"></td></div></tr>');
        // $("#container_value").remove();
    });

    $(document).on('click','#remove_value',function(){
        $(this).closest('tr').remove();
    });

    // change event by item selected
    $('#key_option').change(function() {
        // when item select show container div and hidde another div
        if ($(this).val() === '4') {
            $("#position_type_match").css("display", "none");
            $("#key_option_protocol").css("display", "block");
            $("#container_all_input").css("display", "none");
            $("#module_key_option_protocol").css("display", "block");
            $("#module_position_type_match").css("display", "none");
            $("#falcon_input").css("display", "none");
            $("#sampler_type").css("display", "none");
            $("#module_type").css("display", "none");
            $("#tip_falcon").css("display", "none");

        }else if($(this).val() === '0' || $(this).val() === '2'){
            $("#container_all_input").css("display", "block");
            $("#costom_container_inputs").css("display", "block");
            $("#module_key_option_protocol").css("display", "none");
            $("#module_position_type_match").css("display", "block");
            $("#falcon_input").css("display", "none");
            $("#sampler_type").css("display", "none");
            $("#module_type").css("display", "none");
            $("#tip_falcon").css("display", "none");
            $("#key_option_protocol").css("display", "none");
            $("#position_type_match").css("display", "block");

            // when item selected , all inputs inside that change to required and others inputs not required
            $( "#container_all_input").find("input").prop('required', true);

            $( "#tip_falcon").find("input").prop('required', false);
            $( "#sampler_type").find("input").prop('required', false);
            $( "#falcon_input").find("input").prop('required', false);



        }else if($(this).val() === '3'){
            $("#falcon_input").css("display", "block");
            $("#position_type_match").css("display", "block");
            $("#costom_container_inputs").css("display", "none");
            $("#module_key_option_protocol").css("display", "none");
            $("#container_all_input").css("display", "none");
            $("#key_option_protocol").css("display", "none");
            $("#sampler_type").css("display", "none");
            $("#module_type").css("display", "none");
            $("#tip_falcon").css("display", "none");
            $( "#falcon_input").find("input").prop('required', true);

            $( "#tip_falcon").find("input").prop('required', false);
            $( "#sampler_type").find("input").prop('required', false);
            $( "#container_all_input").find("input").prop('required', false);



        }
        else if($(this).val() === '1'){
            $("#sampler_type").css("display", "block");
            $("#position_type_match").css("display", "block");
            $("#costom_container_inputs").css("display", "none");
            $("#module_key_option_protocol").css("display", "none");
            $("#container_all_input").css("display", "none");
            $("#key_option_protocol").css("display", "none");
            $("#falcon_input").css("display", "none");
            $("#module_type").css("display", "none");
            $("#tip_falcon").css("display", "none");
            $("#key_option_protocol").css("display", "none");
            $( "#sampler_type").find("input").prop('required', true);
            $( "#tip_falcon").find("input").prop('required', false);
            $( "#falcon_input").find("input").prop('required', false);
            $( "#container_all_input").find("input").prop('required', false);

        }
        else if($(this).val() === '6'){
            $("#tip_falcon").css("display", "block");
            $("#position_type_match").css("display", "block");
            $("#sampler_type").css("display", "none");
            $("#costom_container_inputs").css("display", "none");
            $("#module_key_option_protocol").css("display", "none");
            $("#container_all_input").css("display", "none");
            $("#key_option_protocol").css("display", "none");
            $("#falcon_input").css("display", "none");
            $("#module_type").css("display", "none");
            $( "#tip_falcon").find("input").prop('required', true);
            $( "#sampler_type").find("input").prop('required', false);
            $( "#falcon_input").find("input").prop('required', false);
            $( "#container_all_input").find("input").prop('required', false);

        }
        else if($(this).val() != '4' || $(this).val() != '0' || $(this).val() != '3' || $(this).val() != '1' || $(this).val() != '2' || $(this).val() != '6'){
            // all inputs in div container that values != above values , change to not required
            $( "#tip_falcon").find("input").prop('required', false);
            $( "#sampler_type").find("input").prop('required', false);
            $( "#falcon_input").find("input").prop('required', false);
            $( "#container_all_input").find("input").prop('required', false);

            // all div container that not equal to above values , change to display none
            $("#key_option_protocol").css("display", "none");
            $("#position_type_match").css("display", "block");
            $("#container_all_input").css("display", "none");
            $("#module_key_option_protocol").css("display", "none");
            $("#module_position_type_match").css("display", "block");
            $("#falcon_input").css("display", "none");
            $("#sampler_type").css("display", "none");
            $("#module_type").css("display", "none");
            $("#tip_falcon").css("display", "none");

        }
    });

    $('#position_item_selected').change(function(){
        var type = $('option:selected' , this).attr('type');

        if( type === '7'  ){
            $("#girpper_value_container").css("display" , "block");
            $("#folcons_container").css("display" , "none");
            $("#device_position_items").css("display" , "none");
            $("#sampler_value_container").css("display" , "none");
            $("#tips_type").css("display" , "none");
            $("#trash_container").css("display" , "none");

        }

        else if( type === '8'){
            $("#folcons_container").css("display" , "none");
            $("#device_position_items").css("display" , "none");
            $("#girpper_value_container").css("display" , "none");
            $("#sampler_value_container").css("display" , "block");
            $("#tips_type").css("display" , "none");
            $("#trash_container").css("display" , "none");
        }

        else if( type === '6'){
            $("#trash_container").css("display" , "block");
            $("#folcons_container").css("display" , "none");
            $("#device_position_items").css("display" , "block");
            $("#girpper_value_container").css("display" , "none");
            $("#sampler_value_container").css("display" , "none");
            $("#tips_type").css("display" , "none");
        }

        else if( type === '2'){
            $("#folcons_container").css("display" , "block");
            $("#device_position_items").css("display" , "block");
            $("#girpper_value_container").css("display" , "none");
            $("#sampler_value_container").css("display" , "none");
            $("#tips_type").css("display" , "none");
            $("#trash_container").css("display" , "none");


        }
        else if( type === '0'){
            $("#tips_type").css("display" , "block");
            $("#device_position_items").css("display" , "block");
            $("#girpper_value_container").css("display" , "none");
            $("#sampler_value_container").css("display" , "none");
            $("#trash_container").css("display" , "none");

        }
        else {
            $("#girpper_value_container").css("display" , "none");
            $("#device_position_items").css("display" , "block");
            $("#folcons_container").css("display" , "none");
            $("#sampler_value_container").css("display" , "none");
            $("#tips_type").css("display" , "none");
            $("#trash_container").css("display" , "none");

        }

    });


    $('#edit_position_item').change(function(){

        var gripper = $('option:selected' , this).attr('gripper');
        if( gripper === '7'  ){
            $("#girpper_value_container").css("display" , "block");
            $("#folcons_container").css("display" , "none");
            $("#device_position_items").css("display" , "none");
            $("#sampler_value_container").css("display" , "none");
            $("#tips_type").css("display" , "none");
            $("#trash_container").css("display" , "none");


        }

        else if( gripper === '8'){
            $("#folcons_container").css("display" , "none");
            $("#device_position_items").css("display" , "none");
            $("#girpper_value_container").css("display" , "none");
            $("#sampler_value_container").css("display" , "block");
            $("#tips_type").css("display" , "none");
            $("#trash_container").css("display" , "none");

        }

        else if( gripper === '6'){
            $("#trash_container").css("display" , "block");
            $("#folcons_container").css("display" , "none");
            $("#device_position_items").css("display" , "block");
            $("#girpper_value_container").css("display" , "none");
            $("#tips_type").css("display" , "none");
            $("#sampler_value_container").css("display" , "none");
        }


        else if( gripper === '2'){
            $("#folcons_container").css("display" , "block");
            $("#device_position_items").css("display" , "block");
            $("#girpper_value_container").css("display" , "none");
            $("#sampler_value_container").css("display" , "none");
            $("#tips_type").css("display" , "none");
            $("#trash_container").css("display" , "none");

        }

        else if( gripper === '0'){
            $("#tips_type").css("display" , "block");
            $("#device_position_items").css("display" , "block");
            $("#folcons_container").css("display" , "none");
            $("#girpper_value_container").css("display" , "none");
            $("#sampler_value_container").css("display" , "none");
            $("#trash_container").css("display" , "none");

        }

        else {
            $("#girpper_value_container").css("display" , "none");
            $("#device_position_items").css("display" , "block");
            $("#folcons_container").css("display" , "none");
            $("#sampler_value_container").css("display" , "none");
            $("#tips_type").css("display" , "none");
            $("#trash_container").css("display" , "none");


        }
    });

});


