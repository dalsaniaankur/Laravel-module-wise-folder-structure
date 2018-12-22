@php ( $formRoute = (empty($formRoute)) ? \Request::route()->getName() : $formRoute )
{!! Form::open(['method' => 'POST', 'class'=>'search_with_pagination_form','route' => [$formRoute]]) !!}

{{ csrf_field() }}

{!! Form::hidden('_page', 1, array('id' => '_page')) !!}
{!! Form::hidden('_search_data', $searchJsonData, array('id' => '_search_data')) !!}

{!! Form::close() !!} 

<div class="pagination" style="text-align:center"> {!! $paging !!} </div>