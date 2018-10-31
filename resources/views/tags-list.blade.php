
@foreach ($tags as $tag)

    <li class="">
        <a href="#" data-id="{{$tag->id}}">{{$tag->tag}}</a>
    </li>

@endforeach


