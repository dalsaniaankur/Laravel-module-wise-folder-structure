<div class="modal" id="import_csv_modal">

    <div class="modal-dialog">

        {!! Form::open(['class'=>'import_csv_form', 'id' => 'import_csv_form', 'files' => true]) !!}

            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Import csv file</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    {!! Form::hidden('entity', $entity, array('id' => 'entity')) !!}

                    <div class="form-group">

                        {!! Form::label('csv', trans('quickadmin.import_csv') .': ', ['class' => 'col-sm-4 control-label']) !!}

                        <div class="col-sm-8">

                            {!! Form::file('csv_file',['class' => 'form-control' ,'id' => 'csv_file']) !!}
                            <p class="help-block"></p>

                        </div>

                    </div>

                    <div class="form-group display-none" id="csv_import_result_block">

                        {!! Form::label('csv', trans('quickadmin.csv_view_import_result') .': ', ['class' => 'col-sm-4 control-label']) !!}

                        <div class="col-sm-8">

                            <a href="#" target="_blank" id="import_result_link">
                                {!! Form::button(trans('quickadmin.csv_download_result_file'), ['class' => 'btn btn-primary']) !!}
                            </a>
                            <p class="help-block"></p>

                        </div>

                    </div>

                    <!-- Error Message -->
                    <div class="alert alert-danger display-none" id="csv_import_error_message_block">
                        <ul class="list-unstyled" id="csv_import_error_message"></ul>
                    </div>

                    <!-- Success Messgae -->
                    <div class="alert alert-success display-none" id="csv_import_success_message_block">
                        <ul class="list-unstyled" id="csv_import_success_message"></ul>
                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    {!! Form::submit(trans('quickadmin.btn_import'), ['class' => 'btn btn-primary']) !!}
                    {!! Form::button('Close', ['class' => 'btn btn-danger','data-dismiss' => 'modal']) !!}
                </div>

            </div>
        </form>
    </div>
</div>
<script>

    /* Open Import Csv Model Model */
    function OpenImportCsvModel(){
        $("#import_csv_modal").modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    /* Show Error Message */
    function show_csv_import_error_message(message){
        hide_csv_import_success_message();
        $("#csv_import_error_message").html('<li>' + message + '</li>');
        $("#csv_import_error_message_block").show();
    }

    /* Hide Error Message */
    function hide_csv_import_error_message(){
        $("#csv_import_error_message_block").hide();
        $("#csv_import_error_message").html('');
    }

    /* Show Success Message */
    function show_csv_import_success_message(message){
        hide_csv_import_error_message();
        $("#csv_import_success_message").html('<li>' + message + '</li>');
        $("#csv_import_success_message_block").show();
        $("#csv_import_result_block").show();
    }

    /* Hide Success Message */
    function hide_csv_import_success_message(){
        $("#csv_import_success_message_block").hide();
        $("#csv_import_success_message").html('');
        $("#csv_import_result_block").hide();
    }

    /* Close Send Email Model Event */
    $("#import_csv_modal").on("hidden.bs.modal", function () {
        hide_csv_import_error_message();
        hide_csv_import_success_message();
        $('#csv_file').val("");
    });

    /* Import CSV */
    $("#import_csv_form").submit(function(event){
        event.preventDefault();

        hide_csv_import_error_message();
        hide_csv_import_success_message();

        var file = $('#csv_file');
        var extension = file.val().split('.').pop().toLowerCase();

        if(file.val() == undefined || file.val() == '' || file.val() == null){

            var message  ="The csv file field is required.";
            show_csv_import_error_message(message);

        }else if ($.inArray(extension, ['csv', 'xls', 'xlsx']) == -1) {

            var message  ="The csv file must be a file of type: csv";
            show_csv_import_error_message(message);

        } else {

            var data = new FormData();
            var csv_file = file.prop('files')[0];
            data.append('csv_file', csv_file);
            data.append('_token', window._token);
            data.append('entity', $('#entity').val());

            jQuery.ajax({
                url: window.import_csv_url,
                method: 'post',
                dataType: 'JSON',
                data: data,
                success: function (response) {
                    if(response.success == true) {
                        $("a#import_result_link").attr("href", response.resultFilePath)
                        var message = response.message;
                        show_csv_import_success_message(message);
                    }else{
                        var message = response.message;
                        show_csv_import_error_message(message);
                    }
                    hideLoader();
                },
                processData: false,
                contentType: false,
                error: function (xhr, status) {
                    var message  ="Something went wrong";
                    show_csv_import_error_message(message);
                    hideLoader();
                }
            });
        }
    });


</script>