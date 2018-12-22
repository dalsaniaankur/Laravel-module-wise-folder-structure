<div class="modal" id="email_modal">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Send Mail</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            {!! Form::open( ['method' => 'POST', 'class'=>'send_mail_form form-horizontal validation-form','data-parsley-validate']) !!}

            <div class="modal-body">

                <div class="search-form">

                    <!-- Mail Send Success Message-->
                    <div class="alert alert-success send-mail-seccess display-none">The mail has been sent successfully.</div>

                    <div class="form-group">
                        <div class="label">
                            {!! Form::label(trans('front.send_email.first_name').': *') !!}
                        </div>
                        <div class="form-input">
                            {!! Form::text('first_name',null,['id' => 'first_name', 'required' => '']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="label">
                            {!! Form::label(trans('front.send_email.last_name').': *') !!}
                        </div>
                        <div class="form-input">
                            {!! Form::text('last_name',null,['id' => 'last_name', 'required' => '']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="label">
                            {!! Form::label(trans('front.send_email.email').': *') !!}
                        </div>
                        <div class="form-input">
                            {!! Form::email('email',null,['id' => 'email', 'required' => '']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="label">
                            {!! Form::label(trans('front.send_email.message').': *') !!}
                        </div>
                        <div class="form-input">
                            {{ Form::textarea('message', old('message'), ['id' => 'message','rows' => '5' , 'required' => '', ]) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="label">
                            {!! Form::label(trans('front.send_email.captcha').': *') !!}
                        </div>
                        <div class="form-input">
                            {!! app('captcha')->display() !!}
                            </br>
                            <!--Google Captcha Validation Message-->
                                <div class="clear-both google-captcha-error-msg"></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>-->

                <div class="form-group">
                    {!! Form::submit(trans('front.send_email.send_button'), ['class' => 'btn btn-danger search']) !!}
                </div>

            </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>