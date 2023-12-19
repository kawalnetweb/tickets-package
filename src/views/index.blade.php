@extends('tickets::layout')

@section('content')
    <style>
        ul.pagination>li:first-child a, ul.pagination>li:last-child a {
            font-size: 0;
        }

        ul.pagination>li:first-child a::before, ul.pagination>li:last-child a::after {
            content: '\f0d9';
            font-size: 16px;
            font-family: 'Font Awesome 6 Free';
            font-weight: 700;
        }
        ul.pagination>li:last-child a::after{
            content: '\f0da';
        }

        ul.pagination>li:first-child a::after {
            content: 'Previous';
            font-size: 16px;
            margin-left: 5px;
        }
        ul.pagination>li:last-child a::before{
            content: 'Next';
            font-size: 16px;
            margin-right: 5px;
        }
    </style>
    <div class="heading p-2">
        <h4>
            My Tickets
            <a href="{{route('tickets.create')}}" class="btn btn-sm btn-primary float-end"> + Add New</a>
        </h4>
    </div>
    <div class="row">
        <div class="col-md-12" id='data'>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Label</th>
                        <th>Priority</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($tickets) && count($tickets) > 0)
                        {{-- @dd($tickets) --}}
                        @foreach ($tickets['data'] as $ticket)
                            <tr>
                                <td>{{ $ticket['id'] ?? ''}}</td>
                                <td>{{ $ticket['title'] ?? '' }}</td>
                                <td>
                                    @if (isset($labels) && $ticket['label_id'] != null)
                                        <span
                                            class="px-2 py-1 bg-info text-white rounded">{{ $labels[$ticket['label_id']] }}</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $priority = '<span></span>';
                                        if (isset($priorities) && $ticket['priority_id'] != null) {
                                            if (strtoLower($priorities[$ticket['priority_id']]) == 'low') {
                                                $priority = "<span class='px-2 py-1 bg-success text-white rounded'>" . $priorities[$ticket['priority_id']] . '</spn>';
                                            } elseif (strtoLower($priorities[$ticket['priority_id']]) == 'medium') {
                                                $priority = "<span class='px-2 py-1 bg-warning text-dark rounded'>" . $priorities[$ticket['priority_id']] . '</spn>';
                                            } elseif (strtoLower($priorities[$ticket['priority_id']]) == 'high') {
                                                $priority = "<span class='px-2 py-1 bg-danger text-white rounded'>" . $priorities[$ticket['priority_id']] . '</spn>';
                                            }
                                        }
                                    @endphp
                                    {!! $priority !!}
                                </td>
                                <td>{{ $ticket['description'] ?? '' }}</td>
                                <td>
                                    @php
                                        $stage = '<span></span>';
                                        if (isset($stages) && $ticket['stage_id'] != null) {
                                            if ($stages[$ticket['stage_id']] == 'Pending') {
                                                $stage = "<span class='px-2 py-1 bg-warning text-dark rounded'>" . $stages[$ticket['stage_id']] . '</spn>';
                                            } elseif ($stages[$ticket['stage_id']] == 'In Progress') {
                                                $stage = "<span class='px-2 py-1 bg-info text-white rounded'>" . $stages[$ticket['stage_id']] . '</spn>';
                                            } elseif ($stages[$ticket['stage_id']] == 'Complete') {
                                                $stage = "<span class='px-2 py-1 bg-success text-white rounded'>" . $stages[$ticket['stage_id']] . '</spn>';
                                            } elseif ($stages[$ticket['stage_id']] == 'Testing') {
                                                $stage = "<span class='px-2 py-1 bg-secondary text-white rounded'>" . $stages[$ticket['stage_id']] . '</spn>';
                                            }
                                        }
                                    @endphp
                                    {!! $stage !!}
                                </td>
                                <td>{{ $ticket['created_at'] ?? '' }}</td>
                                <td>
                                    <a href="{{ route('tickets.show', encrypt($ticket['id'])) }}" target="_blank"
                                    class="btn btn-primary btn-sm">View</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr colspan="6">
                            <p class="text-center">No Record Found</p>
                        </tr>
                    @endif

                </tbody>
            </table>
            <div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        @foreach ($tickets['links'] as $link)
                            <li class="page-item {{ $link['active'] ? 'active' : '' }}"><a class="page-link"
                                    href="javascript:" value="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).on('click','.page-link',function(){
        var apiUrl = $(this).attr('value');
        if(apiUrl != ''){
            loader('show');
            $.ajax({
                url:`{{route('tickets.pagination')}}`,
                type:'post',
                data:{
                    api_url:apiUrl,
                },
                success:function(res){
                    if(res){
                        $('#data').html(res);
                    }
                    loader('hide');
                }
            });
        }
    });
</script>
@endpush
