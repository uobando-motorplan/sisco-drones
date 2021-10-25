<div class="inner-box">
    @if ($item->recommended)
        <div class="product-ribbon badge badge-success">Recomendado</div>
    @endif
    @if ($like)
        <div class="product-like{{ in_array($item->id, session()->get('items'))  ? ' active' : '' }}" data-toggle="tooltip" data-placement="top" title="{{ $remove ? ' Quitar' : 'Agregar/Quitar' }}" data-id="{{ $item->id }}" data-add-url="{{ route('items.add') }}" data-remove-url="{{ route('items.remove') }}">
            <a href="{{ $remove ? route('items.remove', "id=$item->id&back=1") : 'javascript:void(0);'}} ">
                <i class="mdi mdi-heart"></i>
            </a>
        </div>
    @endif
    @if ($item->url)
        <div class="product-tour" data-toggle="tooltip" data-placement="top" title="Tour virtual">
            <a data-fancybox="single-{{ $item->id }}" data-src="{{ $item->url }}" data-type="iframe" href="{{ $item->url }}" id="iframecontent-{{ $item->id }}">
                <i class="mdi mdi-binoculars"></i>
            </a>
        </div>
    @endif
    <div class="image">
        <a href="javascript:void(0);" data-fancybox="gallery-{{ $item->id }}" data-src="{{ $item->imagePath().$item->image }}" data-caption="<b>{{ ($item->real_estate_project_id ? $item->real_estate_project->name : '') }} {{ $item->name }}</b> {{ $item->name }}">
            <img src="{{ $item->imagePath().$item->image }}" class="w-100">
        </a>
        @foreach ($item->photos as $photo)
            <a data-fancybox="gallery-{{ $item->id }}" data-src="{{ $photo->imagePath().$photo->name }}" data-caption="<b>{{ ($item->real_estate_project_id ? $item->real_estate_project->name : '') }} {{ $item->name }}</b>"></a>
        @endforeach
        <div class="price">${{ number_format($item->price) }}</div>
    </div>
    <div class="brand d-flex justify-content-start h-100">
        <img src="{{ $item->preference->imagePath().$item->preference->image }}" class="brand-logo my-auto">
        <h3 class="my-auto">{{ $item->preference->name }} <span class="text-primary">{{ $item->name }}</span></h3>
    </div>
    <div class="lower-box">
        <ul class="details text-truncate">
            <li><span class="mdi mdi-calendar-clock icon"></span>{{ $item->main_feature_1 }}</li>
            <li><span class="mdi mdi-gas-station icon"></span>{{ $item->main_feature_2 }}</li>
            <li><span class="mdi mdi-engine icon"></span>{{ $item->main_feature_3 }}</li>
        </ul>
    </div>
    <div class="features">
        @if ($item->feature_1)
            <span>&#8226</span> {{ $item->feature_1 }} 
        @endif
        @if ($item->feature_2)
            <span>&#8226</span> {{ $item->feature_2 }} 
        @endif
        @if ($item->feature_3)
            <span>&#8226</span> {{ $item->feature_3 }} 
        @endif
        @if ($item->feature_4)
            <span>&#8226</span> {{ $item->feature_4 }} 
        @endif
        @if ($item->feature_5)
            <span>&#8226</span> {{ $item->feature_5 }} 
        @endif
        @if ($item->feature_6)
            <span>&#8226</span> {{ $item->feature_6 }} 
        @endif
        @if ($item->feature_7)
            <span>&#8226</span> {{ $item->feature_7 }} 
        @endif
        @if ($item->feature_8)
            <span>&#8226</span> {{ $item->feature_8 }} 
        @endif
        @if ($item->feature_9)
            <span>&#8226</span> {{ $item->feature_9 }} 
        @endif
    </div>
</div>