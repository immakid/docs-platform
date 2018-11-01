
<h4>
    <a class="back-link" href="javascript:void(0)"><b>{{$parentGroup->name}}</b></a>
</h4>


@foreach ($groups as $group)

    <li class="">
        <a href="#" class=""><b>{{$group['name']}}</b></a>
    </li>

    <ul class="articles">
    @foreach ($group['articles'] as $article)
        <li>
            <a href="#" class="article" data-group="{{$parentGroup->name}}" data-subgroup="{{$group['name']}}" data-id="{{$article->id}}">{{$article->title}}</a>
        </li>
    @endforeach
    </ul>


@endforeach


