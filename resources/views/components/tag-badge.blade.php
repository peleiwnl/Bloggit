<a href="{{ route('tags.show', ['tag' => $tag->name]) }}" 
{{ $attributes->merge(['class' => $getColorClasses() . ' inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs z-30 relative']) }}>
    {!! $getIconSvg() !!}
    {{ $tag->name }}
</a> 