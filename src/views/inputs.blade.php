@if (isset($priorities))
    {!! Form::select('priority_id', $priorities, null, ['class' => 'form-control']) !!}
@elseif(isset($labels))
    {!! Form::select('label_id', $labels, null, ['class' => 'form-control']) !!}
@endif
