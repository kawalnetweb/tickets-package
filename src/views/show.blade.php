@extends('tickets::layout')

@section('content')
<div class="heading">
    <h4>Ticket Details</h4>
</div>
<hr>
<div class="main">
    <div class="my-2 py-2 px-2 bg-light">
        <h5>Title : {{$ticket['title']}}</h5>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <h6>Comments</h6>
                </div>
                <div class="col-md-12" id="comment-box" style="max-height: 400px; overflow-y:scroll; scroll-behavior: smooth;">
                    @foreach ($comments as $comment)
                        <div class="comment-list">
                            <div class="comment-meta">
                                <div class="username">{{($comment['user_id'] != null)? $users[$comment['user_id']].' ( User )' : ($comment['client_id'] != null? $clients[$comment['client_id']].' ( Client )' : '')}}</div>
                                <div class="float-end">{{ $comment['created_at'] ?? '' }}</div>
                            </div>
                            <div class="comment-msg">
                                <p>{!! $comment['comment'] !!}</p>
                            </div>
                        </div>
                        <hr>
                    @endforeach

                </div>
                <div class="col-md-12">
                    {!! Form::open(['id' => 'comment-form']) !!}
                    <div class="form-group">
                        {!! Form::hidden('ticket_id', $ticket['id']) !!}
                        {!! Form::textarea('comment', null, ['class' => 'form-control editor','id' => 'ticket-comments']) !!}
                    </div>
                    <div class="mt-2" id="message"></div>
                    <div class="float-end mt-2">
                        <input type="submit" class="btn btn-primary">
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <table class="table">
                <tbody>
                    <tr>
                        <th>Label : </th>
                        <td>
                            @if (isset($labels) && $ticket['label_id'] != null)
                                <span class="px-2 py-1 bg-info text-white rounded">{{$labels[$ticket['label_id']]}}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Priority : </th>
                        <td>
                            @php
                                $priority = "<span></span>";
                                if (isset($priorities) && $ticket['priority_id'] != null){
                                    if(strtoLower($priorities[$ticket['priority_id']]) == 'low'){
                                        $priority = "<span class='px-2 py-1 bg-success text-white rounded'>".$priorities[$ticket['priority_id']]."</spn>";
                                    }else if(strtoLower($priorities[$ticket['priority_id']]) == 'medium'){
                                        $priority = "<span class='px-2 py-1 bg-warning text-dark rounded'>".$priorities[$ticket['priority_id']]."</spn>";
                                    }else if(strtoLower($priorities[$ticket['priority_id']]) == 'high'){
                                        $priority = "<span class='px-2 py-1 bg-danger text-white rounded'>".$priorities[$ticket['priority_id']]."</spn>";
                                    }
                                }
                            @endphp
                            {!! $priority !!}
                        </td>
                    </tr>
                    <tr>
                        <th>Stage : </th>
                        <td>
                            @php
                                $stage = "<span></span>";
                                if (isset($stages) && $ticket['stage_id'] != null){
                                    if($stages[$ticket['stage_id']] == 'Pending'){
                                        $stage = "<span class='px-2 py-1 bg-warning text-dark rounded'>".$stages[$ticket['stage_id']]."</spn>";
                                    }else if($stages[$ticket['stage_id']] == 'In Progress'){
                                        $stage = "<span class='px-2 py-1 bg-info text-white rounded'>".$stages[$ticket['stage_id']]."</spn>";
                                    }else if($stages[$ticket['stage_id']] == 'Complete'){
                                        $stage = "<span class='px-2 py-1 bg-success text-white rounded'>".$stages[$ticket['stage_id']]."</spn>";
                                    }else if($stages[$ticket['stage_id']] == 'Testing'){
                                        $stage = "<span class='px-2 py-1 bg-secondary text-white rounded'>".$stages[$ticket['stage_id']]."</spn>";
                                    }
                                }
                            @endphp
                            {!! $stage !!}
                        </td>
                    </tr>
                    <tr>
                        <th>Created Date :</th>
                        <td>{{$ticket['created_at'] ?? ''}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>
<script>
   ClassicEditor.create(document.querySelector('#ticket-comments'));
   $(document).ready(function(){
        var container = document.getElementById('comment-box');
        container.scrollTop = container.scrollHeight;
        $('#comment-form').submit(function(event){
            event.preventDefault();
            var form = $(this);
            var formdata = new FormData(form[0]);
            var url = `{{route('tickets.comments')}}`;
            loader('show');
            $.ajax({
                url: url,
                enctype: "multipart/form-data",
                data: formdata,
                type: 'post',
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log(res);
                    if(res.status){
                        form.trigger("reset");
                        $('#message').html('<p class="alert alert-success">'+res.message+'<p>');
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }else{
                        $('#message').html('<p class="alert alert-danger">'+res.message+'<p>');
                    }
                    loader('hide');
                    setTimeout(() => {
                        $('#message').html('');
                    }, 2000);
                }
            });
        });
    });
</script>
@endpush
