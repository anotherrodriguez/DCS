@component('mail::message')
# ECN Number {{$ecn->id}}
@component('mail::table')
|  | |
------ | --- 
Operator| {{$ecn->operator}}
Part Number| {{$ecn->part_number}}
Sequence| {{$ecn->sequence_number}}
Change Request| {{$ecn->change_request->name}}
Created on| {{$ecn->created_at}}
Doc Pack| {{$ecn->collection_id}}
Notes|{{$ecn->notes}}
Status|{{$ecn->status->name}}
Assigned Engineer|{{$ecn->user->name}}
@endcomponent


@component('mail::button', ['url' => $ecn->url])
View ECN
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent