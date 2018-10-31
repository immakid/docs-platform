
@foreach ($groups as $group)

    <li class="">
        <a href="#" class="{{($group->is_main ? 'main' : '')}}" data-id="{{$group->id}}">{{$group->name}}</a>
    </li>

@endforeach


