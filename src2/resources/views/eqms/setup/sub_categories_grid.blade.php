@foreach($subCatagories as $cat)
<tr>
    <td>{{$cat->sub_catagory_id}}</td>
    <td>{{$cat->sub_catagory_name}}</td>
    <td>{{$cat->sub_catagory_name_bn}}</td>
    <td>del</td>
</tr>
@endforeach
