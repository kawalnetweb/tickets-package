@extends('tickets::layout')

@section('content')
{!! Form::open(['id' => 'ticketForm','files' => true]) !!}
<div class="row p-3" style="line-height: 30px;">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <h4>Generate Ticket
                    <a href="{{route('tickets.index')}}" class="btn btn-light float-end"> View Tickets</a>
                </h4>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Title  <span class="text-danger">*</span></label>
                    {!! Form::text('title', null, ['class' => 'form-control','maxlength' => 30,'placeholder' => 'Enter Title Here','required']) !!}
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Label  <span class="text-danger">*</span> </label>
                    {!! Form::select('label_id', isset($labels) ? $labels : [],null, ['class' => 'form-control','placeholder' => 'Select Label','required']) !!}
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Priority  <span class="text-danger">*</span> </label>
                    {!! Form::select('priority_id', isset($priorities) ? $priorities : [],null, ['class' => 'form-control','placeholder' => 'Select Priority','required']) !!}
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Files  </label>
                    {!! Form::file('files[]', ['class' => 'form-control','multiple']) !!}
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Discription<span class="text-danger">*</span></label>
                    {!! Form::textarea('description',null, ['class' => 'form-control','rows' => '3','placeholder' => 'Enter Description','required']) !!}
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-md-12 pt-2" id="message">
            </div>
            <div class="col-md-12 pt-2">
                <input type="submit" value="Submit" class="btn btn-primary">
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>

</div>
{!! Form::close() !!}
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('#ticketForm').submit(function(event){
                event.preventDefault();
                var form = $(this);
                var formdata = new FormData(form[0]);
                var url = `{{route('tickets.store')}}`;
                loader('show');
                $.ajax({
                    url: url,
                    enctype: "multipart/form-data",
                    data: formdata,
                    type: 'post',
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        if(res.status){
                            form.trigger("reset");
                            $('#message').html('<p class="alert alert-success">'+res.message+'<p>');
                        }else{
                            $('#message').html('<p class="alert alert-danger">'+res.message+'<p>');
                        }
                        loader('hide');
                        setTimeout(() => {
                            $('#message').html('');
                        }, 5000);
                    }
                });
            })

        });
    </script>
@endpush
